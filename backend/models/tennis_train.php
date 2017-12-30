<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tennis_train".
 *
 * @property string $id
 * @property string $a A面用户ID
 * @property string $b B面用户ID
 * @property int $ua_type A面训练项目，1:发球，2:接发球，3:正手击球，4:正手直线，5:正手斜线，6:反手击球，7:反手直线，8:反手斜线，9:截击，10:随意打，11：教练喂球
 * @property int $ub_type B面训练项目，1:发球，2:接发球，3:正手击球，4:正手直线，5:正手斜线，6:反手击球，7:反手直线，8:反手斜线，9:截击，10:随意打，11：教练喂球
 * @property int $mode 训练类型，发球：[1:不限，2:内，3:外]；其他项目：[1:易，2:中，3:难]
 * @property int $group 训练组，1:固定时间，2:固定次数，3:随意
 * @property string $begin_time 训练开始时间
 * @property string $end_time 训练结束时间
 * @property int $ua_score A面成功次数
 * @property int $ua_times A面训练次数
 * @property int $ub_score B面成功次数
 * @property int $ub_times B面训练次数
 * @property int $status 状态 0:无效，1:训练进行中，2:训练结束
 * @property string $site_id 场馆id
 * @property string $court_id 场地id
 * @property string $create_time
 * @property string $update_time
 */
class tennis_train extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tennis_train';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['a', 'b', 'ua_type', 'ub_type', 'mode', 'group', 'begin_time', 'end_time', 'ua_score', 'ua_times', 'ub_score', 'ub_times', 'status', 'site_id', 'court_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'a' => 'A',
            'b' => 'B',
            'ua_type' => 'Ua Type',
            'ub_type' => 'Ub Type',
            'mode' => 'Mode',
            'group' => 'Group',
            'begin_time' => 'Begin Time',
            'end_time' => 'End Time',
            'ua_score' => 'Ua Score',
            'ua_times' => 'Ua Times',
            'ub_score' => 'Ub Score',
            'ub_times' => 'Ub Times',
            'status' => 'Status',
            'site_id' => 'Site ID',
            'court_id' => 'Court ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
