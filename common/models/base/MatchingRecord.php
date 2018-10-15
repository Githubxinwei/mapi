<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%matching_record}}".
 *
 * @property string $id 自增Id
 * @property string $member_card_id 老会员卡Id
 * @property string $member_category_id 新卡种Id
 * @property string $create_at 创建时间
 * @property string $note 备注
 * @property string $create_id 操作人Id
 * @property int $status 状态
 * @property string $attribute_matching 通店属性:1.卡的属性2.卡的类型3.是否带人4.通用场馆限制5.进馆时间限制6.团课套餐7.请假8.赠品9.转让10.合同
 * @property string $member_id 会员Id
 * @property int $type 1.卡种匹配2.会员卡匹配
 */
class MatchingRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%matching_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_card_id', 'member_category_id', 'create_at', 'create_id', 'status', 'member_id', 'type'], 'integer'],
            [['note', 'attribute_matching'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_card_id' => 'Member Card ID',
            'member_category_id' => 'Member Category ID',
            'create_at' => 'Create At',
            'note' => 'Note',
            'create_id' => 'Create ID',
            'status' => 'Status',
            'attribute_matching' => 'Attribute Matching',
            'member_id' => 'Member ID',
            'type' => 'Type',
        ];
    }
}
