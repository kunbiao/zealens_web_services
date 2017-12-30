<?php
namespace frontend\models;
class TennisMatchStats extends Common
{
    public function isMatchStatsSaved($matchID)
    {
        $where = array(
            'id' => $matchID,
            'status' => 2
        );
        return !empty(Court::find()->asArray()->where($where)->one());
    }
    public function rules()
    {
        return [
            [['match_id', 'ua_ace', 'ua_dfault', 'ua_fsin', 'ua_fs_point', 'ua_ss_point', 'ua_bpwon', 'ua_bp_count', 'ua_win_point', 'ua_high_speed', 'ua_avg_speed', 'ua_score', 'ub_ace', 'ub_dfault', 'ub_fsin', 'ub_fs_point', 'ub_ss_point', 'ub_bpwon', 'ub_bp_count', 'ub_win_point', 'ub_high_speed', 'ub_avg_speed', 'ub_score', 'status'], 'integer'],
            [['create_time'], 'safe'],
        ];
    }
    public function insertMatchData($params)
    {
        $matchStats = array(
            // 比赛id
            'match_id' => $params['match_id'],

            // A面用户数据
            'ua_ace' => empty($params['aceA']) ? 0 : $params['aceA'],
            'ua_dfault' => empty($params['d_faultA']) ? 0 : $params['d_faultA'],
            'ua_fsin' => empty($params['fs_inA']) ? 0 : $params['fs_inA'],
            'ua_fs_point' => empty($params['fs_pointA']) ? 0 : $params['fs_pointA'],
            'ua_ss_point' => empty($params['ss_pointA']) ? 0 : $params['ss_pointA'],
            'ua_bpwon' => empty($params['bp_wonA']) ? 0 : $params['bp_wonA'],
            'ua_bp_count' => empty($params['bp_countA']) ? 0 : $params['bp_countA'],
            'ua_win_point' => empty($params['win_pointA']) ? 0 : $params['win_pointA'],
            'ua_high_speed' => empty($params['high_speedA']) ? 0 : $params['high_speedA'],
            'ua_avg_speed' => empty($params['avg_speedA']) ? 0 : $params['avg_speedA'],
            'ua_score' => empty($params['scoreA']) ? 0 : $params['scoreA'],

            // B面用户数据
            'ub_ace' => empty($params['aceB']) ? 0 : $params['aceB'],
            'ub_dfault' => empty($params['d_faultB']) ? 0 : $params['d_faultB'],
            'ub_fsin' => empty($params['fs_inB']) ? 0 : $params['fs_inB'],
            'ub_fs_point' => empty($params['fs_pointB']) ? 0 : $params['fs_pointB'],
            'ub_ss_point' => empty($params['ss_pointB']) ? 0 : $params['ss_pointB'],
            'ub_bpwon' => empty($params['bp_wonB']) ? 0 : $params['bp_wonB'],
            'ub_bp_count' => empty($params['bp_countB']) ? 0 : $params['bp_countB'],
            'ub_win_point' => empty($params['win_pointB']) ? 0 : $params['win_pointB'],
            'ub_high_speed' => empty($params['high_speedB']) ? 0 : $params['high_speedB'],
            'ub_avg_speed' => empty($params['avg_speedB']) ? 0 : $params['avg_speedB'],
            'ub_score' => empty($params['scoreB']) ? 0 : $params['scoreB'],

            'status' => 1,
            'create_time' => date('Y-m-d H:i:s')
        );

        $res = $this->addRecord($matchStats);
        if (empty($res)) {
//            trace('insert TennisMatchStats with [' . json_encode($matchStats) . '] failed.', 'insertMatchData', 'DEBUG', true);
            return false;
        }

        // update match data.
        $matchData = array(
            'end_time' => empty($params['end_time']) ? time() : $params['end_time'],
            'ua_score' => empty($params['scoreA']) ? 0 : $params['scoreA'],
            'ub_score' => empty($params['scoreB']) ? 0 : $params['scoreB'],
            'status' => 2,
            'update_time' => date('Y-m-d H:i:s')
        );
        $tennisMatch=new TennisMatch();
        $where=array('id' => $params['match_id'], 'status' => 1);
        $res=$tennisMatch->updateRecord($matchData,$where);
        if (empty($res)) {
//            trace('update TennisMatch with [' . json_encode($matchData) . '] failed.', 'insertMatchData', 'DEBUG', true);
            return false;
        }

        return true;
    }
}
