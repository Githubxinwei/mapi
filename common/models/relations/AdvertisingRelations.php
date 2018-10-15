<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/5/6
 * Time: 17:34
 */
namespace common\models\relations;

use backend\modules\v1\models\AdvertisingSetting;
use backend\modules\v1\models\AdvertisingStatistics;

trait AdvertisingRelations
{
    /**
     * 会员端 - 广告设置 - 关联广告设置表
     * @author Xin Wei<xinwei@itsports.club>
     * @create 2018/05/06
     * @return \yii\db\ActiveQuery
     */
    public function getAdvertisingSetting()
    {
        return $this->hasOne(AdvertisingSetting::className(), ['id' => 'setting_id']);
    }
    /**
     * 会员端 - 广告统计 - 广告详情统计表
     * @author Xin Wei<xinwei@itsports.club>
     * @create 2018/05/06
     * @return \yii\db\ActiveQuery
     */
    public function getAdvertisingStatistics()
    {
        return $this->hasOne(AdvertisingStatistics::className(), ['ad_id' => 'id']);
    }
}