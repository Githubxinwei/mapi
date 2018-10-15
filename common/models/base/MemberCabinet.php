<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_cabinet}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $price
 * @property string $start_rent
 * @property string $end_rent
 * @property string $back_rent
 * @property integer $status
 * @property string $creater_id
 * @property string $create_at
 * @property string $update_at
 * @property string $cabinet_id
 * @property string $change_cabinet_remark
 * @property string $member_card_id
 * @property string $rent_type
 */
class MemberCabinet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_cabinet}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'creater_id'], 'required'],
            [['member_id', 'start_rent', 'end_rent', 'back_rent', 'status', 'creater_id', 'create_at', 'update_at', 'cabinet_id', 'member_card_id'], 'integer'],
            [['price'], 'number'],
            [['change_cabinet_remark'], 'string'],
            [['rent_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'member_id' => '会员ID',
            'price' => '金额',
            'start_rent' => '起租日',
            'end_rent' => '到期日',
            'back_rent' => '退租日期',
            'status' => '状态：1未到期，2快到期，3到期，4逾期',
            'creater_id' => '经办人ID',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'cabinet_id' => '柜子ID',
            'change_cabinet_remark' => '变更柜子id记录',
            'member_card_id' => '会员卡id',
            'rent_type' => '租柜类型',
        ];
    }
}
