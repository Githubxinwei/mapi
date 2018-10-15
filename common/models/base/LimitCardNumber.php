<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%limit_card_number}}".
 *
 * @property string $id
 * @property string $card_category_id
 * @property string $venue_id
 * @property integer $times
 * @property integer $limit
 * @property integer $level
 * @property integer $status
 * @property string $sell_start_time
 * @property string $sell_end_time
 * @property integer $surplus
 * @property integer $week_times
 * @property string $venue_ids
 * @property integer $identity
 * @property string $apply_start
 * @property string $apply_end
 * @property integer $about_limit
 */
class LimitCardNumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%limit_card_number}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_category_id', 'venue_id'], 'required'],
            [['card_category_id', 'venue_id', 'times', 'limit', 'level', 'status', 'sell_start_time', 'sell_end_time', 'surplus', 'week_times', 'identity', 'apply_start', 'apply_end', 'about_limit'], 'integer'],
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
            'card_category_id' => '卡种ID',
            'venue_id' => '场馆ID',
            'times' => '通店次数',
            'limit' => '卡限发量（-1代表不限）',
            'level' => '同一卡种，场馆不同等级不同',
            'status' => '状态1:进 2:卖 3:进卖 4:私教',
            'sell_start_time' => '售卖开始时间',
            'sell_end_time' => '售卖结束时间',
            'surplus' => '剩余张数',
            'week_times' => '周限制次数',
            'venue_ids' => '数组通店场馆数据',
            'identity' => '1普通,2尊爵',
            'apply_start' => '通店开始时间',
            'apply_end' => '通店结束时间',
            'about_limit' => '团课预约设置-1不受限制1受',
        ];
    }
}
