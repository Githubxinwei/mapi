<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%gift_card_activity}}".
 *
 * @property string $id
 * @property string $active_name
 * @property string $gift_card_num
 * @property string $active_card_num
 * @property string $create_time
 * @property string $start_time
 * @property string $end_time
 * @property string $note
 * @property string $venue_id
 * @property string $company_id
 * @property string $operator_id
 */
class GiftCardActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gift_card_activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active_name'], 'required'],
            [['gift_card_num', 'active_card_num', 'create_time', 'start_time', 'end_time', 'venue_id', 'company_id', 'operator_id'], 'integer'],
            [['active_name', 'note'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'active_name' => '赠送名称',
            'gift_card_num' => '赠送卡总数量',
            'active_card_num' => '开卡数量',
            'create_time' => '创建时间',
            'start_time' => '活动开始时间(开始开卡时间)',
            'end_time' => '活动结束时间(开始结束时间)',
            'note' => '备注',
            'venue_id' => '场馆ID',
            'company_id' => '公司ID',
            'operator_id' => '操作人ID',
        ];
    }
}
