<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_card_time}}".
 *
 * @property string $id
 * @property string $member_card_id
 * @property string $start
 * @property string $end
 * @property string $create_at
 * @property string $update_at
 * @property string $day
 * @property string $week
 * @property string $month
 * @property string $quarter
 * @property string $year
 */
class MemberCardTime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_card_time}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_card_id'], 'required'],
            [['member_card_id', 'start', 'end', 'create_at', 'update_at'], 'integer'],
            [['day', 'week', 'month', 'quarter', 'year'], 'string'],
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
            'start' => '开始时间:每天的几点',
            'end' => '结束时间:每天的几点',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'day' => '日(json[1号,2号...])',
            'week' => '周(json[周一,周二...])',
            'month' => '月(json[1月,2月...])',
            'quarter' => '季(json[1季度,2季度...])',
            'year' => '年(json[1年...])',
        ];
    }
}
