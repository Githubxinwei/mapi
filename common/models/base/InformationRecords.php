<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%information_records}}".
 *
 * @property string $id 自增ID
 * @property string $member_id 会员ID
 * @property string $member_card_id 会员卡ID
 * @property string $create_at 创建时间
 * @property string $note 备注
 * @property int $behavior 行为：1延期开卡，2解冻，3冻结
 * @property int $create_id 创建人id
 * @property int $old_time 赠卡修改前时间
 * @property int $new_time 赠卡修改后时间
 */
class InformationRecords extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%information_records}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'behavior'], 'required'],
            [['member_id', 'member_card_id', 'create_at', 'behavior', 'create_id', 'old_time', 'new_time'], 'integer'],
            [['note'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'member_card_id' => 'Member Card ID',
            'create_at' => 'Create At',
            'note' => 'Note',
            'behavior' => 'Behavior',
            'create_id' => 'Create ID',
            'old_time' => 'Old Time',
            'new_time' => 'New Time',
        ];
    }
}
