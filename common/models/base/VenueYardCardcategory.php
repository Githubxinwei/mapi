<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%venue_yard_cardcategory}}".
 *
 * @property string $id
 * @property string $yard_id
 * @property string $card_category_id
 * @property string $create_at
 */
class VenueYardCardcategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%venue_yard_cardcategory}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['yard_id', 'card_category_id', 'create_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'yard_id' => '场地id',
            'card_category_id' => '场地适用卡种id',
            'create_at' => '创建时间',
        ];
    }
}
