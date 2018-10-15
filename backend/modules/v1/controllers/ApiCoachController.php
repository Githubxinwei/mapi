<?php

namespace backend\modules\v1\controllers;

use backend\modules\v1\models\Employee;
use yii\rest\ActiveController;

class ApiCoachController extends ActiveController
{
    public $modelClass = 'backend\modules\v1\models\Employee';
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * @api {get} /v1/api-coach/get-coach-detail   获取教练详细详情
     * @apiVersion  1.0.0
     * @apiName        获取教练详细详情
     * @apiGroup       coach
     * @apiPermission 管理员
     * @apiParam  {int}            id               教练id
     * @apiParam  {string}        requestType       请求类型（ios）
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-coach/get-coach-detail
     *   {
     *        "id":107，              //教练id
     *        "requestType":"ios"     //请求类型是ios
     *   }
     * @apiDescription   获取教练详细详情
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-coach/get-coach-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     *  "code":1,               //成功标识
     *  "status": "success",    //请求状态
     *  "message": "请求成功"， //返回信息
     *  "data": {
     *      "id": "135",                //教练id
     *      "name": "lala私教",         //教练姓名
     *      "age": null,                //教练年龄
     *      "sex": null,                //教练性别
     *      "position": "",             //教练职务
     *      "status": "1",              //教练状态：1在职 2离职
     *      "created_at": "1494817574", //创建时间(教练信息添加时间)
     *      "intro": "为健身爱好者提供一对一具体指导的健身指导者。",//个人简介
     *      "level": null,              //等级:0新员工1低级2中级3高级
     *      "pic": "http://oo0oj2qmr.bkt.clouddn.com/0.jpg?e=1498014950&to=",//头像
     *      "class_hour": "0",          //课时
     *      "is_check": "0",            //是否需要审核:1需要,0不需要
     *      "is_pass": null,            //是否通过审核:1通过,0未通过
     *      "alias": "是否",            //教练的别名
     *      "work_time": null,          //从业时间
     *      "company_id": "42",         //公司id
     *      "venue_id": "43",           //场馆id
     *      "workTime": null,           //从业时间
     *      "score": 4,                 //星级
     *      "scoreImg": {
     *              "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *              "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *              "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *              "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *              "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
     *          },
     *      "coachPic": "http://oo0oj2qmr.bkt.cloudsW3S:vY0iRs1U="  //教练头像（为空时，这个字段有默认值）
     *     }
     *   }
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     *  {
     *      "code": 0,                   //失败表示
     *      "status": "error",           //请求状态
     *      "message": "未查到教练信息"  //失败原因
     *  }
     */
    public function actionGetCoachDetail($id =1,$requestType = '')
    {
        
        $coach = new Employee();
        if($requestType == 'ios')
        {
            $data = $coach->getCoachDetail($id);
            if(!empty($data))
            {
                return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
            }else{
                return ['code'=>0,'status'=>'error','message'=>'未获取到教练信息数据'];
            }
        }else{
            return $coach->getCoachDetail($id);
        }
    }
    /**
     * @api {get} /v1/api-coach/get-more-private-evaluate   获取所有私教评价
     * @apiVersion  1.7.3
     * @apiName        获取所有私教评价
     * @apiGroup       coach
     * @apiPermission 管理员
     * @apiParam  {int}            id               私教教练id
     * @apiParam  {int}          [page]               分页加载页数（必传字段）第一次传1 第二次 2 第三次3 第四次4
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-coach/get-more-private-detail
     *   {
     *        "id":1272，              //教练id
     *   }
     * @apiDescription   获取所有私教评价
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/6/4
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/api-coach/get-more-private-evaluate
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     *  "code":1,               //成功标识
     *  "status": "success",    //请求状态
     *  "message": "请求成功"， //返回信息
     *   "data": [
     *   {
     *   "pic": "http://oo0oj2qmr.bkt.clouddn.com/7442271525925075.jpg?e=1525928685&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:zTpKM3IZCkGjl_GZnC4xFGAPn44=",
     *   "name": "王谦",
     *   "create_at": "1527818191",
     *   "star_level": "1",
     *   "content": "非常满意"
     *   }
     *   ]
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     *  {
     *      "code": 0,                   //失败表示
     *      "status": "error",           //请求状态
     *      "message": "未查到教练信息"  //失败原因
     *      "data": []
     *  }
     */
    public function actionGetMorePrivateEvaluate()
    {
        $params = \Yii::$app->request->queryParams;
        $coach = new Employee();
        $data = $coach->getMorePrivateEvaluate($params);
        if(!empty($data))
        {
            return ['code' => 1,'status' => 'success','message' => '请求成功','data' => $data];
        }else{
            return ['code' => 0,'status' => 'error','message' => '未获取到教练评价数据','data' => []];
        }
    }
    /**
     * @api {get} /v1/api-coach/get-new-coach-detail   获取私教课教练详情
     * @apiVersion  1.0.0
     * @apiName        获取私教课教练详情
     * @apiGroup       coach
     * @apiPermission 管理员
     * @apiParam  {int}            id               教练id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-coach/get-new-coach-detail
     *   {
     *        "id":107，              //教练id
     *   }
     * @apiDescription   获取私教课教练详情
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/6/5
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/api-coach/get-new-coach-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     *  "code":1,               //成功标识
     *  "status": "success",    //请求状态
     *  "message": "请求成功"， //返回信息
     *  "data": {
     *      "id": "135",                //教练id
     *      "name": "lala私教",         //教练姓名
     *      "age": null,                //教练年龄
     *      "sex": null,                //教练性别
     *      "position": "",             //教练职务
     *      "status": "1",              //教练状态：1在职 2离职
     *      "created_at": "1494817574", //创建时间(教练信息添加时间)
     *      "intro": "为健身爱好者提供一对一具体指导的健身指导者。",//个人简介
     *      "level": null,              //等级:0新员工1低级2中级3高级
     *      "pic": "http://oo0oj2qmr.bkt.clouddn.com/0.jpg?e=1498014950&to=",//头像
     *      "class_hour": "0",          //课时
     *      "is_check": "0",            //是否需要审核:1需要,0不需要
     *      "is_pass": null,            //是否通过审核:1通过,0未通过
     *      "alias": "是否",            //教练的别名
     *      "work_time": null,          //从业时间
     *      "company_id": "42",         //公司id
     *      "venue_id": "43",           //场馆id
     *      "workTime": null,           //从业时间
     *      "score": 4,                 //星级
     *      "coachPics"                 //私教风采展示图片
     *      "coachVideos"               //私教风采展示视频
     *      "scoreImg": {
     *              "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *              "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *              "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *              "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *              "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
     *          },
     *      "coachPic": "http://oo0oj2qmr.bkt.cloudsW3S:vY0iRs1U="  //教练头像（为空时，这个字段有默认值）
     *     }
     *   }
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     *  {
     *      "code": 0,                   //失败表示
     *      "status": "error",           //请求状态
     *      "message": "未查到教练信息"  //失败原因
     *      "data": null
     *  }
     */
    public function actionGetNewCoachDetail($id ='')
    {
        $coach = new Employee();
        $data = $coach->getNewCoachDetail($id);
        if(!empty($data))
        {
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
        }else{
            return ['code'=>0,'status'=>'error','message'=>'未获取到教练信息数据','data'=>NULL];
        }
    }
    /**
     * @api {get} /v1/api-coach/get-coach   获取所有教练
     * @apiVersion  1.0.0
     * @apiName        获取所有教练
     * @apiGroup       coach
     * @apiPermission 管理员
     * @apiParam  {string}        requestType       请求类型（ios）
     * @apiParam  {int}          venueId           场馆id
     * @apiParam  {string}        orderId          订单id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-coach/get-coach
     *   {
     *        "requestType":"ios"     //请求类型是ios
     *        "venueId":11           // 场馆id
     *         "orderId":12         // 不传值将会查询所有 教练   传值 查询 订单绑定的教练
     *   }
     * @apiDescription   获取教练详细详情
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-coach/get-coach
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     *  "code":1,               //成功标识
     *  "status": "success",    //请求状态
     *  "message": "请求成功"， //返回信息
     *   "data": [
     *   {
     *      "name": "lala私教",               //教练名称
     *      "age": null,                      //教练年龄
     *      "id": 135,                        //教练约课
     *      "pic": "img/touxiang.png",        //教练头像
     *      "workTime": 5,                    //教练工作年限
     *      "score": 4,                       //评分
     *      "scoreImg": {                     //图片
     *         "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *         "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *         "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *         "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *         "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
     *     }
     *   },
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     *  {
     *      "code": 0,                   //失败表示
     *      "status": "error",           //请求状态
     *      "message": "未查到教练信息"  //失败原因
     *  }
     */
    public function actionGetCoach($requestType = '',$venueId = '',$orderId="")
    {
        if($requestType == 'ios')
        {
            $coach = new Employee();
            $data = $coach->getCoach($venueId,$orderId);
            if(!empty($data)){
                return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
            }else{
                return ['code'=>0,'status'=>'error','message'=>'未查到教练信息'];
            }
        }else{
            $coach = new Employee();
            return $coach->getCoach($venueId,$orderId);
        }
    }
}
