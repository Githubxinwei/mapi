<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_course_order}}".
 *
 * @property string $id
 * @property integer $course_amount
 * @property string $create_at
 * @property string $money_amount
 * @property string $overage_section
 * @property string $deadline_time
 * @property string $product_id
 * @property integer $product_type
 * @property string $private_type
 * @property integer $charge_mode
 * @property integer $class_mode
 * @property integer $is_same_class
 * @property string $private_id
 * @property string $present_course_number
 * @property string $surplus_course_number
 * @property integer $cashier_type
 * @property string $service_pay_id
 * @property string $member_card_id
 * @property string $seller_id
 * @property string $cashierOrder
 * @property string $member_id
 * @property string $business_remarks
 * @property string $product_name
 * @property integer $type
 * @property string $activeTime
 * @property string $chargePersonId
 * @property integer $set_number
 * @property integer $month_up_num
 * @property integer $course_type
 * @property string $note
 * @property integer $pay_status
 * @property string $class_number_id
 */
class MemberCourseOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_course_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_amount', 'create_at', 'overage_section', 'deadline_time', 'product_id', 'product_type', 'charge_mode', 'class_mode', 'is_same_class', 'private_id', 'present_course_number', 'surplus_course_number', 'cashier_type', 'service_pay_id', 'member_card_id', 'seller_id', 'member_id', 'type', 'activeTime', 'chargePersonId', 'set_number', 'month_up_num', 'course_type', 'pay_status', 'class_number_id'], 'integer'],
            [['money_amount'], 'number'],
            [['business_remarks'], 'string'],
            [['private_type', 'cashierOrder'], 'string', 'max' => 255],
            [['product_name', 'note'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'course_amount' => '总节数',
            'create_at' => '买课时间',
            'money_amount' => '总金额',
            'overage_section' => '剩余节数',
            'deadline_time' => '课程截止时间',
            'product_id' => '产品id',
            'product_type' => '产品类型:1私课2团课',
            'private_type' => '私教类别',
            'charge_mode' => '计费方式（1.计次课程 2.其它）',
            'class_mode' => '上课方式（1.单个教练 2. 多个教练 3.其它）',
            'is_same_class' => '是否同时上课（1:同时上课 2.不同时上课3.其它）',
            'private_id' => '私教id',
            'present_course_number' => '赠课总次数',
            'surplus_course_number' => '剩余总课数',
            'cashier_type' => '收银类型（1.全款 2.转让 3.回款）',
            'service_pay_id' => '收费项目id',
            'member_card_id' => '会员卡ID',
            'seller_id' => '员工表销售人id',
            'cashierOrder' => '订单收银单号',
            'member_id' => '会员id',
            'business_remarks' => '业务备注',
            'product_name' => '产品名称',
            'type' => '类型：(1：PT，2:HS)',
            'activeTime' => '生效时间',
            'chargePersonId' => '收费人员id',
            'set_number' => '存“服务套餐”的总套数或者“单节课程”的总数量',
            'month_up_num' => '每月上课数量',
            'course_type' => '课程类型:1收费课,2免费课,3生日课',
            'note' => '备注',
            'pay_status' => '1:已付款，2:已退款',
            'class_number_id' => '私课编号id',
        ];
    }
}
