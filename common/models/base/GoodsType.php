<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%goods_type}}".
 *
 * @property string $id
 * @property string $goods_type
 * @property string $create_at
 * @property string $venue_id
 * @property string $company_id
 */
class GoodsType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_at', 'venue_id', 'company_id'], 'integer'],
            [['goods_type'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'goods_type' => '商品类别',
            'create_at' => '创建时间',
            'venue_id' => '场馆id',
            'company_id' => '公司id',
        ];
    }
}
