<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%module_functional}}".
 *
 * @property string $id
 * @property string $modular_id
 * @property string $create_id
 * @property string $create_at
 * @property string $functional_id
 * @property string $update_at
 */
class ModuleFunctional extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%module_functional}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modular_id', 'create_id', 'create_at', 'update_at'], 'integer'],
            [['functional_id'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'modular_id' => '模块ID',
            'create_id' => '创建人',
            'create_at' => '创建时间',
            'functional_id' => '功能ID',
            'update_at' => '修改時間',
        ];
    }
}
