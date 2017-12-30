<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tennis_train_stats".
 *
 * @property string $id
 * @property string $train_id 训练id
 * @property int $ua_score A面成功次数
 * @property int $ua_times A面总次数
 * @property int $ua_rate A面有效率
 * @property string $ua_out A面出界次数
 * @property string $ua_off_net A面下网次数
 * @property int $ua_high_speed A面最高发球速度
 * @property int $ua_avg_speed A面平均发球速度
 * @property int $ub_score B面成功次数
 * @property int $ub_times B面总次数
 * @property int $ub_rate B面有效率
 * @property string $ub_out B面出界次数
 * @property string $ub_off_net B面下网次数
 * @property int $ub_high_speed B面最高发球速度
 * @property int $ub_avg_speed B面平均发球速度
 * @property int $status 状态
 * @property string $create_time
 */
class tennis_train_stats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tennis_train_stats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['train_id', 'ua_score', 'ua_times', 'ua_rate', 'ua_out', 'ua_off_net', 'ua_high_speed', 'ua_avg_speed', 'ub_score', 'ub_times', 'ub_rate', 'ub_out', 'ub_off_net', 'ub_high_speed', 'ub_avg_speed', 'status'], 'integer'],
            [['create_time'], 'safe'],
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
            'ua_score' => 'Ua Score',
            'ua_times' => 'Ua Times',
            'ua_rate' => 'Ua Rate',
            'ua_out' => 'Ua Out',
            'ua_off_net' => 'Ua Off Net',
            'ua_high_speed' => 'Ua High Speed',
            'ua_avg_speed' => 'Ua Avg Speed',
            'ub_score' => 'Ub Score',
            'ub_times' => 'Ub Times',
            'ub_rate' => 'Ub Rate',
            'ub_out' => 'Ub Out',
            'ub_off_net' => 'Ub Off Net',
            'ub_high_speed' => 'Ub High Speed',
            'ub_avg_speed' => 'Ub Avg Speed',
            'status' => 'Status',
            'create_time' => 'Create Time',
        ];
    }
}
