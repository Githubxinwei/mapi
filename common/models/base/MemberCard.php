<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_card}}".
 *
 * @property string $id
 * @property string $card_number
 * @property string $create_at
 * @property string $amount_money
 * @property integer $status
 * @property string $active_time
 * @property integer $payment_type
 * @property integer $is_complete_pay
 * @property integer $pay_times
 * @property integer $paid_months
 * @property string $total_times
 * @property string $consumption_times
 * @property string $invalid_time
 * @property string $balance
 * @property integer $is_share
 * @property string $frozen_start_time
 * @property string $frozen_end_time
 * @property string $level
 * @property string $card_category_id
 * @property string $member_id
 * @property string $payment_time
 * @property integer $consume_status
 * @property string $present_money
 * @property integer $present_private_lesson
 * @property string $describe
 * @property integer $first_pay_months
 * @property string $first_pay_price
 * @property string $month_price
 * @property string $employee_id
 * @property string $card_name
 * @property string $another_name
 * @property string $card_type
 * @property integer $count_method
 * @property integer $attributes
 * @property string $active_limit_time
 * @property integer $transfer_num
 * @property string $transfer_price
 * @property string $recharge_price
 * @property string $renew_price
 * @property string $renew_best_price
 * @property integer $renew_unit
 * @property integer $leave_total_days
 * @property integer $leave_least_days
 * @property integer $leave_times
 * @property integer $leave_once_days
 * @property string $deal_id
 * @property string $leave_days_times
 * @property integer $duration
 * @property integer $surplus
 * @property string $company_id
 * @property string $venue_id
 * @property string $absentTimes
 * @property integer $usage_mode
 * @property integer $pid
 * @property string $binding_time
 * @property string $last_freeze_time
 * @property integer $recent_freeze_reason
 * @property integer $bring
 * @property string $ordinary_renewal
 * @property string $validity_renewal
 * @property string $note
 * @property integer $card_attribute
 * @property string $pic
 * @property double $single
 * @property integer $type
 * @property string $student_leave_limit
 * @property integer $leave_type
 */
class MemberCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_card}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_number', 'payment_type', 'is_complete_pay', 'invalid_time', 'level', 'card_category_id', 'member_id'], 'required'],
            [['create_at', 'status', 'active_time', 'payment_type', 'is_complete_pay', 'pay_times', 'paid_months', 'total_times', 'consumption_times', 'invalid_time', 'is_share', 'frozen_start_time', 'frozen_end_time', 'level', 'card_category_id', 'member_id', 'payment_time', 'consume_status', 'present_private_lesson', 'first_pay_months', 'employee_id', 'card_type', 'count_method', 'attributes', 'active_limit_time', 'transfer_num', 'renew_unit', 'leave_total_days', 'leave_least_days', 'leave_times', 'leave_once_days', 'deal_id', 'duration', 'surplus', 'company_id', 'venue_id', 'absentTimes', 'usage_mode', 'pid', 'binding_time', 'last_freeze_time', 'recent_freeze_reason', 'bring', 'card_attribute', 'type', 'leave_type'], 'integer'],
            [['amount_money', 'balance', 'present_money', 'first_pay_price', 'month_price', 'transfer_price', 'recharge_price', 'renew_price', 'renew_best_price', 'ordinary_renewal', 'single'], 'number'],
            [['describe', 'leave_days_times', 'validity_renewal', 'pic', 'student_leave_limit'], 'string'],
            [['card_number', 'card_name', 'another_name', 'note'], 'string', 'max' => 200],
            [['card_number'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'card_number' => '卡号',
            'create_at' => '开卡时间',
            'amount_money' => '卡金额',
            'status' => '状态：1正常，2异常，3冻结，4未激活',
            'active_time' => '激活时间',
            'payment_type' => '付款类型：1全款，2分期',
            'is_complete_pay' => '是否完成付款',
            'pay_times' => '付款次数',
            'paid_months' => '已付月数',
            'total_times' => '总次数（次卡）',
            'consumption_times' => '消费次数',
            'invalid_time' => '失效时间',
            'balance' => '余额',
            'is_share' => '分享信息（针对会员带人）',
            'frozen_start_time' => '冻结开始时间',
            'frozen_end_time' => '冻结结束时间',
            'level' => '等级',
            'card_category_id' => '卡种ID',
            'member_id' => '会员ID',
            'payment_time' => '缴费时间',
            'consume_status' => '1定金 2:发卡3:续费4:回款',
            'present_money' => '买赠金额',
            'present_private_lesson' => '赠送私教课',
            'describe' => '例如:0217683 0000845 原价2188打九折',
            'first_pay_months' => '首付月数',
            'first_pay_price' => '首付金额',
            'month_price' => '每月还款金额',
            'employee_id' => '员工ID(销售)',
            'card_name' => '卡名',
            'another_name' => '另一个卡名',
            'card_type' => '卡类别',
            'count_method' => '计次方式:1按时效,2按次数',
            'attributes' => '属性:1个人,2家庭,3公司',
            'active_limit_time' => '激活期限',
            'transfer_num' => '转让次数',
            'transfer_price' => '转让金额',
            'recharge_price' => '充值卡充值金额',
            'renew_price' => '续费价',
            'renew_best_price' => '续费优惠价',
            'renew_unit' => '续费多长时间/天',
            'leave_total_days' => '请假总天数',
            'leave_least_days' => '每次请假最少天数',
            'leave_times' => '请假次数',
            'leave_once_days' => '每次请假天数',
            'deal_id' => '合同id',
            'leave_days_times' => '每次天数,次数[天，次]',
            'duration' => '有效期',
            'surplus' => '剩余转让次数',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
            'absentTimes' => '缺课次数',
            'usage_mode' => '使用方式：1自用;2送人',
            'pid' => '主卡的id',
            'binding_time' => '副卡的绑定时间',
            'last_freeze_time' => '最后一次冻结时间',
            'recent_freeze_reason' => '1:团课爽约被冻结 2:其它原因被冻结',
            'bring' => '带人，0:不带人',
            'ordinary_renewal' => '普通续费',
            'validity_renewal' => '有效期续费',
            'note' => '备注',
            'card_attribute' => '1.个人,2.公司,3.团体,4学生卡',
            'pic' => '图片',
            'single' => '单数',
            'type' => '卡类型1瑜伽,2健身,3舞蹈,4综合',
            'student_leave_limit' => '学生暑假请假天数,学生寒假请假次数[天，次],[天，次]',
            'leave_type' => '请假类型:NULL正常请假,1学生请假',
        ];
    }
}
