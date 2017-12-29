<?php
namespace frontend\models;
class UserTrain extends Common
{
    public function rules()
    {
        return [
            [['user_id', 'train_id', 'site_id', 'court_id', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
        ];
    }
    public function add($uIDs, $data)
    {
        foreach ($uIDs as $userID) {
            $data['user_id'] = $userID;
            $this->addRecord($data);
        }

    }
}
