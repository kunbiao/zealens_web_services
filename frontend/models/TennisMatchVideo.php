<?php
namespace frontend\models;
class TennisMatchVideo extends Common
{
    public function rules()
    {
        return [
            [['match_id', 'game_num', 'round_num', 'begin_time', 'end_time', 'cateid', 'ua_score', 'ua_round_type', 'ua_size', 'ua_status', 'ub_score', 'ub_round_type', 'ub_size', 'ub_status'], 'integer'],
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
    public function isMatchRoundVideoSaved($matchID, $gameNum, $roundNum)
    {
        $where = array(
            'match_id' => $matchID,
            'game_num' => $gameNum,
            'round_num' => $roundNum,
            'ua_status' => 2,
            'ub_status' => 2
        );
        return TennisMatchVideo::find()->where($where)->count() != 0;
    }

    public function isMatchRoundVideoInserted($matchID, $gameNum, $roundNum)
    {
        $where = array(
            'match_id' => $matchID,
            'game_num' => $gameNum,
            'round_num' => $roundNum,
        );

        return TennisMatchVideo::find()->where($where)->count() != 0;
    }

    public function insertMatchData($params)
    {
        // TODO 所有数据的存入，在yii框架中，都要做数据校验，这个目的是为了确保客户端出现错误的代码上传
        $dataAttr = $this->buildAttrDataFromString(isset($params['dataAttr']) ? $params['dataAttr'] : '');

        // insert match statistics table
        $matchVideoData = array(
            // 比赛id
            'match_id' => isset($params['match_id']) ? $params['match_id'] : 0,

            'game_num' => isset($params['game_num']) ? $params['game_num'] : 0,
            'round_num' => isset($params['round_num']) ? $params['round_num'] : 0,
            'begin_time' => isset($params['begin_time']) ? $params['begin_time'] : 0,
            'end_time' => isset($params['end_time']) ? $params['end_time'] : time(),

            'desc' => isset($params['desc']) ? $params['desc'] : 0,
            'cateid' => isset($params['cateid']) ? $params['cateid'] : 0,
            'tags' => isset($params['tags']) ? $params['tags'] : 0,

            // A面用户数据
            'ua_score' => isset($params['scoreA']) ? $params['scoreA'] : 0,
            'ua_round_type' => isset($params['round_typeA']) ? $params['round_typeA'] : 0,
            'ua_title' => isset($params['titleA']) ? $params['titleA'] : 0,
            'ua_name' => isset($params['nameA']) ? $params['nameA'] : 0,
            'ua_size' => isset($params['sizeA']) ? $params['sizeA'] : 0,
            'ua_coverURL' => isset($params['coverURLA']) ? $params['coverURLA'] : '',
            'ua_status' => 1,

            // B面用户数据
            'ub_score' => isset($params['scoreB']) ? $params['scoreB'] : 0,
            'ub_round_type' => isset($params['round_typeB']) ? $params['round_typeB'] : 0,
            'ub_title' => isset($params['titleB']) ? $params['titleB'] : 0,
            'ub_name' => isset($params['nameB']) ? $params['nameB'] : 0,
            'ub_size' => isset($params['sizeB']) ? $params['sizeB'] : 0,
            'ub_coverURL' => isset($params['coverURLB']) ? $params['coverURLB'] : '',
            'ub_status' => 1,

            'dataAttr' => $dataAttr,

            'create_time' => date('Y-m-d h:i:s')
        );

        $res = $this->addRecord($matchVideoData);
        if (empty($res)) {
//            trace('insert TennisMatchVideo with [' . json_encode($matchVideoData) . '] failed.', 'insertMatchData', 'DEBUG', true);
            return false;
        }

        return true;
    }

    public function updateMatchData($params)
    {
        $fkey = $params['data']['fkey'];
        $matchID = $params['data']['match_id'];
        $gameNum = $params['data']['game_num'];
        $roundNum = $params['data']['round_num'];
        $videoSource = $params['data']['video_source'];
        if (empty($fkey) || !isset($matchID) || !isset($gameNum) || !isset($roundNum) || empty($videoSource))
            return false;

        // update match data.
        if ($videoSource == 'a') {
            $where = array(
                'match_id' => $matchID,
                'game_num' => $gameNum,
                'round_num' => $roundNum,
                'ua_status' => 1
            );

            $matchData = array(
                'ua_videoID' => $fkey,
                'ua_status' => 2,
                'update_time' => date('Y-m-d H:i:s')
            );

            $res = $this->updateRecord($matchData, $where);
        } else if ($videoSource == 'b') {
            $where = array(
                'match_id' => $matchID,
                'game_num' => $gameNum,
                'round_num' => $roundNum,
                'ub_status' => 1
            );

            $matchData = array(
                'ub_videoID' => $fkey,
                'ub_status' => 2,
                'update_time' => date('Y-m-d H:i:s')
            );

            $res = $this->updateRecord($matchData, $where);
        } else {
            return false;
        }

        if (empty($res)) {
//            trace('update TennisMatchVideo with [' . json_encode($matchData) . '] failed.', 'updateMatchData', 'DEBUG', true);
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
                $pos = explode(',', $value, 6);
                if (sizeof($pos) == 6) {
                    $attrData[] = array(
                        'x' => intval($pos[0] / 10),
                        'y' => intval($pos[1] / 10),
                        'z' => intval($pos[2] / 10),
                        'speed' => intval($pos[3]),
                        'timestamp' => intval($pos[4]),
                        'eventid' => intval($pos[5]),
                        'val' => 1
                    );
                }
            }
        }

        return json_encode($attrData);
    }
}
