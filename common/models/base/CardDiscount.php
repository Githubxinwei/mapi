<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%card_discount}}".
 *
 * @property string $id
 * @property string $limit_card_id
 * @property integer $surplus
 * @property double $discount
 * @property string $create_at
 * @property string $update_at
 */
class CardDiscount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%card_discount}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['limit_card_id', 'surplus', 'create_at', 'update_at'], 'integer'],
            [['discount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'limit_card_id' => '通店表ID',
            'surplus' => '剩余张数',
            'discount' => '折扣',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
        ];
    }
}
