<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%role}}".
 *
 * @property string $id
 * @property string $name
 * @property string $company_id
 * @property string $create_id
 * @property string $create_at
 * @property string $update_at
 * @property integer $status
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['company_id', 'create_id', 'create_at', 'update_at', 'status'], 'integer'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '角色名称',
            'company_id' => '所属公司id',
            'create_id' => '创建人',
            'create_at' => '创建时间',
            'update_at' => '修改時間',
            'status' => '状态：1正常；2停用',
        ];
    }
}
