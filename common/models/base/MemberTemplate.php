<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "cloud_member_template".
 *
 * @property string $id 自增ID
 * @property string $class_id 课程ID
 * @property string $coach_id 教练ID
 * @property string $main 模板信息(训练模板, 阶段动作)
 * @property string $create_at 创建时间
 * @property string $calorie 卡路里
 * @property int $number 组数
 */
class MemberTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cloud_member_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_id', 'coach_id', 'create_at', 'calorie', 'number'], 'integer'],
            [['main'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'class_id' => '课程ID',
            'coach_id' => '教练ID',
            'main' => '模板信息(训练模板, 阶段动作)',
            'create_at' => '创建时间',
            'calorie' => '卡路里',
            'number' => '组数',
        ];
    }
}
