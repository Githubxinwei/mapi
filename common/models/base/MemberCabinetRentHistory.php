<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_cabinet_rent_history}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $price
 * @property string $start_rent
 * @property string $end_rent
 * @property string $back_rent
 * @property string $create_at
 * @property string $cabinet_id
 * @property string $remark
 * @property string $member_card_id
 * @property string $rent_type
 * @property string $give_month
 */
class MemberCabinetRentHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_cabinet_rent_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'start_rent', 'end_rent', 'back_rent', 'create_at', 'cabinet_id', 'member_card_id','give_month'], 'integer'],
            [['price'], 'number'],
            [['remark'], 'string'],
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
            'member_id' => '会员id',
            'price' => '租金',
            'start_rent' => '起租日',
            'end_rent' => '到期日',
            'back_rent' => '退租日',
            'create_at' => '创建日期',
            'cabinet_id' => '柜子id',
            'remark' => '备注',
            'member_card_id' => '会员卡id',
            'rent_type' => '柜子租用类型',
            'give_month'=>'赠送月数',
        ];
    }
}
