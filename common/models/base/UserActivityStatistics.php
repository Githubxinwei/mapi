<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%user_activity_statistics}}".
 *
 * @property string $id
 * @property integer $request_page
 * @property integer $device_type
 * @property string $request_date
 * @property string $create_at
 * @property string $venue_id
 * @property string $company_id
 * @property string $device_number
 * @property string $member_id
 */
class UserActivityStatistics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_activity_statistics}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_page', 'device_type', 'create_at', 'member_id'], 'integer'],
            [['venue_id', 'company_id'], 'number'],
            [['request_date', 'device_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'request_page' => '请求页面类型 0:下载安装 1:团课列表 2:私教列表 3:团课详情 4:私课详情',
            'device_type' => '设备类型 1:安卓 2:ios',
            'request_date' => '时长',
            'create_at' => '请求时间',
            'venue_id' => '场馆id',
            'company_id' => '公司id',
            'device_number' => '设备编号',
            'member_id' => '会员id',
        ];
    }
}
