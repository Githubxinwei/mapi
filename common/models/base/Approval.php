<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%approval}}".
 *
 * @property string $id
 * @property string $name
 * @property string $polymorphic_id
 * @property string $number
 * @property string $approval_type_id
 * @property integer $status
 * @property string $create_id
 * @property integer $total_progress
 * @property integer $progress
 * @property string $note
 * @property string $company_id
 * @property string $venue_id
 * @property string $create_at
 * @property string $update_at
 */
class Approval extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%approval}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['polymorphic_id', 'number', 'approval_type_id', 'status', 'create_id', 'total_progress', 'progress', 'company_id', 'venue_id', 'create_at', 'update_at'], 'integer'],
            [['note'], 'string'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'name' => '审批名称',
            'polymorphic_id' => '多态id',
            'number' => '审批编号',
            'approval_type_id' => '审批类型id',
            'status' => '状态:1审批中，2已通过',
            'create_id' => '创建人id',
            'total_progress' => '总进度',
            'progress' => '当前进度',
            'note' => '备注',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
        ];
    }
}
