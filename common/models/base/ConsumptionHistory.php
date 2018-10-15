<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%consumption_history}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $consumption_type
 * @property string $consumption_type_id
 * @property string $type
 * @property string $consumption_date
 * @property string $consumption_time
 * @property string $consumption_times
 * @property string $cashier_order
 * @property string $cash_payment
 * @property string $bank_card_payment
 * @property string $mem_card_payment
 * @property string $coupon_payment
 * @property string $transfer_accounts
 * @property string $other_payment
 * @property string $network_payment
 * @property string $integration_payment
 * @property string $discount_payment
 * @property string $venue_id
 * @property string $seller_id
 * @property string $describe
 * @property string $category
 * @property string $company_id
 * @property string $consumption_amount
 * @property string $consume_describe
 * @property string $remarks
 * @property string $due_date
 * @property string $payment_name
 * @property string $activate_date
 */
class ConsumptionHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%consumption_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'consumption_type', 'consumption_type_id'], 'required'],
            [['member_id', 'consumption_type_id', 'type', 'consumption_date', 'consumption_time', 'consumption_times', 'venue_id', 'seller_id', 'company_id', 'due_date', 'activate_date'], 'integer'],
            [['cash_payment', 'bank_card_payment', 'mem_card_payment', 'coupon_payment', 'transfer_accounts', 'other_payment', 'network_payment', 'integration_payment', 'discount_payment', 'consumption_amount'], 'number'],
            [['describe', 'consume_describe', 'remarks'], 'string'],
            [['consumption_type', 'category', 'payment_name'], 'string', 'max' => 200],
            [['cashier_order'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'member_id' => '会员id ',
            'consumption_type' => '消费类型',
            'consumption_type_id' => '消费项目id',
            'type' => '消费方式(1现金2次卡3充值卡)',
            'consumption_date' => '消费日期',
            'consumption_time' => '消费时间',
            'consumption_times' => '消费次数',
            'cashier_order' => '收银单号',
            'cash_payment' => '现金付款',
            'bank_card_payment' => '银行卡付款',
            'mem_card_payment' => '会员卡付款',
            'coupon_payment' => '优惠券',
            'transfer_accounts' => '转账',
            'other_payment' => '其它转账',
            'network_payment' => '网络支付',
            'integration_payment' => '积分支付',
            'discount_payment' => '折折扣支付',
            'venue_id' => '场馆id',
            'seller_id' => '销售员id',
            'describe' => '消费描述',
            'category' => '消费类型状态 如：升级 续费',
            'company_id' => '公司id',
            'consumption_amount' => '消费金额',
            'consume_describe' => '消费描述',
            'remarks' => '备注',
            'due_date' => '到期日期',
            'payment_name' => '缴费名称',
            'activate_date' => '激活日期',
        ];
    }
}
