<?php
namespace frontend\models;
class Site extends Common
{
    public function getSiteInfo($id)
    {
        $where = array('id' => $id, 'status' => 1);
        return Site::find()->asArray()->where($where)->one();
    }

}