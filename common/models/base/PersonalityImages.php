<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%personality_images}}".
 *
 * @property string $id 自增ID
 * @property string $employee_id 员工ID
 * @property int $type 类型 1图片, 2 视频
 * @property string $url 图片地址
 * @property string $describe 描述
 * @property string $create_at 创建时间
 */
class PersonalityImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%personality_images}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'type', 'create_at'], 'integer'],
            [['url', 'describe'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'employee_id' => '员工ID',
            'type' => '类型 1图片, 2 视频',
            'url' => '图片地址',
            'describe' => '描述',
            'create_at' => '创建时间',
        ];
    }
}
