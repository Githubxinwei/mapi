<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%goods_standard}}".
 *
 * @property string $id 自增ID
 * @property string $goods_id 商品表ID
 * @property string $price 价格(元) 必填
 * @property string $cost 成本(元)
 * @property string $box_price 餐盒费(元)
 * @property int $inventory_type 1无限库存  2有限库存
 * @property string $inventory_amount inventory_type 为2时  库存量
 * @property string $inventory_left inventory_type 为2时  库存剩余
 * @property string $standard 规格值
 * @property int $status 状态 1 启用 2 禁用
 * @property string $create_id 创建人ID
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property string $company_id 公司ID
 * @property int $is_delete 软删标记 0 未删除 1 已删除
 */
class GoodsStandard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_standard}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'inventory_type', 'inventory_amount', 'inventory_left', 'status', 'create_id', 'company_id', 'is_delete'], 'integer'],
            [['price', 'cost', 'box_price'], 'number'],
            [['create_at', 'update_at'], 'safe'],
            [['standard'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'price' => 'Price',
            'cost' => 'Cost',
            'box_price' => 'Box Price',
            'inventory_type' => 'Inventory Type',
            'inventory_amount' => 'Inventory Amount',
            'inventory_left' => 'Inventory Left',
            'standard' => 'Standard',
            'status' => 'Status',
            'create_id' => 'Create ID',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'company_id' => 'Company ID',
            'is_delete' => 'Is Delete',
        ];
    }
}
