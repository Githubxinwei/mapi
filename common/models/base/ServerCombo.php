<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%server_combo}}".
 *
 * @property string $id
 * @property string $name
 * @property string $create_at
 * @property string $server_id
 */
class ServerCombo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%server_combo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'create_at'], 'required'],
            [['create_at'], 'integer'],
            [['server_id'], 'string'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id自增',
            'name' => '活动',
            'create_at' => '创建时间',
            'server_id' => '服务表ID(json)',
        ];
    }
}
