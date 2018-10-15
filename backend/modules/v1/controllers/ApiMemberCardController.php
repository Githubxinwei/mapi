<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/6 0006
 * Time: 上午 9:46
 */

namespace backend\modules\v1\controllers;
use backend\models\EntryRecord;
use backend\models\Member;
use backend\modules\v1\models\CardCategory;
use backend\modules\v1\models\IosSellCardForm;
use backend\modules\v1\models\MemberCard;
use yii\rest\ActiveController;
use yii\web\Response;


class ApiMemberCardController extends ActiveController
{
    public $modelClass = 'backend\modules\v1\models\Member';

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
     * @api {get} /v1/api-member-card/get-card-category   购卡列表
     * @apiVersion  1.0.0
     * @apiName        购卡列表
     * @apiGroup       sellCard
     * @apiPermission 管理员
     * @apiParam  {int}          venueId          场馆id
     * @apiParam  {int}          cardTypeId      卡种类型id
     * @apiParam  {int}          memberId         会员id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member-card/get-card-category
     *   {
     *        "venueId":107               //场馆id
     *        "cardTypeId":107            //卡种类型id
     *         "memberId":1088            // 会员id
     *   }
     * @apiDescription   购卡列表
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/6
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member-card/get-card-category
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     * "code": 1,                           //成功标识
     * "status": "success",                 //成功状态
     * "message": "请求成功",               //成功提示信息
     * "data": [
     *      {
     *          "id": "97",                 //卡种id
     *          "card_name": "有效期3",     //卡名称
     *          "type_name": "时间卡",      //卡类型
     *          "pic": "http://oo0oj2qmr.bkt.clouddn.com/pic.png?e",  //图片
     *          "price": "2498.00",         //卡价格
     *          "validPeriod": 250          //有效期（单位/天）
     *      },
     *      {
     *          "id": "98",                 //卡种id
     *          "card_name": "混合卡",      //卡名称
     *          "type_name": "混合卡",      //卡类型
     *          "pic": "http://oo0oj2qmr.bkt.clouddn.com/pic.png?e",  //图片
     *          "price": "1200.00",         //卡价格
     *          "validPeriod": 1100         //有效期（单位/天）
     *      }
     * ]
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * ｛
     *   "code": 0,                         //失败标识
     *   "status": "error",                 //失败标识
     *   "message": "暂时没有可以售卖的卡", //失败原因
     * ｝
     */
    public function actionGetCardCategory($venueId,$cardTypeId = '',$memberId = "")
    {
        $model = new CardCategory();
        $data = $model->getCardCategory($venueId,$cardTypeId);
//        // 判断会员信息是否完善
//        $judgeResult  =  $model->judgeMemberMessage($memberId);
        if(!empty($data))
        {
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
        }else{
            return ['code'=>0,'status'=>'error','message'=>'暂时没有可以售卖的卡'];
        }
    }
    /**
     * @api {get} /v1/api-member-card/get-card-category-detail   卡种详情
     * @apiVersion  1.0.0
     * @apiName        卡种详情
     * @apiGroup       sellCards
     * @apiPermission 管理员
     * @apiParam  {int}          cardCategoryId          卡种id
     * @apiParam  {int}          memberId                会员id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member-card/get-card-category-detail
     *   {
     *        "cardCategoryId ":107             //卡种id
     *        "accountID"      :90040            // 账户id
     *        "venue_id"                        //场馆id
     *   }
     * @apiDescription   卡种详情
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/6
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member-card/get-card-category-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     *{
     *  "code": 1,                              //成功标识
     *  "status": "success",                    //成功标识
     *  "message": "请求成功",                  //成功信息
     *  "data": {
     *      "id": "3",                          //卡种id
     *      "card_name": "测试卡种专属",        //卡种名称
     *      "attributes": "家庭",               //卡种属性
     *      "transfer_number": 10,              //转让次数
     *      "transfer_price": 100,              //转让金额
     *      "type_name": "时间卡",              //卡种类型
     *      "validPeriod": 100,                 //有效天数
     *      "price": "1000.00",                 //金额
     *      "totalDay": 100,                    //请假总天数
     *      "leastDay": 10,                     //最少请假天数
     *      "shopVenue": "大上海馆/大卫城馆/大学路馆"   //通店场馆
     *  }
     *}
     * @apiSuccessExample {json}返回值详情（失败）
     * ｛
     *   "code": 0,                         //失败标识
     *   "status": "error",                 //失败标识
     *   "message": "获取详细信息失败", //失败原因
     * ｝
     */
    public function actionGetCardCategoryDetail($cardCategoryId,$accountId="",$venue_id)
    {
        $model = new CardCategory();
        $data  = $model->getCardCategoryDetail($cardCategoryId);
        $judgeResult  =  $model->judgeMemberMessage($accountId,$venue_id);
        if(!empty($data))
        {
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data,"identify"=>$judgeResult];
        }else{
            return ['code'=>0,'status'=>'error','message'=>'获取详细信息失败'];
        }
    }
    /**
     * @api {get} /v1/api-member-card/get-class-package   套餐
     * @apiVersion  1.0.0
     * @apiName        套餐
     * @apiGroup       sellCards
     * @apiPermission 管理员
     * @apiParam  {int}          cardCategoryId          卡种id
     * @apiParam  {string}       type                    套餐类型（class表示课程套餐，server表示服务套餐）
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member-card/get-class-package
     *   {
     *        "cardCategoryId ":107             //卡种id
     *        "type":"class"                    //class表示课程套餐，server表示服务套餐
     *   }
     * @apiDescription   卡种详情
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/6
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member-card/get-class-package
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     * "code": 1,                       //成功标识
     * "status": "success",             //成功标识
     * "message": "请求成功",           //成功信息
     * "data": [
     *   {
     *      "id": "19",                 //卡种绑定表id
     *      "polymorphic_id": "11",     //多态id(课种id或者服务id)
     *      "number": "-1",             //-1表示每天课程每日节数不限 或者服务次数不限
     *      "courseId": "11",           //课种表id
     *      "name": "团A"               //课程名称
     *   },
     *   {
     *      "id": "20",                 //卡种绑定表id
     *      "polymorphic_id": "13",     //多态id(课种或者服务id)
     *      "number": "22",             //课程：每日的节数（服务：每日次数）
     *      "courseId": "13",           //课种id
     *      "name": "团B"               //课种名称
     *   }
     * ]
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * ｛
     *   "code": 0,                         //失败标识
     *   "status": "error",                 //失败标识
     *   "message": "获取信息失败", //失败原因
     * ｝
     */
    public function actionGetClassPackage($cardCategoryId,$type)
    {
        $model = new CardCategory();
        $data  = $model->getPackage($cardCategoryId,$type);
        if(!empty($data))
        {
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
        }else{
            if($type == 'class')
            {
                return ['code'=>0,'status'=>'error','message'=>'没有绑定课程套餐'];
            }else if($type == 'server')
            {
                return ['code'=>0,'status'=>'error','message'=>'没有绑定服务套餐'];
            }
            return ['code'=>0,'status'=>'error','message'=>'没有绑定该套餐'];
        }
    }
    /**
     * @api {get} /v1/api-member-card/get-card-type   卡种类型
     * @apiVersion  1.0.0
     * @apiName        卡种类型
     * @apiGroup       sellCardType
     * @apiPermission 管理员
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-member-card/get-card-type
     * @apiDescription   卡种详情
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/7
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member-card/get-card-type
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     * "code": 1,                               //成功标识
     * "status": "success",                     //成功标识
     * "message": "请求成功",                   //成功信息
     * "data": [
     *      {
     *          "id": "3",                       //卡种类型id
     *          "type_name": "充值卡"            //卡种类型名称
     *      },
     *      {
     *          "id": "1",                        //卡种类型id
     *           "type_name": "时间卡"            //卡种类型名称
     *      },
     *      {
     *          "id": "2",                         //卡种类型id
     *           "type_name": "次卡"               //卡种类型名称
     *      },
     *      {
     *           "id": "4",                        //卡种类型id
     *           "type_name": "混合卡"             //卡种类型名称
     *      }
     *      ]
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * ｛
     *   "code": 0,                                   //失败标识
     *   "status": "error",                           //失败标识
     *   "message": "没有卡种类型",                   //失败原因
     * ｝
     */
    public function actionGetCardType()
    {
        $model = new CardCategory();
        $data  = $model->getCardType();
        if(!empty($data))
        {
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
        }else{
            return ['code'=>0,'status'=>'error','message'=>'没有卡种类型'];
        }
    }
    /**
     * @api {POST} /v1/api-member-card/set-member-card-info   购卡
     * @apiVersion  1.0.0
     * @apiName        购卡
     * @apiGroup       sellCard
     * @apiPermission 管理员
     * @apiParamExample {json} 请求参数
     *   POST /v1/api-member-card/set-member-card-info
     *   {
     *        "memberId ":107             //卡种id
     *        "cardCategoryId":2         //卡种id
     *        "amountMoney":2000         //总金额
     *        "payMethod":2              //1现金；2支付宝；3微信；4pos刷卡；
     *   }
     * @apiDescription   购卡
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/9
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member-card/set-member-card-info
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *      "code": 1,              //成功标识
     *      "status": "success",    //成功标识
     *      "data": "提交成功"      //成功信息
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *      " code": 0,             //购买失败
     *      "status": "error",      //失败标识
     *      "message": "购买失败",  //失败信息
     *      "data": []              //保存失败原因
     * }
     */
    public function actionSetMemberCardInfo()
    {
        $post      = \Yii::$app->request->post();
        $model     = new IosSellCardForm();
        $limit = $model->setSellNum($post['cardCategoryId']);
        if($limit == true) {
            if ($model->load($post, '') && $model->validate()) {
                $data = $model->saveMemberCard();
                if ($data === true) {
                    $model->sendMessage();                             //售卡成功发送短信
                    return ['code'=>1,'status' => 'success', 'message' => '购买成功'];
                } else {
                    return ['code'=>0,'status' => 'error','message'=>'购买失败', 'data' => $model->errors];
                }
            } else {
                return ['code'=>0,'status' => 'error','message'=>'购买失败', 'data' => $model->errors];
            }
        }else{
            return ['code'=>0,'status' => 'error','message'=>'此卡种已售卖完，请选择其他卡种', 'data' =>$limit];
        }
    }
    /**
     * @api {GET} /v1/api-member-card/absent-record 会员旷课记录
     * @apiVersion  1.0.0
     * @apiName       会员旷课记录
     * @apiGroup      absentRecord
     * @apiPermission 管理员
     * @apiParam  {string}    identify                //请求身份 card：会员卡 member：会员
     * @apiParam  {int}       identifyId              // 会员卡id 或 账户id
     * @apiParam  {int}       courseType               // 1 代表 私课  2代表 团课  3 代表私课和团课
     * @apiParamExample {json} 请求参数
     *   GET  /v1/api-member-card/absent-record
     *   {
     *        "identify":"member"             //请求身份 member：会员
     *        "identifyId":2              // 会员卡id 或 会员id
     *        "courseType":1              // 1 代表 私课  2代表 团课
     *   }
     * @apiDescription   会员旷课记录
     * <br/>
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/10/06
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member-card/absent-record
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *      "code": 1,              //成功标识
     *      "status": "success",    //成功标识
     *      "data": "提交成功",      //成功信息
     *      "data":[
     *       "id": "4029",       // 约课id
     *       "member_card_id": "43030", // 会员卡id
     *       "class_id": "6387",       // 排课id
     *       "coach_id": "76",        // 教练id
     *       "coachName": "贾慧",     // 教练名称
     *       "classroomName": "3号厅",  // 上课教师
     *       "courseName": "爵士街舞",   // 课程名称
     *       "memberCardName": "D12M MD",  // 卡名称
     *       "courseStartTime": "1507188900",  // 开课时间
     *       "courseEndTime": "1507189020",   // 课程结束时间
     *        "courseDate": "2017-10-05"    // 课程日期
     *        "courseType": "2""            // 1 私课 2 团课
     * ]
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *      " code": 0,             //购买失败
     *      "status": "error",      //失败标识
     *      "message": "购买失败",  //失败信息
     *      "data": []              //保存失败原因
     * }
     */
    public function actionAbsentRecord($identify = null,$identifyId = null,$courseType = null){
        $model = new MemberCard();
        $data  = $model->gainAbsentRecord($identify,$identifyId,$courseType);
        if(!empty($data))
        {
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
        }else{
            return ['code'=>0,'status'=>'error','message'=>'没有旷课记录哦','data'=>$data];
        }
    }
    /**
     * @api {GET}     /v1/api-member-card/search-member-message 搜索会员信息
     * @apiVersion   1.0.0
     * @apiName       搜索会员信息
     * @apiGroup      searchMember
     * @apiPermission 管理员
     * @apiParam  {string}    accountId                //账户id
     * @apiParamExample {json} 请求参数
     *   GET  /v1/api-member-card/search-member-message
     *   {
     *        "accountId":12             //账户id
     *   }
     * @apiDescription   搜索会员信息
     * <br/>
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/11/29
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-member-card/search-member-message
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     *{
     *"code": 1,
     *"status": "success",
     *"message": "请求成功",
     *"data": {
     *"name": "崔鹏",
     *"sex": "1",
     *"id_card": "410104198703050017"
     *}
     *}
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *      " code": 0,             //购买失败
     *      "status": "error",      //失败标识
     *      "message": "购买失败",  //失败信息
     *      "data": []              //保存失败原因
     * }
     */
    public function actionSearchMemberMessage($accountId=""){
        $model  = new Member();
        $member = $model->memberMessage($accountId);
        return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$member];
    }

    public function actionIsCanCode($memberCardId)
    {
        $card = MemberCard::findOne($memberCardId);
        if(empty($card)) return ['code' => 0, 'status' => 'error', 'message' =>"会员卡不存在"];
        if($card->pid){
            $had = EntryRecord::find()->where(['member_card_id'=>$card->pid])->andWhere(['between', 'entry_time', strtotime(date('Y-m-d')), strtotime(date('Y-m-d').' 23:59:59')])->one();
            if(!$had) return ['code' => 0, 'status' => 'error', 'message' =>"主卡未进馆"];
        }

        return ['code' =>1, 'status' => 'success','message' =>"可以生成二维码"];
    }
    public function actionIsMember($cardCategoryId,$accountId='',$venue_id=''){
        if(empty($accountId) || empty($venue_id)){
            return ['code' =>1, 'status' => 'success','message' =>"000"]; //参数异常！
        }
        $member = Member::find()->where(["member_account_id"=>$accountId,'venue_id'=>$venue_id])->one();
        if (empty($member)) return ['code' =>1, 'status' => 'success','message' =>"001"];;//不存在潜在会员
        if ($member->member_type == 2){
          return ['code' =>1, 'status' => 'success','message' =>"002"];//潜在会员
        }else {
            return ['code' =>1, 'status' => 'success','message' =>"003"];//正式会员
        }
        return ['code' =>0, 'status' => 'error','message' =>"005"];
    }

}