<?php

namespace common\models\base;

use Yii;
use common\models\relations\ChargeClassPeopleRelations;
/**
 * This is the model class for table "{{%charge_class_people}}".
 *
 * @property string $id
 * @property string $class_price_id
 * @property integer $people_least
 * @property integer $people_most
 * @property integer $least_number
 * @property string $unit_price
 * @property string $pos_price
 */
class ChargeClassPeople extends \yii\db\ActiveRecord
{
    use ChargeClassPeopleRelations;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_class_people}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['charge_class_id','class_price_id', 'people_least', 'people_most', 'least_number'], 'integer'],
            [['unit_price', 'pos_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'charge_class_id' => '私教id',
            'class_price_id' => '私课节数区间id',
            'people_least' => '最少人数',
            'people_most' => '最多人数',
            'least_number' => '最低开课人数',
            'unit_price' => '优惠单价',
            'pos_price' => 'pos价',
        ];
    }
}
