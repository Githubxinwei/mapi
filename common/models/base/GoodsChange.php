<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%goods_change}}".
 *
 * @property string $id
 * @property string $goods_id
 * @property integer $status
 * @property string $operation_num
 * @property string $list_num
 * @property string $unit
 * @property string $create_at
 * @property string $describe
 * @property string $unit_price
 * @property string $surplus_amount
 * @property integer $type
 */
class GoodsChange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_change}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'status', 'operation_num', 'create_at', 'surplus_amount', 'type'], 'integer'],
            [['describe'], 'string'],
            [['unit_price'], 'number'],
            [['list_num', 'unit'], 'string', 'max' => 200],
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
            'status' => '状态1:入库 2：出库 3：报损 4:退库 5:报溢',
            'operation_num' => '操作数量',
            'list_num' => '商品单号',
            'unit' => '单位',
            'create_at' => '创建时间',
            'describe' => '描述',
            'unit_price' => '商品单价',
            'surplus_amount' => '剩余数量',
            'type' => '类型:1剩余2不足',
        ];
    }
}
