<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/5/6
 * Time: 13:50
 * Desc:会员端-开屏广告
 */
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\Advertising;
use backend\modules\v1\models\AdvertisingStatistics;

class AdvertisingController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\Advertising';

    /**
     * {get}
     * 开屏广告
     * /v1/advertising/list
     * $companyId   公司id
     * $type        广告位置类型
     * $venueId     场馆id    可为空
    */
    public function actionList($companyId,$type,$venueId = null)
    {
        if ($companyId && $type) {
            $model = new Advertising();
            $data = $model->getOpenAdvertising($companyId,$type,$venueId);
            if ($data) {
                return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data];
            } else {
                return ['code' => 0, 'status' => 'error', 'message' => '没有数据','data' => NULL];
            }
        } else {
            return ['code' => 0, 'status' => 'error', 'message' => '没有数据','data' => NULL];
        }
    }
    /**
     * {get}
     * /v1/advertising/detail
     * 增加点击量
     * $adId  广告id
     * $venueId 场馆id  可为空
     */
    public function actionDetail()
    {
        $adId   = \Yii::$app->request->get('adId','0');
        $venueId   = \Yii::$app->request->get('venueId','0');
        if ($adId) {
            if (empty($venueId)) {
                $data = AdvertisingStatistics::updateAllCounters(['click_num' => 1],['ad_id' => $adId]);
            } else {
                $data = AdvertisingStatistics::updateAllCounters(['click_num' => 1],['venue_id' => $venueId,'ad_id' => $adId]);
            }
            if ($data) {
                return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data];
            } else {
                return ['code' => 0, 'status' => 'error', 'message' => '没有数据','data' => NULL];
            }
        }
        return NULL;
    }
}