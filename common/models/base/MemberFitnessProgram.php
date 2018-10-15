<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_fitness_program}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $target_weight
 * @property string $fitness_id
 * @property string $diet_id
 * @property string $create_at
 * @property string $update_at
 */
class MemberFitnessProgram extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_fitness_program}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'target_weight', 'fitness_id', 'diet_id', 'create_at', 'update_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'member_id' => '会员id',
            'target_weight' => '目标体重',
            'fitness_id' => '健身目标id',
            'diet_id' => '饮食计划id',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
        ];
    }
}
