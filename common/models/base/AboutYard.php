<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%about_yard}}".
 *
 * @property string $id
 * @property string $yard_id
 * @property string $member_id
 * @property string $member_card_id
 * @property string $about_interval_section
 * @property string $aboutDate
 * @property string $cancel_about_time
 * @property integer $status
 * @property string $create_at
 * @property string $about_start
 * @property string $about_end
 */
class AboutYard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%about_yard}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['yard_id', 'member_id', 'member_card_id', 'cancel_about_time', 'status', 'create_at', 'about_start', 'about_end'], 'integer'],
            [['about_interval_section', 'aboutDate'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'yard_id' => '场地id',
            'member_id' => '会员id',
            'member_card_id' => '会员卡id',
            'about_interval_section' => '预约区间段',
            'aboutDate' => '预约日期',
            'cancel_about_time' => '取消预约日期',
            'status' => '1:未开始 2:已开始 3:已结束 4:旷课(没去)5:取消预约',
            'create_at' => '创建时间(预约时间)',
            'about_start' => '预约开始时间',
            'about_end' => '预约结束时间',
        ];
    }
}
