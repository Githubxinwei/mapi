<?php

namespace backend\modules\v1\controllers;

use backend\modules\v1\models\Organization;
use yii\rest\ActiveController;

class ApiVenueController extends ActiveController
{
    public $modelClass = 'backend\modules\v1\models\Organization';
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * @api {get} /v1/api-venue/get-venue-detail   场馆详情
     * @apiVersion  1.0.0
     * @apiName        场馆详情
     * @apiGroup       venue
     * @apiPermission 管理员
     * @apiParam  {int}           id                场馆id或者公司id
     * @apiParam  {string}        requestType       请求类型（ios）
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-venue/get-venue-detail
     *   {
     *        "id":1,                 // 场馆id或者公司id
     *        "requestType":"ios"     //请求类型是ios
     *   }
     * @apiDescription   获取场馆详情
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/17
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-venue/get-venue-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     *  {
     *    "code": 1,                        //成功标识（ios需要的zi）
     *    "status": "success",              //成共状态
     *    "message": "请求成功",            //成功信息
     *    "data": {
     *      "id": "2",                      //场馆id
     *      "pid": "1",                     //父id(公司id)
     *      "name": "大上海",               //场馆名称
     *      "style": "2",                   //1.公司，2.场馆，3.部门
     *      "area": "2000",                 //场馆面积
     *      "establish_time":'147554754110',//场馆成立时间，时间戳格式
     *      "address": "12",                //场馆地址
     *      "venueAddress": "12",           //场馆地址(和address一样，只是字段名为了和其他接口保持一致)
     *      "phone": "121212",              //场馆电话
     *      "describe": "zhe shasdf asd ",  //场馆描述
     *      "pic": "http://oo0oj2qmr.bkt.clouddn.com/5.jpg?", //场馆图片
     *      "businessHours": "8:00 - 21:30", //场馆营业时间
     *      "longitude": "113.676957",      // 场馆精度
     *      "latitude":"113.676957"         // 场馆维度
     *      "score": 4                       //场馆级别
     *    }
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                             //失败标识
     *   "status": "error",                     //失败标识
     *   "message": "获取场馆详细信息失败"      //提示信息
     * }
     */
    public function actionGetVenueDetail($id =1,$requestType = '')
    {
        $coach = new Organization();
        if($requestType == 'ios')
        {
            $data = $coach->getVenueDetail($id);
            if(!empty($data))
            {
                return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
            }else{
                return ['code'=>0,'status'=>'error','message'=>'获取场馆详细信息失败',];
            }
        }else{
            return $coach->getVenueDetail($id);
        }
    }
    /**
     * @api {get} /v1/api-venue/get-all-company   获取所有公司
     * @apiVersion  1.0.0
     * @apiName        获取所有公司
     * @apiGroup       company
     * @apiPermission 管理员
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-venue/get-all-company
     * @apiDescription   获取所有公司
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/3
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-venue/get-all-company
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     * "code": 1,                   //成功标识
     * "status": "success",         //成功状态
     * "message": "请求成功",       //成功信息
     * "data": [
     * {
     *      "id": "1",              //公司id
     *      "name": "我爱运动"      //公司名称
     * },
     * {
     *      "id": "8",              //公司id
     *      "name": "多少度"        //公司名称
     * }
     * ]
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                             //失败标识
     *   "status": "error",                     //失败标识
     *   "message": "获取公司信息失败"      //提示信息
     * }
     */
    public function actionGetAllCompany($type = "")
    {
        $model = new Organization();
        $data  = $model->getAllCompany($type);
        if(!empty($data))
        {
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
        }else{
            return ['code'=>0,'status'=>'error','message'=>'获取公司信息失败',];
        }
    }
    /**
     * @api {get} /v1/api-venue/get-all-venue   获取选中的公司下面的场馆
     * @apiVersion  1.0.0
     * @apiName        获取选中的公司下面的场馆
     * @apiGroup       company
     * @apiParam  {int}           companyId               公司id
     * @apiParam  {int}           type                    公司来源区分
     * @apiParam  {int}            maId                   公司账号Id
     * @apiPermission 管理员
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-venue/get-all-venue
     *   {
     *        "companyId":1,                 //公司id
     *   }
     * @apiDescription   获取选中的公司下面的场馆
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/4
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-venue/get-all-venue
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     * "code": 1,                   //成功表示
     * "status": "success",         //成功状态
     * "message": "请求成功",       //成功信息
     * "data": [
     *      {
     *      "id": "13",              //场馆id
     *      "name": "迈步场馆"       //场馆名称
     *      },
     *      {
     *      "id": "14",              //场馆id
     *      "name": "瀑布场馆"      //场馆名称
     *      }
     *   ]
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                             //失败标识
     *   "status": "error",                     //失败标识
     *   "message": "没有找到场馆信息"         //提示信息
     * }
     */
    public function actionGetAllVenue($companyId = '',$type = "",$maId = "")
    {
        if(empty($companyId))
        {
            return ['code'=>0,'status'=>'error','message'=>'请先选择公司',];
        }
        $model = new Organization();
        $data  = $model->getAllVenue($companyId,"ios",$type,$maId);
        if(!empty($data))
        {
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
        }else{
            return ['code'=>0,'status'=>'error','message'=>'没有找到场馆信息',];
        }
    }
    /**
     * @api {get} /v1/api-venue/get-complain-message  指定场馆部门信息
     * @apiVersion  1.0.0
     * @apiName       获取指定场馆的投诉信息
     * @apiGroup       company
     * @apiParam  {int}           companyId               公司id
     * @apiParam  {int}           venueId                 部门id
     * @apiPermission 管理员
     * @apiParamExample {json} 请求参数
     *   GET  /v1/api-venue/get-complain-message
     *   {
     *        "companyId":1,                 //公司id
     *        "venueId":2,                   // 部门id
     *   }
     * @apiDescription  获取指定公司下的场馆信息和 所属场馆部门信息
     * <br/>
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/8/17
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-venue/get-complain-message
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     * "code": 1,                   //成功表示
     * "status": "success",         //成功状态
     * "message": "请求成功",       //成功信息
     * "data":
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                             //失败标识
     *   "status": "error",                     //失败标识
     *   "message": "没有找到场馆信息"         //提示信息
     * }
     */
      public function actionGetComplainMessage($companyId = '',$venueId =""){
          $model = new Organization();
          $data  = $model->getAllAppointMessage($companyId,$venueId);
          if(!empty($data))
          {
              return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>[$data]];
          }else{
              return ['code'=>0,'status'=>'error','message'=>'没有指定信息',];
          }
      }
    /**
     * @api {get} /v1/api-venue/get-dep-message  获取指定场馆的部门
     * @apiVersion  1.0.0
     * @apiName        获取指定场馆的部门
     * @apiGroup       company
     * @apiParam  {int}           venueId                 部门id
     * @apiPermission 管理员
     * @apiParamExample {json} 请求参数
     *   GET  /v1/api-venue/get-complain-message
     *   {
     *        "venueId":2,                   // 部门id
     *   }
     * @apiDescription  获取指定场馆的部门
     * <br/>
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/8/17
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-venue/get-dep-message
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     * "code": 1,                   //成功表示
     * "status": "success",         //成功状态
     * "message": "请求成功",       //成功信息
     * "data": []                  //返回的数据信息
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                             //失败标识
     *   "status": "error",                     //失败标识
     *   "message": "没有找到场馆信息"         //提示信息
     * }
     */
      public function actionGetDepMessage($venueId =""){
          $model = new Organization();
          $data  = $model->getDepMessage($venueId);
          if(!empty($data))
          {
              return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
          }else{
              return ['code'=>0,'status'=>'error','message'=>'没有指定信息',];
          }
      }
    /**
     * @api {get} /v1/api-venue/gain-com-about-data   公司,场馆
     * @apiVersion  1.0.0
     * @apiName        公司,场馆
     * @apiGroup       company
     * @apiParam  {string}   isNOtMB                 是否是迈步公司
     * @apiPermission 管理员
     * @apiParamExample {json} 请求参数
     *   GET  /v1/api-venue/get-complain-message
     *   {
     *        "isNOtMB ":"maibu",                   // 迈步标志
     *   }
     * @apiPermission 管理员
     * @apiDescription  获取对应公司的所有部门（是迈步公司的传标志,不是的不传）
     * <br/>
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/8/17
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-venue/gain-com-about-data
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     * "code": 1,                   //成功表示
     * "status": "success",         //成功状态
     * "message": "请求成功",       //成功信息
     * "data": []                  //返回的数据信息
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                             //失败标识
     *   "status": "error",                     //失败标识
     *   "message": "没有找到场馆信息"         //提示信息
     * }
     */
    public function actionGainComAboutData($isNOtMB = null){
        $model = new Organization();
        $data  = $model->gainAllCompanyVenueData($isNOtMB);
        if(!empty($data))
        {
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
        }else{
            return ['code'=>0,'status'=>'error','message'=>'没有指定信息',];
        }
    }


}
