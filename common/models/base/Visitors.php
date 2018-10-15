<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%visitors}}".
 *
 * @property string $id
 * @property string $create_id
 * @property string $visitor_name
 * @property string $visitor_mobile
 * @property integer $visitor_sex
 * @property string $reservation
 * @property string $alone
 * @property string $activity
 * @property string $referrer_name
 * @property string $referrer_mobile
 * @property string $transportation
 * @property string $visitor_sport_status
 * @property string $visitor_hope_time
 * @property string $visitor_hope_limit
 * @property string $visitor_like_equipment
 * @property string $visitor_like_yoga
 * @property string $visitor_like_dance
 * @property string $visitor_like_course
 * @property string $visitor_aims
 * @property string $visitor_ready_time
 * @property string $visitor_fulfil
 * @property string $create_at
 */
class Visitors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%visitors}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_id'], 'required'],
            [['create_id', 'visitor_sex', 'create_at'], 'integer'],
            [['visitor_name', 'visitor_mobile', 'referrer_name', 'referrer_mobile', 'transportation', 'visitor_sport_status', 'visitor_hope_time', 'visitor_hope_limit', 'visitor_like_equipment', 'visitor_like_yoga', 'visitor_like_dance', 'visitor_like_course', 'visitor_aims', 'visitor_ready_time', 'visitor_fulfil'], 'string', 'max' => 200],
            [['reservation', 'alone', 'activity'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'create_id' => '创建人id',
            'visitor_name' => '访客姓名',
            'visitor_mobile' => '访客手机号',
            'visitor_sex' => '访客性别：1表示男；2表示女',
            'reservation' => '预约来店方式',
            'alone' => '自访来店方式',
            'activity' => '活动来店方式',
            'referrer_name' => '推荐人姓名',
            'referrer_mobile' => '推荐人手机号',
            'transportation' => '访客交通方式',
            'visitor_sport_status' => '访客运动状态',
            'visitor_hope_time' => '访客希望上课时间',
            'visitor_hope_limit' => '访客希望上课时段',
            'visitor_like_equipment' => '访客喜欢的健身设备',
            'visitor_like_yoga' => '访客喜欢的瑜伽',
            'visitor_like_dance' => '访客喜欢的舞蹈',
            'visitor_like_course' => '访客喜欢的课程',
            'visitor_aims' => '访客的健身目标',
            'visitor_ready_time' => '访客想完成健身目标有多长时间了，',
            'visitor_fulfil' => '访客希望达到目标的时间',
            'create_at' => '添加时间',
        ];
    }
}
