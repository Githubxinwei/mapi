<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%transfer_record}}".
 *
 * @property string $id 自增ID
 * @property string $member_card_id 会员卡id
 * @property string $to_member_id 会员id
 * @property string $from_member_id 转让人id
 * @property string $transfer_time 转让时间
 * @property int $times 剩余次数
 * @property string $path 卡的转让经历(json)
 * @property string $transfer_price 转让金额
 * @property string $register_person 登记人
 * @property string $note 转让原因
 * @property string $cashier_number 收银单号
 * @property string $cashier 收银员
 * @property string $cash_payment 现金支付
 * @property string $bank_card_payment 银行卡支付
 * @property string $network_payment 网络支付
 * @property string $member_card 会员卡（余额）支付
 * @property string $discount 折扣折让
 * @property string $coupon 优惠卷
 * @property string $transfer 转账
 * @property string $other 其他
 * @property string $integral 积分
 * @property string $spare 备用(json) 
 * @property int $operator_id 操作人id
 */
class TransferRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transfer_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_card_id', 'to_member_id', 'from_member_id', 'transfer_time', 'path'], 'required'],
            [['member_card_id', 'to_member_id', 'from_member_id', 'transfer_time', 'times', 'operator_id'], 'integer'],
            [['path', 'spare'], 'string'],
            [['transfer_price', 'cash_payment', 'bank_card_payment', 'network_payment', 'member_card', 'discount', 'coupon', 'transfer', 'other', 'integral'], 'number'],
            [['register_person', 'note', 'cashier_number', 'cashier'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_card_id' => 'Member Card ID',
            'to_member_id' => 'To Member ID',
            'from_member_id' => 'From Member ID',
            'transfer_time' => 'Transfer Time',
            'times' => 'Times',
            'path' => 'Path',
            'transfer_price' => 'Transfer Price',
            'register_person' => 'Register Person',
            'note' => 'Note',
            'cashier_number' => 'Cashier Number',
            'cashier' => 'Cashier',
            'cash_payment' => 'Cash Payment',
            'bank_card_payment' => 'Bank Card Payment',
            'network_payment' => 'Network Payment',
            'member_card' => 'Member Card',
            'discount' => 'Discount',
            'coupon' => 'Coupon',
            'transfer' => 'Transfer',
            'other' => 'Other',
            'integral' => 'Integral',
            'spare' => 'Spare',
            'operator_id' => 'Operator ID',
        ];
    }
}
