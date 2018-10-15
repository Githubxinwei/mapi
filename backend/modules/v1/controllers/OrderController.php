<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/9
 * Time: 16:59
 * Desc:健康餐订单
 */
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\Order;
use Yii;
use yii\data\ActiveDataProvider;

class OrderController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\Order';
    /**
     * @api {post} /v1/order/get-meal-order-list   获取健身餐订单列表
     * @apiVersion  1.8.1
     * @apiName        获取健身餐订单列表
     * @apiGroup       order-list
     * @apiPermission 管理员
     * @apiParam  {json}          accountId            账户ID
     * @apiParam  {int}           state               订单状态
     * @apiParam  {int}           type               退款/售后标识
     * @apiParamExample {json} 请求参数
     *   POST /v1/order/get-meal-order-list
     *   {
     *        "accountId":72550                                           //账户ID
     *   }
     * @apiDescription   获取健身餐订单列表
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/07/11
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/order/get-meal-order-list
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     * "message": "",
     * "code": 1,
     * "status": 200,
     * "data": {
     * "items": [
     * {
     * "id": "31180",
     * "status": 6,
     * "order_time": "2018-07-24",
     * "specs": [
     * {
     * "pic": "http://oo0oj2qmr.bkt.clouddn.com/6217571531813203.png?e=1531816803&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:Mez_of5D7R1bbmC8wuCrmT47Iis=",
     * "name": "0.1",
     * "specName": "测试规格1：444 测试规格2：333 测试规格3：333 ",
     * "num": "1",
     * "unit_price": "0.10",
     * "total_price": "0.10"
     * }
     * ],
     * "purchase_num": 1,
     * "total_price": "0.10",
     * "receive_time": ""
     * },
     * {
     * "id": "31110",
     * "status": 4,
     * "order_time": "2018-07-23",
     * "specs": [
     * {
     * "pic": "http://oo0oj2qmr.bkt.clouddn.com/6217571531813203.png?e=1531816803&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:Mez_of5D7R1bbmC8wuCrmT47Iis=",
     * "name": "0.1",
     * "specName": "444 333 333",
     * "num": "1",
     * "unit_price": "0.10",
     * "total_price": "0.10"
     * }
     * ],
     * "purchase_num": 1,
     * "total_price": "0.10",
     * "receive_time": ""
     * }
     * ],
     * "_links": {
     * "self": {
     * "href": "http://192.168.6.178/v1/order/get-meal-order-list?page=1"
     * }
     * },
     * "_meta": {
     * "totalCount": 2,
     * "pageCount": 1,
     * "currentPage": 1,
     * "perPage": 20
     * }
     * }
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,              //失败标识
     *  "status": "error",      //失败标识
     *  "message":"网络错误，请稍后重试!"    //失败提示信息
     * }
     */
    public function actionGetMealOrderList()
    {
        $post = \Yii::$app->request->post();
        $model = new Order();
        if ($model->load($post, '') && $model->validate()) {
            $query = $model->getMealOrderList();
            return new ActiveDataProvider(['query' => $query]);
        }
        return NULL;
    }
    /**
     * @api {post} /v1/order/get-meal-order-detail   获取健身餐订单详情
     * @apiVersion  1.8.1
     * @apiName        获取健身餐订单详情
     * @apiGroup       order
     * @apiPermission 管理员
     * @apiParam  {json}          orderId            订单ID
     * @apiParamExample {json} 请求参数
     *   POST /v1/order/get-meal-order-detail
     *   {
     *        "orderId":31121                                           //订单ID
     *   }
     * @apiDescription   获取健身餐订单详情
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/07/11
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/order/get-meal-order-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     * "message": "",
     *  "code": 1,
     *  "status": 200,
     *  "data": [
     *   {
     *  "id": "154",
     *  "pay_money_mode": "2",
     *  "order_number": "1532401447337650",
     *  "order_time": "1532401447",
     *  "name": "青菜",
     *   "spec": "加 加",
     *  "num": "1",
     *  "unit_price": "2.00",
     *  "total_price": "2.00",
     *  "meal_date": "2018-07-24",
     *  "meal_time": "10:00-14:00"
     *  },
     *  {
     *  "id": "155",
     *  "pay_money_mode": "2",
     *  "order_number": "1532401447337650",
     *  "order_time": "1532401447",
     * "name": "0.1",
     *  "spec": "444 333 333",
     *   "num": "1",
     *   "unit_price": "0.10",
     * "total_price": "0.10",
     *  "meal_date": "2018-07-24",
     *  "meal_time": "10:00-14:00"
     *  }
     * ]
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     * "message": "",
     * "code": 1,
     * "status": 200,
     * "data": []
     * }
     */
    public function actionGetMealOrderDetail()
    {
        $post = \Yii::$app->request->post();
        $model = new Order();
        if ($model->load($post, '') && $model->validate()) {
            $data = $model->getMealOrderDetail();
            return $data;
        }
        return NULL;
    }
    /**
     * @api {post} /v1/order/get-take-meal-time   获取取餐日期和时间段
     * @apiVersion  1.8.1
     * @apiName        获取取餐日期和时间段
     * @apiGroup       order
     * @apiPermission 管理员
     * @apiParam  {json}          venueId            场馆ID
     * @apiParamExample {json} 请求参数
     *   POST /v1/order/get-take-meal-location-time
     *   {
     *        "venueId":59                                           //场馆ID
     *   }
     * @apiDescription   获取取餐日期和时间段
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/07/10
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/order/get-take-meal-time
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "message": "",
     *  "code": 1,              //成功标识
     *  "status": 200,          //成功标识
     *  "data": [
     *      {
     *      "mealDate": "2018-07-26",
     *      "mealTime": [
     *      "17:00-23:00"
     *      ]
     *      },
     *      {
     *      "mealDate": "2018-07-27",
     *      "mealTime": [
     *      "10:00-14:00",
     *      "17:00-23:00"
     *      ]
     *      }
     *   ]
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "message": "",
     *  "code": 0,              //失败标识
     *  "status": "error",      //失败标识
     *  "message":"网络错误，请稍后重试!"    //失败提示信息
     * }
     */
    public function actionGetTakeMealTime()
    {
        $post = \Yii::$app->request->post();
        $model = new Order();
        if ($model->load($post, '') && $model->validate()) {
            $data = $model->getTakeMealTime();
            return $data;
        }
        return NULL;
    }
    /**
     * @api {post} /v1/order/cancel-order   取消订单
     * @apiVersion  1.8.1
     * @apiName        取消订单
     * @apiGroup       order
     * @apiPermission 管理员
     * @apiParam  {int}           orderId               订单ID
     * @apiParamExample {json} 请求参数
     *   POST /v1/order/get-meal-order-list
     *   {
     *        "orderId":31027                                           //订单ID
     *   }
     * @apiDescription   取消订单
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/07/21
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/order/cancel-order
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"取消成功！"    //成功提示信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,              //失败标识
     *  "status": "error",      //失败标识
     *  "message":"网络错误，请稍后重试!"    //失败提示信息
     * }
     */
    public function actionCancelOrder()
    {
        $post = \Yii::$app->request->post();
        $model = new Order();
        if ($model->load($post, '') && $model->validate()) {
            $arr = $model->getOrderInfo($post['orderId']);
            if ($arr['status'] <> 1) {
                return ['code' => '0','status' => 'error', 'message' => '对不起，只有未付款的才可以取消订单!'];
            }
            $data = $model->cancelOrder();
            if ($data) {
                return ['code' => '1','status' => 'success', 'message' => '取消成功!'];
            }
            return ['code' => '0','status' => 'error', 'message' => '网络错误，请稍后重试!'];
        }
        return NULL;
    }
    /**
     * @api {post} /v1/order/refund-order   退款
     * @apiVersion  1.8.1
     * @apiName        退款
     * @apiGroup       order
     * @apiPermission 管理员
     * @apiParam  {int}           orderId               订单ID
     * @apiParamExample {json} 请求参数
     *   POST /v1/order/refund-order
     *   {
     *        "orderId":31027                                           //订单ID
     *   }
     * @apiDescription   退款
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/07/21
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/order/cancel-order
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"退款申请成功！"    //成功提示信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,              //失败标识
     *  "status": "error",      //失败标识
     *  "message":"网络错误，请稍后重试!"    //失败提示信息
     * }
     */
    public function actionRefundOrder()
    {
        $post = \Yii::$app->request->post();
        $model = new Order();
        if ($model->load($post, '') && $model->validate()) {
            $data = $model->refundOrder();
            return $data;
        }
        return NULL;
    }
}