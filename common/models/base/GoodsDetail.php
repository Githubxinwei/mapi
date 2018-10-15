<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%goods_detail}}".
 *
 * @property string $id
 * @property string $goods_id
 * @property string $storage_num
 * @property string $unit
 * @property string $create_at
 * @property string $note
 * @property string $unit_price
 */
class GoodsDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'storage_num', 'create_at'], 'integer'],
            [['unit_price'], 'number'],
            [['unit', 'note'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'goods_id' => '商品id',
            'storage_num' => '库存数量',
            'unit' => '单位',
            'create_at' => '创建时间',
            'note' => '备注',
            'unit_price' => '商品单价',
        ];
    }
}
