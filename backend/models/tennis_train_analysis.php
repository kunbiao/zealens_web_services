<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tennis_train_analysis".
 *
 * @property string $id
 * @property string $train_id 训练id
 * @property string $ua_inside_hit A面场内击球数
 * @property string $ua_ground_hit A面底线击球数,底线外2米
 * @property string $ua_off_hit A面场外击球数，底线2米外
 * @property string $ua_down A面(击球或发球）落点数据分布
 * @property string $ub_inside_hit B面场内击球数
 * @property string $ub_ground_hit B面底线击球数,底线外2米
 * @property string $ub_off_hit B面场外击球数，底线2米外
 * @property string $ub_down B面(击球或发球）落点数据分布
 * @property string $ua_hit_attr A面击球点数据
 * @property string $ua_down_attr A面(击球或发球）落点数据
 * @property string $ub_hit_attr B面击球点数据
 * @property string $ub_down_attr B面(击球或发球）落点数据
 * @property int $status 状态
 * @property string $create_time
 */
class tennis_train_analysis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tennis_train_analysis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['train_id', 'ua_inside_hit', 'ua_ground_hit', 'ua_off_hit', 'ub_inside_hit', 'ub_ground_hit', 'ub_off_hit', 'status'], 'integer'],
            [['ua_hit_attr', 'ua_down_attr', 'ub_hit_attr', 'ub_down_attr'], 'required'],
            [['ua_hit_attr', 'ua_down_attr', 'ub_hit_attr', 'ub_down_attr'], 'string'],
            [['create_time'], 'safe'],
            [['ua_down', 'ub_down'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'train_id' => 'Train ID',
            'ua_inside_hit' => 'Ua Inside Hit',
            'ua_ground_hit' => 'Ua Ground Hit',
            'ua_off_hit' => 'Ua Off Hit',
            'ua_down' => 'Ua Down',
            'ub_inside_hit' => 'Ub Inside Hit',
            'ub_ground_hit' => 'Ub Ground Hit',
            'ub_off_hit' => 'Ub Off Hit',
            'ub_down' => 'Ub Down',
            'ua_hit_attr' => 'Ua Hit Attr',
            'ua_down_attr' => 'Ua Down Attr',
            'ub_hit_attr' => 'Ub Hit Attr',
            'ub_down_attr' => 'Ub Down Attr',
            'status' => 'Status',
            'create_time' => 'Create Time',
        ];
    }
}
