<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%action_category_relation}}".
 *
 * @property string $id 自增ID
 * @property string $cid 分类ID
 * @property string $aid 动作ID
 */
class ActionCategoryRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%action_category_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cid', 'aid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'cid' => '分类ID',
            'aid' => '动作ID',
        ];
    }
}
