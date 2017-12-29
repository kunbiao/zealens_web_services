<?php
/**
 * @name MatchController
 * @author
 * @desc
 */
namespace frontend\controllers;
use frontend\models\Court;
use frontend\models\TennisMatch;
use frontend\models\TennisMatchStats;
use frontend\models\TennisMatchAnalysis;
use frontend\models\TennisMatchVideo;
use frontend\models\UserMatch;
use frontend\models\UserMatchModel;
use Yii;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class MatchController extends CommonController
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
        $this->_bizType     = "match";
//        var_dump(Yii::$app->params['bucket_name']['match_a']);die;
//        $config = Yaf_Registry::get('config');
        $this->_bucket_a = Yii::$app->params['bucket_name']['match_a'];
        $this->_bucket_b = Yii::$app->params['bucket_name']['match_b'];
        $this->_pipeline_a = Yii::$app->params['pipeline']['match_a'];
        $this->_pipeline_b = Yii::$app->params['pipeline']['match_b'];
        $this->_watermark = \Qiniu\base64_urlSafeEncode(Yii::$app->params['watermark']);
        $this->_policy = Yii::$app->params['policy'];
        $accessKey = Yii::$app->params['access_key'];
        $secretKey = Yii::$app->params['secret_key'];
        $this->_auth = new Auth($accessKey, $secretKey);
    }

    public function actionGetMatchID()
    {
        $this->_func = __FUNCTION__;
        $this->chkDebug();
        $groundID = $this->_postData['ground_id'];
        $court=new Court();
        $courtData = $court->getRecord(array('ground_id' => $groundID));

        if (empty($courtData)) {
            $this->_errCode = 1002;
            $this->_errMsg = '非法的场地ID';
            $this->outputMsg();
        }
        $matchID = $this->addMatchRecord($courtData);
        if (empty($matchID)) {
            $this->_errCode = 1003;
            $this->_errMsg = '添加比赛ID失败';
            $this->outputMsg();
        }
        $this->_reinfo = array('match_id' => $matchID);
        $this->outputMsg();
      
    }

    public function actionSubmitStatistics()
    {
        $this->_func = __FUNCTION__;
        $this->chkDebug();

        $matchID = $this->_postData['match_id'];
        if (empty($matchID)) {
            $this->_errCode = 1004;
            $this->_errMsg = '非法的比赛ID';
            $this->outputMsg();
        }
        $tennisMatch=new TennisMatchStats();
//        $tennisMatch = D('TennisMatchStats');
        if ($tennisMatch->isMatchStatsSaved($matchID)) {
            $this->_errCode = 1005;
            $this->_errMsg = '重复的比赛技术统计';
            $this->outputMsg();
        }
        
        if (!$tennisMatch->insertMatchData($this->_postData)) {
            $this->_errCode = 1006;
            $this->_errMsg = '保存比赛技术统计失败';
            $this->outputMsg();
        }
        $this->outputMsg();
    }

    public function actionSubmitAnalysis()
    {
        $this->_func = __FUNCTION__;
        $this->chkDebug();
        $matchID = $this->_postData['match_id'];
        if (empty($matchID)) {
            $this->_errCode = 1007;
            $this->_errMsg = '非法的比赛ID';
            $this->outputMsg();
        }
        $matchAnalysis=new TennisMatchAnalysis();
//        $matchAnalysis = D('TennisMatchAnalysis');
        if ($matchAnalysis->isMatchAnalysisSaved($matchID)) {
            $this->_errCode = 1008;
            $this->_errMsg = '重复的比赛落点统计';
            $this->outputMsg();
        }

        if (!$matchAnalysis->insertMatchData($this->_postData)) {
            $this->_errCode = 1009;
            $this->_errMsg = '保存比赛落点统计失败';
            $this->outputMsg();
        }

        $this->outputMsg();
    }

    public function actionSubmitRoundEvent()
    {
        $this->_func = __FUNCTION__;
        $this->chkDebug();

        $matchID = $this->_postData['match_id'];
        $gameNum = $this->_postData['game_num'];
        $roundNum = $this->_postData['round_num'];
        if (!isset($matchID) || !isset($gameNum) || !isset($roundNum)) {
            $this->_errCode = 1010;
            $this->_errMsg = '非法的比赛ID或局数或回合数';
            $this->outputMsg();
        }

        $nameA = $this->_postData['nameA'];
        $nameB = $this->_postData['nameB'];
        if (empty($nameA) || empty($nameB)) {
            $this->_errCode = 1011;
            $this->_errMsg = '非法的比赛回合视频源文件名';
            $this->outputMsg();
        }
        $matchVideo=new TennisMatchVideo();
//        $matchVideo = D('TennisMatchVideo');
        if ($matchVideo->isMatchRoundVideoSaved($matchID, $gameNum, $roundNum)) {
            $this->_errCode = 1012;
            $this->_errMsg = '重复的比赛回合视频数据';
            $this->outputMsg();
        } else if (!$matchVideo->isMatchRoundVideoInserted($matchID, $gameNum, $roundNum)) {
            if (!$matchVideo->insertMatchData($this->_postData)) {
                $this->_errCode = 1013;
                $this->_errMsg = '保存比赛回合视频数据失败';
                $this->outputMsg();
            }
        }

//        $matchID = 394;
//        $gameNum = 1;
//        $roundNum = 1;
//        $nameA = '第一局第一回合A.mp4';
//        $nameB = '第一局第一回合B.mp4';
        $upTokenA = $this->buildUploadToken($matchID, $gameNum, $roundNum, $nameA, 'a', $this->_bucket_a, $this->_pipeline_a);
        $upTokenB = $this->buildUploadToken($matchID, $gameNum, $roundNum, $nameB, 'b', $this->_bucket_b, $this->_pipeline_b);

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

        $matchID = $this->_postData["data"]["match_id"];
        $gameNum = $this->_postData["data"]['game_num'];
        $roundNum = $this->_postData["data"]['round_num'];
        if (!isset($matchID) || !isset($gameNum) || !isset($roundNum)) {
            $this->_errCode = 1014;
            $this->_errMsg = '非法的比赛ID或局数或回合数';
            $this->outputMsg();
        }
        $matchVideo = new TennisMatchVideo;
//        $matchVideo = D('TennisMatchVideo');
        if ($matchVideo->isMatchRoundVideoSaved($matchID, $gameNum, $roundNum)) {
            $this->_errCode = 1015;
            $this->_errMsg = '重复的比赛回合视频数据';
            $this->outputMsg();
        } else if (!$matchVideo->isMatchRoundVideoInserted($matchID, $gameNum, $roundNum)) {
            $this->_errCode = 1016;
            $this->_errMsg = '非法的比赛回合视频数据更新请求';
            $this->outputMsg();
        } else if (!$matchVideo->updateMatchData($this->_postData)) {
            $this->_errCode = 1017;
            $this->_errMsg = '更新比赛视频数据失败';
            $this->outputMsg();
        }

        $fkey = $this->_postData["data"]["fkey"];
        $fname = $this->_postData["data"]["fname"];
        $videoSource = $this->_postData["data"]["video_source"];
        $this->_reinfo = array(
            "fkey" => $fkey,
            "fname" => $fname,
            "match_id" => $matchID,
            "game_num" => $gameNum,
            "round_num" => $roundNum,
            "video_source" => $videoSource,
//            "authorization" => $authorization,
//            "postData" => json_encode($this->_postData),
            "update_time" => time()
        );
        $this->outputMsg();
    }

    private function addMatchRecord($courtData)
    {
        $uidA1 = empty($this->_postData['user_idA1']) ? 0 : $this->_postData['user_idA1'];
        $uidA2 = empty($this->_postData['user_idA2']) ? 0 : $this->_postData['user_idA2'];
        $uidB1 = empty($this->_postData['user_idB1']) ? 0 : $this->_postData['user_idB1'];
        $uidB2 = empty($this->_postData['user_idB2']) ? 0 : $this->_postData['user_idB2'];
        if ((empty($uidA1) || empty($uidB1)) && (empty($uidA2) || empty($uidB2)))
            return 0;

        if ($uidA1) {
            $uIDs[] = $uidA1;
        }
        if ($uidA2) {
            $uIDs[] = $uidA2;
        }
        if ($uidB1) {
            $uIDs[] = $uidB1;
        }
        if ($uidB2) {
            $uIDs[] = $uidB2;
        }
       
        if (sizeof($uIDs) == 3)
            return 0;

        $matchType = empty($this->_postData['match_type']) ? 1 : $this->_postData['match_type'];
        $matchMode = empty($this->_postData['match_mode']) ? 1 : $this->_postData['match_mode'];
        $beginTime = empty($this->_postData['begin_time']) ? time() : $this->_postData['begin_time'];

        $matchRecord = array(
            'a1' => $uidA1,
            'a2' => $uidA2,
            'b1' => $uidB1,
            'b2' => $uidB2,
            'type' => $matchType,
            'mode' => $matchMode,
            'begin_time' => $beginTime,
            'status' => 1,
            'site_id' => $courtData['site_id'],
            'court_id' => $courtData['id'],
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s')
        );
        $TennisMatch=new TennisMatch();
        $matchID = $TennisMatch->addRecord($matchRecord);
        $this->addUserMatchRecord($uIDs, $matchID, $courtData);
        return $matchID;
    }

    private function addUserMatchRecord($uIDs, $matchID, $courtData)
    {
        $userMatch = array(
            'match_id' => $matchID,
            'site_id' => $courtData['site_id'],
            'court_id' => $courtData['id'],
            'status' => 1,
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s')
        );

        if (empty($uIDs))
            return;
        $UserMath=new UserMatch();
        $UserMath->add($uIDs, $userMatch);
    }

    private function buildUploadToken($matchID, $gameNum, $roundNum, $name, $video_source, $bucketName, $pileline)
    {
        // vb 5m
        $originKey = "match_id_" . $matchID . "_" . $gameNum . "_" . $roundNum . "_source_" . $video_source . "_name_" . time() . "-" . $name;
        $convertKey = "640x360-match_id_" . $matchID . "_" . $gameNum . "_" . $roundNum . "_source_" . $video_source . "_name_" . time() . "-" . $name;
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
            "match_id" => $matchID,
            "game_num" => $gameNum,
            "round_num" => $roundNum,
            "video_source" => $video_source
        );

        $this->_policy['callbackBodyType'] = "application/json";
        $this->_policy['callbackBody'] = json_encode($callbackBody);
        $this->_policy['callbackUrl'] = 'http://biz.perobot.ai/match/videoCallback';
//        $this->_policy['persistentOps'] = $mp4Fop;
        $this->_policy['persistentOps'] = $mp4Fop . $watermarkFop;
        $this->_policy['saveKey'] = $originKey;
        $this->_policy['persistentPipeline'] = $pileline;
        return $this->_auth->uploadToken($bucketName, null, 3600, $this->_policy);
    }
}

