<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%apply_record}}".
 *
 * @property string $id
 * @property string $apply_id
 * @property string $be_apply_id
 * @property string $start_apply
 * @property string $end_apply
 * @property integer $status
 * @property string $not_apply_length
 * @property string $note
 * @property string $create_id
 * @property string $create_at
 * @property string $update_at
 */
class ApplyRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%apply_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apply_id', 'be_apply_id', 'start_apply', 'end_apply', 'status', 'not_apply_length', 'create_id', 'create_at', 'update_at'], 'integer'],
            [['note'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'apply_id' => '申请公司id',
            'be_apply_id' => '被申请公司id',
            'start_apply' => '通店开始日期',
            'end_apply' => '通店结束日期',
            'status' => '状态：1已通过；2等待通过；3未通过；4取消；5过期；',
            'not_apply_length' => '不可申请时长',
            'note' => '备注',
            'create_id' => '操作人id',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
        ];
    }
}
