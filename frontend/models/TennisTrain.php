<?php
namespace frontend\models;
class TennisTrain extends Common
{
    public function rules()
    {
        return [
            [['a', 'b', 'ua_type', 'ub_type', 'mode', 'group', 'begin_time', 'end_time', 'ua_score', 'ua_times', 'ub_score', 'ub_times', 'status', 'site_id', 'court_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
        ];
    }
    public function start($params)
    {
        return 1;
    }

    public function updateMatchData($matchData)
    {
        // update match table

        return 1;
    }
}
