<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%approval_process}}".
 *
 * @property string $id
 * @property integer $type
 * @property string $approver_id
 * @property string $sender_id
 * @property string $company_id
 * @property string $venue_id
 * @property string $create_at
 */
class ApprovalProcess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%approval_process}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'approver_id', 'sender_id', 'company_id', 'venue_id', 'create_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'type' => '审批类型',
            'approver_id' => '审批人id',
            'sender_id' => '抄送人id',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
            'create_at' => '创建时间',
        ];
    }
}
