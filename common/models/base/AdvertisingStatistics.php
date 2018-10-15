<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/5/6
 * Time: 14:34
 */
namespace common\models\base;

use Yii;
class AdvertisingStatistics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advertising_statistics}}';
    }
    public function rules()
    {
        return [
            [['id', 'ad_id','venue_id','show_num','click_num'], 'integer'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'ad_id' => '广告ID',
            'venue_id' => '场馆ID',
            'show_num' => '曝光量',
            'click_num' => '点击量',
        ];
    }
}