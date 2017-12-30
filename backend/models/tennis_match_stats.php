<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tennis_match_stats".
 *
 * @property string $id
 * @property string $match_id 比赛id
 * @property int $ua_ace A面ace数
 * @property int $ua_dfault A面双误数
 * @property int $ua_fsin A面一发进球率
 * @property int $ua_fs_point A面一发得分率
 * @property int $ua_ss_point A面二发得分率
 * @property int $ua_bpwon A面破发得分
 * @property int $ua_bp_count A面破发点
 * @property int $ua_win_point A面制胜分
 * @property int $ua_high_speed A面最高发球速度
 * @property int $ua_avg_speed A面平均发球速度
 * @property int $ua_score A面得分
 * @property int $ub_ace B面ace数
 * @property int $ub_dfault B面双误数
 * @property int $ub_fsin B面一发进球率
 * @property int $ub_fs_point B面一发得分率
 * @property int $ub_ss_point B面二发得分率
 * @property int $ub_bpwon B面破发得分
 * @property int $ub_bp_count B面破发点
 * @property int $ub_win_point B面制胜分
 * @property int $ub_high_speed B面最高发球速度
 * @property int $ub_avg_speed B面平均发球速度
 * @property int $ub_score B面得分
 * @property int $status 状态
 * @property string $create_time
 */
class tennis_match_stats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tennis_match_stats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id', 'ua_ace', 'ua_dfault', 'ua_fsin', 'ua_fs_point', 'ua_ss_point', 'ua_bpwon', 'ua_bp_count', 'ua_win_point', 'ua_high_speed', 'ua_avg_speed', 'ua_score', 'ub_ace', 'ub_dfault', 'ub_fsin', 'ub_fs_point', 'ub_ss_point', 'ub_bpwon', 'ub_bp_count', 'ub_win_point', 'ub_high_speed', 'ub_avg_speed', 'ub_score', 'status'], 'integer'],
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
            'match_id' => 'Match ID',
            'ua_ace' => 'Ua Ace',
            'ua_dfault' => 'Ua Dfault',
            'ua_fsin' => 'Ua Fsin',
            'ua_fs_point' => 'Ua Fs Point',
            'ua_ss_point' => 'Ua Ss Point',
            'ua_bpwon' => 'Ua Bpwon',
            'ua_bp_count' => 'Ua Bp Count',
            'ua_win_point' => 'Ua Win Point',
            'ua_high_speed' => 'Ua High Speed',
            'ua_avg_speed' => 'Ua Avg Speed',
            'ua_score' => 'Ua Score',
            'ub_ace' => 'Ub Ace',
            'ub_dfault' => 'Ub Dfault',
            'ub_fsin' => 'Ub Fsin',
            'ub_fs_point' => 'Ub Fs Point',
            'ub_ss_point' => 'Ub Ss Point',
            'ub_bpwon' => 'Ub Bpwon',
            'ub_bp_count' => 'Ub Bp Count',
            'ub_win_point' => 'Ub Win Point',
            'ub_high_speed' => 'Ub High Speed',
            'ub_avg_speed' => 'Ub Avg Speed',
            'ub_score' => 'Ub Score',
            'status' => 'Status',
            'create_time' => 'Create Time',
        ];
    }
}
