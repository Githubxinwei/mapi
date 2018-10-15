<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%advice}}".
 *
 * @property string $id
 * @property string $admin_id
 * @property string $content
 * @property string $create_at
 */
class Advice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'create_at'], 'integer'],
            [['content'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'admin_id' => '登陆用户id',
            'content' => '反馈内容',
            'create_at' => '创建时间',
        ];
    }
}
