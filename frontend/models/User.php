<?php
namespace frontend\models;
class User extends Common
{
    public function getUserInfo($id)
    {
        if (empty($id)) {
            return false;
        }

        $where = array('id' => $id, 'status' => 1);
        return User::find()->asArray()->where($where)->one();
    }
    public function rules()
    {
        return [
            [['mobile', 'credential'], 'required'],
            [['type', 'status', 'identify_expTime', 'age', 'is_follow', 'follow_time'], 'integer'],
            [['provide_type', 'gender'], 'string'],
            [['last_time', 'last_wechat_time', 'create_time', 'update_time'], 'safe'],
            [['mobile'], 'string', 'max' => 12],
            [['credential', 'trade', 'location'], 'string', 'max' => 100],
            [['from'], 'string', 'max' => 24],
            [['provide_id'], 'string', 'max' => 255],
            [['identify', 'access_token'], 'string', 'max' => 200],
            [['nickname'], 'string', 'max' => 120],
            [['portrait'], 'string', 'max' => 250],
        ];
    }
    public function getUserInfoWithUnionID($unionID)
    {
        if (empty($unionID)) {
            return NULL;
        }

        $where = array('identify' => $unionID, 'status' => 1);
        $record = User::find()->asArray()->where($where)->one();;
        if (empty($record)) {
            return NULL;
        }

        $now = date('Y-m-d H:i:s');
        $this->updateRecord(array('last_time' => $now, 'update_time' => $now),$where);
        return $record;
    }

    public function addUser($unionID)
    {
        $data = array(
            'identify' => $unionID,
            'status' => 1,
            'is_follow' => 0,
            'last_time' => date('Y-m-d H:i:s'),
            'create_time' => date('Y-m-d H:i:s')
        );

        $id = $this->addRecord($data);
        return array('id' => $id);
    }
}
