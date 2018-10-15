<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%store_house}}".
 *
 * @property string $id
 * @property string $name
 * @property string $created_at
 * @property string $update_at
 * @property string $create_id
 * @property string $company_id
 * @property string $venue_id
 */
class StoreHouse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_house}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'update_at', 'create_id', 'company_id', 'venue_id'], 'integer'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'name' => '仓库名',
            'created_at' => '创建时间',
            'update_at' => '修改时间',
            'create_id' => '创建人ID',
            'company_id' => '公司ID',
            'venue_id' => '场馆ID',
        ];
    }
}
