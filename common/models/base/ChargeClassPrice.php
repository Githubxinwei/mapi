<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%charge_class_price}}".
 *
 * @property string $id 自增ID
 * @property string $charge_class_id 私课ID
 * @property string $course_package_detail_id 课程ID
 * @property string $intervalStart 开始节数
 * @property string $intervalEnd 结束节数
 * @property string $unitPrice 优惠单价
 * @property string $posPrice pos价
 * @property string $create_time 创建时间
 */
class ChargeClassPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_class_price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['charge_class_id', 'course_package_detail_id'], 'required'],
            [['charge_class_id', 'course_package_detail_id', 'intervalStart', 'intervalEnd', 'create_time'], 'integer'],
            [['unitPrice', 'posPrice'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'charge_class_id' => 'Charge Class ID',
            'course_package_detail_id' => 'Course Package Detail ID',
            'intervalStart' => 'Interval Start',
            'intervalEnd' => 'Interval End',
            'unitPrice' => 'Unit Price',
            'posPrice' => 'Pos Price',
            'create_time' => 'Create Time',
        ];
    }
}
