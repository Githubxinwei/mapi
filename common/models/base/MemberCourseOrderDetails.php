<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_course_order_details}}".
 *
 * @property string $id
 * @property string $course_order_id
 * @property string $course_id
 * @property integer $course_num
 * @property integer $course_length
 * @property string $original_price
 * @property string $sale_price
 * @property string $pos_price
 * @property integer $type
 * @property integer $category
 * @property string $product_name
 * @property string $course_name
 * @property integer $class_length
 * @property string $pic
 * @property string $desc
 * @property integer $product_type
 * @property integer $activated_time
 * @property integer $transfer_num
 * @property string $transfer_price
 * @property string $deal_id
 */
class MemberCourseOrderDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_course_order_details}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_order_id', 'course_id'], 'required'],
            [['course_order_id', 'course_id', 'course_num', 'course_length', 'type', 'category', 'class_length', 'product_type', 'activated_time', 'transfer_num', 'deal_id'], 'integer'],
            [['original_price', 'sale_price', 'pos_price', 'transfer_price'], 'number'],
            [['product_name', 'course_name'], 'string', 'max' => 200],
            [['pic', 'desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键自增',
            'course_order_id' => '课程订单表id',
            'course_id' => '课种id',
            'course_num' => '课量',
            'course_length' => '有效天数',
            'original_price' => '单节原价',
            'sale_price' => '单节售价',
            'pos_price' => '单节pos售价',
            'type' => '订单类型：1私课2团课',
            'category' => '课程类型：1多课程2单课程',
            'product_name' => '产品名称',
            'course_name' => '课种名称',
            'class_length' => '单节时长，一般默认为分钟',
            'pic' => '产品图片',
            'desc' => '产品描述',
            'product_type' => '产品类型:1常规pt,2特色课,3游泳课',
            'activated_time' => '激活期限',
            'transfer_num' => '转让次数',
            'transfer_price' => '转让金额',
            'deal_id' => '合同id',
        ];
    }
}
