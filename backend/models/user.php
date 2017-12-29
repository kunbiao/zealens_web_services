<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id 自增id
 * @property string $mobile 手机号
 * @property string $credential 密码
 * @property string $from 注册来源, wechat, xxx
 * @property int $type 用户类型，1:普通用户，2:客服
 * @property int $status 用户状态，1:正常，0:注销
 * @property string $provide_type 第三方登录提供方代号(0:应用注册，1:微信,2:qq)
 * @property string $provide_id 第三方open_id
 * @property string $last_time 上次客户端登陆时间
 * @property string $last_wechat_time 上次微信访问时间
 * @property string $identify 用户身份标识，目前是微信id，即unionid
 * @property string $access_token access_token，访问微信的票据信息
 * @property int $identify_expTime 票据过期时间
 * @property string $nickname 用户昵称
 * @property int $age 用户年龄
 * @property string $gender 用户性别(1:男,2:女)
 * @property string $portrait 用户头像
 * @property int $is_follow 是否关注公众号，1:已关注，0:未关注
 * @property string $follow_time 用户关注公众号的时间
 * @property string $trade 用户所在行业
 * @property string $location 用户所在城市
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 */
class user extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'credential'], 'required'],
            [['type', 'status', 'identify_expTime', 'age', 'is_follow', 'follow_time'], 'integer'],
            [['provide_type', 'gender'], 'string'],
            [['last_time', 'last_wechat_time', 'create_time', 'update_time'], 'safe'],
            [['mobile'], 'string', 'max' => 12],
            [['credential', 'trade', 'location'], 'string', 'max' => 100],
            [['from'], 'string', 'max' => 24],
            [['provide_id'], 'string', 'max' => 255],
            [['identify', 'access_token'], 'string', 'max' => 200],
            [['nickname'], 'string', 'max' => 120],
            [['portrait'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'credential' => 'Credential',
            'from' => 'From',
            'type' => 'Type',
            'status' => 'Status',
            'provide_type' => 'Provide Type',
            'provide_id' => 'Provide ID',
            'last_time' => 'Last Time',
            'last_wechat_time' => 'Last Wechat Time',
            'identify' => 'Identify',
            'access_token' => 'Access Token',
            'identify_expTime' => 'Identify Exp Time',
            'nickname' => 'Nickname',
            'age' => 'Age',
            'gender' => 'Gender',
            'portrait' => 'Portrait',
            'is_follow' => 'Is Follow',
            'follow_time' => 'Follow Time',
            'trade' => 'Trade',
            'location' => 'Location',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
