<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_match".
 *
 * @property string $id
 * @property string $user_id 用户ID
 * @property string $match_id 比赛id
 * @property string $site_id 场馆id
 * @property string $court_id 场地id
 * @property int $status 状态
 * @property string $create_time
 * @property string $update_time
 */
class TennisMath extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_match';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'match_id', 'site_id', 'court_id', 'status'], 'integer'],
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
            'user_id' => 'User ID',
            'match_id' => 'Match ID',
            'site_id' => 'Site ID',
            'court_id' => 'Court ID',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
