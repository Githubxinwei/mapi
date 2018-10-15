<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%gift_card_list}}".
 *
 * @property string $id
 * @property string $gift_card_activity_id
 * @property string $category_type_id
 * @property string $card_category_id
 * @property string $card_number
 * @property string $ID_code
 * @property string $member_id
 * @property string $mobile
 * @property string $nickname
 * @property string $create_time
 * @property string $active_card_time
 * @property string $expire_card_time
 * @property string $is_bind
 * @property string $venue_id
 * @property string $company_id
 * @property string $operator_id
 */
class GiftCardList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gift_card_list}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gift_card_activity_id', 'category_type_id', 'card_category_id', 'member_id', 'create_time', 'update_time', 'is_bind', 'venue_id', 'company_id', 'operator_id'], 'integer'],
            [['card_number', 'ID_code'], 'required'],
            [['card_number', 'ID_code', 'mobile', 'nickname'], 'string', 'max' => 200],
            [['card_number'], 'unique'],
            [['ID_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'gift_card_activity_id' => '新增赠送活动表id',
            'category_type_id' => '卡种类型id',
            'card_category_id' => '卡种id',
            'card_number' => '卡号',
            'ID_code' => '识别码',
            'member_id' => '会员id',
            'mobile' => '手机号',
            'nickname' => '会员昵称',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'is_bind' => '是否绑定(1.未绑定 2.绑定)',
            'venue_id' => '场馆ID',
            'company_id' => '公司ID',
            'operator_id' => '操作人ID',
        ];
    }
}
