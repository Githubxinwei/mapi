<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%plan}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $content
 */
class Plan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'content'], 'required'],
            [['content'], 'string'],
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
            'name' => '名称',
            'content' => '内容',
        ];
    }
}
