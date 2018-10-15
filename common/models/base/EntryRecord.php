<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%entry_record}}".
 *
 * @property string $id
 * @property string $member_card_id
 * @property string $venue_id
 * @property string $entry_time
 * @property string $create_at
 * @property string $leaving_time
 * @property string $member_id
 * @property string $company_id
 * @property integer $entry_way
 * @property string $note
 */
class EntryRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%entry_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_card_id', 'venue_id', 'entry_time', 'create_at', 'leaving_time', 'member_id', 'company_id', 'entry_way'], 'integer'],
            [['note'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'member_card_id' => '会员卡id	',
            'venue_id' => '场馆id',
            'entry_time' => '进场时间',
            'create_at' => '创建时间',
            'leaving_time' => '离场时间',
            'member_id' => '会员ID',
            'company_id' => '场馆id',
            'entry_way' => '进馆方式1.前台验卡 2闸机刷卡',
            'note' => '备注',
        ];
    }
}
