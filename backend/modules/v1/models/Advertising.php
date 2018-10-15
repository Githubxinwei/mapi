<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/5/6
 * Time: 14:57
 */
namespace backend\modules\v1\models;

use backend\modules\v1\models\AdvertisingSetting;
use backend\modules\v1\models\AdvertisingStatistics;
use common\models\relations\AdvertisingRelations;

class Advertising extends \common\models\base\Advertising
{
    use AdvertisingRelations;
    public $companyId;
    public $venueId;
    public $type;
    /**
     * 移动端API - 获取开屏图片
     * @author 辛威 <xinwei@itsport.club>
     * @createAt 2018/5/6
     */
    public function getOpenAdvertising($companyId,$type,$venueId)
    {
        //是否开启广告
        $model = AdvertisingSetting::find()
            ->where(['status' => 1,'company_id'=>$companyId,'type' => $type])
            ->select('id')
            ->asArray()
            ->one();
        if (!$model) return NULL;
        //是否可用
        if (!empty($venueId)) {
            $data = $this->getAdvertising($companyId,$type);
            if ($data) {
                $info = json_decode($data['venue_ids'],true);
                $venue_ids = [];
                foreach ($info as $k => $v){
                    $venue_ids[] = $v['venue'];
                }
                if (!in_array($venueId,$venue_ids) && ($venue_ids[0] <> 0)) {
                    return NULL;
                } else {
                    //增加曝光量
                    AdvertisingStatistics::updateAllCounters(['show_num' => 1],['venue_id' => $venueId,'ad_id' => $data['id']]);
                    return $data;
                }
            }
            return NULL;
        } else {
            $data = $this->getAdvertising($companyId,$type);
            if ($data) {
                //增加曝光量
                AdvertisingStatistics::updateAllCounters(['show_num' => 1],['ad_id' => $data['id']]);
                return $data;
            }
            return NULL;
        }
    }
    /**
     * 移动端API - 获取图片信息
     * @author 辛威 <xinwei@itsport.club>
     * @createAt 2018/5/6
     */
    public function getAdvertising($companyId,$type)
    {
        $data = Advertising::find()
            ->alias('ad')
            ->joinWith('advertisingSetting ads',false)
            ->where(['ad.status' => 1])
            ->andWhere(['ads.status' => 1,'ads.company_id' => $companyId,'type' => $type])
            ->andWhere(['and',['<=','ad.start',time()],['>=','ad.end',time()]])
            ->select('
                ad.id,
                ad.name,
                ad.photo,
                ad.url,
                ad.is_over,
                ad.show_time,
                ad.venue_ids,
                ad.start,
                ad.end,
              ')
            ->asArray()
            ->one();
        return $data;
    }
}