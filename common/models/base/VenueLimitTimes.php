<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%venue_limit_times}}".
 *
 * @property string $id
 * @property string $member_card_id
 * @property string $venue_id
 * @property integer $total_times
 * @property integer $overplus_times
 * @property string $company_id
 * @property integer $week_times
 * @property string $venue_ids
 * @property integer $level
 * @property string $apply_start
 * @property string $apply_end
 * @property integer $about_limit
 */
class VenueLimitTimes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%venue_limit_times}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_card_id', 'venue_id', 'total_times', 'overplus_times', 'company_id', 'week_times', 'level', 'apply_start', 'apply_end', 'about_limit'], 'integer'],
            [['venue_ids'], 'string'],
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
            'venue_id' => '场馆ID',
            'total_times' => '总次数',
            'overplus_times' => '剩余次数',
            'company_id' => '公司id',
            'week_times' => '周限制次数',
            'venue_ids' => '数组通店场馆数据',
            'level' => '等级',
            'apply_start' => '通店开始时间',
            'apply_end' => '通店结束时间',
            'about_limit' => '团课预约设置-1不受限制1受',
        ];
    }
}
