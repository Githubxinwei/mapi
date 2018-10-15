<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%approval_details}}".
 *
 * @property string $id
 * @property string $approval_id
 * @property integer $status
 * @property string $approver_id
 * @property string $describe
 * @property string $approval_process_id
 * @property string $create_at
 * @property string $update_at
 */
class ApprovalDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%approval_details}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['approval_id', 'status', 'approver_id', 'approval_process_id', 'create_at', 'update_at'], 'integer'],
            [['describe'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'approval_id' => '审核表ID',
            'status' => '状态：1.审批中,2.已同意，3已拒绝，4已撤销',
            'approver_id' => '审批人',
            'describe' => '审批描述',
            'approval_process_id' => '审批流程ID',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
        ];
    }
}
