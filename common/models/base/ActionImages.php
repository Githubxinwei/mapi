<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%action_images}}".
 *
 * @property string $id 自增ID
 * @property string $aid 动作ID
 * @property int $type 0 错误，1正确
 * @property string $url 图片地址
 * @property int $status 状态
 * @property int $sort 排序
 */
class ActionImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%action_images}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aid', 'type', 'status', 'sort'], 'integer'],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'aid' => '动作ID',
            'type' => '0 错误，1正确',
            'url' => '图片地址',
            'status' => '状态',
            'sort' => '排序',
        ];
    }
}
