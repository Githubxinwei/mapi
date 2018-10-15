<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%help}}".
 *
 * @property string $id
 * @property integer $type_id
 * @property string $question
 * @property string $content
 * @property string $create_at
 * @property string $update_at
 */
class Help extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%help}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'create_at', 'update_at'], 'integer'],
            [['question', 'content'], 'required'],
            [['content'], 'string'],
            [['question'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'type_id' => '类型ID',
            'question' => '问题',
            'content' => '内容',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
        ];
    }
}
