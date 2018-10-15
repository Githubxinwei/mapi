<?php
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\YardForm;
use backend\modules\v1\models\YardModel;
use Yii;

class ApiYardController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\YardForm';
    /**
     * @api {get} v1/api-yard/yards 场地列表
     * @apiVersion      1.6.0
     * @apiName         场地列表
     * @apiGroup       yards
     * @apiPermission  管理员
     * @apiParam  {string} venueId 场馆ID
     * @apiParamExample {json} 请求参数
     *   {
     *      "venueId" :  2   场馆ID
     *   }
     * @apiDescription  场地列表
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/5/14
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/api-yard/yards
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code"   : 1,              //成功标识
     *  "status" : "success",      //成功标识
     *  "message":"请求成功"        //成功提示信息
     *  "data"   : [{
     *            'id' : 场地ID
     *     'yard_name  : 场地名称
     *     }]
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code"   : 0,
     *  "status" : "errors",
     *  "message": "没有可选择的场地",
     *  "data"   : [{
     *          "暂无数据"
     *     }]
     * }
     */
    public function actionYards($venueId)
    {
        $model = new YardModel();
        $data = $model->getYardLister($venueId);
        if($data){
            return $this->success($data,'请求成功');
        }
        return $this->errors('没有可选择的场地');
    }
    /**
     * @api {get} v1/api-yard/details  场地详情
     * @apiVersion      1.6.0
     * @apiName         场地详情
     * @apiGroup        details
     * @apiPermission  管理员
     * @apiParam  {string} yardId 场地ID  aboutTimes 预约时间
     * @apiParamExample {json} 请求参数
     *   {
     *      "yardId"  :  2,   场地ID
     *      "aboutTimes" : 2018-04-04,  预约时间 Y-m-d
     *   }
     * @apiDescription  场地详情
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/5/14
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/api-yard/details
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code"   : 1,              //成功标识
     *  "status" : "success",      //成功标识
     *  "message":"请求成功"        //成功提示信息
     *  "data"   : [{
     *   timeColumns: '12:00-13:00',
     *   status: '过期:0, 正常: 1',    //时间段状态
     *    peoplesLimit: 20,           //人数限制
     *   aboutPeoples: 5              //已预约人数
     *     }]
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code"   : 0,
     *  "status" : "errors",
     *  "message": "请求失败",
     *  "data"   : [{
     *          "暂无数据"
     *    }]
     * }
     */
    public function actionDetails($yardId, $aboutTimes)
    {
        $model = new YardModel();
        $data = $model->getYardSlotLister($yardId, $aboutTimes);
        if(is_array($data)){
            return $this->success($data, "请求成功");
        }
        return $this->errors('请求失败');
    }
    /**
     * @api {get} v1/api-yard/allow-cards  获取场地预约会员卡
     * @apiVersion      1.6.0
     * @apiName         获取场地预约会员卡
     * @apiGroup        allow-cards
     * @apiPermission  管理员
     * @apiParam  {string}   yardId 场地ID venueId 场馆ID accountId 账户ID
     * @apiParamExample {json} 请求参数
     *   {
     *      "yardId"  :  2   场地ID
     *      "venueId" :  2   场馆ID
     *      "accountId"  : 1 账户ID
     *   }
     * @apiDescription  获取场地预约会员卡
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/4/7
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/api-yard/allow-cards
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code"   : 1,              //成功标识
     *  "status" : "success",      //成功标识
     *  "message":"请求成功"        //成功提示信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code"   : 1,
     *  "status" : "errors",
     *  "message": "请求失败",
     *  "data"   : [{
     *          "暂无数据"
     *     }]
     * }
     */
    public function actionAllowCards()
    {
        $accountId = Yii::$app->request->get('accountId', 0);
        $venueId = Yii::$app->request->get('venueId', 0);
        $yardId = Yii::$app->request->get('yardId', 0);
        $model = new YardModel();
        if($accountId && $venueId && $yardId){
            return $model->getMemberVenueCards($accountId,$venueId,$yardId);
        }
        return ['status' => 'errors', 'code' => 0, 'message' => '请求失败' , 'data' => []];
    }
    /**
     * @api {post} v1/api-yard/about  预约场地表单提交
     * @apiVersion      1.6.0
     * @apiName         预约场地表单提交
     * @apiGroup        about
     * @apiPermission  管理员
     * @apiParam  {string} venueId  场馆ID
     * @apiParamExample {json} 请求参数
     *   {
     *      "yardId"  :  2,   场地ID
     *      "venueId" :  2,   场馆ID
     *      "accountId"  : 1, 账户ID
     *      'memberId': 555,  会员ID
     *      "memberCardId" :1, 卡ID
     *      "aboutIntervalSection" : 12:00-13:00,  //预约时段
     *      "aboutDate" : 2018-04-07,    //预约日期
     *   }
     * @apiDescription  预约场地表单提交
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/5/15
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/api-yard/about
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code"   : 1,              //成功标识
     *  "status" : "success",      //成功标识
     *  "message":"请求成功"        //成功提示信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code"   : 0,
     *  "status" : "errors",
     *  "message": "提示语",
     *  "data"   : [{
     *          "暂无数据"
     *     }]
     * }
     */
    public function actionAbout()
    {
        $post = \Yii::$app->request->post();
        $accountId = isset($post['accountId']) ? $post['accountId'] : null;
        $aboutTime = isset($post['aboutDate']) ? $post['aboutDate'] : null;
        $timeSection = isset($post['aboutIntervalSection']) ? $post['aboutIntervalSection'] : null;
        if($accountId && $aboutTime && $timeSection){
            $model = new YardForm();
            if($model->load($post, '') && $model->validate()){
                return $model->verify();
            }
            return ['status' => 'errors', 'code' => 0, 'message' => '验证失败' , 'data' => []];
        }
        return ['status' => 'errors', 'code' => 0, 'message' => '请求失败' , 'data' => []];
    }
    /**
     * @api {get} v1/api-yard/cancel-about  取消预约
     * @apiVersion      1.6.0
     * @apiName         取消预约
     * @apiGroup        cancel-about
     * @apiPermission  管理员
     * @apiParam  {string} aboutYardId 预约场地Id
     * @apiParamExample {json} 请求参数
     *   {
     *     aboutYardId  :  132,    预约场地Id
     *   }
     * @apiDescription  取消预约
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/5/15
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/api-yard/cancel-about
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code"   : 1,              //成功标识
     *  "status" : "success",      //成功标识
     *  "message":"取消预约成功!"        //成功提示信息
     *  }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code"   : 0,
     *  "status" : "errors",
     *  "message": "取消预约失败!",
     *  "data"   : [{
     *          "暂无数据"
     *     }]
     * }
     */
    public function actionCancelAbout($aboutYardId)
    {
        $result = YardModel::cancelYardAbout($aboutYardId);
        return $result;
    }
    /**
     * @api {get} v1/api-yard/my-yard  我的场地列表
     * @apiVersion      1.6.0
     * @apiName         场地列表
     * @apiGroup        my-yard
     * @apiPermission  管理员
     * @apiParam  {string} accountId 账户ID
     * @apiParamExample {json} 请求参数
     *   {
     *     accountId : 1,    //账户ID
     *     page :  2,             //第2页
     *     per-page : 8,          //每页8条数据
     *   }
     * @apiDescription  我的场地列表
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/5/15
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/api-yard/my-yard
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code"   : 1,              //成功标识
     *  "status" : "success",      //成功标识
     *  "message":"请求成功!"        //成功提示信息
     *  }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code"   : 0,
     *  "status" : "errors",
     *  "message": "暂无数据!",
     *  "data"   : [{
     *          "暂无数据"
     *     }]
     * }
     */
    public function actionMyYard($accountId)
    {
        $model = new YardModel();
        return $model->gainMyYardAboutLister($accountId);
    }
}