<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_deposit}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $price
 * @property integer $voucher
 * @property string $start_time
 * @property string $end_time
 * @property string $create_id
 * @property string $create_at
 * @property string $pay_mode
 * @property integer $type
 * @property integer $is_use
 */
class MemberDeposit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_deposit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['member_id', 'voucher', 'start_time', 'end_time', 'create_id', 'create_at', 'pay_mode', 'type', 'is_use'], 'integer'],
            [['price'], 'number'],
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
            'voucher' => '券',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'create_id' => '创建人',
            'create_at' => '创建时间',
            'pay_mode' => '付款方式1.现金，2.微信，3.支付宝4.pos机',
            'type' => '定金类型1.购卡2.购课3.续费4.升级',
            'is_use' => '是否使用1.未使用2.已使用',
        ];
    }
}
