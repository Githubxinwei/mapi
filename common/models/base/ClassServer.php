<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%class_server}}".
 *
 * @property string $id
 * @property string $server_name
 * @property string $create_at
 */
class ClassServer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%class_server}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['server_name', 'create_at'], 'required'],
            [['create_at'], 'integer'],
            [['server_name'], 'string', 'max' => 200],
            [['server_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'server_name' => '套餐名',
            'create_at' => '添加时间',
        ];
    }
}
