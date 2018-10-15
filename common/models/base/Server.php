<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%server}}".
 *
 * @property string $id
 * @property string $description
 * @property string $name
 * @property string $create_at
 * @property string $company_id
 * @property string $venue_id
 */
class Server extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%server}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'name', 'create_at'], 'required'],
            [['description'], 'string'],
            [['create_at', 'company_id', 'venue_id'], 'integer'],
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
            'description' => '描述',
            'name' => '名称',
            'create_at' => '创建时间',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
        ];
    }
}
