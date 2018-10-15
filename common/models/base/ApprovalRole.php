<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%approval_role}}".
 *
 * @property string $id
 * @property integer $type
 * @property string $approval_type_id
 * @property string $role_id
 * @property string $employee_id
 * @property string $company_id
 * @property string $venue_id
 * @property string $department_id
 * @property string $create_at
 */
class ApprovalRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%approval_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'approval_type_id', 'role_id', 'employee_id', 'company_id', 'venue_id', 'department_id', 'create_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'type' => '类型：1审批角色，2抄送角色',
            'approval_type_id' => '审批类型id',
            'role_id' => '角色id',
            'employee_id' => '员工id',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
            'department_id' => '部门id',
            'create_at' => '创建时间',
        ];
    }
}
