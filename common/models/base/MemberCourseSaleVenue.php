<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_course_sale_venue}}".
 *
 * @property string $id
 * @property string $course_order_id
 * @property string $venue_id
 * @property string $sale_num
 * @property string $sale_start_time
 * @property string $sale_end_time
 * @property integer $status
 */
class MemberCourseSaleVenue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_course_sale_venue}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_order_id', 'venue_id'], 'required'],
            [['course_order_id', 'venue_id', 'sale_num', 'sale_start_time', 'sale_end_time', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'course_order_id' => '课程订单表id',
            'venue_id' => '场馆ID',
            'sale_num' => '售卖数量',
            'sale_start_time' => '售卖开始时间',
            'sale_end_time' => '售卖结束时间',
            'status' => '状态：1私课，2团课',
        ];
    }
}
