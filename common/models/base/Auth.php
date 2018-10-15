<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%auth}}".
 *
 * @property string $id
 * @property string $role_id
 * @property string $create_id
 * @property string $create_at
 * @property string $module_id
 * @property string $function_id
 * @property string $update_at
 * @property string $company_id
 * @property string $venue_id
 * @property string $sync_role_id
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'create_id', 'create_at', 'update_at', 'sync_role_id'], 'integer'],
            [['module_id', 'function_id', 'company_id', 'venue_id'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'role_id' => '角色ID',
            'create_id' => '创建人',
            'create_at' => '创建时间',
            'module_id' => '模块ID',
            'function_id' => '功能ID',
            'update_at' => '修改時間',
            'company_id' => '公司ID',
            'venue_id' => '场馆ID',
            'sync_role_id' => '同步角色ID',
        ];
    }
}
