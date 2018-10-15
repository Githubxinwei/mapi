<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%image_management_type}}".
 *
 * @property string $id
 * @property string $type_name
 * @property integer $create_id
 * @property string $created_at
 * @property string $update_at
 * @property string $company_id
 * @property string $venue_id
 */
class ImageManagementType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image_management_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_id', 'created_at', 'update_at', 'company_id', 'venue_id'], 'integer'],
            [['type_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'type_name' => '图片类别名',
            'create_id' => '创建人id',
            'created_at' => '创建时间',
            'update_at' => '修改时间',
            'company_id' => '公司ID',
            'venue_id' => '场馆ID',
        ];
    }
}
