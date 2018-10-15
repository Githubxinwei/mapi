<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%card_category}}".
 *
 * @property string $id
 * @property string $category_type_id
 * @property string $card_name
 * @property string $create_at
 * @property integer $class_server_id
 * @property integer $server_combo_id
 * @property string $times
 * @property string $count_method
 * @property string $sell_start_time
 * @property string $sell_end_time
 * @property integer $attributes
 * @property integer $total_store_times
 * @property string $venue_id
 * @property integer $payment
 * @property integer $payment_months
 * @property string $total_circulation
 * @property integer $sex
 * @property integer $age
 * @property integer $transfer_number
 * @property string $create_id
 * @property string $regular_renew_time
 * @property string $regular_transform_time
 * @property string $original_price
 * @property string $sell_price
 * @property string $max_price
 * @property string $min_price
 * @property string $sales_mode
 * @property integer $missed_times
 * @property integer $limit_times
 * @property string $active_time
 * @property integer $status
 * @property string $transfer_price
 * @property string $leave_total_days
 * @property string $leave_total_times
 * @property string $leave_long_limit
 * @property string $recharge_price
 * @property string $recharge_give_price
 * @property string $single_price
 * @property string $recharge_start_time
 * @property string $recharge_end_time
 * @property integer $person_times
 * @property string $service_pay_ids
 * @property string $renew_price
 * @property integer $leave_least_Days
 * @property string $duration
 * @property string $deal_id
 * @property string $another_name
 * @property string $offer_price
 * @property integer $renew_unit
 * @property string $company_id
 * @property double $single
 * @property integer $is_app_show
 * @property integer $bring
 * @property integer $card_type
 * @property string $ordinary_renewal
 * @property string $validity_renewal
 * @property string $pic
 * @property string $app_sell_price
 * @property string $student_leave_limit
 */
class CardCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%card_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_type_id', 'card_name', 'venue_id', 'create_id', 'deal_id'], 'required'],
            [['category_type_id', 'create_at', 'class_server_id', 'server_combo_id', 'times', 'count_method', 'sell_start_time', 'sell_end_time', 'attributes', 'total_store_times', 'venue_id', 'payment', 'payment_months', 'total_circulation', 'sex', 'age', 'transfer_number', 'create_id', 'sales_mode', 'missed_times', 'limit_times', 'active_time', 'status', 'leave_total_days', 'leave_total_times', 'person_times', 'leave_least_Days', 'deal_id', 'renew_unit', 'company_id', 'is_app_show', 'bring', 'card_type'], 'integer'],
            [['regular_renew_time', 'regular_transform_time'], 'safe'],
            [['original_price', 'sell_price', 'max_price', 'min_price', 'transfer_price', 'recharge_price', 'recharge_give_price', 'single_price', 'renew_price', 'offer_price', 'single', 'ordinary_renewal', 'app_sell_price'], 'number'],
            [['leave_long_limit', 'service_pay_ids', 'duration', 'validity_renewal', 'pic', 'student_leave_limit'], 'string'],
            [['card_name', 'recharge_start_time', 'recharge_end_time', 'another_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'category_type_id' => '类别表ID',
            'card_name' => '卡名',
            'create_at' => '添加时间',
            'class_server_id' => '课程套餐ID',
            'server_combo_id' => '服务套餐ID',
            'times' => '次数，用于次卡',
            'count_method' => '计次方式：时间段、计次',
            'sell_start_time' => '售卖开始时间',
            'sell_end_time' => '售卖结束时间',
            'attributes' => '属性：1个人2公司3团体',
            'total_store_times' => '总通店次数：-1不限',
            'venue_id' => '办卡场馆',
            'payment' => '付款类型：1全款2分期',
            'payment_months' => '付款总月数:用于分期',
            'total_circulation' => '卡的总发行量（-1代表不限）',
            'sex' => '限制性别：1男2女-1不限',
            'age' => '限制年龄：-1不限',
            'transfer_number' => '可转让次数',
            'create_id' => '创建人：对应员工表中有权限的管理者',
            'regular_renew_time' => '规定续约时间',
            'regular_transform_time' => '规定转让时间',
            'original_price' => '一口原价',
            'sell_price' => '一口售价',
            'max_price' => '最高价',
            'min_price' => '最低价',
            'sales_mode' => '销售方式：1实体店2网络店3不限',
            'missed_times' => '未赴约次数限制：-1不限',
            'limit_times' => '限制约课天数：限制多长时间内不能约课/-1不限',
            'active_time' => '激活期限',
            'status' => '状态1：正常2：冻结3.过期',
            'transfer_price' => '转让金额',
            'leave_total_days' => '请假总天数',
            'leave_total_times' => '请假总次数',
            'leave_long_limit' => '最长请假天数,最长请假次数[天，次],[天，次]',
            'recharge_price' => '充值卡充值金额',
            'recharge_give_price' => '赠送金额',
            'single_price' => '单次消费金额',
            'recharge_start_time' => '按时段消费开始时间',
            'recharge_end_time' => '按时段消费结束时间',
            'person_times' => '次卡每次多少人',
            'service_pay_ids' => '消费表ID',
            'renew_price' => '续费金额',
            'leave_least_Days' => '每次请假最少天数',
            'duration' => '有效期/时长(json[\"秒\"=>,\"分钟\"=>\"小时\"=>\"天\"=>\"周\"=>\"月\"=>\"季度\"=>\"年\"=>])',
            'deal_id' => '合同id',
            'another_name' => '另一个卡名',
            'offer_price' => '优惠价',
            'renew_unit' => '续费多长时间/天',
            'company_id' => '公司id',
            'single' => '单数',
            'is_app_show' => '是否在app显示1:显示,2:不显示',
            'bring' => '带人卡设置，0代表不待人',
            'card_type' => '卡种类型，1:瑜伽,2:健身,3舞蹈,4:综合等',
            'ordinary_renewal' => '普通续费',
            'validity_renewal' => '有效期续费',
            'pic' => '图片',
            'app_sell_price' => '移动端售价',
            'student_leave_limit' => '学生暑假请假天数,学生寒假请假次数[天，次],[天，次]',
        ];
    }
}
