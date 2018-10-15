<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%course_package_detail}}".
 *
 * @property string $id
 * @property string $charge_class_id
 * @property string $course_id
 * @property string $course_num
 * @property string $course_length
 * @property string $original_price
 * @property string $sale_price
 * @property string $pos_price
 * @property integer $type
 * @property string $create_at
 * @property integer $category
 * @property string $app_original
 */
class CoursePackageDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%course_package_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['charge_class_id', 'course_id'], 'required'],
            [['charge_class_id', 'course_id', 'course_num', 'course_length', 'type', 'create_at', 'category'], 'integer'],
            [['original_price', 'sale_price', 'pos_price', 'app_original'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'charge_class_id' => '收费课程ID',
            'course_id' => '课程ID',
            'course_num' => '课量',
            'course_length' => '时长',
            'original_price' => '单节原价',
            'sale_price' => '单节售价',
            'pos_price' => '单节pos价',
            'type' => '状态：1私课，2团课',
            'create_at' => '创建时间',
            'category' => '类型:1多课程，2单课程',
            'app_original' => '移动端单节原价',
        ];
    }
}
