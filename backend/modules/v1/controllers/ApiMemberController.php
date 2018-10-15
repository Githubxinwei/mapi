<?php

namespace backend\modules\v1\controllers;

use backend\models\Cabinet;
use backend\models\CabinetRuleForm;
use backend\models\Config;
use backend\models\LeaveRecordForm;
use backend\modules\v1\models\AboutClass;
use backend\modules\v1\models\BinkLandForm;
use backend\modules\v1\models\BindMemberInfoForm;
use backend\modules\v1\models\LoginCodeForm;
use backend\modules\v1\models\LoginForm;
use backend\modules\v1\models\Member;
use backend\modules\v1\models\MemberCard;
use backend\modules\v1\models\MemberDetailUpdateForm;
use backend\modules\v1\models\MemberInfoForm;
use backend\modules\v1\models\BindMemberCardInfoForm;
use backend\modules\v1\models\MemberLeave;
use backend\modules\v1\models\ModifyPasswordForm;
use backend\modules\v1\models\MotionRecord;
use backend\modules\v1\models\RetrievePasswordForm;
use backend\modules\v1\models\ScanCode;
use backend\modules\v1\models\SignUpForm;
use backend\modules\v1\models\MemberCourseOrder;
use backend\modules\v1\models\TreadmailMemberInfo;
use backend\modules\v1\models\UserActivityStatistics;
use backend\modules\v1\models\AboutRecordForm;
use common\models\base\MemberAccount;
use common\models\base\MessageCode;
use common\models\Func;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\data\Pagination;

header('Content-type: text/json; charset=UTF-8');

class ApiMemberController extends ActiveController
{
    public $modelClass = 'backend\modules\v1\models\Member';
    public $memberStatus;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                // restrict access to
                'Access-Control-Request-Method' => ['*'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];
        $behaviors['contentNegotiator']['formats'] = ['application/json' => Response::FORMAT_JSON];
        return $behaviors;
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @api {get} /v1/api-member/get-member-class   会员最近课程
     * @apiVersion  1.0.0
     * @apiName        会员最近课程
     * @apiGroup       memberClass1
     * @apiPermission 管理员
     * @apiParam  {int}          accountId         账户id
     * @apiParam  {string}       requestType      请求类型(ios)
     * @apiParam   {string}       page            分页请求 （第一次请求 传“” 第二次 1 第三次 2）
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/get-member-class
     *   {
     *        "accountId":107            //账户id
     *        "requestType":"ios"      //请求类型：ios是区别其他app请求的标识
     *   }
     * @apiDescription   会员最近课程
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/get-member-class
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status" : "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     *   "endPage":2            // 1 不是最后一页 2 最后一页
     *    "data": [
     *  {
     *      "id": "17",                     //团课课程id
     *      "start": "19:00",               //上课时间
     *      "end": "1498910400",            //下课时间
     *      "class_date": "2017-07-01",     //上课日期
     *      "created_at": "1498897366",     //创建时间
     *      "status": "1",                  //团课状态 1正常2调课3旷课4请假
     *      "course_id": "12",              //课种id
     *      "coach_id": "3",                //教练id
     *      "pic": "http://oo0oj2qmr.bkt.clouddn.com/20160803094909_6316.jpg?",//课程图片
     *      "name": "团a",                  //课种名称
     *      "aboutId": "18",                //预约记录id
     *      "coachName": "花花虎",         //教练姓名
     *      "classType": "group",          //课程类型（group表示团课，charge表示私课）
     *      "type": 2,                     //   2表示团课类型，1表示私课类型
     *      "courseFlag": false,           //false表示团课，true表示私课
     *      "cancelFlag": true             //true表示课程已取消，false表示未取消
     *  },
     * {
     *     "id": "5",                       //订单详情id
     *     "course_order_id": "5",          //订单id
     *     "course_id": "11",               //课种id
     *     "course_num": null,              //课量
     *     "course_length": "1000",         //有效期
     *     "original_price": "15.00",       //单节原价
     *     "sale_price": null,              //单节售价
     *     "pos_price": null,               //单节pos售价
     *     "type": "1",                     //订单类型：1私课2团课
     *     "category": "2",                 //课程类型：1多课程2单课程
     *     "product_name": "month单节",     //产品名称
     *     "course_name": "month",          //课种名称
     *     "class_length": "100",           //课种时长
     *     "pic": "http://oo0oj2qmr.bkt.clouddn.com/0.jpg?=", //图片
     *     "desc": "",                      //描述
     *     "aboutId": "41",                 //约课记录id
     *     "memberCardId": "34",            //会员卡id
     *     "classId": "5",                  //订单详情id
     *     "status": "3",                   //订单状态 1.约课中，2取消，3上课中，4课程结束，5课程过期
     *     "classType": "charge",           //课程类型：charge是私课，group是团课
     *     "create_at": "1498116444",       //订单创建时间
     *     "seatId": null,                  //座位id
     *     "coach_id": "5",                 //教练id
     *     "class_date": "2017-06-22",      //订单日期
     *     "start": "15:27",                //开始时间
     *     "end": "1498116999",             //结束时间
     *     "cancel_time": null,             //取消时间
     *     "memberId": "107",               //会员id
     *     "classStatus": true,             //上课中,true表示上课中，false，表示下课
     *     "coachName": "小A",              // 教练名称
     *     "chargeNum": 0,                  //第几节课
     *     "courseFlag": true,              //true表示是私课，false表示团课
     *     "unusedFlag": false              //true表示待使用，false,表示已使用
     *    }
     *  ]
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * ｛
     *   "code": 0,                       //失败标识
     *   "status": "error",               //失败标识
     *   "message": "该会员最近没有课程", //失败原因
     * ｝
     */
    public function actionGetMemberClass($accountId, $requestType = '',$page="")
    {
        $member = new Member();
        $arr = 'limit';
        if ($requestType == 'ios') {
            $data = $member->getMemberClassData($accountId,$arr,$page);
            if(!empty($data))
            {
                return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data,"endPage"=>$member->endPage];
            }else{
                return ['code' => 0, 'status' => 'error', 'message' => '您最近没有课程',"endPage"=>$member->endPage];
            }
        }else{
            return $member->getMemberClassData($accountId,$arr,"noPage");
        }
    }

    /**
     * @api {get} /v1/api-member/get-whole-member-class   会员全部课程
     * @apiVersion  1.0.0
     * @apiName        会员全部课程
     * @apiGroup       memberClass2
     * @apiPermission 管理员
     * @apiParam  {int}          accountId         账户id
     * @apiParam  {string}       requestType      请求类型(ios)
     * @apiParam  {int}          page             空（表示第一页）  1（表示第二页） 2（表示第三页）
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/get-whole-member-class
     *  "accountId":107            //账户id
     *  "requestType":"ios"      //请求类型：ios是区别其他app请求的标识
     * @apiDescription   会员全部课程
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/get-whole-member-class
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     *  "endPage":1             // 1 不是末页 2表示末页
     *    "data": [
     *  {
     *      "id": "17",                     //团课课程id
     *      "start": "19:00",               //上课时间
     *      "end": "1498910400",            //下课时间
     *      "class_date": "2017-07-01",     //上课日期
     *      "created_at": "1498897366",     //创建时间
     *      "status": "1",                  //团课状态 1正常2调课3旷课4请假
     *      "course_id": "12",              //课种id
     *      "coach_id": "3",                //教练id
     *      "pic": "http://oo0oj2qmr.bkt.clouddn.com/20160803094909_6316.jpg?",//课程图片
     *      "name": "团a",                  //课种名称
     *      "aboutId": "18",                //预约记录id
     *      "coachName": "花花虎",         //教练姓名
     *      "classType": "group",          //课程类型（group表示团课，charge表示私课）
     *      "type": 2,                     //   2表示团课类型，1表示私课类型
     *      "courseFlag": false,           //false表示团课，true表示私课
     *      "cancelFlag": true             //true表示课程已取消，false表示未取消
     *  },
     * {
     *     "id": "5",                       //订单详情id
     *     "course_order_id": "5",          //订单id
     *     "course_id": "11",               //课种id
     *     "course_num": null,              //课量
     *     "course_length": "1000",         //有效期
     *     "original_price": "15.00",       //单节原价
     *     "sale_price": null,              //单节售价
     *     "pos_price": null,               //单节pos售价
     *     "type": "1",                     //订单类型：1私课2团课
     *     "category": "2",                 //课程类型：1多课程2单课程
     *     "product_name": "month单节",     //产品名称
     *     "course_name": "month",          //课种名称
     *     "class_length": "100",           //课种时长
     *     "pic": "http://oo0oj2qmr.bkt.clouddn.com/0.jpg?=", //图片
     *     "desc": "",                      //描述
     *     "aboutId": "41",                 //约课记录id
     *     "memberCardId": "34",            //会员卡id
     *     "classId": "5",                  //订单详情id
     *     "status": "3",                   //订单状态 1.约课中，2取消，3上课中，4课程结束，5课程过期
     *     "classType": "charge",           //课程类型：charge是私课，group是团课
     *     "create_at": "1498116444",       //订单创建时间
     *     "seatId": null,                  //座位id
     *     "coach_id": "5",                 //教练id
     *     "class_date": "2017-06-22",      //订单日期
     *     "start": "15:27",                //开始时间
     *     "end": "1498116999",             //结束时间
     *     "cancel_time": null,             //取消时间
     *     "memberId": "107",               //会员id
     *     "classStatus": true,             //上课中,true表示上课中，false，表示下课
     *     "coachName": "小A",              // 教练名称
     *     "chargeNum": 0,                  //第几节课
     *     "courseFlag": true,              //true表示是私课，false表示团课
     *     "unusedFlag": false              //true表示待使用，false,表示已使用
     *    }
     *  ]
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * ｛
     *   "code": 0,                       //失败标识
     *   "status": "error",               //失败标识
     *   "message": "该会员最近没有课程", //失败原因
     * ｝
     */
    public function actionGetWholeMemberClass($accountId, $requestType = '',$page="")
    {
        $member = new Member();
        $arr = 'all';
        if ($requestType == 'ios'){
            $data = $member->getMemberClassData($accountId,$arr,$page,"wholeMemberClass");
            if(!empty($data))
            {
                return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data,"endPage"=>$member->endPage];
            }else{
                return ['code' => 0, 'status' => 'error', 'message' => '您没有预约课程',"endPage"=>$member->endPage];
            }
        } else {
            return $member->getMemberClassData($accountId,$arr,$page="noPage","wholeMemberClass");
        }
    }

    /**
     * @api {get} /v1/api-member/get-class-not-class   会员未使用课程
     * @apiVersion  1.0.0
     * @apiName        会员未使用课程
     * @apiGroup       memberClass3
     * @apiPermission 管理员
     * @apiParam  {int}          accountId         账户id
     * @apiParam  {string}       requestType      请求类型(ios)
     * @apiParam  {int}          page             分页参数   空（表示第一页） 1（表示第二页） 2（表示第三页）
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/get-class-not-class
     *   {
     *        "accountId":107            //账户id
     *        "requestType":"ios"      //请求类型：ios是区别其他app请求的标识
     *   }
     * @apiDescription   会员全部课程
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/get-class-not-class
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     *  "endPage": 1            // 1 不是末页 2是末页
     *  "data":[
     * {
     *   "id": "357",                   //团课课程id
     *   "start": "12:00",              //开始时间
     *   "end": "1498194000",           //结束时间（时间戳）
     *   "class_date": "2017-06-23",    //上课日期
     *   "created_at": "1498042669",    //约课时间
     *   "status": "1",                 //团课状态 1正常2调课3旷课4请假
     *   "course_id": "48",             //课种id
     *   "coach_id": "138",             //教练id
     *   "pic": null,                   //图片
     *   "name": "瑜伽理疗",            //课种名称
     *   "aboutId": "789",              //预约记录id
     *   "aboutStatus": "1",            //预约记录的状态 1正常，2取消，3上课，4下课，5过期
     *   "coachName": "测试人员",       //教练姓名
     *   "classType": "group",          //课程类型（group:团课，charge:私课）
     *   "type": 2,                     //预约类型1私课2团课
     *   "courseFlag": false,           //false表示 是团课，true表示是私课
     *   "unusedFlag": true             //true表示未使用，false表示已使用
     *   },
     *   {
     *   "id": "94",                    //私课订单详情id
     *   "course_order_id": "1386",     //订单id
     *   "course_id": "43",             //课种id
     *   "course_num": "10",            //课量
     *   "course_length": "1000",       //有效期/天
     *   "original_price": "12.00",     //单价原价
     *   "sale_price": null,            //单节售价
     *   "pos_price": null,             //单击pos价
     *   "type": "1",                   //订单类型：1私课2团课
     *   "category": "2",               //课程类型：1多课程2单课程
     *   "product_name": "手机端",      //产品名称
     *   "course_name": "app测试",      //产品名称
     *   "class_length": "100",         //时长
     *   "pic": "http://oo0oj2qmr.bkt.clouddn.com/0.jpg?",图片
     *   "desc": "买课减费，多买东送，手机app测试",  //介绍
     *   "aboutId": "788",              //预约记录id
     *   "memberCardId": "12516",       //会员卡id
     *   "classId": "94",                //订单详情id
     *   "status": "1",                  //预约状态状态
     *   "classType": "charge",          //预约类型 。charge表示私课
     *   "create_at": "1498118418",      //预约时间
     *   "seatId": null,                 //座位id
     *   "coach_id": "135",              //教练id
     *   "class_date": "2017-06-24",     //上课日期
     *   "start": "00:00",               //上课时间
     *   "end": "1498239600",            //下课时间（时间戳）
     *   "cancel_time": null,            //取消时间
     *   "memberId": "18112",            //会员id
     *   "coachName": "lala私教",        //教练姓名
     *   "chargeNum": 5,                 //第几节课
     *   "courseFlag": true,             //true表示是私课
     *   "unusedFlag": true              //true表示未使用
     *   },
     * ]
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * ｛
     *   "code": 0,                       //失败标识
     *   "status": "error",               //失败标识
     *   "message": "该会员最近没有课程", //失败原因
     * ｝
     */
    public function actionGetClassNotClass($accountId, $requestType = '',$page="")
    {
        $member = new Member();
        if ($requestType == 'ios') {
            $data = $member->getMemberNotClassData($accountId,$page);
            if(!empty($data))
            {
                return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data,"endPage"=>$member->endPage];
            }else{
                return ['code' => 0, 'status' => 'error', 'message' => '您没有未使用课程',"endPage"=>$member->endPage];
            }
        } else {
            return $member->getMemberNotClassData($accountId,$page="noPage");
        }
    }

    /**
     * @api {get} /v1/api-member/get-class-cancel-class   会员已取消课程
     * @apiVersion  1.0.0
     * @apiName        会员已取消课程
     * @apiGroup       memberClass4
     * @apiPermission 管理员
     * @apiParam  {int}          accountId         账户id
     * @apiParam  {string}       requestType      请求类型(ios)
     * @apiParam  {string}       page             请求分页
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/get-class-cancel-class
     *   {
     *        "accountId":107            //会员id
     *        "requestType":"ios"      //请求类型：ios是区别其他app请求的标识
     *   }
     * @apiDescription   会员已取消课程
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/get-class-cancel-class
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     *  "endPage": 1            // 1不是末页  2是末页
     *    "data": [
     *  {
     *      "id": "17",                     //团课课程id
     *      "start": "19:00",               //上课时间
     *      "end": "1498910400",            //下课时间
     *      "class_date": "2017-07-01",     //上课日期
     *      "created_at": "1498897366",     //创建时间
     *      "status": "1",                  //团课状态 1正常2调课3旷课4请假
     *      "course_id": "12",              //课种id
     *      "coach_id": "3",                //教练id
     *      "pic": "http://oo0oj2qmr.bkt.clouddn.com/20160803094909_6316.jpg?",//课程图片
     *      "name": "团a",                  //课种名称
     *      "aboutId": "18",                //预约记录id
     *      "coachName": "花花虎",         //教练姓名
     *      "classType": "group",          //课程类型（group表示团课，charge表示私课）
     *      "type": 2,                     //   2表示团课类型，1表示私课类型
     *      "courseFlag": false,           //false表示团课，true表示私课
     *      "cancelFlag": true             //true表示课程已取消，false表示未取消
     *  },
     * {
     *     "id": "5",                       //订单详情id
     *     "course_order_id": "5",          //订单id
     *     "course_id": "11",               //课种id
     *     "course_num": null,              //课量
     *     "course_length": "1000",         //有效期
     *     "original_price": "15.00",       //单节原价
     *     "sale_price": null,              //单节售价
     *     "pos_price": null,               //单节pos售价
     *     "type": "1",                     //订单类型：1私课2团课
     *     "category": "2",                 //课程类型：1多课程2单课程
     *     "product_name": "month单节",     //产品名称
     *     "course_name": "month",          //课种名称
     *     "class_length": "100",           //课种时长
     *     "pic": "http://oo0oj2qmr.bkt.clouddn.com/0.jpg?=", //图片
     *     "desc": "",                      //描述
     *     "aboutId": "41",                 //约课记录id
     *     "memberCardId": "34",            //会员卡id
     *     "classId": "5",                  //订单详情id
     *     "status": "3",                   //订单状态 1.约课中，2取消，3上课中，4课程结束，5课程过期
     *     "classType": "charge",           //课程类型：charge是私课，group是团课
     *     "create_at": "1498116444",       //订单创建时间
     *     "seatId": null,                  //座位id
     *     "coach_id": "5",                 //教练id
     *     "class_date": "2017-06-22",      //订单日期
     *     "start": "15:27",                //开始时间
     *     "end": "1498116999",             //结束时间
     *     "cancel_time": null,             //取消时间
     *     "memberId": "107",               //会员id
     *     "classStatus": true,             //上课中,true表示上课中，false，表示下课
     *     "coachName": "小A",              // 教练名称
     *     "chargeNum": 0,                  //第几节课
     *     "courseFlag": true,              //true表示是私课，false表示团课
     *     "unusedFlag": false              //true表示待使用，false,表示已使用
     *    }
     *  ]
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * ｛
     *   "code": 0,                       //失败标识
     *   "status": "error",               //失败标识
     *   "message": "该会员最近没有课程", //失败原因
     * ｝
     */
    public function actionGetClassCancelClass($accountId, $requestType = '',$page="")
    {
        $member = new Member();
        if ($requestType == 'ios') {
            $data = $member->getMemberCancelClassData($accountId,$page);
            if(!empty($data))
            {
                return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data,"endPage"=>$member->endPage];
            }else{
                return ['code' => 0, 'status' => 'error', 'message' => '您没有取消的课程',"endPage"=>$member->endPage];
            }
        } else {
            return $member->getMemberCancelClassData($accountId,$page);
        }
    }

    /**
     * @api {post} /v1/api-member/login   密码登录
     * @apiVersion  1.0.0
     * @apiName        密码登录
     * @apiGroup       Login
     * @apiPermission 管理员
     * @apiParam  {string}          mobile           手机号
     * @apiParam  {string}          password         密码
     * @apiParam  {string}          companySign      app所属公司标识(WAYD:我爱运动瑜伽健身   MB:迈步运动健身;)
     * @apiParam  {int}             loginVenueId     登录场馆id
     * @apiParamExample {json} 请求参数
     *   POST /v1/api-member/login
     *   {
     *        "mobile":15011122233   //会员id
     *        "password":******      //密码
     *         "companySign":WAYD       // WAYD:我爱运动瑜伽健身   MB:迈步运动健身;
     *         "loginVenueId": 33       // 登录场馆id
     *   }
     * @apiDescription   用户登录
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/login
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情
     *{
     *  "code":1,                            //成功标识
     *  "status":"success",
     *  "message":"登录成功",
     *  "data": {
     *     "id": 18112,
     *     "username": "刘",                 //用户名（基本信息表）
     *     "password": "$2y$13$F6YkUNcKw6wduAkvVuhyxOOVJLliq3j.ykdu69TAw2j7FjNfqv7z6",  //加密后的密码
     *     "mobile": "17740400375",         //手机号
     *     "register_time": "1495699487",   //注册时间
     *     "password_token": null,
     *     "hash": null,
     *     "update_at": null,               //修改时间
     *     "last_time": null,               //最近一次登录时间
     *     "last_fail_login_time": null,    //上次登录失败时间
     *     "times": null,                   //登录失败次数
     *     "status": 0,                     //状态：0待审核，1正常，2禁用
     *     "lock_time": null,               //锁定时长
     *     "params": null,                  //扩展
     *     "counselor_id": "94",            //顾问ID
     *     "member_type": 1,                //1普通会员 2 潜在会员
     *     "venue_id": null,                //场馆id
     *     "is_employee": null,             //是不是员工：1 代表是
     *     "company_id": null,              //公司id
     *     "fixPhone": null,                //固定电话
     *     "detailsId": "13",               //会员详情表id
     *     "detailsName": "黄鹏举",         //姓名（详细信息表）
     *     "member_id": "13",               //会员id
     *     "pic": "http://oo0oj2qmr.bkt.clouddn.com/", //会员头像
     *     "nickname": "张三",              //昵称
     *     "identify": 1                    //身份1 普通 2金爵、尊爵
     *   }
     * },
     *{
     *  "code":0,                   //失败标识
     *  "status":"error",           //失败标识
     *  "message":"登录失败",       //登录失败信息
     *   "data": {                  //登录失败报错信息
     *      "password": [
     *       "密码不能为空"
     *      ]
     *   }
     * },
     */
    public function actionLogin()
    {
        $post = \Yii::$app->request->post();
        $model = new LoginForm();

        if ($model->load($post, '')) {
            if ($member = $model->validateLogin()) {
                $a = $model->updeviceNumber();
                if ($a == 1 ){
                    $result = ['code' => 1, 'status' => 'success', 'message' =>'登录成功' , 'data' => $member];
                    return $result;
                }else{
                    $result =  ['code' => 2, 'status' => 'error', 'message' => $a];
                    return $result;
                }
            }
            $return = Func::setReturnMessageArr($model->errors,'登录失败');
            $result = ['code' => 0, 'status' => 'error', 'message' =>$return, 'data' => $model->errors];
            return $result;
        }
        $return = Func::setReturnMessageArr($model->errors,'登录失败');
        $result = ['code' => 0, 'status' => 'error', 'message' =>$return, 'data' => $model->errors];
        return $result;
    }

    /**
     * @api {post} /v1/api-member/login-code   验证码登录
     * @apiVersion  1.0.0
     * @apiName        验证码登录
     * @apiGroup       Login
     * @apiPermission 管理员
     * @apiParam  {string}          mobile           手机号
     * @apiParam  {string}          code             验证码
     * @apiParam  {string}          companySign      app所属公司标识(WAYD:我爱运动瑜伽健身   MB:迈步运动健身;)
     * @apiParam  {int}             loginVenueId     登录场馆id
     * @apiParamExample {json} 请求参数
     *   POST /v1/api-member/login
     *   {
     *        "mobile":15011122233  //会员手机号
     *        "code":456123         //验证码
     *         "companySign":WAYD   // 场馆标识
     *          "loginVenueId":33   // 登录场馆id
     *   }
     * @apiDescription   用户登录
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/login-code
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情
     *{
     *  "code":1,                   //成功标识
     *  "status":"success",
     *  "message":"登录成功",
     *  "data": {
     *     "id": 18112,
     *     "username": "刘",                 //用户名
     *     "password": "$2y$13$F6YkUNcKw6wduAkvVuhyxOOVJLliq3j.ykdu69TAw2j7FjNfqv7z6",  //加密后的密码
     *     "mobile": "17740400375",         //手机号
     *     "register_time": "1495699487",   //注册时间
     *     "password_token": null,
     *     "hash": null,
     *     "update_at": null,               //修改时间
     *     "last_time": null,               //最近一次登录时间
     *     "last_fail_login_time": null,    //上次登录失败时间
     *     "times": null,                   //登录失败次数
     *     "status": 0,                     //状态：0待审核，1正常，2禁用
     *     "lock_time": null,               //锁定时长
     *     "params": null,                  //扩展
     *     "counselor_id": "94",            //顾问ID
     *     "member_type": 1,                //1普通会员 2 潜在会员
     *     "venue_id": null,                //场馆id
     *     "is_employee": null,             //是不是员工：1 代表是
     *     "company_id": null,              //公司id
     *     "fixPhone": null,                //固定电话
     *     "detailsId": "13",               //会员详情表id
     *     "detailsName": "黄鹏举",         //姓名
     *     "pic": "http://oo0oj2qmr.bkt.clouddn.com/", //会员头像
     *     "nickname": "张三",              //昵称
     *     "identify": 1                    //身份1 普通 2金爵、尊爵
     *   }
     * },
     *{
     *  "code":0,                   //失败标识
     *  "status":"error",
     *  "message":"登录失败",* },
     */
    public function actionLoginCode()
    {
        $post = \Yii::$app->request->post();
        $model = new LoginCodeForm();
        if ($model->load($post, '')) {

            if ($results = $model->validateLogin()) {
                $a = $model->updeviceNumber();
                if ($a==4){
                    $result =  ['code' => 1 , 'status' => 'error', 'message' => $a];
                    return $result;
                }
                $result = ['code' => 2, 'status' => 'success', 'message' =>$a , 'data' => $results];
                return $result;
            }
            $return = Func::setReturnMessageArr($model->errors,'登录失败');
            $result = ['code' => 0, 'status' => 'error', 'message' =>$return, 'data' => $model->errors];
            return $result;
        }
        $return = Func::setReturnMessageArr($model->errors,'登录失败');
        $result = ['code' => 0, 'status' => 'error', 'message' =>$return, 'data' => $model->errors];
        return $result;
    }
    /**
     * @api {post} /v1/api-member/get-member-one   获取会员信息
     * @apiVersion  1.0.0
     * @apiName        密码登录
     * @apiGroup       Login
     * @apiPermission 管理员
     * @apiParam  {string}          memberId         会员ID
     * @apiParamExample {json} 请求参数
     *   POST /v1/api-member/login
     *   {
     *        "memberId":15011122233   //会员id
     *   }
     * @apiDescription   用户登录
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/get-member-one
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情
     *{
     *  "code":1,                            //成功标识
     *  "status":"success",
     *  "message":"登录成功",
     *  "data": {
     *     "id": 18112,
     *     "username": "刘",                 //用户名（基本信息表）
     *     "password": "$2y$13$F6YkUNcKw6wduAkvVuhyxOOVJLliq3j.ykdu69TAw2j7FjNfqv7z6",  //加密后的密码
     *     "mobile": "17740400375",         //手机号
     *     "register_time": "1495699487",   //注册时间
     *     "password_token": null,
     *     "hash": null,
     *     "update_at": null,               //修改时间
     *     "last_time": null,               //最近一次登录时间
     *     "last_fail_login_time": null,    //上次登录失败时间
     *     "times": null,                   //登录失败次数
     *     "status": 0,                     //状态：0待审核，1正常，2禁用
     *     "lock_time": null,               //锁定时长
     *     "params": null,                  //扩展
     *     "counselor_id": "94",            //顾问ID
     *     "member_type": 1,                //1普通会员 2 潜在会员
     *     "venue_id": null,                //场馆id
     *     "is_employee": null,             //是不是员工：1 代表是
     *     "company_id": null,              //公司id
     *     "fixPhone": null,                //固定电话
     *     "detailsId": "13",               //会员详情表id
     *     "detailsName": "黄鹏举",         //姓名（详细信息表）
     *     "member_id": "13",               //会员id
     *     "pic": "http://oo0oj2qmr.bkt.clouddn.com/", //会员头像
     *     "nickname": "张三",              //昵称
     *     "identify": 1                    //身份1 普通 2金爵、尊爵
     *   }
     * },
     *{
     *  "code":0,                   //失败标识
     *  "status":"error",           //失败标识
     *  "message":"登录失败",       //登录失败信息
     *   "data": {                  //登录失败报错信息
     *      "password": [
     *       "密码不能为空"
     *      ]
     *   }
     * },
     */
    public function actionGetMemberOne($accountId)
    {
        $model = new LoginForm();
        $mOne  = $model->getMemberOne($accountId);
        if(!empty($mOne)){
            $result = ['code' => 1, 'status' => 'success', 'message' => '获取成功', 'data' => $mOne];
            return $result;
        }
        $result = ['code' => 0, 'status' => 'error', 'message' =>'获取失败', 'data' =>[]];
        return $result;
    }
    /**
     * @api {post} /v1/api-member/bind-member-base-info   绑定会员信息
     * @apiVersion  1.0.0
     * @apiName        绑定会员信息
     * @apiGroup       binds
     * @apiPermission 管理员
     * @apiParam  {string}          memberId         会员ID
     * @apiParam  {string}          mobile           手机号
     * @apiParam  {string}          venueId          场馆ID
     * @apiParam  {string}          idCard           身份证号
     * @apiParam  {string}          sex             性别
     * @apiParamExample {json} 请求参数
     *   POST /v1/api-member/login
     *   {
     *        "memberId":15011122233   //会员id
     *   }
     * @apiDescription   用户登录
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/bind-member-base-info
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情
     *{
     *  "code":1,                            //成功标识
     *  "status":"success",
     *  "message":"绑定成功",
     *  "data": {
     *   }
     * },
     *{
     *  "code":0,                   //失败标识
     *  "status":"error",           //失败标识
     *  "message":"绑定失败",       //登录失败信息
     *   "data": {                  //登录失败报错信息
     *
     *   }
     * },
     */
    public function actionBindMemberBaseInfo()
    {
        $model = new BindMemberInfoForm();
        $post  = \Yii::$app->request->post();
        $model->load($post,'');
        $mOne  = $model->updateMember();
        if($mOne !== false){
            $result = ['code' => 1, 'status' => 'success', 'message' => '绑定成功', 'data' => $mOne];
            return $result;
        }
        $result = ['code' => 0, 'status' => 'error', 'message' =>'绑定失败', 'data' =>$mOne];
        return $result;
    }
    /**
     * @api {post} /v1/api-member/create-code   获取验证码
     * @apiVersion  1.0.0
     * @apiName        获取验证码
     * @apiGroup       code
     * @apiPermission 管理员
     * @apiParam  {string}          mobile           手机号
     * @apiParamExample {json} 请求参数
     *   POST /v1/api-member/create-code
     *   {
     *        "mobile":15011122233   //手机号
     *   }
     * @apiDescription   用户登录
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/create-code
     * @apiSuccess (返回值) {string} data 返回验证码或者失败信息
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "data": 404316          //验证码
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *   "code": 0,             //失败标识
     *   "status": "error",     //失败标识
     *   "data": [
     *   "发送失败"             //失败信息
     *   ]
     *  }
     */
    public function actionCreateCode()
    {
        $mobile = \Yii::$app->request->post();
        if (!isset($mobile) && !isset($mobile['mobile']) && empty($mobile['mobile'])) {
            return ['status' => 'error', 'data' => '请填写正确的手机号'];
        }
        $code = mt_rand(100000, 999999);
        $session = new MessageCode();
        $session->mobile = $mobile['mobile'];
        $session->code = $code;
        $session->create_at = time();
        if ($session->save()) {
            Func::sendCode($mobile['mobile'], $code);
            return ['code' => 1, 'status' => 'success', 'data' => $code];
        }
        $return = Func::setReturnMessageArr($session->errors,'发送失败');
        return ['code' => 0, 'status' => 'error','message'=>$return, 'data' => ['发送失败']];
    }

    /**
     * @api {post} /v1/api-member/sign-up   注册
     * @apiVersion  1.0.0
     * @apiName        注册
     * @apiGroup       register
     * @apiPermission 管理员
     * @apiParam  {int}             companyId       公司id
     * @apiParam  {int}             venueId         场馆id
     * @apiParam  {string}          mobile         手机号
     * @apiParam  {string}          password       密码
     * @apiParam  {string}          code           验证码
     * @apiParamExample {json} 请求参数
     *   POST /v1/api-member/sign-up
     *   {
     *        "companyId":1            //公司id
     *        "venueId":2             //场馆id
     *        "mobile":15011122233   //手机号
     *        "password":******      //密码
     *        "code":123456         //验证码
     *   }
     * @apiDescription   用户注册
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/17
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/sign-up
     * @apiSuccess (返回值) {string} data 返回注册成功和失败信息
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"注册成功"    //成功提示信息
     *  "data": {
     *      "mobile": "15713716632",            //会员手机号
     *      "username": "15713716632",          //会员临时姓名
     *      "password": "$2y$13$ZJjDeZIXOhI.",  //加密过的密码
     *      "register_time": 1498548933,        //注册时间（时间戳）
     *      "status": 1,                        //状态 1：普通会员
     *      "id": "212"                         //会员id
     *  }
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,             //失败标识
     *  "status": "error",     //失败标识
     *  "message":"注册失败"   //失败提示信息
     *  "data": {
     *      "code": "验证码错误",            //会员手机号
     *  }
     * }
     */
    public function actionSignUp()
    {
        $post = \Yii::$app->request->post();
        $post = (array)$post;
        $model = new SignUpForm();
        if ($model->load($post, '')) {
            $sign = $model->validateSignUp();
            if (isset($sign[0]) && isset($sign[0]['id'])) {
//                $extraData = ["nickname"=>"暂无数据","pic"=>"http://oo0oj2qmr.bkt.clouddn.com/2x.png?e=1499151514&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dPF-Zr1Y8OtzB8iyxd8At9GufkU="];
//                ,"extraData"=>$extraData,"belMessage"=>$model->memberBelMessage 老流程返回数据
                return ['code' => 1, 'status' => 'success', 'message' => '注册成功','data' =>$sign];
            }
            $return = Func::setReturnMessageArr($sign,'注册失败');
            return ['code' => 0, 'status' => 'error', 'message' =>$return, 'data' => $sign];
        }
        $return = Func::setReturnMessageArr(['数据格式不正确'],'注册失败');
        $result = ['code' => 0, 'status' => 'error','message' =>$return, 'data' => ['数据格式不正确']];
        return $result;
    }

    /**
     * @api {post} /v1/api-member/retrieve-password   找回密码
     * @apiVersion  1.0.0
     * @apiName        找回密码
     * @apiGroup       retrievePassword
     * @apiPermission 管理员
     * @apiParam  {string}          mobile         手机号
     * @apiParam  {string}          password       密码
     * @apiParam  {string}          code           验证码
     * @apiParam  {string}          companySign    公司标识
     * @apiParam  {string}          theVenueId     场馆id
     * @apiParamExample {json} 请求参数
     *   POST /v1/api-member/sign-up
     *   {
     *        "mobile":15011122233   //手机号
     *        "password":******      //密码
     *        "code":123456         //验证码
     *        "companySign":wayd    // mb 表示 迈步公司  wayd 表示我爱运动公司
     *         "theVenueId":12      // 场馆id
     *   }
     * @apiDescription   找回密码
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/17
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/retrieve-password
     * @apiSuccess (返回值) {string} data 返回成功和失败
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"修改成功"    //成功提示信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,             //失败标识
     *  "status": "error",     //失败标识
     *  "message":"修改失败"   //失败提示信息
     *  "data":"报错信息"      //失败报错信息
     * }
     */
    public function actionRetrievePassword()
    {
        $post = \Yii::$app->request->post();
        $model = new RetrievePasswordForm();
        if ($model->load($post, '')) {
            $model->loadCode();
            if ($model->validate()) {
                $sign = $model->setRetrieveSave();
                if ($sign === true) {
                    return ['code' => 1, 'status' => 'success', 'message' => '修改成功'];
                }
                $return = Func::setReturnMessageArr($sign,'修改失败');
                return ['code' => 0, 'status' => 'error', 'message' =>$return, 'data' => $sign];
            }
            $return = Func::setReturnMessageArr($model->errors,'修改失败');
            return ['code' => 0, 'status' => 'error', 'message' =>$return, 'data' => $model->errors];
        }
        $return = Func::setReturnMessageArr($model->errors,'修改失败');
        $result = ['code' => 0, 'status' => 'error', 'message' =>$return, 'data' => $model->errors];
        return $result;
    }

    /**
     * @api {post} /v1/api-member/reset-password   重置密码
     * @apiVersion  1.0.0
     * @apiName        重置密码
     * @apiGroup       resetPassword
     * @apiPermission 管理员
     * @apiParam  {string}          mobile         手机号
     * @apiParam  {string}          password       新密码
     * @apiParam  {string}          oldPassword    旧密码
     * @apiParam  {string}          code           验证码
     * @apiParam  {string}          venueMemberId  会员id
     * @apiParamExample {json} 请求参数
     *   POST /v1/api-member/reset-password
     *   {
     *        "mobile":15011122233   //手机号
     *        "password":******      //新密码
     *        "oldPassword":******   //旧密码
     *        "code":123456         //验证码
     *        "account_id":256  // 账户id
     *   }
     * @apiDescription   重置密码
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/17
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/reset-password
     * @apiSuccess (返回值) {string} data 返回成功和失败
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"修改成功"    //成功提示信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,             //失败标识
     *  "status": "error",     //失败标识
     *  "message":"修改失败"   //失败提示信息
     *  "data":"报错信息"      //失败报错信息
     * }
     */
    public function actionResetPassword()
    {
        $post = \Yii::$app->request->post();
        $model = new ModifyPasswordForm();
        if ($model->load($post, '')) {
            $model->loadCode();
            if ($model->validate()) {
                $sign = $model->updatePassword();
                if ($sign === true) {
                    return ['code' => 1, 'status' => 'success', 'message' => '修改成功'];
                }
                $return = Func::setReturnMessageArr($sign,'修改失败');
                return ['code' => 0, 'status' => 'error', 'message' =>$return, 'data' => $sign];
            }
            $return = Func::setReturnMessageArr($model->errors,'修改失败');
            return ['code' => 0, 'status' => 'error', 'message' =>$return, 'data' => $model->errors];
        }
        $return = Func::setReturnMessageArr($model->errors,'修改失败');
        $result = ['code' => 0, 'status' => 'error', 'message' => $return, 'data' => $model->errors];
        return $result;
    }

    /**
     * @api {get} /v1/api-member/member-detail   会员信息
     * @apiVersion  1.0.0
     * @apiName        会员信息
     * @apiGroup       member
     * @apiPermission 管理员
     * @apiParam  {int}          memberId         会员id
     * @apiParam  {string}       requestType      请求类型(ios)
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/member-detail
     *   {
     *        "memberId":107,       //会员id
     *        "requestType":"ios"   //请求类型：ios是区别其他app请求的标识
     *   }
     * @apiDescription   获取会员信息
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/17
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/get-member-detail
     * @apiSuccess (返回值) {string} data 返回成功和失败
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     *  "data": {
     *      "id": "24668",      //会员id
     *      "username": "yyyy", //用户名
     *      "pic": null,        //头像
     *      "venueId":1         //场馆id
     *      "name": "黄鹏举",   //姓名
     *      "nickname": "测试"  //昵称
     *  }
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,             //失败标识
     *  "status": "error",     //失败标识
     *  "message":"请求失败"   //失败提示信息
     * }
     */
    public function actionGetMemberDetail($memberId, $requestType = '')
    {
        $member = new Member();
        if ($requestType == 'ios') {
            $data = $member->getMemberDetail($memberId);
            if (!empty($data)) {
                return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data];
            } else {
                $return = Func::setReturnMessageArr($data,'未获取到会员信息');
                return ['code' => 0, 'status' => 'error', 'message' => $return,];
            }
        } else {
            return $member->getMemberDetail($memberId);
        }
    }

    /**
     * @api {get} /v1/api-member/get-class-detail   会员预约课程详情
     * @apiVersion  1.0.0
     * @apiName        会员课程详情
     * @apiGroup       class
     * @apiPermission 管理员
     * @apiParam  {int}          aboutId          会员预约记录id
     * @apiParam  {string}       type             课程类型(charge表示私课，group表示团课)
     * @apiParam  {string}       requestType      请求类型(ios)
     * @apiParam  {string}       venueId          登录端场馆id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/get-class-detail
     *   {
     *        "aboutId":107              //会员预约记录id
     *        "type":"charge",          //私课
     *        "requestType":"ios"       //请求类型：ios是区别其他app请求的标识
     *        "venueId" : 22;           // 场馆id
     *   }
     * @apiDescription   会员课程详情
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/get-class-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(团课 成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     * "data": {
     *         "id": "5",                   //团课课程id
     *         "coach_id": "3",             //教练id
     *        "classroomName": "瑜伽教室"           // 教室名称
     *        "seatNum": 1                         // 座位号
     *         "course_id": "2",            //课种id
     *         "classroom_id": "1",         //教室id
     *         "start": "1496556000",       //上课时间点
     *         "end": "1496560500",         //下课时间点
     *         "class_date": "2017-06-13",  //上课日期
     *         "difficulty": "2",           //课程难度（1低，2中，3高）
     *         "pic": "",                   //图片
     *         "aboutId": "32",             //约课记录id
     *         "class_id": "5",             //团课课程id
     *         "coachId": "3",              //教练id
     *         "coachName": "舞蹈教练",     //教练姓名
     *         "age": null,                 //教练姓名
     *         "entry_date": null,          //任职日期
     *         "classroomId": "1",          //教室id
     *         "total_seat": "12",          //总座位数
     *         "venue_id": "2",             //场馆id
     *         "courseId": "2",             //课种id
     *         "classLength":"60"，         //时长
     *         "name": "单车2",             //课种名称
     *         "course_desrc": "这是团课",  //介绍
     *         "venueName": null,           //场馆名称
     *         "venueAddress": null,        //场馆地址
     *         "unusedFlag": true,          //true表示未使用，false表示已使用（先不用）
     *         "courseFlag": false,         //false表示团课       （先不用）
     *          "isClass": 2                // 1 上课中 3 已使用 2待使用  (目前使用)
     *         "level": "中级",             //课程级别
     *         "create_at": "2017-06-12 10:00",     //预约时间
     *         "cancel_time": null,                 //取消时间
     *         "score": 4,                          //级别
     *    "scoreImg": {
     *        "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *        "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *        "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *        "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *        "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
     *    },
     *    "limit": true       //true限制取消预约 ,false不限制取消预约
     *   }
     *  * @apiSuccessExample {json}返回值详情(私课 成功时)
     * {
     *     "code": 1,               //成功标识
     *     "status": "success",     //成功标识
     *     "message": "请求成功",   //成功提示信息
     *     "data": {
     *     "id": "788",                         //预约记录id
     *     "member_card_id": "12516",           //会员卡id
     *     "classroomName": "瑜伽教室"           // 教室名称
     *      "seatNum": 1                         // 座位号
     *     "class_id": "94",                    //订单详情id
     *     "status": "1",                       //预约状态
     *     "create_at": "2017-06-22 16:00",     //预约时间
     *     "coach_id": "135",                   //教练id
     *     "unusedFlag": true,                  //true表示未使用，false表示已使用
     *     "class_date": "2017-06-24",          //上课日期
     *     "start": "1498233600",               //上课时间点
     *     "end": "1498239600",                 //下课时间点
     *     "cancel_time": null,                 //预约取消时间
     *     "member_id": "18112",                //会员id
     *     "orderId": "1386",                   //订单id
     *     "type": "charge",                    //课程类型
     *     "productName": "手机端",             //产品名
     *     "courseName": "app测试",             //课程名
     *     "coachName": "lala私教",             //教练名
     *      "coachPic":"dddddd"，               //教练头像
     *     "classLength": "100",                //课时长
     *     "category": "2",                     //课程类型：1多课程2单课程
     *     "courseNum": "10",                   //课量
     *     "courseAmount": "10",                //总数量
     *     "pic": "http://oo0oj2qmr.bkt.clouddn.com/0.jpg?",//图片
     *     "originalPrice": "12.00",                        //单节原价
     *     "totalPrice": "100",                             //总价
     *     "venueId":234,                                   //场馆id
     *     "venueName": "大上海瑜伽健身馆",                 //场馆名称
     *     "venueAddress": "√东太康路大上海城C区6楼",      //场馆地址
     *     "chargeNum": 5,                                  //目前第几课
     *     "score": 4,                                      //星级
     *     "scoreImg": {
     *          "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *          "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *          "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *          "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *          "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
     *     },
     * "packageClass": [
     *      {
     *      "coachName": "lala私教",          //教练名
     *      "name": "app测试",                //课程名
     *      "times": 1,                       //第一节
     *      "course_length": "100",           //课时长
     *      "sale_price": "12.00",            //单节原价
     *      "is_member": "1",                 //1表示是会员
     *      "status": "1"                     //1表示课程上过了
     *      },
     *      {
     *      "coachName": "lala私教",          //教练名
     *      "name": "app测试",                //课程名
     *      "times": 2,                       //第二节
     *      "course_length": "100",           //课时长
     *      "sale_price": "12.00",            //单节原价
     *      "is_member": "1",                 //1表示是会员
     *      "status": "2"                     //2表示课程没上过
     *      },
     * ],
     *   "courseFlag": true,
     *   "limit": false
     *   }
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *   "code": 0,                             //失败标识
     *   "status": "error",                     //失败状态
     *   "message": "没找到会员的预约信息"      //失败原因
     * }
     */
    public function actionGetClassDetail($aboutId, $type, $requestType = '',$venueId)
    {
        $about = new AboutClass();
        if ($type == 'charge') {
            if ($requestType == 'ios') {
                $data = $about->getAboutOne($aboutId,$venueId);
                if(!empty($data))
                {
                    return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => [$data]];
                }else{
                    return ['code' => 0, 'status' => 'error', 'message' => '没找到会员的预约信息',];
                }
            } else {
                return $about->getAboutOne($aboutId,$venueId);
            }
        } else {
            if ($requestType == 'ios') {
                $data = $about->getGroupClassMemberOne($aboutId,$venueId);
                if(!empty($data))
                {
                    return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => [$data]];
                }else{
                    return ['code' => 0, 'status' => 'error', 'message' => '没找到会员的预约信息'];
                }
            } else {
                return $about->getGroupClassMemberOne($aboutId,$venueId);
            }
        }
    }
    /**
     * @api {post} /v1/api-member/alter-member-detail   会员个人资料保存
     * @apiVersion  1.7.3
     * @apiName        会员上传头像
     * @apiGroup       memberImg
     * @apiPermission 管理员
     * @apiParam  {int}          account_id         账户id
     * @apiParam  {string}       nickname           昵称
     * @apiParam  {smallint}     sex               性别
     * @apiParam  {date}         birth_date        生日
     * @apiParam  {varchar}      profession        职业
     * @apiParam  {text}         now_address       居住地
     * @apiParam  {varchar}      introduction      个人简介
     * @apiParamExample {json} 请求参数
     *   POST v1/api-member/alter-member-detail
     *   {
     *        "account_id":72550                                           //账户id
     *        "nickname":"thinkwei"                                       //昵称
     *        "sex":1                                                    //性别 1男2女
     *        "birth_date" :"1998-10-26"                                //出生日期
     *        "profession" :"计算机/互联网/通信"                         //职业
     *        "now_address":"河南省-郑州市-金水区"                       //居住地
     *        "introduction":"热爱运动"                                 //个人简介
     *   }
     * @apiDescription   会员个人资料保存
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/05/29
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/api-member/alter-member-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,              //失败标识
     *  "status": "error",      //失败标识
     *  "message":"请求成功"    //失败提示信息
     *  "data":"报错信息"       //具体报错信息
     * }
     */
    public function actionAlterMemberDetail()
    {
        $post = \Yii::$app->request->post();
        $model = new MemberDetailUpdateForm();
        if ($model->load($post, '') && $model->validate()) {
            $sign = $model->alterMember();
            if ($sign === true) {
                return ['code' => 1, 'status' => 'success', 'message' => '保存成功','data' => $post];
            }
            $return = Func::setReturnMessageArr($sign,'保存失败');
            return ['code' => 0, 'status' => 'error', 'message' => $return, 'data' => $sign];
        }
        $return = Func::setReturnMessageArr($model->errors,'保存失败');
        $result = ['code' => 0, 'status' => 'error', 'message' => $return, 'data' => $model->errors];
        return $result;
    }
    /**
     * @api {post} /v1/api-member/update-member-detail   修改会员信息
     * @apiVersion  1.0.0
     * @apiName        修改会员信息
     * @apiGroup       member
     * @apiPermission 管理员
     * @apiParam  {int}          memberId         会员id
     * @apiParam  {string}       name             会员姓名
     * @apiParamExample {json} 请求参数
     *   POST /v1/api-member/update-member-detail
     *   {
     *        "memberId":107,       //会员id
     *        "name":"张三"        //会员姓名
     *   }
     * @apiDescription   修改会员信息
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/17
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/update-member-detail
     * @apiSuccess (返回值) {string} data 返回成功和失败
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"修改成功"    //成功提示信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,             //失败标识
     *  "status": "error",     //失败标识
     *  "message":"修改失败"   //失败提示信息
     *  "data":"报错信息"      //失败报错信息
     * }
     */
    public function actionUpdateMemberDetail()
    {
        $post = \Yii::$app->request->post();
        $model = new MemberDetailUpdateForm();
        if ($model->load($post, '') && $model->validate()) {
            $sign = $model->updateMember();
            if ($sign === true) {
                return ['code' => 1, 'status' => 'success', 'message' => '修改成功'];
            }
            $return = Func::setReturnMessageArr($sign,'修改失败');
            return ['code' => 0, 'status' => 'error', 'message' => $return, 'data' => $sign];
        }
        $return = Func::setReturnMessageArr($model->errors,'修改失败');
        $result = ['code' => 0, 'status' => 'error', 'message' => $return, 'data' => $model->errors];
        return $result;
    }

    /**
     * @api {get} /v1/api-member/get-class-comment   会员预约课程结束信息
     * @apiVersion  1.0.0
     * @apiName        会员预约课程结束信息
     * @apiGroup       class
     * @apiPermission 管理员
     * @apiParam  {int}          aboutId          会员预约记录id
     * @apiParam  {string}       type             课程类型(charge表示私课，group表示团课)
     * @apiParam  {string}       requestType      请求类型(ios)
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/get-class-comment
     *   {
     *        "aboutId":107              //会员预约记录id
     *        "type":"charge",          //私课
     *        "requestType":"ios"       //请求类型：ios是区别其他app请求的标识
     *   }
     * @apiDescription   会员课程结束信息
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/get-class-comment
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(团课成功时)
     *{
     *  "code": 1,                      //成功标识
     *  "status": "success",            //成功标识
     *  "message": "请求成功",          //成功提示信息
     *  "data": {
     *  "coachName": "花花虎",          //教练名称
     *  "courseName": "团a",            //课种名称
     *  "classPic": "http://oo0oj2qmr.bkt.cl.com/201609_6316.jpg?Upr",//团课图片
     *  "score": 4,                     //课程级别
     *  "scoreImg": {                   //课程星星
     *      "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?",
     *      "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?",
     *      "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?",
     *      "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?",
     *      "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?"
     *   },
     *      "venueName": "大上海馆",    //场馆名称
     *      "venueAddress": "",         //场馆地址
     *      "endTime": "2017-07-01 20:00",//课程下课时间
     *      "classType": "group"        //课程类型：group表示团课
     *  }
     *  }
     * @apiSuccessExample {json}返回值详情(私课成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     *  "data": {
     *     "coachName": "xiaoA",            //教练名称
     *     "productName": "私课单节",       //私课产品名称
     *     "chargeNum": 1,                  //第几节课
     *     "courseName": "减脂",            //课种名称
     *     "classLength": "100",            //课时长
     *     "classPic": "http://oo0oj2qmr.bkt.clouddn.com/128432235.jpg?",//私课产品图片
     *     "totalPrice": "90",              //总价
     *     "classCount": "10",              //总节数
     *     "score": 4,                      //产品级别
     *     "scoreImg": {                    //评论星星
     *      "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?",
     *      "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?",
     *      "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?",
     *      "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?",
     *      "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?"
     *     },
     *      "venueName": "大上海馆",        //场馆名称
     *      "venueAddress": "",             //场馆地址
     *      "endTime": "2017-06-29 15:00",  //课程结束时间
     *      "classType": "charge"           //课程类型：charge表示私课
     *    }
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *     "code": 0,                       //失败标识
     *     "status": "error",               //失败标识
     *     "message": "没找到预约课程相关信息"  //失败提示信息
     * }
     */
    public function actionGetClassComment($aboutId, $type, $requestType = '')
    {
        $about = new AboutClass();
        if ($type == 'charge') {
            if ($requestType == 'ios') {
                $data = $about->getChargeClassComment($aboutId);
                if(!empty($data))
                {
                    return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data];
                }else{
                    return ['code' => 0, 'status' => 'error', 'message' => '没找到预约课程相关信息'];
                }
            } else {
                return $about->getChargeClassComment($aboutId);
            }
        }else{
            if($requestType == 'ios'){
                $data = $about->getGroupClassComment($aboutId);
                if(!empty($data))
                {
                    return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data];
                }else{
                    return ['code' => 0, 'status' => 'error', 'message' => '没找到预约课程相关信息'];
                }
            }else{
                return $about->getGroupClassComment($aboutId);
            }
        }
    }

    /**
     * @api {post} /v1/api-member/set-upload   会员上传头像
     * @apiVersion  1.0.0
     * @apiName        会员上传头像
     * @apiGroup       memberImg
     * @apiPermission 管理员
     * @apiParam  {int}          memberId          会员预约记录id
     * @apiParam  {string}       pic               图片
     * @apiParamExample {json} 请求参数
     *   POST /v1/api-member/set-upload
     *   {
     *        "account_id":18112                                          //会员id
     *        "pic":"http://oo0oj2qmr.bkt.clouddn.com/5.jpg?=",        //图片
     *   }
     * @apiDescription   会员上传头像
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/23
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/set-upload
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,              //失败标识
     *  "status": "error",      //失败标识
     *  "message":"请求成功"    //失败提示信息
     *  "data":"报错信息"       //具体报错信息
     * }
     */
    public function actionSetUpload()
    {
        $account_id = $_POST['account_id'];
        $file = \backend\modules\v1\models\Func::uploadImage();
        if(is_array($file))
        {
            return $file;
        }else if(is_string($file)){
            $url = $file;
            $data['imgLink']  =  $url;
            $data['account_id'] = $account_id;
            $model = new MemberInfoForm();
            if ($model->load($data, '')) {
                $member = $model->updateMember();
                if ($member === true) {
                    return ['code' => 1, 'status' => 'success', 'message' => '上传成功', 'data' => $data['imgLink']];
                }
                $return = Func::setReturnMessageArr($member,'上传失败');
                return ['code' => 0, 'status' => 'error', 'message' => $return, 'data' => $member];
            }
            $return = Func::setReturnMessageArr(['验证失败'],'上传失败');
            $result = ['code' => 0, 'status' => 'error', 'message' => $return, 'data' => ['验证失败']];
            return $result;
        }
    }
    /**
     * @api {get} /v1/api-member/get-member-info   详细信息
     * @apiVersion  1.0.0
     * @apiName        详细信息
     * @apiGroup       memberCardData
     * @apiPermission 管理员
     * @apiParam  {int}          memberCardId   会员卡id
     * @apiParam  {int}          memberId       会员id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/get-member-info
     *   {
     *      "memberCardId":3,                                   //会员卡id
     *      "memberId" :100                                     //会员id
     *   }
     * @apiDescription   详细信息
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/27
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/get-member-info
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     *   "data": {
     *      "cardNumber": "041497854875",       //会员卡号
     *      "createAt": "2017-06-19",           //开卡时间
     *      "amountMoney": "6666",              //总金额
     *      "status": "1",                      //卡状态：1正常，2异常，3冻结，4未激活
     *      "activeTime": "2017-06-19",         //激活时间
     *      "invalidTime": "2017-09-27",        //失效时间
     *      "balance": 0,                       //余额
     *      "cardName": "时间卡",               //卡名
     *      "duration": "100",                  //有效期（单位：天）
     *      "memberId": "3",                    //会员id
     *      "lastTime": 92                      //剩余天数
     *   }
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,                   //失败标识
     *  "message":"请求失败"         //成功提示信息
     *  "data":"该会员没有会员卡"   //具体报错信息
     * }
     */
    public function actionGetMemberInfo($memberCardId)
    {
        $model = new MemberCard();
        $data = $model->getMemberCardInfo($memberCardId);
        if (!empty($data)) {
            return ['code' => 1, 'message' => '请求成功', 'data' => $data];
        } else {
            return ['code' => 0, 'message' => '会员卡信息不存在', 'data' => '会员卡信息不存在'];
        }
    }

    /**
     * @api {get} /v1/api-member/get-member-card-all   会员卡列表
     * @apiVersion  1.0.0
     * @apiName        会员卡列表
     * @apiGroup       memberCardData
     * @apiPermission 管理员
     * @apiParam  {int}          accountId       账户id
     * @apiParam  {int}          venueId         场馆id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/get-member-card-all
     *   {
     *      "accountId" :'100'                                   //账户id
     *   }
     * @apiDescription   会员卡列表
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/27
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/api-member/get-member-card-all
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     *  "data": [
     *   {
     *      "memberCardId": "3",                //会员卡id
     *      "cardNumber": "041497854875",       //会员卡号
     *      "cardName": "时间卡"                //会员卡名称
     *      "cardStatus":1                      //  1正常 2异常 3冻结 4未激活
     *      "leaveRecordStatus":0               // 0表示不在请假中 1表示在请假中
     *   },
     *   {
     *      "memberCardId": "9",                //会员卡id
     *      "cardNumber": "051497940599",       //会员卡号
     *      "cardName": "时间卡"                //会员卡名称
     *      "cardStatus":1                      //  1正常 2异常 3冻结 4未激活
     *      "leaveRecordStatus":0               // 0表示不在请假中 1表示在请假中
     *   }
     * ]
     *  "cardStatus":3                         // 1：有正常的会员卡 2：没有会员卡 3 卡有异常
     *  "cardMessage": "您的卡有异常或未激活"  // 会员办卡信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,                   //失败标识
     *  "message":"请求失败"         //成功提示信息
     *  "data":"该会员没有会员卡"   //具体报错信息
     *  "cardStatus":2                          // 1：有正常的会员卡 2：没有会员卡 3 卡有异常
     *  "cardMessage": "您还没有办理会员卡"      // 会员办卡信息
     * }
     */
    public function actionGetMemberCardAll($accountId,$venueId = null,$source = null)
    {
        $model = new MemberCard();
        $data = $model->getMemberCardAll($accountId,$venueId,$source);
        if (!empty($data)) {
            return ['code' => 1, 'message' => '请求成功', 'data' => $data,"cardStatus"=>$model->cardStatus,"cardMessage"=>$model->cardMessage,"freezeWay"=>$model->theFreezeWay];
        } else {
            return ['code' => 0, 'message' => '您没有会员卡', 'data' => '您没有会员卡',"cardStatus"=>$model->cardStatus,"cardMessage"=>$model->cardMessage,"freezeWay"=>$model->theFreezeWay];
        }
    }

    /**
     * @api {get} /v1/api-member/get-member-cabinet   会员所有柜子
     * @apiVersion  1.0.0
     * @apiName        会员所有柜子
     * @apiGroup       cabinetList
     * @apiPermission 管理员
     * @apiParam  {int}          accountId       账户id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/get-member-cabinet
     *   {
     *      "accountId" :'100'                                   //账户id
     *   }
     * @apiDescription   会员卡列表
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/27
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/get-member-cabinet
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     *  "data": [
     *  {
     *      "memberCabinetId": "1",     //会员柜子id
     *      "cabinet_id": "1",          //柜子id
     *      "cabinetNum": "-0001",      //柜子编号
     *      "cabinetModel": "大柜"      //柜子类型
     *  },
     *  {
     *      "memberCabinetId": "2",     //会员柜子id
     *      "cabinet_id": "2",          //柜子id
     *      "status": "1",              //柜子状态：1未到期，2快到期，3到期，4逾期
     *      "cabinetNum": "-0002",      //柜子编号
     *      "cabinetModel": "大柜"      //柜子类型
     *  }
     *  ]
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,                   //失败标识
     *  "message":"请求失败"         //失败提示信息
     *  "data":"该会员未租用柜子"    //具体报错信息
     * }
     */
    public function actionGetMemberCabinet($accountId)
    {
        $model = new MemberCard();
        $data = $model->getMemberCabinet($accountId);
        if ($data) {
            return ['code' => 1, 'message' => '请求成功', 'data' => $data];
        } else {
            return ['code' => 0, 'message' => '您未租用柜子', 'data' => '该会员未租用柜子'];
        }
    }

    /**
     * @api {get} /v1/api-member/get-member-cabinet-info   柜子详细信息
     * @apiVersion  1.0.0
     * @apiName        柜子详细信息
     * @apiGroup       cabinetInfo
     * @apiPermission 管理员
     * @apiParam  {int}          memberCabinetId       会员柜子id
     * @apiParam  {int}          memberId              会员id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/get-member-cabinet-info
     *   {
     *      "memberCabinetId" :'100'                            //会员柜子id
     *      "memberId" :'100'                                   //会员id
     *   }
     * @apiDescription   会员卡列表
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/27
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/get-member-cabinet-info
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     *  "data": {
     *      "memberCabinetId": "1",         //会员柜子id
     *      "cabinet_id": "1",              //柜子表id
     *      "price": "650",                 //总金额
     *      "start_rent": "2017-06-27",     //租用日期
     *      "end_rent": "2017-12-27",       //结束日期
     *      "status": "1",                  //柜子状态：1未到期，2快到期，3到期，4逾期
     *      "cabinetNum": "-0001",          //柜子编号
     *      "cabinetModel": "大柜",         //柜子类型
     *      "cabinetDay": 183,              //租用天数
     *      "deposit": 50                   //押金（注：会员绑定柜子时没有存押金，沟通后得知所有柜子押金都为50）
     *  }
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,                   //失败标识
     *  "message":"请求失败"         //失败提示信息
     *  "data":"柜子详细信息不存在"    //具体报错信息
     * }
     */
    public function actionGetMemberCabinetInfo($memberCabinetId, $memberId)
    {
        $model = new MemberCard();
        $data = $model->getMemberCabinetInfo($memberCabinetId, $memberId);
        if (!empty($data)) {
            return ['code' => 1, 'message' => '请求成功', 'data' => $data];
        } else {
            return ['code' => 0, 'message' => '柜子详细信息不存在', 'data' => '柜子详细信息不存在'];
        }
    }
    /**
     * @api {post} /v1/api-member/set-party-landing   第三方登录
     * @apiVersion  1.0.0
     * @apiName        第三方登录
     * @apiGroup       partyLanding
     * @apiPermission 管理员
     *
     * @apiParam  {int}          type       第三方服务商类型
     * @apiParam  {int}          openId     唯一ID
     * @apiParam  {int}           companySign  公司标识
     * @apiParam  {string}       scenario   场景
     *
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/set-party-landing
     *   {
     *      "type" :'100'                            //第三方服务商类型
     *      "openId" :'100'                          //唯一ID
     *      "scenario":'login'                       //场景
     *   }
     * @apiDescription   会员卡列表
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/27
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/set-party-landing
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              // 1.已经绑定 2.未绑定
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     *  "data": {
     *      "mobile": "15713716632",            //会员手机号
     *      "username": "15713716632",          //会员临时姓名
     *      "password": "$2y$13$ZJjDeZIXOhI.",  //加密过的密码
     *      "register_time": 1498548933,        //注册时间（时间戳）
     *      "status": 1,                        //状态 1：普通会员
     *      "id": "212"                         //会员id
     *  }
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,                   //失败标识
     *  "message":"请求失败"         //失败提示信息
     *  "data":"数据格式不正确"    //具体报错信息
     * }
     */
    public function actionSetPartyLanding()
    {
        $post  = \Yii::$app->request->post();
        $scenario = $post['scenario'];
        $model = new BinkLandForm([],$scenario);
        if ($model->load($post,'')) {
            $member = $model->landLogin();
            if($member===false){
                return ['code' => 2, 'message' => '未进行第三方绑定', 'data' => $member];
            }
//            if($member=="noRegister"){
//                return ['code' => 3, 'message' => '本场馆会员未注册', 'data' => $member];
//            }
//            if($member=="messageUnusual"){
//                return ['code' => 4, 'message' => '该场馆您的信息异常,请到场馆登记', 'data' => $member];
//            }
            return ['code' => 1, 'message' => '请求成功', 'data' => $member];
        } else {
            return ['code' => 0, 'message' => '数据格式不正确', 'data' => '会员信息不存在'];
        }
    }
    /**
     * @api {post} /v1/api-member/set-bink-member   绑定用户
     * @apiVersion  1.0.0
     * @apiName        绑定用户
     * @apiGroup       partyLanding
     * @apiPermission 管理员
     *
     * @apiParam  {int}          type       第三方服务商类型
     * @apiParam  {int}          openId     唯一ID
     * @apiParam  {int}          mobile     手机号
     * @apiParam  {int}          companyId   公司ID
     * @apiParam  {int}          venueId     场馆Id
     * @apiParam  {int}          password     密码
     * @apiParam  {int}          companySign  公司标识
     * @apiParam  {string}       scenario   场景
     * @apiParam  {string}       code       场景
     * @apiParamExample {json} 请求参数
     *   post /v1/api-member/set-bink-member
     *   {
     *      "type"      :'qq'                            //第三方服务商类型
     *      "openId"    :'skahdausjhd'                    //唯一ID
     *      "mobile"    :'13556565656'                 //手机号
     *      "companyId" :'1'                          //公司ID
     *      "venueId"   :'2'                          //场馆Id
     *      "password"  :'asas'                          //密码
     *      "scenario"  :'bink'                       //场景
     *   }
     * @apiDescription   会员卡列表
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/27
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/set-bink-member
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              // 1.已经绑定 2.未绑定
     *  "status": "success",    //成功标识
     *  "message":"请求成功"    //成功提示信息
     *  "data": {
     *      "mobile": "15713716632",            //会员手机号
     *      "username": "15713716632",          //会员临时姓名
     *      "password": "$2y$13$ZJjDeZIXOhI.",  //加密过的密码
     *      "register_time": 1498548933,        //注册时间（时间戳）
     *      "status": 1,                        //状态 1：普通会员
     *      "id": "212"                         //会员id
     *  }
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,                   //失败标识
     *  "message":"请求失败"         //失败提示信息
     *  "data":"数据格式不正确"    //具体报错信息
     * }
     */
    public function actionSetBinkMember()
    {
        $post  = \Yii::$app->request->post();
        $scenario = $post['scenario'];
        $model = new BinkLandForm([],$scenario);
        if ($model->load($post,'')){
            if($scenario == 'sign'){
                $member = $model->landSignUp();
            }else{
                $member = $model->binkMobile($post['deviceNumber']);
            }
            if($member["status"]=="success"){
                return ['code' => 1, 'message' => '请求成功', 'data' => $member["data"],"belMessage"=>$model->memberBelMessage];
            }
            if($member["status"]=="error"){
                $dataS =  \backend\modules\v1\models\Func::dataChange($member["data"]);
                return ['code' => 0, 'message' =>$dataS,'data' => $member["data"],"belMessage"=>$model->memberBelMessage];
            }
        } else {
            return ['code' => 0, 'message' => '数据格式不正确', 'data' => '绑定会员失败'];
        }
    }
    /**
     * @api {get} /v1/api-member/insert-scan-code  二维码扫描记录
     * @apiVersion  1.0.0
     * @apiName         二维码扫描记录
     * @apiGroup        scanCode
     * @apiPermission  管理员
     * @apiParam  {string}    data          前台发送的二维码信息
     * @apiParamExample {json} 请求参数
     *   get /v1/api-member/insert-scan-code
     *   {
     *      "data"      : 12456@15555127518@412644      //前台发送的二维码信息(会员id@时间戳@会员卡id)
     *   }
     * @apiDescription                        // 前台发送的二维码信息(会员id@时间戳)
     * <br/>
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/28
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/insert-scan-code
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,
     *  "status": "success",    //成功标识
     *  "message":"刷卡成功"    //成功提示信息
     *  "data": true          // 录入成功的信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,
     *  "status": "error",      //失败标识
     *  "message":"刷卡失败"     //失败返回信息
     *  "data": "报错信息"
     * }
     */
    public function actionInsertScanCode(){
        $param  = \Yii::$app->request->get("data");
        $model  = new ScanCode();
        $result = $model->insertData($param);
        if($result===true){
            return ['code' => 1, 'status' => 'success', 'message' =>"刷卡成功","data"=>$result];
        }else{
            return ['code' => 0, 'status' => 'error', 'message' =>"刷卡失败","data"=>$result];
        }
    }
   // 信息对比
    /**
     * @api {get} /v1/api-member/message-contrast  闸机验卡
     * @apiVersion      1.0.0
     * @apiName         闸机验卡
     * @apiGroup        scanCode
     * @apiPermission  管理员
     * @apiParam  {int}   memberId  会员id
     * @apiParamExample {json} 请求参数
     *   get  /v1/api-member/message-contrast
     *   {
     *      "memberId"      : 12313     //会员id
     *   }
     * @apiDescription         //会员id
     * <br/>
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/28
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/message-contrast
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,
     *  "status": "success",    //成功标识
     *  "message":"刷卡成功"    //成功提示信息
     *  "data": true          // 录入成功的信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,
     *  "status": "error",      //失败标识
     *  "message":"刷卡失败"     //失败返回信息
     *  "data": "刷卡失败信息"
     * }
     */
     public function actionMessageContrast($memberId=null){
         $model  = new ScanCode();
         $result = $model->searchMember($memberId);
         if($result===true){
             return ['code' => 1, 'status' => 'success', 'message' =>"开机成功","data"=>$result];
         }elseif ($result=="noMessageCode"){
             return ['code' => 0, 'status' => 'error', 'message' =>"请先生成二维码","data"=>$result];
         }else{
             return ['code' => 0, 'status' => 'error', 'message' =>"您的二维码失效","data"=>$result];
         }
     }
    /**
     * @api {get}        /v1/api-member/machine-save-message  机器信息存储
     * @apiVersion      1.0.0
     * @apiName         机器信息存储
     * @apiGroup        scanCode
     * @apiPermission  管理员
     * @apiParam  {string}   machineModel   机器型号
     * @apiParam  {string}   ip             机器ip
     * @apiParam  {string}   machineType    机器类型
     * @apiParam  {string}   machineStatus  机器状态 1:正常 2：不正常
     * @apiParam  {int}   venueId        场馆id
     * @apiParam  {int}   companyId      公司id
     * @apiParamExample {json} 请求参数
     *   get   /v1/api-member/machine-save-message
     *   {
     *      "machineModel"  :  机器型号
     *      "ip"            :  机器ip
     *      "machineType"   : 机器类型
     *      "machineStatus" : 机器型号
     *       "venueId"      : 场馆id
     *       "companyId"    : 公司id
     *   }
     * @apiDescription     机器信息存储
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/29
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/machine-save-message
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,
     *  "status": "success",        //成功标识
     *  "message":"数据录入成功"    //数据录入成功
     *  "data": true                // 录入成功的信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,
     *  "status": "error",      //失败标识
     *  "message":"录入失败信息"     //失败返回信息
     *  "data": 报错信息
     * }
     */
    public function actionMachineSaveMessage(){
        $param  = \Yii::$app->request->get();
        $model  = new \backend\modules\v1\models\MachineRecord();
        $result = $model->insertData($param);
        if($result === true){   // 完全匹配
            return ['code' => 1, 'status' => 'success', 'message' =>"数据录入成功","data"=>$result];
        }else{
            return ['code' => 0, 'status' => 'error','message' =>"数据录入失败","data"=>$result];
        }
    }

    public function actionA(){
        $data = new SignUpForm();
        $data->getBelongMessage();
        echo 1111;
        exit;
    }
    /**
     * @api {get}        /v1/api-member/is-can-code  是否可以生成二维码
     * @apiVersion      1.0.0
     * @apiName         是否可以生成二维码
     * @apiGroup        scanCode
     * @apiPermission  管理员
     * @apiParam  {int}     memberId    会员id
     * @apiParam  {string}  companyName 公司名称
     * @apiParamExample {json} 请求参数
     *   get    /v1/api-member/is-can-code
     *   {
     *      "memberId"      :  会员id
     *      "companyName"   :  公司名称  //$companyName="maibu"  迈步运动健身   $companyName = 'wayd'  “我爱运动瑜伽健身”
     *   }
     * @apiDescription     是否可以生成二维码
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/11/20
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/is-can-code
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,
     *  "status": "success",        //成功标识
     *  "message":"数据录入成功"    //数据录入成功
     *  "data": true                // 录入成功的信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,
     *  "status": "error",      //失败标识
     *  "message":"录入失败信息"     //失败返回信息
     *  "data": false 或者 true
     * }
     */
    public function actionIsCanCode($memberId="",$companyName=""){
        $model   = new \backend\modules\v1\models\MachineRecord();
        $result  = $model->judgeIsNotGenerateCode($memberId,$companyName);
        if($result===true){
            return ['code' =>1, 'status' => 'success','message' =>"存在开卡时间在12小时内的卡","data"=>$result];
        }
        return ['code' => 0, 'status' => 'error','message' =>"不存在开卡时间在12小时内的卡","data"=>$result];
   }
    /**
     * @api {post}        /v1/api-member/save-member-message  是否可以保存会员信息
     * @apiVersion      1.0.0
     * @apiName         是否可以保存会员信息
     * @apiGroup        scanCode
     * @apiPermission  管理员
     * @apiParam   {int}    id     会员id      会员id
     * @apiParam  {int}     sex    会员性别 	性别1:男2：女
     * @apiParam  {string}  name   会员姓名
     * @apiParam  {string}  idCard 身份证号
     * @apiParamExample {json} 请求参数
     * post    v1/api-member/save-member-message
     *   {
     *      "id"     : 2    // 会员id
     *      "sex"   :  1    //性别1:男2：女
     *      "name"  :  王金林 // 会员姓名
     *      "idCard":  4107821993202125   // 身份证号
     *   }
     * @apiDescription    是否可以保存会员信息
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/11/27
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/save-member-message
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,
     *  "status": "success",        //成功标识
     *  "message":"数据录入成功"    //数据录入成功
     *  "data": true                // 录入成功的信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,
     *  "status": "error",      //失败标识
     *  "message":"录入失败信息"     //失败返回信息
     *  "data": false 或者 true
     * }
     */
   public function actionSaveMemberMessage(){
       $param       = \Yii::$app->request->post();
       $model       = new Member();
       $saveResult  = $model->saveMemberMessage($param);
       if($saveResult===true){
           return ['code' =>1, 'status' => 'success','message' =>"信息保存成功","data"=>$saveResult];
       }else{
           return ['code' =>0, 'status' => 'error','message' =>"信息保存失败,请重新尝试","data"=>$saveResult];
       }
   }
    /**
     * @api {get}        /v1/api-member/send  app版本是否更新
     * @apiVersion      1.0.0
     * @apiName          app版本是否更新
     * @apiGroup        version
     * @apiPermission  管理员
     * @apiParam   {string}   versionCode     版本号
     * @apiParam   {string}  requestType     请求类型  安卓:android  苹果 ios
     * @apiParam   {string}   venueName       WAYD  代表我爱运动  其它 迈步
     * @apiParamExample {json} 请求参数
     * get    /v1/api-member/send
     *   {
     *      "versionCode"     : 2           // 版本号
     *      "requestType"     :  android    //性别  android：代表安卓  ios 代表ios
     *      "venueName"       : WAYD        // 场馆名称  WAYD：我爱运动  MB:迈步
     *   }
     * @apiDescription    app版本是否更新
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/11/30
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/send
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *"code": 1,
     *"status": "success",
     *"message": "app必须更新",
     *"url": "/opt/lampp/htdocs/cloudsports/backend\\web\\ios\\I Love Sportsandroid.apk",
     *"update": true,
     *"isMustUpdate": true
     * "file":true  // true文件存在  false:文件不存在
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,
     *  "status": "error",      //失败标识
     *  "message":"录入失败信息"     //失败返回信息
     *  "data": false 或者 true
     * }
     */
    public function actionSend($versionCode = 18,$requestType="android",$venueName="WAYD")
    {
        $textName     = "";
        $apkName      = ($venueName=="WAYD")?'ILoveSports':'maibu';
        $versionCode  = !empty($versionCode)?$versionCode:0;
        $bathPath = \Yii::$app->basePath;
        //查找配置(判断是否必须更新)
        $config = new Config();
        $update = $config->gainAppConfig($requestType,$venueName);
        $isMustUpdate = ($update==1)?true:false;
        $message      = ($isMustUpdate===true)?"app必须更新":"app可以更新";
        $nextDir  = ($requestType=="android")?'/web/android/':'/web/ios/';
        $nextDir1 = ($requestType=="android")?'/android/':'/ios/';
        $path     =  $bathPath.$nextDir;  // 文件目录
        $versionCode  = "/".$apkName.$versionCode.".apk";  // 文件名
        $endPath  = $path.$versionCode;
        // 查找指定目录下是否有 指定的文件
        if(file_exists($endPath)){   // 文件存在 不用更新
            return ['code'=>1,'status'=>'success','message'=>'app已经是最新文件','url'=>$endPath,"update"=>false,"file"=>true];
        }
        // 获取请求域名
        $donationName = \Yii::$app->request->hostInfo;
        if(is_dir($path)&&(file_exists($path))){           // 是目录文件
            $file = scandir($path);
            foreach ($file as $keys=>$values){
                if($values=="."||$values==".."){
                    continue;
                }
                if(preg_match('/^'.$apkName.'*/',$values)){
                    $textName = $values;
                    break;
                }
            }
        }
        $checkResult  = empty($textName)?false:true;
        $endPath = $donationName.$nextDir1.$textName;
        return ['code'=>1,'status'=>'success','message'=>$message,'url'=>$endPath,"update"=>true,"isMustUpdate"=>$isMustUpdate,"file"=>$checkResult];
    }
    /**
     * @api {post}        /v1/api-member/update-config  app更新设置
     * @apiVersion      1.0.0
     * @apiName         App更新设置
     * @apiGroup        AppConfig
     * @apiPermission  管理员
     * @apiParam   {string}  appType         app类型      android 或则 ios
     * @apiParam  {string}   venue          场馆名称 	   WAYD 代表我爱运动  maibu 代表迈步
     * @apiParam  {string}   isMustUpdate   是否必须更新  1代表必须更新 2代表不必须更新
     * @apiParamExample {json} 请求参数
     * post           /v1/api-member/update-config  app更新设置
     *   {
     *      "appType" : android    // android 或则 ios（代表app类型）
     *      "venue"   :  WAYD     //WAYD 代表我爱运动  maibu 代表迈步
     *      "isMustUpdate" :  1  // 是否必须更新 1代表必须更新 2代表 不必须更新
     *   }
     * @apiDescription    app更新设置
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/12/5
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/update-config
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,
     *  "status": "success",        //成功标识
     *  "message":"数据录入成功"    //数据录入成功
     *  "data": true                // 录入成功的信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,
     *  "status": "error",      //失败标识
     *  "message":"录入失败信息"     //失败返回信息
     *  "data": false 或者 true
     * }
     */
    public function actionUpdateConfig(){
           $param       = \Yii::$app->request->post();
           $model        = new Member();
           $endResult    = $model->appConfig($param["appType"],$param["venue"],$param["isMustUpdate"]);
           if($endResult["status"]===true){
               return ['code'=>1,'status'=>'success','message'=>"设置成功","data"=>["appType"=>$endResult["data"]->key,"isMustUpdate"=>$endResult["data"]->value,"venue"=>$endResult["data"]->type]];
           }
           return   ['code'=>0,'status'=>'error','message'=>"设置失败","isMustUpdate"=>"暂无设置","data"=>["error"=>$endResult["data"]]];
    }


    /**
     * @api {get}        /v1/api-member/is-update  app是否更新
     * @apiVersion      1.0.0
     * @apiName         App是否更新
     * @apiGroup        App
     * @apiPermission  管理员
     * @apiParam   {string}  appType         app类型      android 或则 ios
     * @apiParam  {string}   venue          场馆名称 	   WAYD 代表我爱运动  maibu 代表迈步
     * @apiParam  {string}   versionCode    版本号
     * @apiParamExample {json} 请求参数
     * get   /v1/api-member/is-update app更新设置
     *   {
     *      "appType" : android    // android 或则 ios（代表app类型）
     *      "venue"   :  WAYD     //WAYD 代表我爱运动  maibu 代表迈步
     *      "versionCode":  1.14.10  // 版本号
     *   }
     * @apiDescription    app是否更新
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/12/5
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/is-update
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *"code": 1,     // 参数传入是否合法
     *"message": "请求正常",
     *"update": 2,         // 1是必须更新 2是不必须更新
     *"hasVersion": false  //是否有新版本
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *"code": 0,     // 参数传入是否合法
     *"message": "请求正常",
     *"update": 1,         // 1是必须更新 2是不必须更新
     *"hasVersion": true  //是否有新版本
     * }
     */
    public function actionIsUpdate($appType="",$venue="",$versionCode=null){
        if(($appType!="android"&&$appType!="ios")||($venue!="maibu"&&$venue!="WAYD")||empty($versionCode)){
            return ['code'=>0,'message'=>"参数不合法","update"=>2,"hasVersion"=>false];
        }

        if ($appType =="android"){
            if ($venue=="WAYD"){
                $version = '2.0.0';
            }else{
                $version = '2.0.0';
            }
        }else{
            if ($venue=="WAYD"){
                $version = '2.0.0';
            }else{
                $version = '2.0.0';
            }
        }
        if($version==$versionCode){
            return ['code'=>1,'message'=>"请求正常","update"=>1,"hasVersion"=>false];
        }elseif($versionCode < $version){
            return ['code'=>1,'message'=>"请求正常","update"=>1,"hasVersion"=>true];
        }else{
            return ['code'=>1,'message'=>"请求正常","update"=>1,"hasVersion"=>false];
        }
    }

    /**
     * @api {post}        /v1/api-member/user-statics  用户活跃统计
     * @apiVersion      1.0.0
     * @apiName         用户活跃统计
     * @apiGroup        userStatics
     * @apiPermission  管理员
     * @apiParam   {string}  deviceNumber   设备编号
     * @apiParam  {int}     theRequestPage  求页面类型 0:下载安装 1:团课列表 2:私教列表 3:团课详情 4:私课详情
     * @apiParam  {int}     deviceType     设备类型
     * @apiParam  {int}     venueId        场馆id
     * @apiParam  {string}  companySign    公司标识    mb：迈步  wayd：我爱运动
     * @apiParam  {int}     memberId        会员id
     * @apiParamExample {json} 请求参数
     * post   /v1/api-member/user-statics   用户活跃统计
     *   {
     *      "deviceNumber"  :    62837t128         设备编号
     *      "theRequestPage"   : 1                 求页面类型 0:下载安装 1:团课列表 2:私教列表 3:团课详情 4:私课详情
     *      "deviceType"    : 1                 1:安卓 2:ios
     *      "venueId"      :  1                 场馆id
     *      "companySign"  : mb                 公司标识
     *      "memberId": 2235                     会员id
     *   }
     * @apiDescription    用户活跃统计
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>侯凯新@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/01/03
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/user-statics
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     *{
     *    "code": 1,                 //参数传入是否合法
     *    "status": "success",       //成功状态
     *    "message": "成功",         //成功状态
     *   "data": [
     *       {
     *       }
     *   ]
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     *{
     *"code": 0,                             //参数传入是否合法
     *"status": "error",                     //错误状态
     *"message": "您还不是会员，请注册！",
     *}
     */
    public function actionUserStatics()
    {
        $post = \Yii::$app->request->post();
        $model = new  UserActivityStatistics();
        $endResult = $model->insertActiveUser($post);
        if ($endResult === true) {
            return ['code' => 1, 'status' => 'success', 'message' => "请求成功", "data" => $endResult];
        }
        return ['code' => 1, 'status' => 'error', 'message' => "请求失败", "data" => $endResult];
    }

//--------------------------------------------------------会员请假--------------------------------------------------
    /**
     * @api {get}        /v1/api-member/gain-card-limit   获取会员卡的请假规则获取
     * @apiVersion      1.0.0
     * @apiName         会员卡请假
     * @apiGroup        memberLeave
     * @apiPermission  管理员
     * @apiParam  {int}     cardId      会员卡id
     * @apiParamExample {json} 请求参数
     * get   /v1/api-member/gain-card-limit  会员卡请假
     *   {
     *      "cardId"     :  71609    会员卡id
     *   }
     * @apiDescription    获取会员卡的请假规则
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/01/06
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/gain-card-limit
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     *         按照请假总次数限制(第一种情况)
     *{
     *    "code": 1,                 //参数传入是否合法
     *    "status": "success",       //成功状态
     *    "message": "成功",         //成功状态
     *   "data": [
     *       {
     *      leave_total_days => 20      // 请假总天数
     *      leave_least_days => 3      // 每次最低请假天数
     *      leave_days_times =>
     *      invalid_time => 1519971568   // 卡失效时间
     *      attributes => 1              // 1个人,2家庭,3公司
     *      leave_long_limit =>
     *       }
     *   ]
     *               按照请假类型限制(第二种情况)
     *{
     *    "code": 1,                 //参数传入是否合法
     *    "status": "success",       //成功状态
     *    "message": "成功",         //成功状态
     *   "data": [
     *       {
     * [leave_total_days] =>
     * [leave_least_days] =>
     * [leave_days_times] => Array
     * (
     * [0] => Array
     * (
     * [0] => 4      //请假次数:4次
     * [1] => 4      // 每次请假天数:4天
     * )
     * [1] => Array
     * (
     * [0] => 5   // 请假次数 5次
     * [1] => 5  // 每次请假 5天
     *  )
     * [2] => Array
     * (
     * [0] => 6
     * [1] => 6
     * )
     * )
     * [invalid_time] => 1565763419
     * [attributes] => 1
     *
     *       }
     *   ]
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     *{
     *"code": 0,                             //参数传入是否合法
     *"status": "error",                     //错误状态
     *"message": "您还没有请假天数！",
     * "data":""
     *}
     */
    public function actionGainCardLimit($cardId=""){
           $model = new MemberLeave();
           $data  = $model->gainLeaveLimit($cardId);
           if(!empty($data)){
               return ['code' =>1, 'status' => 'success','message' =>"成功","data"=>$data];
           }
           return ['code' =>1, 'status' => 'success','message' =>"您的请假天数不够了","data"=>[]];
    }


    /**
     * @api {post} /v1/api-member/submit-leave 会员请假表单验证
     * @apiVersion 1.0.0
     * @apiName   会员请假表单验证
     * @apiGroup  memberLeave
     * @apiParam   {int}   leaveArrayIndex 按照请假类型请求时选择哪一个 将对应数组下标传过来
     * @apiParam   {int}   leaveType       传1代表正常请假 传2代表特殊请假
     * @apiParam  {int}    leavePersonId   请假人id（会员ID）
     * @apiParam  {string} leaveReason     请假原因（原因）
     * @apiParam  {string} leaveStartTime  请假开始时间 （2017-03-06）
     * @apiParam  {string} leaveEndTime    请假结束时间 (2017-03-10）
     * @apiParam  {int}   leaveTotalDays   *请假离开总天数
     * @apiParam  {int}   leaveLimitStatus *请假限制识别（1代表按照总次数限制 2代表按照请假类型限制）
     * @apiParam  {int}   memberCardId      会员卡ID
     * @apiParam   {string}  requestSource  请求来源   默认值 app
     * @apiParamExample {json} Request Example
     *   POST /check-card/leave-record
     *   {
     *        “leaveArrayIndex”:0           //请假类型的数组 下标(只有按照请假各种类别限制的时候 才会传)
     *        "leaveType"   : 1               // 请假类型  1是正常请假 2是特殊请假
     *       "leavePersonId": 2,             // 请假会员ID
     *       "leaveReason": "不请了",        // 请假原因
     *       "leaveStartTime": 2017-06-06,   //请假开始时间
     *       "leaveEndTime":2017-08-08,      // 请假结束时间
     *       "leaveTotalDays" :30,           // 请假的总天数
     *       "leaveLimitStatus" :1,          // 请假限制状态  1 代表 请假总天数限制  2 代表按照各种请假次数遍历
     *       "memberCardId" :3,              // 会员卡id
     *        “requestSource”：app        // 请求来源app
     *   }
     * @apiDescription    会员请假表单验证
     * <span><strong>作    者：</strong></span>侯凯新<br>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br>
     * <span><strong>创建时间：</strong></span>2017/6/27
     * @apiSampleRequest  http://qa.uniwlan.com/v1/api-member/submit-leave
     * @apiSuccess (返回值) {string} status 请假保存状态
     * @apiSuccess (返回值) {string} data   返回请假状态数据
     * @apiSuccessExample {json} 成功示例:
     * {'status':'success','status'=>'success','data':请假保存数据状态}
     * @apiErrorExample {json} 错误示例:
     * {'status':'error','status'=>'error','data':请假保存数据状态}
     */
    public function actionSubmitLeave(){
        $post = \Yii::$app->request->post();
        $post["leaveStartTime"] = !empty($post["leaveStartTime"])?strtotime($post["leaveStartTime"]):null;
        $post["leaveEndTime"]   = !empty($post["leaveEndTime"])?strtotime($post["leaveEndTime"]." 23:59:59"):null;
        $model = new LeaveRecordForm();
        if($model->load($post,'') && $model->validate()){
            $save = $model->leaveRecord($post["requestSource"]);
            if($save === true){
                return ['code' =>1, 'status' => 'success','message' =>"请假成功","data"=>$save];
            }else{
                return ['code' =>0, 'status' => 'error','message' =>$save,"data"=>$save];
            }
        }else{
            return ['code' =>0, 'status' => 'error','message' =>$model->errors,"data"=>$model->errors];
        }
    }
//--------------------------------------------------------会员更柜管理--------------------------------------------------
    /**
     * @api {get} /v1/api-member/get-cabinet-type  获取柜子类型各个类型
     * @apiVersion 1.0.0
     * @apiName   获取柜子类型各个 参数类型
     * @apiGroup cabinet
     * @apiPermission 管理员
     * @apiParam {int} venueId  场馆id
     * @apiParamExample {json} Request Example
     * GET  /v1/api-member/get-cabinet-type
     * {
     *      venueId :52,   //场馆id
     *
     * }
     * @apiDescription  获取柜子类型的各个参数 1 柜子名称 2 柜子总数量 3 已租柜子数量
     * <br/>
     * <span><strong>作    者：</strong></span>侯凯新<br>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br>
     * <span><strong>创建时间：</strong></span>2017/6/4
     *
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/get-cabinet-type
     * @apiSuccess (返回值) data  返回数据
     * * @apiSuccessExample {json} 成功示例:
     *{
     *      "id":1,             //柜子类型id
     *      "type_name":"女大柜"， //柜子类型名称
     *      'cabinetNum':13，    //柜子总数量
     *      'is_rent':12，      //柜子被租数量
     * };
     */
    public function actionGetCabinetType($venueId=""){
        $model = new Cabinet();
        $data  = $model->getAllCabinetData($venueId,"venue");
        $data  =  $model->dealCabinet($data);
        if(empty($data)){
            return ['code' =>0, 'status' => 'error','message' =>"还没有可用的柜子呢","data"=>$data];
        }
        return ['code' =>1, 'status' => 'success','message' =>"请求成功","data"=>$data];
    }

    /**
     * @api {get} /v1/api-member/home-data  柜子（对应柜子类型下面的）
     * @apiVersion 1.0.0
     * @apiName   指定各个柜子的租用状态数据分页显示
     * @apiGroup     cabinet
     * @apiPermission 管理员
     * @apiParam {int} typeId         柜子类型id
     * @apiParam {int}  nowBelongId   场馆id
     * @apiParam  {int} page          第几页 如果是 第一页 可以不传  第二页 传2
     * @apiParamExample {json} Request Example
     * GET   /v1/api-member/home-data
     * {
     *       typeId   :52,             //柜子类型id
     *       nowBelongId：55,         // 场馆id
     *       page：2                  // 请求页码
     * }
     * @apiDescription 指定各个柜子的租用状态数据分页显示
     * <br/>
     * <span><strong>作    者：</strong></span>侯凯新<br>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsports.club
     * <span><strong>创建时间：</strong></span>2017/6/4
     *
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/home-data
     * @apiSuccess (返回值) data  返回数据
     * * @apiSuccessExample {json} 成功示例:
     *{
     *   'id' => string '1' ,                    //柜子id
     *   'cabinet_type_id' => string '1' ,       //柜子类型id
     *  'cabinet_number' => string '大大上海瑜伽健身馆-0001'  //柜子编号
     *   'status' => string '2'                 // 柜子租用状态 （1未租 2 已租 3 维修状态）
     *  'type_name' => string '萨嘎'            // 柜子类型名字
     *  'venueId' => string '47' (length=2)    // 柜子场馆id
     *  'cabinetModel' => string '2'           //柜子规格 1:大柜2:中柜3:小柜
     *  'cabinetType' => string '2'           //柜子类型  1:临时2:正式
     *   "old_give_month"=>1                      //赠送月数(老规则)
     *   "monthRentPrice"=>"50"               // 月租金
     *   "yearRentPrice"=>"100"               // 年租金
    *   "give_month"{
    *      "month": 6,      // 购买6个月
    *      "give": 1,      // 赠送一个月
    *     "money": "600"   // 金额 600
    *}
    *],
     * };
     */
    public function actionHomeData(){
        $name                  = \Yii::$app->request->queryParams;
        $name['nowBelongType'] = "venue";
        $model                 = new Cabinet();
        $cabinetDataObj      = $model->getData($name,"app");
        $endData             = Member::mobileCabinetDeal($cabinetDataObj->models);
        return  ["code"=>1,"status"=>"success","data"=>$endData,"totalPage"=>$model->totalPage];
    }
    /**
     * @api {get} /v1/api-member/calculate-date  计算柜子到期日期
     * @apiVersion 1.0.0
     * @apiName       计算柜子到期日期
     * @apiGroup     cabinet
     * @apiPermission 管理员
     * @apiParam {string} startRent      租柜开始日期
     * @apiParam {int}   numberMonth    租柜月数
     * @apiParamExample {json} Request Example
     * GET   /v1/api-member/home-data
     * {
     *       startRent :2016-12-08,     //租柜开始日期
     *       numberMonth：3,            // 租用月数
     * }
     * @apiDescription   根据前端输入值计算柜子到期日期
     * <br/>
     * <span><strong>作    者：</strong></span>侯凯新<br>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsports.club
     * <span><strong>创建时间：</strong></span>2017/01/08
     *
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/calculate-date
     * @apiSuccess (返回值) data  返回数据
     * * @apiSuccessExample {json} 成功示例:
     *{
     *   'id' => string '1' ,                    //柜子id
     * };
     */
    public function actionCalculateDate($numberMonth="",$startRent=""){
        $model = new CabinetRuleForm();
        $data  = $model->bindMemberDateCalculate($numberMonth,$startRent);
        return  ["code"=>1,"status"=>"success","data"=>$data];
    }
    /**
     * @api {get}        /v1/api-member/member-leave-record
     * @apiVersion      1.0.0
     * @apiName         会员请假记录
     * @apiGroup        MemberLeaveRecord
     * @apiPermission  管理员
     * @apiParam  {int}   accountId  账户id
     * @apiParamExample {json} 请求参数
     * get    /v1/api-member/member-leave-record  用户活跃统计
     *   {
     * [member_card_id] => 20278          //  会员卡id
     * [leaveProperty] =>1                // 1代表未审核 2代表已经审核 3:已拒绝 4：（针对特殊请假 正常请假直接是2）
     * [leaveStatus] => 2                  // status 1代表假期中 2代表 已销假 3已过期
     * [leave_start_time] => 1501084800    // 请假开始时间
     * [leave_end_time] => 1503849599     // 请假结束时间
     * [leaveType] => 1            //  1表示正常 请假   2代表特殊请假
     * [create_at] => 1501854942   // 请假创建时间
     * [note] =>                     // 请假原因
     * [card_name] => *WDSXK0018*13个月卡 // 会员卡名称
     * [hour] => 9:25:25             // 几点请的假
     * [date] => 2017-08-04   // 请假日期
     * [leaveDays]=>30       // 请假天数
     * @apiDescription    用户活跃统计
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>侯凯新@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/01/03
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/member-leave-record
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     *{
     *    "code": 1,                 //参数传入是否合法
     *    "status": "success",       //成功状态
     *    "message": "成功",         //成功状态
     *   "data": [
     *       {
     *       }
     *   ]
     * }
     */
    public function actionMemberLeaveRecord($accountId=""){
        $model = new Member();
        $endResult = $model->memberLeaveRecord($accountId);
        if(empty($endResult)){
            return ['code' =>0,'message' =>"暂无请假记录","data"=>$endResult];
        }
        return ['code' =>1,'message' =>"请求成功","data"=>$endResult];
    }


    public function actionVenueNumStatics()
    {
           $get      = \Yii::$app->request->get();
           $model    = new UserActivityStatistics();
           $endData  = $model->combineData($get);
           return json_encode(["data"=>$endData]);
    }
    public function actionTableNumStatics()
    {
        $get      = \Yii::$app->request->get();
        $model    = new UserActivityStatistics();
        $endData  = $model->tableData($get);
        return json_encode(["data"=>$endData]);
    }

    /**
     * @api {get}        /v1/api-member/verify-login-status
     * @apiVersion      1.0.0
     * @apiName         验证手机登陆状态
     * @apiGroup        Member
     * @apiPermission  管理员
     * @apiParam     mobile  会员手机号
     * @apiParam     deviceNumber  会员手机设备识别码
     * @apiParamExample {json} 请求参数
     * get    /v1/api-member/verify-login-status  验证手机登陆状态
     *  code = 1 正常 0让用户退出
     * @apiDescription    用户活跃统计
     * <span><strong>作    者：</strong></span>王亮亮<br/>
     * <span><strong>邮    箱：</strong></span>wangliangliang@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/01/03
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/verify-login-status
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
    {
    "code": 0
    }
     */
    public function actionVerifyLoginStatus($mobile,$deviceNumber){
        $type      = \Yii::$app->request->get('type',0);
        if (!$type){
            $type= 1;
        }else{
           $type =$type;
        }
        $a = MemberAccount::find()->select('deviceNumber,count')->where(['mobile'=>$mobile,'company_id'=>$type])->asArray()->all();
        if (empty($a)) return ['code'=>1];
    if ($a[0]['deviceNumber'] == '' && $a[0]['count'] == 0){
        return ['code'=>1];
    }
    if ($deviceNumber !== $a[0]['deviceNumber']){
        return ['code'=>0];
    }
    if( $a[0]['deviceNumber'] == null){
        return ['code'=>1];
    }
    if ($a[0]['count'] >= 4){
        return ['code'=>0];
    }
        return ['code'=>1];
    }
    /**
     * @api {get}        /v1/api-member/automatic-logon
     * @apiVersion      1.0.0
     * @apiName         自动登录
     * @apiGroup        Member
     * @apiPermission  管理员
     * @apiParam     id   公司
     * @apiParam     deviceNumber  会员手机设备识别码
     * @apiParamExample {json} 请求参数
     * get    /v1/api-member/automatic-login  自动登录
     *  code = 1 正常 0让用户退出
     * @apiDescription    用户活跃统计
     * <span><strong>作    者：</strong></span>王亮亮<br/>
     * <span><strong>邮    箱：</strong></span>wangliangliang@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/01/03
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member/verify-login-status
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
    {
    "code": 0
    }
     */
    public  function actionAutomaticLogon(){
        $deviceNumber = \Yii::$app->request->get('deviceNumber',0);
        $companyid = \Yii::$app->request->get('companyid',0);
        if (empty($deviceNumber))   return ['code' =>0,'message' =>"初始化异常！"];
        if (empty($companyid))   return ['code' =>0,'message' =>"初始化异常！"];
        $count = MemberAccount::find()->where(['deviceNumber'=>$deviceNumber,'company_id'=>$companyid])->count();
        if ($count ==1){
            $deviceNumbers = MemberAccount::findOne(['deviceNumber'=>$deviceNumber,'company_id'=>$companyid]);
            if (empty($deviceNumbers)) return ['code' =>0,'message' =>"初始化异常！"];
            return ['code'=>1,'data'=>$deviceNumbers->mobile];
        }else{
            return ['code' =>0,'message' =>"初始化异常！"];
        }

    }
    /**
     * @api {get} /v1/api-member/tencent-card   移动端-扫码进馆(预约私课)-自动选卡
     * @apiVersion  1.6.0
     * @apiName        移动端-扫码进馆(预约私课)-自动选卡
     * @apiGroup       member
     * @apiPermission 管理员
     * @apiParam  {int}          mobile         会员手机号
     * @apiParam  {int}          companyId     公司id   companyId 公司ID(迈步传我爱运动不用传)
     * @apiParam  {int}          venueId      场馆id
     * @apiParam  {int}          type       类型   和请假相关的时候会用
     * @apiParam  {int}          vid       //1.code 进出门禁     2.about  预约私课等
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member/tencent-card
     *   {
     *      "mobile  " :'15890168856'          //手机号
     *      'venueId'  : 2423341
     *      'vid'     : 'code' | 'about'       //  1.code 进出门禁     2.about  预约私课等
     *   }
     * @apiDescription   移动端-扫码进馆(预约私课)-自动选卡
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>辛威@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/3/30
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/api-member/tencent-card
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     *  vid = code
     *{
     *  "code": 1,
     *  "status": "success",
     *  "message": "请求成功"
     * 'data' : {id: 1233, cardNumber : 12134t5636}
     *}
     *
     * vid = about
     * {
     *  "code": 1,
     *  "status": "success",
     *  "message": "请求成功"
     * 'data' : [{id: 1233, cardNumber : 12134t5636}]
     *}
     * @apiSuccessExample {json}返回值详情(失败时)
     * vid =code  & vid = about
     *{
     *  "code": 0,
     *  "status": "error",
     *  "message": "没有数据"
     *   'data' : []
     *}
     */
    public function actionTencentCard()
    {
        $type   = \Yii::$app->request->get('type','0');
        $mobile   = \Yii::$app->request->get('mobile');
        $companyId   = \Yii::$app->request->get('companyId','1');
        $venueId  = \Yii::$app->request->get('venueId');
        $vid      = \Yii::$app->request->get('vid');
        $model = new MemberCourseOrder();
        $data = $model->getEntryVenueMemberCard($mobile,$companyId,$venueId, $vid,$type);
        if ($data) {
            $scenario = $type == 'charge' ? 'charge':'group';
            $about    = new AboutRecordForm([],$scenario);
            $memberCardId = 1;
            if($vid == 'code'){
                $memberCardId = $data['id'];
            }else{
                foreach ($data as $k => $v) {
                    $memberCardId = $v['id'];
                }
            }
            $totalNum = $about->getVenueLimitTimes($memberCardId,$venueId);             //查询馆次数核算表（获取总次数）
            $memberId = $model->getMemberId($mobile,$companyId,$venueId);
            $num      = $about->getEntryRecord($memberCardId,$memberId,$venueId);                 //统计会员卡进场次数
            $cardTime = $about->getCardTime($memberCardId,$venueId);
            if ($cardTime === false) {
                if ($vid == 'code') {
                    return ['code' => 0,'status' => 'error','message' => '您的会员卡在当前时间段无法进馆,如有疑问请咨询工作人员!'];
                } else {
                    return ['code' => 0,'status' => 'error','message' => '您的会员卡在当前时间段不能使用!'];
                }
            }
            if ($vid == 'code') {
                if ($num >= $totalNum  && $totalNum != -1) {
                    return ['code' => 0,'status' => 'error','message' => '会员卡本月的使用次数已用完!'];
                }
            }
            if (isset($data['is_leave']) && ($data['is_leave'] == 1)) {
                return ['code' => 0, 'status' => 'error', 'message' => '您的会员卡处于请假中，请您先销假后再约课!','data' => NULL];
            } elseif(isset($data['cardType']) && ($data['cardType'] == 2)) {
                return ['code' => 0, 'status' => 'error', 'message' => '对不起，次卡不能扫码进馆!','data' => NULL];
            } else {
                return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data];
            }
        } else {
            return ['code' => 0, 'status' => 'error', 'message' => '没有可用会员卡','data' => NULL];
        }
    }
    /**{get}
     * 判断会员是否有卡
     * accountId 账户id
     * v1/api-member/is-member-card
     */
    public function actionIsMemberCard()
    {
        $model = new Member();
        $accountId  = \Yii::$app->request->get('accountId',0);
        if (!empty($accountId)) {
            $data = $model->getMemberCard($accountId);
            if ($data) {
                return ['code' => 1,'message' => '会员卡已存在','data' => $data];
            } else{
                return ['code' => 0,'message' => '会员卡不存在，请绑定会员卡','data' => $data];
            }
        }
    }
    /**{post}
     * 绑定会员卡
     * cardNumber 会员提供的卡号
     * mobile     手机号
     * memberId   登录时的会员id
     * accountId  登录时的账户id
     * venueId    场馆id
     * document_type 证件类型
     * id_card      证件号
     * source   来源 1-手机APP-android; 2-手机APP-ios; 3-小程序
     * v1/api-member/set-bind-member-card
     */
    public function actionSetBindMemberCard()
    {
        $post  = \Yii::$app->request->post();
        $model = new BindMemberCardInfoForm();
        if ($model->load($post,'')) {
            $data  = $model->updateMemberCard();
            return $data;
        }
    }


    /**
     * {get}
     * 扫码跑步机
     *memberId 会员id
     * randomStr 随机字符串
     */
    public function actionGetTreadmill()
    {
        $param  = \Yii::$app->request->get();
        if (empty($param['memberId'])) return ['code' => 1,'message' => '参数错误','data' =>'参数错误'];
//        $model = new TreadmailMemberInfo();//实例化跑步机
        $model = TreadmailMemberInfo::findOne(['random_string'=>$param['randomStr']]);
        $model->member_id = $param['memberId'];
        if ($model->save() > 0){
            return ['code' => 1,'message' => '扫码成功','data' =>'扫码成功'];
        }else{
            return ['code' => 0,'message' => '扫码失败','data' =>'扫码失败'];
        }
    }

    /**
     * 获取个人跑步历史
     * {get}
     * memberId 会员id
     * venueId 场馆id
     * memberCardId 会员卡id
     *type 不传参数默认是跑步机(1 2)
     * $pageSize 一页多少条
     * page 第几页
     * all为yes的时候表示全部
     */
    public function actionGetHistoryRecordList(){
        $param = \Yii::$app->request->get();
        if (empty($param['memberId']) || empty($param['venueId'])) return ['code'=>0,'message'=>'参数错误','data'=>'参数错误'];
        //根据member_id 获取account_id
        $account_id = Member::find()->where(['id'=>$param['memberId']])->select('member_account_id')->asArray()->one();
        //根据account_id反查所有的member_ids
        $ids = Member::find()->where(['member_account_id'=>$account_id['member_account_id']])->asArray()->select('id')->all();
        $member_ids = array_column($ids,'id');
        $type = !empty($param['type']) ? $param['type'] : 1;
        $model = new MotionRecord();
        $result = $model::find()->where(['status'=>0,'type'=>$type,'member_id'=>$member_ids,'venue_id'=>$param['venueId']])->orderBy('create_at desc');
        $countQuery = clone $result;
        $pageSize = !empty($param['pageSize']) ? $param['pageSize'] : 20;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>$pageSize]);
        $models = $result->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        $data = [];
        if (!empty($models)){
            foreach ($models as $v){
                $data['item'][] = [
                    'id'=>$v['id'],
                    'date'=>date('Y-m-d',$v['create_at']),
                    'calories'=>$v['calories'],
                    'distict'=>sprintf("%.2f",$v['distict']/100),
                ];
            }

            $data['totalPage'] = $countQuery->count()/$pageSize < 1 ? 1 : ceil($countQuery->count()/$pageSize);
        }else{
//            $data['item'] = [];
            $data = null;
        }

        return ['code' => 1,'message' => '请求成功','data' =>$data];

    }

    /**
     * 获取历史详情
     * id 详情id
     * date 时间  2018-04-02
     */
    public function actionGetHistoryInfo(){
        $param = \Yii::$app->request->get();
        //初始化参数
        $start_date  = null;
        $end_date = null;
        $condition['id'] = null;
        if (!empty($param['id'])){//按照id查询历史详情
            $condition['id'] = $param['id'];
        }

        if (!empty($param['memberId'])){
            //根据member_id 获取account_id
            $account_id = Member::find()->where(['id'=>$param['memberId']])->select('member_account_id')->asArray()->one();
            //根据account_id反查所有的member_ids
            $ids = Member::find()->where(['member_account_id'=>$account_id['member_account_id']])->asArray()->select('id')->all();
            $condition['member_id'] = array_column($ids,'id');

        }

        if (!empty($param['venueId'])){
            $condition['venue_id'] = $param['venueId'];
        }

        $condition['type'] = !empty($param['type']) ? $param['type'] : 1;//默认为跑步机
        if (!empty($param['date'])){//按照日期时间
            $start_date =  strtotime($param['date'].' 00:00:00');
            $end_date = strtotime($param['date'].' 23:59:59');
        }
        if (empty($param['id']) && empty($param['date']) ) return ['code'=>0,'message'=>'参数错误','data'=>'参数错误'];
        $model = new MotionRecord();
        $result = $model::find()->filterWhere($condition)->andFilterWhere(['between','create_at',$start_date,$end_date])->orderBy('id desc')->asArray()->all();
        if (!empty($result)){//
            foreach ($result as $v){
                $data['item'][] = [
                    'distict'=>sprintf("%.2f",$v['distict']/100),
                    'calories'=>$v['calories'],
                    'speed'=>sprintf("%.2f",$v['speed']/100),
                    'time'=>$v['time'],
                    'rate'=>$v['rate'],
                    'slope'=>$v['slope'],
                    'date'=>date('Y-m-d',$v['create_at']),

                ];
            }
        }else{
            return ['code'=>1,'message'=>'暂无数据','data'=>null];
        }

         return ['code'=>1,'message'=>'请求成功','data'=>$data];

    }
}
