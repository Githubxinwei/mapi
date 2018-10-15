<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%leave_record}}".
 *
 * @property string $id
 * @property string $leave_employee_id
 * @property string $note
 * @property integer $is_approval
 * @property string $create_at
 * @property string $is_approval_id
 * @property string $leave_length
 * @property string $leave_start_time
 * @property string $leave_end_time
 * @property integer $status
 * @property string $member_card_id
 * @property integer $leave_property
 * @property integer $leave_type
 * @property string $reject_note
 * @property integer $is_read
 * @property string $terminate_time
 * @property integer $type
 */
class LeaveRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%leave_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['leave_employee_id', 'create_at'], 'required'],
            [['leave_employee_id', 'is_approval', 'create_at', 'is_approval_id', 'leave_length', 'leave_start_time', 'leave_end_time', 'status', 'member_card_id', 'leave_property', 'leave_type', 'is_read', 'terminate_time', 'type'], 'integer'],
            [['note', 'reject_note'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id自增',
            'leave_employee_id' => '请假人id',
            'note' => '原因',
            'is_approval' => '是否批准(true:批准，false：不批准)',
            'create_at' => '创建时间',
            'is_approval_id' => '批准人ID',
            'leave_length' => '请假时长',
            'leave_start_time' => '请假开始时间',
            'leave_end_time' => '请假结束时间',
            'status' => '类型:1.假期中 2:已销假',
            'member_card_id' => '会员卡id',
            'leave_property' => '请假类型1.特殊请假2.正常请假3.学生请假',
            'leave_type' => '请假类型 1:怀孕 2:伤病 3:其它',
            'reject_note' => '拒绝原因',
            'is_read' => '是否已读',
            'terminate_time' => '销假时间',
            'type' => '状态1待处理,2已同意,3已拒绝',
        ];
    }
}
