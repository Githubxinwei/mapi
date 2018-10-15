<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%about_class}}".
 *
 * @property string $id
 * @property string $member_card_id
 * @property string $class_id
 * @property integer $status
 * @property string $cancel_reason
 * @property string $type
 * @property string $create_at
 * @property string $seat_id
 * @property integer $limit_time
 * @property string $coach_id
 * @property string $class_date
 * @property string $start
 * @property string $end
 * @property string $cancel_time
 * @property string $member_id
 * @property integer $is_print_receipt
 * @property integer $about_type
 * @property string $employee_id
 * @property integer $is_read
 * @property string $in_time
 */
class AboutClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%about_class}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_card_id', 'class_id', 'status', 'create_at', 'seat_id', 'limit_time', 'coach_id', 'start', 'end', 'cancel_time', 'member_id', 'is_print_receipt', 'about_type', 'employee_id', 'is_read', 'in_time'], 'integer'],
            [['class_id'], 'required'],
            [['class_date'], 'safe'],
            [['cancel_reason'], 'string', 'max' => 20],
            [['type'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'member_card_id' => '会员卡ID',
            'class_id' => '课程Id',
            'status' => '1:未上课 2:取消预约 3:上课中 4:下课 5:过期 6:旷课(卡未被冻结) 7:旷课(团课爽约)',
            'cancel_reason' => '取消原因',
            'type' => '类型:例如团课私课',
            'create_at' => '创建时间',
            'seat_id' => '座位号ID',
            'limit_time' => '上课前多长时间不能取消预约',
            'coach_id' => '教练id',
            'class_date' => '上课日期',
            'start' => '开始时间',
            'end' => '结束时间',
            'cancel_time' => '取消预约时间',
            'member_id' => '会员ID',
            'is_print_receipt' => '是否打印过小票（1有2没有）',
            'about_type' => '预约类型（1电话预约 2自助预约）',
            'employee_id' => '员工id',
            'is_read' => '是否已读',
            'in_time' => '手环上课打卡时间',
        ];
    }
}
