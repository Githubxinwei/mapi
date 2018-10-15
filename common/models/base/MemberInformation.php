<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_information}}".
 *
 * @property string $id 自增ID
 * @property string $member_id 会员id
 * @property int $height 会员身高
 * @property string $weight 会员体重
 * @property int $fitness_foundation 健身基础（1-入门，2-初级，3-中级，4-高级）
 * @property string $fitness_goal 健身目标
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property int $is_delete 软删除标记 0 默认 未删除, 1 删除
 */
class MemberInformation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_information}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['member_id', 'height', 'fitness_foundation', 'is_delete'], 'integer'],
            [['weight'], 'number'],
            [['create_at', 'update_at'], 'safe'],
            [['fitness_goal'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'member_id' => '会员id',
            'height' => '会员身高',
            'weight' => '会员体重',
            'fitness_foundation' => '健身基础（1-入门，2-初级，3-中级，4-高级）',
            'fitness_goal' => '健身目标',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'is_delete' => '软删除标记 0 默认 未删除, 1 删除',
        ];
    }
}