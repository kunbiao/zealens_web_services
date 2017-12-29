<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tennis_match_analysis".
 *
 * @property string $id
 * @property string $match_id 比赛id
 * @property string $ua_net_hit A面网前击球数--暂不区分
 * @property string $ua_inside_hit A面场内击球数
 * @property string $ua_ground_hit A面底线击球数,底线外2米
 * @property string $ua_off_hit A面场外击球数，底线2米外
 * @property string $ua_fsin A面一发落点分布
 * @property string $ua_ssin A面二发落点分布
 * @property string $ub_net_hit B面网前击球数--暂不区分
 * @property string $ub_inside_hit B面场内击球数
 * @property string $ub_ground_hit B面底线击球数,底线外2米
 * @property string $ub_off_hit B面场外击球数，底线2米外
 * @property string $ub_fsin B面一发落点分布
 * @property string $ub_ssin B面二发落点分布
 * @property string $ua_ace_attr A面ace球落点数据
 * @property string $ua_fsin_attr A面一发落点数据
 * @property string $ua_ssin_attr A面二发落点数据
 * @property string $ua_hit_attr A面击球落点数据
 * @property string $ub_ace_attr B面ace球落点数据
 * @property string $ub_fsin_attr B面一发落点数据
 * @property string $ub_ssin_attr B面二发落点数据
 * @property string $ub_hit_attr B面击球落点数据
 * @property int $status 状态
 * @property string $create_time
 */
class tennis_match_analysis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tennis_match_analysis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id', 'ua_net_hit', 'ua_inside_hit', 'ua_ground_hit', 'ua_off_hit', 'ub_net_hit', 'ub_inside_hit', 'ub_ground_hit', 'ub_off_hit', 'status'], 'integer'],
            [['ua_ace_attr', 'ua_fsin_attr', 'ua_ssin_attr', 'ua_hit_attr', 'ub_ace_attr', 'ub_fsin_attr', 'ub_ssin_attr', 'ub_hit_attr'], 'required'],
            [['ua_ace_attr', 'ua_fsin_attr', 'ua_ssin_attr', 'ua_hit_attr', 'ub_ace_attr', 'ub_fsin_attr', 'ub_ssin_attr', 'ub_hit_attr'], 'string'],
            [['create_time'], 'safe'],
            [['ua_fsin', 'ua_ssin', 'ub_fsin', 'ub_ssin'], 'string', 'max' => 64],
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
            'ua_net_hit' => 'Ua Net Hit',
            'ua_inside_hit' => 'Ua Inside Hit',
            'ua_ground_hit' => 'Ua Ground Hit',
            'ua_off_hit' => 'Ua Off Hit',
            'ua_fsin' => 'Ua Fsin',
            'ua_ssin' => 'Ua Ssin',
            'ub_net_hit' => 'Ub Net Hit',
            'ub_inside_hit' => 'Ub Inside Hit',
            'ub_ground_hit' => 'Ub Ground Hit',
            'ub_off_hit' => 'Ub Off Hit',
            'ub_fsin' => 'Ub Fsin',
            'ub_ssin' => 'Ub Ssin',
            'ua_ace_attr' => 'Ua Ace Attr',
            'ua_fsin_attr' => 'Ua Fsin Attr',
            'ua_ssin_attr' => 'Ua Ssin Attr',
            'ua_hit_attr' => 'Ua Hit Attr',
            'ub_ace_attr' => 'Ub Ace Attr',
            'ub_fsin_attr' => 'Ub Fsin Attr',
            'ub_ssin_attr' => 'Ub Ssin Attr',
            'ub_hit_attr' => 'Ub Hit Attr',
            'status' => 'Status',
            'create_time' => 'Create Time',
        ];
    }
}
