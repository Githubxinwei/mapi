<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%gift_day}}".
 *
 * @property string $id
 * @property integer $days
 * @property integer $gift_amount
 * @property integer $surplus
 * @property integer $type
 * @property string $note
 * @property string $venue_id
 * @property string $company_id
 * @property string $create_at
 * @property string $update_at
 * @property string $role_id
 * @property string $category_id
 */
class GiftDay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gift_day}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['days', 'gift_amount', 'surplus', 'type', 'venue_id', 'company_id', 'create_at', 'update_at'], 'integer'],
            [['role_id', 'category_id'], 'string'],
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
            'days' => '赠送天数',
            'gift_amount' => '赠送量',
            'surplus' => '剩余量',
            'type' => '类型：1购卡，2其它',
            'note' => '备注',
            'venue_id' => '场馆id',
            'company_id' => '公司id',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
            'role_id' => '角色ID存储',
            'category_id' => '卡种id',
        ];
    }
}
