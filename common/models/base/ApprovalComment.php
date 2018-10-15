<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%approval_comment}}".
 *
 * @property string $id
 * @property string $approval_detail_id
 * @property string $content
 * @property string $reviewer_id
 * @property string $create_at
 * @property string $update_at
 */
class ApprovalComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%approval_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['approval_detail_id', 'reviewer_id', 'create_at', 'update_at'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'approval_detail_id' => '审核详情表ID',
            'content' => '评论内容',
            'reviewer_id' => '评论人ID',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
        ];
    }
}
