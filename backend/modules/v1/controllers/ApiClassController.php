<?php
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\GroupClass;
use yii\rest\ActiveController;
use yii\web\Response;

class ApiClassController extends ActiveController
{
    /**
     * 云运动 - Api - 课程调用的课程class、
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @var string
     */
    public $modelClass = 'backend\modules\v1\models\GroupClass';
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
     * @api {get} /v1/api-class/get   团课所有课程
     * @apiVersion  1.0.0
     * @apiName    团课所有课程
     * @apiGroup   groupClass
     * @apiPermission 管理员
     * @apiParam {string}      venueId         课程id
     * @apiParam {string}      date           课程日期
     * @apiParam {string}      course          课程id
     * @apiParam  {string}     requestType     请求类型（ios）
     * @apiParam  {string}     venueName]     场馆名称
     * @apiParam  {string}     version        请求版本app 请求类型不同  遍历数据不同
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-class/get
     *   {
     *        "venueId": 1,
     *        "date":"2017-03-06,
     *        "course": 3,
     *        "requestType":"ios",          //ios请求标识
     *   }
     * @apiDescription   不发送日期：默认搜索 近5天的课程数据 不发送课程id  不限制搜索指定的课程
     * <br/>
     * <span><strong>作   者：</strong></span>黄鹏举<br>
     * <span><strong>邮   箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/30
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-class/get
     * @apiSuccess (返回值) {string} data   返回课程数据
     * @apiSuccessExample {json}返回值详情(ios专属)
     * {
     * "code": 1,                            //请求成功表示
     * "status": "success",                  //请求成功状态0
     * "message": "请求成功",                //请求成功信息
     * "data": [
     * {
     *    "class_date": "2017-06-30",        // 课程上课日期
     *    "info": "今天",                    // 上课日期别名
     *    "list": [
     *    {
     *     "id": "8",                        // 团课课程id
     *     "start": "15:00",                 //上课开始时间
     *     "end": "16:58",                   //上课结束时间
     *     "class_date": "2017-06-30",       //上课日期
     *     "difficulty": "1",                //课程难度（1=>'初级',2=>'中级',3=>'高级'）
     *     "level": "初级"                   // 课程难度（已经处理过）
     *     "isTimeEndClass": true,           // 课程是否已经结束(true表示还未上课的课程（可以点击查看，预约），false表示课程已经上课)
     *     "courseName": "团b",               //课种名称(代码优化后的课种名称)
     *     "coach": "玫瑰",
     *     "coachPic": "http://oo0oj2qmr.bkt.clouddn.com/头像-拷贝@2x.png?eck3jtHKFXo=",  //教练头像
     *     "scoreImg": {
     *          "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=14EysW3S:-3U-1EZN17xhI=",
     *          "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=149OqC6lg63U-1EZN17xhI=",
     *          "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1mwMiysW3U-1EZN17xhI=",
     *          "four": "http://oo0oj2qmr.bkt.clouddn.com/xwUM2iX2wn_2F6lg63U-1EZN17xhI=",
     *          "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=_2Dj3vNdMcJ-DXSkxBnp4="
     *       },
     *    }
     *    ]
     *    }
     * ]
     * }
     *
     * {
     *   "code": 0,                     //失败标识
     *   "status": "error",           //失败状态
     *   "message": "场馆暂时没有排课"  //失败原因
     * }
     * @apiSuccessExample {json}返回值详情
     *data{
     *{
     *"class_date": "2017-06-16",      // 课程上课日期
     *"info": "今天",                  // 上课日期别名
     *"list": [
     *{
     *"id": "17",                      // 团课课程id
     *"start": "02:08",                // 上课开始时间
     *"end": "05:25",                  // 上课结束时间
     *"class_date": "2017-06-16",      // 上课日期
     *"difficulty": "1",               // 课程难度（1=>'初级',2=>'中级',3=>'高级'）
     *"level": "初级",                 // 课程难度（已经处理过）
     *"isTimeEndClass": false,         // 课程是否已经结束(true表示还未上课的课程（可以预约），false表示课程已经上课)
     *"courseName": "团b",             //课种名称（优化后处理的字段）
     *"name": {                        //优化后删除了(这个字段已删除，优化后课种名称是courseName)
     *      "name": "测试舞蹈",              //  课种名称
     *      "category": "舞蹈",              //  课程分类
     *      "courseDesrc": "存储"            //  课程介绍
     *},
     *"coach": "单车教练",             //课程教练
     *"coachPic": "img/touxiang.png",  // 教练头像
     *"scoreImg": {                    //  课程*星星* 级别
     *      "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *      "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *      "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *      "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *      "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
     *  }
     *}
     * ]
     * }}
     *
     *
     */
    public function actionGet()
    {
        $params    = \Yii::$app->request->queryParams;
        $group       = new GroupClass();
        if(isset($params['requestType']) && $params['requestType'] == 'ios')
        {
            unset($params['requestType']);
            $data        = $group->search($params);
            if(!empty($data->models))
            {
                return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data->models];
            }else{
                return ['code'=>0,'status'=>'error','message'=>'该课种暂时没有排课'];
            }
        }else{
            $data        = $group->search($params);
            return $data;
        }
    }
    /**
     * @api {get} /v1/api-class/get-detail   获取单条团课课程详情数据
     * @apiVersion  1.0.0
     * @apiName      获取单条团课课程详情数据
     * @apiGroup   groupClass
     * @apiPermission 管理员
     * @apiParam {int}        id               团课课程id
     * @apiParam {int}      memberId           会员id
     * @apiParam  {string}     requestType     请求类型（ios）
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-class/get-detail
     *   {
     *        "id":12,                      // 团课课程id
     *        "memberId": 3,                // 会员id
     *        "requestType":"ios",          //ios请求标识
     *   }
     * @apiDescription   获取单条团课课程详情数据
     * <br/>
     * <span><strong>作   者：</strong></span>侯凯新<br>
     * <span><strong>邮   箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-class/get-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情
    *{
    *"id": "18",          // 团课课程id
    *"start": "11:50",   // 上课开始时间
    *"end": "17:30",     // 上课结束时间
    *"class_date": "2017-06-16",  // 上课日期
    *"created_at": "1497489158",  // 课程创建时间
    *"status": "1",      // 课程状态 1正常2调课3旷课4请假
     *"course_id": "3",  // 课程id
    *"coach_id": "2",    // 教练id
    *"classroom_id": "1", // 教室id
    *"create_id": "2",    // 创建人id（员工id）
    *"difficulty": "1",    // 课程难度（1 中 2 底 3 高）
    *"desc": null,         // 团课课程介绍
    *"pic": null,          //团课课程图片
    *"class_limit_time": null,  //开课前多少分钟不能约课
    *"cancel_limit_time": null,  // 开课前多少分钟不能取消约课
     *"least_people": null,      //最少开课人数
    *"company_id": null,      //公司id
    *"venue_id": "2",          //场馆id
    *"limitTime": true,        // true可以预约 ,false表示快上课了
    *"isAboutClass": false,    //会员没有约课记录（true表示在这个课程的上课时间段会员已经预约过其他课程了，false表示没有预约过其他课程）
    *"isCanClass": false,       // 是否有绑定这节课程(true有，可以预约，false没有，不可以预约)
    *"name": "测试舞蹈",        //课程名称
    *"courseDesrc": "存储",     //课程介绍
    *"isDance": false,          // 是否是舞蹈课
    *"classScore": 4,           // 课程分数
    *"classScoreImg": {        // 课程  星星级别
    *"one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
    *"two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
    *"three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
    *"four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
    *"five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
    },
    *"coach": "单车教练",     // 教练
    *"coachAge": null,        //教练年龄
    *"coachPic": "img/touxiang.png", //教练头像
    *"workTime": null,         // 工作时间
    *"venue": "大上海",        // 工作场馆
    *"classroom": "12",        //教室最大容纳人数
    *"classroomName": "瑜伽室", //教室名称
    *"score": 4,               // 教练分数
    *"scoreImg": {             //教练星星
    *"one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
    *"two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
    *"three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
    *"four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
    *"five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
    *},                       //
    "address": "12"           // 场馆地址
    }
     */
    public function actionGetDetail($id,$memberId = '',$requestType = '')
    {
        $group = new GroupClass();
        if($requestType == 'ios')
        {
            $data = $group->getGroupClassDetail($id,$memberId);
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
        }else{
            return $group->getGroupClassDetail($id,$memberId);
        }
    }
    /**
     * @api {get} /v1/api-class/get-seat-detail  获取指定课程的座位详情
     * @apiVersion  1.0.0
     * @apiName     获取指定课程的座位详情
     * @apiGroup    groupClass
     * @apiPermission 管理员
     * @apiParam {int}        id               团课课程id
     * @apiParam {int}      memberId           会员id
     * @apiParam  {string}   requestType     请求类型（ios）
     * @apiParam  {int}      memberCardId    会员卡id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-class/get-seat-detail
     *   {
     *        "id":12,                      // 团课课程id
     *        "memberId": 3,                // 会员id
     *        "requestType":"ios",          //ios请求标识
     *   }
     * @apiDescription   获取指定课程的座位详情
     * <br/>
     * <span><strong>作   者：</strong></span>侯凯新<br>
     * <span><strong>邮   箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-class/get-seat-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情
     * "id": "18",         //团课排课id
     * "start": "11:50",   //上课开始时间
     * "end": "17:30",     //上课结束时间
     * "class_date": "2017-06-16",   //上课日期
     * "created_at": "1497489158",   //课程创建时间
     * "status": "1",           // 课程状态 1正常2调课3旷课4请假
     * "course_id": "3",        //课程id
     * "coach_id": "2",          //教练id
     * "classroom_id": "1",      //教室id
     * "create_id": "2",         //创建人id
     * "difficulty": "1",        // 课程难度（1 中 2 底 3 高）
     * "desc": null,              //课程介绍
     * "pic": null,              //课程图片
     * "class_limit_time": null, //开课前多少分钟不能约课
     * "cancel_limit_time": null,// 开课前多少分钟不能取消约课
     * "least_people": null,    //最少开课人数
     * "company_id": null,     // 公司id
     * "venue_id": "2",        //场馆id
     * "classroomName": "瑜伽室", //教室名称
     * "venue": "大上海",       //场馆名称
     * "venueAddress": "12",    //场馆地址
     * "isAboutClass": false,  // 是否约过此课程
     * "isCanClass": false,     //是否买过此课程
     * "name": "测试舞蹈",     //课程名称
     * "coach": "单车教练",    //教练名称
     * "coachPic": "img/touxiang.png", //教练头像
     * "classroom": "12",      //教室最大容纳人数
     *  "isCanOrder":true,     // 是否能够跨店预约
     * "seatDetail": [        //教室详情
     * {
     * "id": "1",            // 座位id
     * "classroom_id": "1",  //教室id
     * "seat_type": "2",    // 座位级别 1普通，2VIP，3贵族
     * "seat_number": "1",   // 座位编号
     * "is_anyone": 0       // 是否有人占用此位置 0 表示无人 1表示有人
     *  "authority":true    //  true表示有权限预约 false无权限预约
     * },
     * {
     * "id": "11",
     * "classroom_id": "1",
     * "seat_type": "1",
     * "seat_number": "11",
     * "is_anyone": 0
     *  "authority":true    //  true表示有权限预约 false无权限预约
     * },
     * {
     * "id": "12",
     * "classroom_id": "1",
     * "seat_type": "1",
     * "seat_number": "12",
     * "is_anyone": 0
     *  "authority":true    //  true表示有权限预约 false无权限预约
     *  }
     * ],
     * "score": 4,    // 教练分数
     * "scoreImg": {  //教练 头像
     * "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     * "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     * "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     * "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     * "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
     * }
     * }
     */
    public function actionGetSeatDetail($id,$seatType = 'true',$memberId = '',$requestType = '',$memberCardId = "")
    {
        $group = new GroupClass();
        if($requestType == 'ios')
        {
            $data = $group->getSeatDetail($id,$seatType,$memberId,$memberCardId);
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
        }else{
            return $group->getSeatDetail($id,$seatType,$memberId,$memberCardId);
        }
    }
    /**
     * @api {get} /v1/api-class/get-course-data   获取所有课种
     * @apiVersion  1.0.0
     * @apiName      获取所有团课课程数据
     * @apiGroup     groupClass
     * @apiPermission  管理员
     * @apiParam {int}          type            1 代表私课 2代表团课
     * @apiParam  {string}      requestType     请求类型（ios）
     * @apiParam  {int}         venueId           场馆id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-class/get-course-data
     *   {
     *        "type": 2,             // 课程类别   1 代表私课 2代表团课
     *        "requestType":"ios",   //ios请求标识
     *        "venueId": 2,          // 场馆id   
     *   }
     * @apiDescription   获取所有团课课程数据
     * <br/>
     * <span><strong>作   者：</strong></span>侯凯新<br>
     * <span><strong>邮   箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-class/get-course-data
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}
     *{
    *[
    *{
    *"id": "1",        //课程id
    *"name": "舞蹈"    //课程名称
    *"pic":""          //课种图片
    *},
    *{
    *"id": "2",        //课程id
    *"name": "单车2"   //课程名称
    *"pic":""          //课种图片
     *},
    *}
     */
    public function actionGetCourseData($type = 2,$requestType = '',$venueId = '',$companyId="")
    {
        $group = new GroupClass();
        if($requestType == 'ios')
        {
//            $data = $group->getCourseData($type,$venueId);
            $data = $group->getCourseTopData($type,$venueId,$companyId);
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
        }else{
            return $group->getCourseTopData($type,$venueId,$companyId);
        }
    }
}
