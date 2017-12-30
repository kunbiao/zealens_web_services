<?php
namespace frontend\models;

class TennisMatch extends Common
{
    public function rules()
    {
        return [
            [['a1', 'a2', 'b1', 'b2', 'type', 'mode', 'begin_time', 'end_time', 'ua_score', 'ub_score', 'status', 'site_id', 'court_id'], 'integer'],
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
