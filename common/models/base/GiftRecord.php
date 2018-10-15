<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%gift_record}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $member_card_id
 * @property string $service_pay_id
 * @property integer $num
 * @property integer $status
 * @property string $name
 * @property string $create_at
 * @property string $get_day
 * @property string $class_type
 * @property string $note
 * @property integer $type
 */
class GiftRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gift_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'service_pay_id'], 'required'],
            [['member_id', 'member_card_id', 'service_pay_id', 'num', 'status', 'create_at', 'get_day', 'type'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['class_type', 'note'], 'string', 'max' => 200],
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
            'member_card_id' => '数量',
            'service_pay_id' => '收费项目ID',
            'num' => '数量',
            'status' => '状态：1未领取，2已领取',
            'name' => '物品名称',
            'create_at' => '创建时间',
            'get_day' => '领取时间',
            'class_type' => '私课类型，hs,pt,birth',
            'note' => '备注',
            'type' => '1未撤销2已撤销',
        ];
    }
}
