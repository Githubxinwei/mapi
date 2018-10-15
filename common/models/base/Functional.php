<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%functional}}".
 *
 * @property string $id
 * @property string $name
 * @property string $note
 * @property string $e_name
 * @property string $create_id
 * @property string $create_at
 * @property string $update_at
 */
class Functional extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%functional}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['create_id', 'create_at', 'update_at'], 'integer'],
            [['name', 'note', 'e_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '功能名称',
            'note' => '说明',
            'e_name' => '功能英文名',
            'create_id' => '创建人',
            'create_at' => '创建时间',
            'update_at' => '修改時間',
        ];
    }
}
