<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tennis_train_video".
 *
 * @property string $id
 * @property string $train_id 训练id
 * @property string $begin_time 比赛开始时间
 * @property string $end_time 比赛结束时间
 * @property string $desc 视频描述
 * @property int $cateid 视频分类
 * @property string $tags 视频标签
 * @property int $ua_score A面比分
 * @property string $ua_title A面视频标题
 * @property string $ua_name A面视频源文件名，包含扩展名
 * @property string $ua_size A面视频文件大小，单位KB
 * @property string $ua_coverURL A面视频封页地址
 * @property string $ua_videoID A面视频ID或者KEY
 * @property int $ua_status 状态 0:无效，1:正在进行上传，2:上传结束
 * @property int $ub_score B面比分
 * @property string $ub_title B面视频标题
 * @property string $ub_name B面视频源文件名，包含扩展名
 * @property string $ub_size B面视频文件大小，单位KB
 * @property string $ub_coverURL B面视频封页地址
 * @property string $ub_videoID B面视频ID或者KEY
 * @property int $ub_status 状态 0:无效，1:正在进行上传，2:上传结束
 * @property string $dataAttr 视频事件数据
 * @property string $create_time
 * @property string $update_time
 */
class tennis_train_video extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tennis_train_video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['train_id', 'begin_time', 'end_time', 'cateid', 'ua_score', 'ua_size', 'ua_status', 'ub_score', 'ub_size', 'ub_status'], 'integer'],
            [['dataAttr'], 'required'],
            [['dataAttr'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['desc'], 'string', 'max' => 1024],
            [['tags'], 'string', 'max' => 64],
            [['ua_title', 'ua_name', 'ub_title', 'ub_name'], 'string', 'max' => 128],
            [['ua_coverURL', 'ub_coverURL'], 'string', 'max' => 250],
            [['ua_videoID', 'ub_videoID'], 'string', 'max' => 512],
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
            'begin_time' => 'Begin Time',
            'end_time' => 'End Time',
            'desc' => 'Desc',
            'cateid' => 'Cateid',
            'tags' => 'Tags',
            'ua_score' => 'Ua Score',
            'ua_title' => 'Ua Title',
            'ua_name' => 'Ua Name',
            'ua_size' => 'Ua Size',
            'ua_coverURL' => 'Ua Cover Url',
            'ua_videoID' => 'Ua Video ID',
            'ua_status' => 'Ua Status',
            'ub_score' => 'Ub Score',
            'ub_title' => 'Ub Title',
            'ub_name' => 'Ub Name',
            'ub_size' => 'Ub Size',
            'ub_coverURL' => 'Ub Cover Url',
            'ub_videoID' => 'Ub Video ID',
            'ub_status' => 'Ub Status',
            'dataAttr' => 'Data Attr',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
