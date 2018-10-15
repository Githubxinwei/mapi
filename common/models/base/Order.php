<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property string $id 自增ID
 * @property string $venue_id 售卖场馆id
 * @property string $member_id 会员id
 * @property string $card_category_id 卡种id
 * @property string $total_price 总价
 * @property string $order_time 订单创建时间
 * @property string $pay_money_time 付款时间
 * @property int $pay_money_mode 付款方式：1现金；2支付宝；3微信；4pos刷卡；
 * @property string $sell_people_id 售卖人id
 * @property string $payee_id 收款人id
 * @property string $create_id 操作人id
 * @property int $status 订单状态 1 未付款, 2 已付款, 3 已取消, 4 待处理, 5 已退款, 6 已拒绝, 7 续费, 8 待领取（已发货）, 9 已完成（已收货）, 10 已过期
 * @property string $note 备注
 * @property string $order_number 订单编号
 * @property string $card_name 卡名称
 * @property string $sell_people_name 售卖人姓名
 * @property string $payee_name 收款人姓名
 * @property string $member_name 购买人姓名
 * @property string $pay_people_name 付款人姓名
 * @property string $company_id 公司id
 * @property string $merchant_order_number 商户单号
 * @property string $consumption_type_id 多态id
 * @property string $consumption_type 多态类型
 * @property string $deposit 订单：定金
 * @property string $cash_coupon 订单：代金券
 * @property string $net_price 订单：实收价格
 * @property string $all_price 订单：商品总价格
 * @property string $refund_note 退款理由
 * @property string $refuse_note 拒绝原因
 * @property string $apply_time 申请退款时间
 * @property string $review_time 审批时间
 * @property int $is_receipt 0、未开票；1、已开票
 * @property int $purchase_num 购买数量(1:私课节数2:卡数量)
 * @property string $new_note 新备注
 * @property string $many_pay_mode 多付款方式1.现金2.微信3.支付宝4.建设分期5.广发分期6.招行分期7.借记卡8.贷记卡
 * @property string $other_note 其它备注（该字段不用于对外展示)
 * @property string $sign 签名
 * @property string $approval_id 退款审批人id
 * @property int $source 来源 1 pc, 2 小程序, 3 公众号, 4 APP
 * @property string $request_refund_money 申请退款金额
 * @property string $actual_refund_money 实际退款金额
 * @property string $pay_params 支付参数（用于退款时的依据）
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['venue_id', 'member_id', 'card_category_id', 'order_number'], 'required'],
            [['venue_id', 'member_id', 'card_category_id', 'order_time', 'pay_money_time', 'pay_money_mode', 'sell_people_id', 'payee_id', 'create_id', 'status', 'company_id', 'consumption_type_id', 'apply_time', 'review_time', 'is_receipt', 'purchase_num', 'approval_id', 'source'], 'integer'],
            [['total_price', 'deposit', 'cash_coupon', 'net_price', 'all_price', 'request_refund_money', 'actual_refund_money'], 'number'],
            [['note', 'refund_note', 'refuse_note', 'many_pay_mode', 'other_note', 'pay_params'], 'string'],
            [['order_number', 'merchant_order_number', 'sign'], 'string', 'max' => 255],
            [['card_name', 'sell_people_name', 'payee_name', 'member_name', 'pay_people_name', 'consumption_type', 'new_note'], 'string', 'max' => 200],
            [['order_number'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'venue_id' => '售卖场馆id',
            'member_id' => '会员id',
            'card_category_id' => '卡种id',
            'total_price' => '总价',
            'order_time' => '订单创建时间',
            'pay_money_time' => '付款时间',
            'pay_money_mode' => '付款方式：1现金；2支付宝；3微信；4pos刷卡；',
            'sell_people_id' => '售卖人id',
            'payee_id' => '收款人id',
            'create_id' => '操作人id',
            'status' => '订单状态 1 未付款, 2 已付款, 3 已取消, 4 待处理, 5 已退款, 6 已拒绝, 7 续费, 8 待领取（已发货）, 9 已完成（已收货）, 10 已过期',
            'note' => '备注',
            'order_number' => '订单编号',
            'card_name' => '卡名称',
            'sell_people_name' => '售卖人姓名',
            'payee_name' => '收款人姓名',
            'member_name' => '购买人姓名',
            'pay_people_name' => '付款人姓名',
            'company_id' => '公司id',
            'merchant_order_number' => '商户单号',
            'consumption_type_id' => '多态id',
            'consumption_type' => '多态类型',
            'deposit' => '订单：定金',
            'cash_coupon' => '订单：代金券',
            'net_price' => '订单：实收价格',
            'all_price' => '订单：商品总价格',
            'refund_note' => '退款理由',
            'refuse_note' => '拒绝原因',
            'apply_time' => '申请退款时间',
            'review_time' => '审批时间',
            'is_receipt' => '0、未开票；1、已开票',
            'purchase_num' => '购买数量(1:私课节数2:卡数量)',
            'new_note' => '新备注',
            'many_pay_mode' => '多付款方式1.现金2.微信3.支付宝4.建设分期5.广发分期6.招行分期7.借记卡8.贷记卡',
            'other_note' => '其它备注（该字段不用于对外展示)',
            'sign' => '签名',
            'approval_id' => '退款审批人id',
            'source' => '来源 1 pc, 2 小程序, 3 公众号, 4 APP',
            'request_refund_money' => '申请退款金额',
            'actual_refund_money' => '实际退款金额',
            'pay_params' => '支付参数（用于退款时的依据）',
        ];
    }
}