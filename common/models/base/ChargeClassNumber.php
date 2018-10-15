<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%charge_class_number}}".
 *
 * @property string $id
 * @property string $charge_class_id
 * @property string $class_people_id
 * @property string $class_number
 * @property string $sell_number
 * @property string $surplus
 * @property string $total_class_num
 * @property integer $attend_class_num
 * @property string $venue_id
 * @property string $company_id
 * @property string $valid_start_time
 * @property string $valid_time
 */
class ChargeClassNumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_class_number}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['charge_class_id', 'class_people_id', 'sell_number', 'surplus', 'total_class_num', 'attend_class_num', 'venue_id', 'company_id', 'valid_start_time', 'valid_time'], 'integer'],
            [['class_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'charge_class_id' => '私课表id	',
            'class_people_id' => '私课人数区间id	',
            'class_number' => '私课编号',
            'sell_number' => '售卖数量',
            'surplus' => '剩余数量',
            'total_class_num' => '总节数',
            'attend_class_num' => '剩余节数',
            'venue_id' => '场馆id	',
            'company_id' => '公司id	',
            'valid_start_time' => '有效期开始时间',
            'valid_time' => '产品有效期(天)',
        ];
    }
}
