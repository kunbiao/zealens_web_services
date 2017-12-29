<?php
namespace frontend\models;
class TennisTrainStats extends Common
{
    public function rules()
    {
        return [
            [['train_id', 'ua_score', 'ua_times', 'ua_rate', 'ua_out', 'ua_off_net', 'ua_high_speed', 'ua_avg_speed', 'ub_score', 'ub_times', 'ub_rate', 'ub_out', 'ub_off_net', 'ub_high_speed', 'ub_avg_speed', 'status'], 'integer'],
            [['create_time'], 'safe'],
        ];
    }
    public function isTrainStatsSaved($trainID)
    {
        $where = array(
            'id' => $trainID,
            'status' => 2
        );
        $TennisTrain=new TennisTrain();
        return $TennisTrain::find()->asArray()->where($where)->one();
    }

    public function insertTrainData($params)
    {
        $trainStats = array(
            // 比赛id
            'train_id' => $params['train_id'],

            // A面用户数据
            'ua_score' => empty($params['scoreA']) ? 0 : $params['scoreA'],
            'ua_times' => empty($params['timesA']) ? 0 : $params['timesA'],
            'ua_rate' => empty($params['rateA']) ? 0 : $params['rateA'],
            'ua_out' => empty($params['outA']) ? 0 : $params['outA'],
            'ua_off_net' => empty($params['netA']) ? 0 : $params['netA'],
            'ua_high_speed' => empty($params['high_speedA']) ? 0 : $params['high_speedA'],
            'ua_avg_speed' => empty($params['avg_speedA']) ? 0 : $params['avg_speedA'],

            // B面用户数据
            'ub_score' => empty($params['scoreB']) ? 0 : $params['scoreB'],
            'ub_times' => empty($params['timesB']) ? 0 : $params['timesB'],
            'ub_rate' => empty($params['rateB']) ? 0 : $params['rateB'],
            'ub_out' => empty($params['outB']) ? 0 : $params['outB'],
            'ub_off_net' => empty($params['netB']) ? 0 : $params['netB'],
            'ub_high_speed' => empty($params['high_speedB']) ? 0 : $params['high_speedB'],
            'ub_avg_speed' => empty($params['avg_speedB']) ? 0 : $params['avg_speedB'],

            'status' => 1,
            'create_time' => date('Y-m-d H:i:s')
        );

        $res = $this->addRecord($trainStats);
        if (empty($res)) {
//            trace('insert TennisTrainStats with [' . json_encode($trainStats) . '] failed.', 'insertTrainData', 'DEBUG', true);
            return false;
        }

        // update train data.
        $trainData = array(
            'end_time' => empty($params['end_time']) ? time() : $params['end_time'],
            'ua_score' => empty($params['scoreA']) ? 0 : $params['scoreA'],
            'ua_times' => empty($params['timesA']) ? 0 : $params['timesA'],
            'ub_score' => empty($params['scoreB']) ? 0 : $params['scoreB'],
            'ub_times' => empty($params['timesB']) ? 0 : $params['timesB'],
            'status' => 2,
            'update_time' => date('Y-m-d H:i:s')
        );
        $TennisTrain=new TennisTrain();
        $where=array('id' => $params['train_id'], 'status' => 1);
        $res = $TennisTrain->updateRecord($trainData,$where);
        if (empty($res)) {
//            trace('update TennisTrain with [' . json_encode($trainData) . '] failed.', 'insertTrainData', 'DEBUG', true);
            return false;
        }

        return true;
    }
}
