<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_buy_class}}".
 *
 * @property string $id
 * @property string $member_card_id
 * @property string $class_id
 * @property integer $type
 * @property string $num
 * @property string $valid_day
 * @property string $buy_class_time
 * @property string $amount_money
 * @property string $create_at
 */
class MemberBuyClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_buy_class}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_card_id', 'class_id', 'type'], 'required'],
            [['member_card_id', 'class_id', 'type', 'num', 'valid_day', 'buy_class_time', 'create_at'], 'integer'],
            [['amount_money'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'member_card_id' => '会员卡ID',
            'class_id' => '课程ID',
            'type' => '类型:1私课 2团课',
            'num' => '总节数',
            'valid_day' => '有效天数',
            'buy_class_time' => '买课时间',
            'amount_money' => '金额',
            'create_at' => '创建时间',
        ];
    }
}
