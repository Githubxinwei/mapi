<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%venue_yard}}".
 *
 * @property string $id
 * @property string $venue_id
 * @property string $yard_name
 * @property string $people_limit
 * @property string $business_time
 * @property string $active_duration
 * @property string $create_at
 */
class VenueYard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%venue_yard}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['venue_id', 'people_limit', 'active_duration', 'create_at'], 'integer'],
            [['yard_name', 'business_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'venue_id' => '场馆id',
            'yard_name' => '场地名称',
            'people_limit' => '人数限制',
            'business_time' => '每天营业时间段',
            'active_duration' => '单次活动时长(分钟)',
            'create_at' => '场地创建时间',
        ];
    }
}
