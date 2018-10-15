<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%feedback_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $pid
 * @property integer $child
 * @property integer $do
 */
class FeedbackType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feedback_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'child', 'do'], 'integer'],
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
            'name' => '类型名称',
            'pid' => '父ID',
            'child' => '是否有子类型0无1有',
            'do' => '此分类下是否可以进入提交内容页面0不可1可',
        ];
    }
}
