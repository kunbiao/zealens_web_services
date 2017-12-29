<?php
/**
 * @name TrainController
 * @author
 * @desc
 */
namespace frontend\controllers;
use frontend\models\Court;
use frontend\models\TennisMatchStats;
use frontend\models\TennisTrain;
use frontend\models\TennisTrainAnalysis;
use frontend\models\TennisTrainModel;
use frontend\models\TennisTrainStats;
use frontend\models\TennisTrainVideo;
use frontend\models\UserTrain;
use Qiniu\Auth;
use Yii;
class TrainController extends CommonController
{
    private $_auth;
    private $_policy;
    private $_bucket_a;
    private $_bucket_b;
    private $_pipeline_a;
    private $_pipeline_b;
    private $_watermark;

    public function init()
    {
        parent::init();
        $this->_bizType     = "train";
        $this->_bucket_a = Yii::$app->params['bucket_name']['match_a'];
        $this->_bucket_a = Yii::$app->params['bucket_name']['train_a'];
        $this->_bucket_b = Yii::$app->params['bucket_name']['train_b'];
        $this->_pipeline_a = Yii::$app->params['pipeline']['train_a'];
        $this->_pipeline_b = Yii::$app->params['pipeline']['train_b'];
        $this->_watermark = \Qiniu\base64_urlSafeEncode(Yii::$app->params['watermark']);
        $this->_policy = Yii::$app->params['policy'];

        $accessKey = Yii::$app->params['access_key'];
        $secretKey = Yii::$app->params['secret_key'];
        $this->_auth = new Auth($accessKey, $secretKey);
    }

    public function actionGetTrainID()
    {
        $this->_func = __FUNCTION__;
        $this->chkDebug();
        $groundID = $this->_postData['ground_id'];
        $Court=new Court();
        $courtData = $Court->getRecord(array('ground_id' => $groundID));
        if (empty($courtData)) {
            $this->_errCode = 1002;
            $this->_errMsg = '非法的场地ID';
            $this->outputMsg();
        }

        $trainID = $this->addTrainRecord($courtData);
        if (empty($trainID)) {
            $this->_errCode = 1003;
            $this->_errMsg = '添加训练ID失败';
            $this->outputMsg();
        }

        $this->_reinfo = array('train_id' => $trainID);
        $this->outputMsg();
    }

    public function actionSubmitStatistics()
    {
        $this->_func = __FUNCTION__;
        $this->chkDebug();

        $trainID = $this->_postData['train_id'];
        if (empty($trainID)) {
            $this->_errCode = 1004;
            $this->_errMsg = '非法的训练ID';
            $this->outputMsg();
        }

//        $tennisTrain = D('TennisTrainStats');
        $tennisTrain = new TennisTrainStats();
        
        if ($tennisTrain->isTrainStatsSaved($trainID)) {
            $this->_errCode = 1005;
            $this->_errMsg = '重复的训练技术统计';
            $this->outputMsg();
        }

        if (!$tennisTrain->insertTrainData($this->_postData)) {
            $this->_errCode = 1006;
            $this->_errMsg = '保存训练技术统计失败';
            $this->outputMsg();
        }

        $this->outputMsg();
    }

    public function actionSubmitAnalysis()
    {
        $this->_func = __FUNCTION__;
        $this->chkDebug();

        $trainID = $this->_postData['train_id'];
        if (empty($trainID)) {
            $this->_errCode = 1007;
            $this->_errMsg = '非法的训练ID';
            $this->outputMsg();
        }
        $trainAnalysis=new TennisTrainAnalysis();
//        $trainAnalysis = D('TennisTrainAnalysis');
        if ($trainAnalysis->isTrainAnalysisSaved($trainID)) {
            $this->_errCode = 1008;
            $this->_errMsg = '重复的训练落点统计';
            $this->outputMsg();
        }

        if (!$trainAnalysis->insertTrainData($this->_postData)) {
            $this->_errCode = 1009;
            $this->_errMsg = '保存训练落点统计失败';
            $this->outputMsg();
        }

        $this->outputMsg();
    }

    public function submitRoundEventAction()
    {
        $this->_func = __FUNCTION__;
        $this->chkDebug();

        $trainID = $this->_postData['train_id'];
        if (empty($trainID)) {
            $this->_errCode = 1010;
            $this->_errMsg = '非法的训练ID';
            $this->outputMsg();
        }

        $nameA = $this->_postData['nameA'];
        $nameB = $this->_postData['nameB'];
        if (empty($nameA) || empty($nameB)) {
            $this->_errCode = 1011;
            $this->_errMsg = '非法的训练视频源文件名';
            $this->outputMsg();
        }
        $trainVideo = new TennisTrainVideo();
//        $trainVideo = D('TennisTrainVideo');
        if ($trainVideo->isTrainVideoSaved($trainID)) {
            $this->_errCode = 1012;
            $this->_errMsg = '重复的训练视频数据';
            $this->outputMsg();
        } else if (!$trainVideo->isTrainVideoInserted($trainID)) {
            if (!$trainVideo->insertTrainData($this->_postData)) {
                $this->_errCode = 1013;
                $this->_errMsg = '保存训练视频数据失败';
                $this->outputMsg();
            }
        }

//        $trainID = 14;
//        $nameA = '第一场训练A.mp4';
//        $nameB = '第一场训练B.mp4';
        $upTokenA = $this->buildUploadToken($trainID, $nameA, 'a', $this->_bucket_a, $this->_pipeline_a);
        $upTokenB = $this->buildUploadToken($trainID, $nameB, 'b', $this->_bucket_b, $this->_pipeline_b);

        $this->_reinfo = array(
            'type' => 1,
            'host' => 'http://up-z1.qiniu.com',
            'up_tokenA' => $upTokenA,
            'up_tokenB' => $upTokenB,
        );
        $this->outputMsg();
    }

    public function videoCallbackAction()
    {
        $this->_func = __FUNCTION__;
        $this->chkPostData();

//        $contentType = 'application/json';
//        $authorization = $_SERVER['HTTP_AUTHORIZATION'];
//        $isQiniuCallback = $this->_auth->verifyCallback($contentType, $authorization, $this->_policy['callbackUrl'], $this->_postData);
//        if (!$isQiniuCallback) {
//            $this->_errCode = 1014;
//            $this->_errMsg = '非法的回调请求';
//            $this->outputMsg();
//        }

        $trainID = $this->_postData["data"]["train_id"];
        if (empty($trainID)) {
            $this->_errCode = 1014;
            $this->_errMsg = '非法的训练ID';
            $this->outputMsg();
        }
        $trainVideo = new TennisTrainVideo();
//        $trainVideo = D('TennisTrainVideo');
        if ($trainVideo->isTrainVideoSaved($trainID)) {
            $this->_errCode = 1015;
            $this->_errMsg = '重复的训练视频数据';
            $this->outputMsg();
        } else if (!$trainVideo->isTrainVideoInserted($trainID)) {
            $this->_errCode = 1016;
            $this->_errMsg = '非法的训练视频数据更新请求';
            $this->outputMsg();
        } else if (!$trainVideo->updateTrainData($this->_postData)) {
            $this->_errCode = 1017;
            $this->_errMsg = '更新训练视频数据失败';
            $this->outputMsg();
        }

        $fkey = $this->_postData["data"]["fkey"];
        $fname = $this->_postData["data"]["fname"];
        $videoSource = $this->_postData["data"]["video_source"];
        $this->_reinfo = array(
            "fkey" => $fkey,
            "fname" => $fname,
            "train_id" => $trainID,
            "video_source" => $videoSource,
//            "authorization" => $authorization,
//            "postData" => json_encode($this->_postData),
            "update_time" => time()
        );
        $this->outputMsg();
    }

    private function addTrainRecord($courtData)
    {
        $uidA = empty($this->_postData['user_idA']) ? 0 : $this->_postData['user_idA'];
        $uidB = empty($this->_postData['user_idB']) ? 0 : $this->_postData['user_idB'];
        if (empty($uidA) && empty($uidB))
            return 0;

        $trainTypeA = empty($this->_postData['train_typeA']) ? 0 : $this->_postData['train_typeA'];
        $trainTypeB = empty($this->_postData['train_typeB']) ? 0 : $this->_postData['train_typeB'];
        $trainMode = empty($this->_postData['train_mode']) ? 1 : $this->_postData['train_mode'];
        $trainGroup = empty($this->_postData['train_group']) ? 1 : $this->_postData['train_group'];
        $beginTime = empty($this->_postData['begin_time']) ? time() : $this->_postData['begin_time'];

        $trainRecord = array(
            'a' => $uidA,
            'b' => $uidB,
            'ua_type' => $trainTypeA,
            'ub_type' => $trainTypeB,
            'mode' => $trainMode,
            'group' => $trainGroup,
            'begin_time' => $beginTime,
            'status' => 1,
            'site_id' => $courtData['site_id'],
            'court_id' => $courtData['id'],
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s')
        );
        $TennisTrain=new TennisTrain();
        $trainID = $TennisTrain->addRecord($trainRecord);

        if ($uidA) {
            $uIDs[] = $uidA;
        }
        if ($uidB) {
            $uIDs[] = $uidB;
        }
        $this->addUserTrainRecord($uIDs, $trainID, $courtData);

        return $trainID;
    }

    private function addUserTrainRecord($uIDs, $trainID, $courtData)
    {
        $userTrain = array(
            'train_id' => $trainID,
            'site_id' => $courtData['site_id'],
            'court_id' => $courtData['id'],
            'status' => 1,
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s')
        );

        if (empty($uIDs))
            return;
        $UserTrain=new UserTrain();
        $UserTrain->add($uIDs, $userTrain);
    }

    private function buildUploadToken($trainID, $name, $video_source, $bucketName, $pileline)
    {
        // vb 5m
        $originKey = "train_id_" . $trainID . "_source_" . $video_source . "_name_" . time() . "-" . $name;
        $convertKey = "640x360-train_id_" . $trainID . "_source_" . $video_source . "_name_" . time() . "-" . $name;
        // 无水印
//        $mp4Fop = "avthumb/mp4/vb/1.25m/s/640x360|saveas/" . Qiniu\base64_urlSafeEncode($bucketName . ":" . $convertKey);

        // 有水印
        $mp4Fop = "avthumb/mp4/vb/1.25m/s/640x360";
        $watermarkFop = "/wmImage/$this->_watermark/wmGravity/SouthEast"
            . "|saveas/" . \Qiniu\base64_urlSafeEncode($bucketName . ":" . $convertKey);

        // TODO 增加图片以及文字水印（比分），已达成品牌传播
//        $mp4Fop = "avthumb/mp4/vb/1.25m/s/640x360";
//        $watermarkFop = "/wmImage/$this->_watermark/wmGravity/SouthEast"
//            . "/wmText/" . \Qiniu\base64_urlSafeEncode("15:30")
//            . "/wmGravityText/SouthWest"
//            . "/wmFont/" . \Qiniu\base64_urlSafeEncode("仿宋")
//            . "/wmFontColor/" . \Qiniu\base64_urlSafeEncode("红色")
//            . "/wmFontSize/60|saveas/" . Qiniu\base64_urlSafeEncode($bucketName . ":" . $convertKey);

        $callbackBody["err_code"] = 0;
        $callbackBody["err_msg"] = "";
        $callbackBody["data"] = array(
            "fkey" => "$(key)",
            "fname" => "$(fname)",
            "train_id" => $trainID,
            "video_source" => $video_source
        );

        $this->_policy['callbackBodyType'] = "application/json";
        $this->_policy['callbackBody'] = json_encode($callbackBody);
        $this->_policy['callbackUrl'] = 'http://biz.perobot.ai/train/videoCallback';
//        $this->_policy['persistentOps'] = $mp4Fop;
        $this->_policy['persistentOps'] = $mp4Fop . $watermarkFop;
        $this->_policy['saveKey'] = $originKey;
        $this->_policy['persistentPipeline'] = $pileline;
        return $this->_auth->uploadToken($bucketName, null, 3600, $this->_policy);
    }
}

