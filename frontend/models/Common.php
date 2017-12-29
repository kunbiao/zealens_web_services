<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

//ActiveRecord
class Common extends ActiveRecord
{


    /**
     * @desc 单条记录
     *
     * @param array where
     * @return object
     */
    public function getRecord($where)
    {
        $result = self::find()->asArray()->where($where)->one();;
        return $result;
    }

    /**
     * @desc 添加记录
     *
     * @param array $data
     * @return bool
     */
    public function addRecord($data = array())
    {
        $this->setAttributes($data);
        
        $this->save();

        return Yii::$app->db->getLastInsertID();
    }

    /**
     * @desc 修改记录
     *
     * @param array $data
     * @param array $where
     * @return bool
     */
    public function updateRecord($data = array(), $where = array())
    {
        return $this->updateAll($data,$where);
    }

    /**
     * @desc 假删除记录，更新STATUS＝0
     *
     * @return bool
     */
    public function deleteRecord($where)
    {
       return $this->updateRecord(array( "status" => 0, "update_time" => time()), $where);
    }

    public  function leapYear($year)
    {
        if(($year%4 == 0 && $year%100 != 0) || ($year%400 == 0 )) {
            return true;
        }else {
            return false;
        }
    }

}