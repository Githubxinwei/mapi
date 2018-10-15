<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_complaint}}".
 *
 * @property string $id
 * @property string $venue_id
 * @property string $department_id
 * @property string $member_id
 * @property integer $complaint_type
 * @property string $complaint_content
 * @property string $create_at
 * @property string $update_at
 */
class MemberComplaint extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_complaint}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['venue_id', 'department_id', 'member_id', 'complaint_type', 'create_at', 'update_at'], 'integer'],
            [['complaint_content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'venue_id' => '场馆id',
            'department_id' => '部门id',
            'member_id' => '会员id',
            'complaint_type' => '投诉类型',
            'complaint_content' => '投诉内容',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
        ];
    }
}
