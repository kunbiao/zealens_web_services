<?php
namespace frontend\models;
class TennisTrainVideo extends Common
{
    public function isTrainVideoSaved($trainID)
    {
        $where = array(
            'train_id' => $trainID,
            'ua_status' => 2,
            'ub_status' => 2
        );
        return TennisTrainVideo::find()->where($where)->count() != 0;
    }
    public function rules()
    {
        return [
            [['train_id', 'begin_time', 'end_time', 'cateid', 'ua_score', 'ua_size', 'ua_status', 'ub_score', 'ub_size', 'ub_status'], 'integer'],
            [['dataAttr'], 'required'],
            [['dataAttr'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['desc'], 'string', 'max' => 1024],
            [['tags'], 'string', 'max' => 64],
            [['ua_title', 'ua_name', 'ub_title', 'ub_name'], 'string', 'max' => 128],
            [['ua_coverURL', 'ub_coverURL'], 'string', 'max' => 250],
            [['ua_videoID', 'ub_videoID'], 'string', 'max' => 512],
        ];
    }

    public function isTrainVideoInserted($trainID)
    {
        $where = array(
            'train_id' => $trainID,
        );

        return TennisTrainVideo::find()->where($where)->count() != 0;
    }

    public function insertTrainData($params)
    {
        // TODO 所有数据的存入，在yii框架中，都要做数据校验，这个目的是为了确保客户端出现错误的代码上传
        $dataAttr = $this->buildAttrDataFromString(isset($params['dataAttr']) ? $params['dataAttr'] : '');

        // insert train statistics table
        $trainVideoData = array(
            // 比赛id
            'train_id' => isset($params['train_id']) ? $params['train_id'] : 0,

            'begin_time' => isset($params['begin_time']) ? $params['begin_time'] : 0,
            'end_time' => isset($params['end_time']) ? $params['end_time'] : time(),

            'desc' => isset($params['desc']) ? $params['desc'] : 0,
            'cateid' => isset($params['cateid']) ? $params['cateid'] : 0,
            'tags' => isset($params['tags']) ? $params['tags'] : 0,

            // A面用户数据
            'ua_score' => isset($params['scoreA']) ? $params['scoreA'] : 0,
            'ua_title' => isset($params['titleA']) ? $params['titleA'] : 0,
            'ua_name' => isset($params['nameA']) ? $params['nameA'] : 0,
            'ua_size' => isset($params['sizeA']) ? $params['sizeA'] : 0,
            'ua_coverURL' => isset($params['coverURLA']) ? $params['coverURLA'] : '',
            'ua_status' => 1,

            // B面用户数据
            'ub_score' => isset($params['scoreB']) ? $params['scoreB'] : 0,
            'ub_title' => isset($params['titleB']) ? $params['titleB'] : 0,
            'ub_name' => isset($params['nameB']) ? $params['nameB'] : 0,
            'ub_size' => isset($params['sizeB']) ? $params['sizeB'] : 0,
            'ub_coverURL' => isset($params['coverURLB']) ? $params['coverURLB'] : '',
            'ub_status' => 1,

            'dataAttr' => $dataAttr,

            'create_time' => date('Y-m-d H:i:s')
        );

        $res = $this->addRecord($trainVideoData);
        if (empty($res)) {
//            trace('insert TennisTrainVideo with [' . json_encode($trainVideoData) . '] failed.', 'insertTrainData', 'DEBUG', true);
            return false;
        }

        return true;
    }

    public function updateTrainData($params)
    {
        $fkey = $params['data']['fkey'];
        $trainID = $params['data']['train_id'];
        $videoSource = $params['data']['video_source'];
        if (empty($fkey) || empty($trainID) || empty($videoSource))
            return false;

        // update train data.
        if ($videoSource == 'a') {
            $trainData = array(
                'ua_videoID' => $fkey,
                'ua_status' => 2,
                'update_time' => date('Y-m-d H:i:s')
            );

            $res = $this->updateRecord($trainData, array('train_id' => $trainID, 'ua_status' => 1));
        } else if ($videoSource == 'b') {
            $trainData = array(
                'ub_videoID' => $fkey,
                'ub_status' => 2,
                'update_time' => date('Y-m-d H:i:s')
            );

            $res = $this->updateRecord($trainData, array('train_id' => $trainID, 'ub_status' => 1));
        } else {
            return false;
        }

        if (empty($res)) {
//            trace('update TennisVideoVideo with [' . json_encode($trainData) . '] failed.', 'updateTrainData', 'DEBUG', true);
            return false;
        }

        return true;
    }

    private function buildAttrDataFromString($attribute)
    {
        // example:"-57100,-38900,158, 1501503480,2;-49600,-1700,140, 1501503481,3;"
        $posArr = explode(';', $attribute);
        if (empty($posArr)) {
            return json_encode(array());
        }

        $attrData = array();
        foreach ($posArr as $value) {
            if (!empty($value)) {
                $pos = explode(',', $value, 7);
                if (sizeof($pos) == 7) {
                    $attrData[] = array(
                        'x' => intval($pos[0] / 10),
                        'y' => intval($pos[1] / 10),
                        'z' => intval($pos[2] / 10),
                        'speed' => intval($pos[3]),
                        'timestamp' => intval($pos[4]),
                        'eventid' => intval($pos[5]),
                        'val' => intval($pos[6])
                    );
                }
            }
        }

        return json_encode($attrData);
    }
}
