<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%charge_class}}".
 *
 * @property string $id
 * @property string $name
 * @property string $course_id
 * @property string $amount
 * @property string $class_length
 * @property string $original_price
 * @property string $sell_price
 * @property string $describe
 * @property string $pic
 * @property string $create_id
 * @property string $created_at
 * @property integer $category
 * @property string $venue_id
 * @property string $total_amount
 * @property string $single_price
 * @property integer $status
 * @property integer $not_reservation
 * @property integer $not_cancel
 * @property integer $reservation_days
 * @property integer $valid_time
 * @property integer $activated_time
 * @property integer $total_sale_num
 * @property string $sale_start_time
 * @property string $sale_end_time
 * @property string $total_pos_price
 * @property integer $transfer_num
 * @property string $transfer_price
 * @property integer $type
 * @property integer $private_class_type
 * @property string $company_id
 * @property string $deal_id
 * @property integer $course_type
 * @property integer $month_up_num
 * @property integer $product_type
 * @property string $app_amount
 * @property integer $show
 * @property string $app_original_price
 * @property integer $group
 */
class ChargeClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_class}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'amount', 'class_length', 'create_id', 'created_at', 'category', 'venue_id', 'status', 'not_reservation', 'not_cancel', 'reservation_days', 'valid_time', 'activated_time', 'total_sale_num', 'sale_start_time', 'sale_end_time', 'transfer_num', 'type', 'private_class_type', 'company_id', 'deal_id', 'course_type', 'month_up_num', 'product_type', 'show', 'group'], 'integer'],
            [['original_price', 'sell_price', 'total_amount', 'single_price', 'total_pos_price', 'transfer_price', 'app_amount', 'app_original_price'], 'number'],
            [['describe', 'pic'], 'string'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '名称',
            'course_id' => '课种ID',
            'amount' => '课量',
            'class_length' => '单节课时长',
            'original_price' => '原价',
            'sell_price' => '售价',
            'describe' => '介绍',
            'pic' => '图片',
            'create_id' => '创建人ID(员工的ID)',
            'created_at' => '创建时间',
            'category' => '类型 1私教2团课',
            'venue_id' => '场馆id',
            'total_amount' => '总金额',
            'single_price' => '单节pos价',
            'status' => '状态1:正常 2:冻结 3:过期',
            'not_reservation' => '不可预约时间(分钟)',
            'not_cancel' => '不可取消时间(分钟)',
            'reservation_days' => '可预约天数',
            'valid_time' => '有效期',
            'activated_time' => '激活期限',
            'total_sale_num' => '售卖总量',
            'sale_start_time' => '售卖开始日期',
            'sale_end_time' => '售卖结束日期',
            'total_pos_price' => '总pos价',
            'transfer_num' => '转让次数',
            'transfer_price' => '转让金额',
            'type' => '类型:1多课程，2单课程',
            'private_class_type' => '收银类型（1.PT 2.其它）',
            'company_id' => '公司id',
            'deal_id' => '合同ID',
            'course_type' => '课程类型:1购买,2赠送',
            'month_up_num' => '每月上课数量',
            'product_type' => '1常规PT,2特色课,3游泳课',
            'app_amount' => '移动端总售价',
            'show' => '移动端价格:1不显示,2显示',
            'app_original_price' => '移动端总原价',
            'group' => '1单人私课,2多人私课',
        ];
    }
}
