<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%card_time}}".
 *
 * @property string $id
 * @property string $card_category_id
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
class CardTime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%card_time}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_category_id'], 'required'],
            [['card_category_id', 'create_at', 'update_at'], 'integer'],
            [['day', 'week', 'month', 'quarter', 'year'], 'string'],
            [['start', 'end'], 'string', 'max' => 200],
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
            'start' => '开始时间：每天的几点',
            'end' => '结束时间：每天的几点',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'day' => '日时段(json[1号,2号，3，4...])',
            'week' => '周时段(json[周1,周二，3，4...])',
            'month' => '月时段(json[1月,2月，3，4...])',
            'quarter' => '季度时段(json[1季度,2季度，3，4])',
            'year' => '年时段(json[1年，...])',
        ];
    }
}
