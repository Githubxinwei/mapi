<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%service_pay}}".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $create_at
 * @property string $category
 */
class ServicePay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%service_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'create_at', 'category'], 'required'],
            [['create_at'], 'integer'],
            [['name', 'description', 'category'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '名称',
            'description' => '描述',
            'create_at' => '添加时间',
            'category' => '类型(课程类、消耗类、服务类）',
        ];
    }
}
