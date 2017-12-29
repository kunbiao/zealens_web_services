<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "stadium".
 *
 * @property string $address 场馆地址
 * @property string $iphone 联系方式
 * @property string $creat_time 创建时间
 * @property string $stadium_name 场馆名字
 * @property int $id id
 */
class Stadium extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stadium';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creat_time'], 'safe'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['address', 'iphone', 'stadium_name'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'address' => 'Address',
            'iphone' => 'Iphone',
            'creat_time' => 'Creat Time',
            'stadium_name' => 'Stadium Name',
            'id' => 'ID',
        ];
    }
}
