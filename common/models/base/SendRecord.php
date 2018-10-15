<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%send_record}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $cover_member_id
 * @property string $member_card_id
 * @property string $note
 * @property string $send_time
 * @property string $created_at
 */
class SendRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%send_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'cover_member_id', 'member_card_id', 'send_time', 'created_at'], 'integer'],
            [['note'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'member_id' => '赠送会员ID',
            'cover_member_id' => '被赠送赠送会员ID',
            'member_card_id' => '被赠送会员卡ID',
            'note' => '备注',
            'send_time' => '赠送时间',
            'created_at' => '创建时间',
        ];
    }
}
