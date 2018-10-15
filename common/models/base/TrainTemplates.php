<?php

namespace common\models\base;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%train_templates}}".
 * 训练模板
 * @property int $id
 * @property string $title 模板名称
 * @property string $describe 描述
 * @property string $tags 标签
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 * @property int $status 状态
 */
class TrainTemplates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%train_templates}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['title', 'describe', 'tags'], 'string', 'max' => 255],
            ['cid', 'default', 'value' => 0],
            [['title'], 'unique','message'=>'该名已被占用！'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '模板名称',
            'describe' => '描述',
            'tags' => '标签',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'status' => '状态',
            'cid' => '类别',
        ];
    }

}
