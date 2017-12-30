<?php
namespace app\models;
use yii\db\ActiveRecord;
class User extends ActiveRecord{
    public function ceshi(){
//        $User = User::findOne(1);
        $b=array('location'=>'啊');
        $a=array('username'=>'123','email'=>'777777');
//        $kkk = User::updateAll($a,$b);
        User::setAttributes($b);
        User::save();
//        var_dump($kkk);
//        $User->username = 'username';
//        $User->save($b);
    }
}