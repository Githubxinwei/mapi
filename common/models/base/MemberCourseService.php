<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_course_service}}".
 *
 * @property string $id
 * @property string $course_order_id
 * @property string $service_id
 * @property string $gift_id
 * @property integer $type
 * @property integer $category
 * @property string $create_time
 * @property string $service_num
 * @property string $gift_num
 */
class MemberCourseService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_course_service}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_order_id'], 'required'],
            [['course_order_id', 'service_id', 'gift_id', 'type', 'category', 'create_time', 'service_num', 'gift_num'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'course_order_id' => '	课程订单表id	',
            'service_id' => '服务ID',
            'gift_id' => '赠品ID',
            'type' => '类型：1服务，2赠品',
            'category' => '类别：1私课，2团课',
            'create_time' => '创建时间',
            'service_num' => '服务数量',
            'gift_num' => '赠品数量',
        ];
    }
}
