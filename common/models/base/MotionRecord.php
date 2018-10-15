<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%motion_record}}".
 *
 * @property string $id 自增ID
 * @property string $member_id 会员编号
 * @property string $venue_id 场馆编号
 * @property string $company_id 公司标识
 * @property string $member_card_id 会员卡编号
 * @property string $user_code 卡号
 * @property string $create_at 创建时间
 * @property int $status 状态
 * @property int $type 类型 1 跑步机, 2 椭圆机
 * @property int $distict 距离(千米)
 * @property int $calories 卡路里
 * @property int $speed 最大速度(千米/小时)
 * @property int $time 时长(秒)
 * @property int $rate 心率
 * @property int $slope 坡度
 * @property int $resistance 阻力
 * @property int $sort 排序
 */
class MotionRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%motion_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'venue_id', 'company_id', 'member_card_id', 'create_at', 'status', 'type', 'distict', 'calories', 'speed', 'time', 'rate', 'slope', 'resistance', 'sort'], 'integer'],
            [['user_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'venue_id' => 'Venue ID',
            'company_id' => 'Company ID',
            'member_card_id' => 'Member Card ID',
            'user_code' => 'User Code',
            'create_at' => 'Create At',
            'status' => 'Status',
            'type' => 'Type',
            'distict' => 'Distict',
            'calories' => 'Calories',
            'speed' => 'Speed',
            'time' => 'Time',
            'rate' => 'Rate',
            'slope' => 'Slope',
            'resistance' => 'Resistance',
            'sort' => 'Sort',
        ];
    }
}
