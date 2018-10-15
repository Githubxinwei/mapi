<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%class_record}}".
 *
 * @property string $id
 * @property string $multiple_type
 * @property string $multiple_id
 * @property string $member_id
 * @property integer $status
 * @property string $created_at
 * @property string $coach_id
 * @property string $start
 * @property string $end
 */
class ClassRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%class_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['multiple_id', 'member_id', 'status', 'created_at', 'coach_id', 'start', 'end'], 'integer'],
            [['member_id'], 'required'],
            [['multiple_type'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'multiple_type' => '多态类型',
            'multiple_id' => '多态ID',
            'member_id' => '会员ID',
            'status' => '状态1上课2请假3旷课',
            'created_at' => '创建时间',
            'coach_id' => '教练id',
            'start' => '开始时间',
            'end' => '结束时间',
        ];
    }
}
