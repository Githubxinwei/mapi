<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%mobilise}}".
 *
 * @property string $id
 * @property string $goods_id
 * @property string $num
 * @property string $note
 * @property string $reject_note
 * @property string $created_at
 * @property string $update_at
 * @property string $create_id
 * @property string $company_id
 * @property string $venue_id
 * @property string $store_id
 * @property string $be_store_id
 * @property integer $be_venue_id
 */
class Mobilise extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mobilise}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'num', 'created_at', 'update_at', 'create_id', 'company_id', 'venue_id', 'store_id', 'be_store_id', 'be_venue_id'], 'integer'],
            [['note', 'reject_note'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'goods_id' => '商品ID',
            'num' => '调拨数量',
            'note' => '备注',
            'reject_note' => '拒绝原因',
            'created_at' => '创建时间',
            'update_at' => '更新时间',
            'create_id' => '创建人ID',
            'company_id' => '公司ID',
            'venue_id' => '场馆ID',
            'store_id' => '原仓库ID',
            'be_store_id' => '被调拨仓库ID',
            'be_venue_id' => '被调拨的场馆ID',
        ];
    }
}
