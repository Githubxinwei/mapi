<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%card_category_type}}".
 *
 * @property string $id
 * @property string $type_name
 * @property string $create_at
 * @property string $update_at
 * @property string $company_id
 * @property string $venue_id
 */
class CardCategoryType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%card_category_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name'], 'required'],
            [['create_at', 'update_at', 'company_id', 'venue_id'], 'integer'],
            [['type_name'], 'string', 'max' => 200],
            [['type_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'type_name' => '类别名（时间卡、次卡）',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
        ];
    }
}
