<?php

namespace backend\modules\v1\controllers;
use backend\modules\v1\models\ChargeClass;
use backend\modules\v1\models\IosSellClassForm;
use backend\modules\v1\models\LoginForm;
use backend\modules\v1\models\Member;
use yii\rest\ActiveController;
use yii\web\Response;

class ApiPrivateController extends ActiveController
{
    public $modelClass = 'backend\modules\v1\models\ChargeClass';
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
     * @api {get} /v1/api-private/get   获取会员所有购买产品
     * @apiVersion  1.0.0
     * @apiName        获取会员所有购买产品
     * @apiGroup       memberClass
     * @apiPermission 管理员
     * @apiParam  {int}            accountId         账户id
     * @apiParam  {string}        requestType       请求类型（ios）
     * @apiParam  {string}        [course]          课种id(搜索时传)
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-private/get
     *   {
     *        "accountId":107，        //账户id
     *        "requestType":"ios"     //请求类型是ios
     *        "course":10             //课种id(搜索时传)
     *   }
     * @apiDescription   获取会员所有购买产品
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-private/get
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     *  "code":1,               //成功标识
     *  "status": "success",    //请求状态
     *  "message": "请求成功"， //返回信息
     *  "data": [
     * {
     *   "id": "5",                             //订单id
     *   "classCount": "10",                    //总节数
     *   "totalPrice": "100",                   //总价钱
     *   "surplusCount": "8",                //剩余节数
     *   "product_name": "month单节",           //产品名称
     *   "course_order_id": "5",                //订单id
     *   "pic": "http://oo0oj2qmr.bkt.clouddn.com/0.jpg?e=1497070119&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:8LWhLhqOIVZ9lbdwOLBlvYppdZg=", //图片
     *   "category": "1",                       //课程类型（1多课程 2单课程）
     *   "package": 1,                          //套餐的数量（此字段数据只在多课程时出现）
     *   "memberCourseOrderDetails": [          //课程数组
     *   {
     *     "id": "5",                           //订单详情id
     *     "course_order_id": "5",              //订单id
     *     "course_id": "11",                   //课种id
     *     "course_num": "10",                  //课程基础课量
     *     "course_length": "1000",             //课程有效天数
     *     "original_price": "15.00",           //单节原价
     *     "sale_price": null,                  //单节售价
     *     "pos_price": null,                   //单节pos售价
     *     "type": "1",                         //订单类型：1私课2团课
     *     "category": "2",                     //课程类型：1多课程2单课程
     *     "product_name": "month单节",         //产品名称
     *     "course_name": "month",              //课程名称
     *     "class_length": "100",               //课程时长
     *     "pic": "http://oo0oj2qmr.bkt.clouddn.com/0.jpg?e=1497070119&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:8LWhLhqOIVZ9lbdwOLBlvYppdZg=",//图片
     *     "desc": ""                           //产品描述
     *   }
     *   ],
     *   "score": {
     *     "score": 4,                          //产品级别
     *     "scoreImg": {                        //评论星图
     *        "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *        "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *        "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *        "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *        "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
     * }
     * },
     * "tag": [                                 //产品特色
     *  "减脂",
     *  "特色"
     * ]
     * },
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                                   //失败标识
     *   "status": "error",                           //失败状态
     *   "message": "未获取到数据"                   //失败原因
     *   }
     */
    public function actionGet($accountId,$requestType = '',$course = '')
    {
//        $params    = \Yii::$app->request->queryParams;
        $group       = new ChargeClass();
//        $data        = $group->search($params);
        if($requestType == 'ios')
        {
            $data = $group->getMemberOrder($accountId,$course);
            $model = new LoginForm();
            $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
            foreach ($members as $member) {
                $ids[] = $member['id'];
            }
            $model->accountId = $accountId;
            $cardStatus = $model->getMemberCardOne();
            if(!empty($data))
            {
                return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data,"cardStatus"=>$cardStatus];
            }else{
                return ['code'=>0,'status'=>'error','message'=>'您还未购买课程',"cardStatus"=>$cardStatus];
            }
        }else{
            $data = $group->getMemberOrder($accountId,$course);
            return $data;
        }

    }
    /**
     * @api {get} /v1/api-private/get-detail   会员购买产品详情
     * @apiVersion  1.0.0
     * @apiName        会员购买产品详情
     * @apiGroup       memberClass
     * @apiPermission 管理员
     * @apiParam  {int}            id               订单id
     * @apiParam  {int}            memberId         会员id
     * @apiParam  {string}        requestType       请求类型（ios）
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-private/get-detail
     *   {
     *        "id":10                 //订单id
     *        "memberId":107，        //会员id
     *        "requestType":"ios"     //请求类型是ios
     *   }
     * @apiDescription   会员购买产品详情
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-private/get-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     *  "code":1,               //成功标识
     *  "status": "success",    //请求状态
     *  "message": "请求成功"， //返回信息
     *  "data": [
     *   "id": "5",                                   //订单id
     *   "create_at": "1497149134",                   //买课时间
     *   "product_name": "month单节",                 //产品名称
     *   "course_amount": "10",                       //总节数
     *    "category":"1",                             // 1:多课程 2：单课程
     *   "money_amount": "100",                       //总价格
     *   "course_order_id": "5",                      //订单id
     *   "course_id": "11",                           //课种id
     *   "pic": "http://oo0oj2qmr.bkt.clouddn.com/0.jpg?e=1497070119&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:8LWhLhqOIVZ9lbdwOLBlvYppdZg=", //产品图片
     *   "desc": "",                                  //产品描述
     *   "venue_id": "29",                            //场馆id
     *   "venueName": "一月",                         //场馆名称
     *   "venueAddress": "dsfasdfddddddddddddd",      //场馆地址
     *    "longitude": "113.675505"                   // 精度
     *     "latitude":"60.23232"                      // 维度
     *   "memberCourseOrderDetails": [                //产品详情
     *   {
     *      "id": "5",                                //订单详情id
     *      "course_order_id": "5",                   //订单id
     *      "course_id": "11",                        //课种id
     *      "course_num": "10",                       //课程基础课量
     *      "course_length": "1000",                  //课程有效天数
     *      "original_price": "15.00",                //单节原价
     *      "sale_price": null,                       //单节售价
     *      "pos_price": null,                        //pos售价
     *      "type": "1",                              //订单类型：1私课2团课
     *      "category": "2",                          //课程类型：1多课程2单课程
     *      "product_name": "month单节",              //产品名称
     *      "course_name": "month",                   //课程名称
     *      "class_length": "100",                    //课程时长
     *      "pic": "http://oo0oj2qmr.bkt.clouddn.com/0.jpg?e=1497070119&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:8LWhLhqOIVZ9lbdwOLBlvYppdZg=", //图片
     *      "desc": ""                                //描述
     *      }
     *      ],
     *   "score": 4,                                    //产品级别
     *   "scoreImg": {                                  //产品星星图
     *     "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *     "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *     "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *     "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *     "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
     *   },
     *   "orderStatus": true                           //订单状态，true可以预约，false，课程已用完
     *    "newMember"：true                            // 是否是新会员 （true新会员 false老会员）
     *   "aboutNum": 1                                 //1表示预约第1节，2表示预约第2节...0表示课程已用完
     *   }
     * ]
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                                   //失败标识
     *   "status": "error",                           //失败状态
     *   "message": "未获取到数据"                   //失败原因
     *   }
     */
    public function actionGetDetail($id,$memberId = 0)
    {
        $group = new ChargeClass();
        $data = $group->getChargeDetail($id,$memberId);
        if(!empty($data))
        {
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>[$data]];
        }else{
            return ['code'=>0,'status'=>'error','message'=>'未获取到数据'];
        }
    }
    /**
     * @api {get} /v1/api-private/get-class-package-detail   产品课程详情
     * @apiVersion  1.0.0
     * @apiName        产品课程详情
     * @apiGroup       memberClass
     * @apiPermission 管理员
     * @apiParam  {int}            id               订单id
     * @apiParam  {string}        requestType       请求类型（ios）
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-private/get-class-package-detail
     *   {
     *        "id":107，               //订单id
     *        "requestType":"ios"     //请求类型是ios
     *   }
     * @apiDescription   产品课程详情
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-private/get-class-package-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     *    {
     *    "code": 1,                                //成功标识
     *    "status": "success",                      //成功状态 
     *    "message": "请求成功",                    //成功信息
     *    "data": {         
     *      "id": "8",                              //订单id
     *      "create_at": "1497335694",              //下单时间（买课时间）
     *      "product_name": "两节课",               //产品名称
     *      "course_amount": "20",                  //总节数
     *      "money_amount": "210",                  //总价格
     *      "course_order_id": "8",                 //订单id
     *      "course_id": "6",                       //课种id
     *      "pic": "http://oo0oj2qmr.bkt.clouddn.com/u=2=0.jpg?e=1497265234&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:YqgyPQ0_98D_ADiNJdKML4X-wXs=", //产品图片
     *      "score": 4,                             //产品级别
     *      "scoreImg": {                           //星星图
     *      "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *      "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *      "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *      "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
     *      "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
     *    },
     *    "packageClass": [                        //课程数组
     *    {
     *    "name": "塑形",                          //课程名称
     *    "times": 1,                              //第几节
     *    "class_length": "100",                   //时长
     *    "sale_price": "11.00",                   //价格
     *    "is_member": "1",                        //是不是会员标识
     *    "status": "1"                            //1表示已上过 2表示未上过
     *    },
     *  ...
     *    {
     *    "name": "month",
     *    "times": 1,
     *    "class_length": "100",
     *    "sale_price": "10.00",
     *    "is_member": "1",
     *    "status": "1"
     *    },
     *    ...
     *    ]
     *    }
     *    }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                                   //失败标识
     *   "status": "error",                           //失败状态
     *   "message": "未获取到数据"                   //失败原因
     *   }
     */
    public function actionGetClassPackageDetail($id,$requestType = '',$memberId=null)
    {
        $group       = new ChargeClass();
        if($requestType == 'ios')
        {
            $data        = $group->getChargePackageDetail($id,$memberId);
            if(!empty($data))
            {
                return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>[$data]];
            }else{
                return ['code'=>0,'status'=>'error','message'=>'未获取到数据'];
            }
        }else{
            $data        = $group->getChargePackageDetail($id,$memberId);
            return $data;
        }
    }

    /**
     * @api {get} /v1/api-private/get-all-private-class   所有私课产品
     * @apiVersion  1.0.0
     * @apiName        所有私课产品
     * @apiGroup       chargeClass
     * @apiPermission 管理员
     * @apiParam  {int}            venueId            场馆id
     * @apiParam  {int}           [course]            课种id
     * @apiParam  {string}        [requestType]       请求类型（ios）
     * @apiParam  {int}          [page]               分页加载页数（必传字段）第一次传1 第二次 2 第三次3 第四次4
     * @apiParam  {int}          type                 请求类型
     * @apiParam  {string}       requestSign          请求标志 （有值的话只有前两条数据  没有值的所有数据 有值的话前两条 ）
     * @apiParam  {string}       versionNum           版本号
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-private/get-all-private-class
     *   {
     *        "venueId":1             //场馆id
     *        "requestType":"ios"     //请求类型是ios
     *        "course":10             //课种id(搜索时传)
     *         "page":2               // 分页加载参数   表示第几页
     *         "type":hhh             // 类型（忘了）
     *         "versionNum":1.10.1    // 版本号
     *        "requestSign":sign      // 请求标志       有值的话只有前两条数据  没有值表示按照先前加载分页
     *   }
     * @apiDescription    所有私课产品
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/30
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-private/get-all-private-class
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     *{
     *   "code": 1,                                      //请求成功标识
     *   "status": "success",                            //请求成功状态
     *   "message": "请求成功",                          //请求成功信息
     *   "data": [
     *   {
     *      "id": "1",                                   //产品id
     *      "name": "私课套餐",                          //产品名称
     *      "type": 1,                                   // 1 多课程 2 是单课程
     *      "pic": "http://oo0oj2qm.com/x1.png",         //产品图片
     *      "classCount": "20",                          //产品总量
     *      "totalPrice": 150,                           //产品总价
     *      "charge": "减脂课程10节 /塑形课程10节 ",     //产品详细
     *      "score": 4,                                  //产品星级
     *      "scoreImg": {                                //产品星星
     *      "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=",
     *      "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=",
     *      "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=",
     *      "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=",
     *      "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e="
     *   },
     *   "tag": [                                       //产品特色
     *          "减脂",
     *          "特色"
     *      ]
     *   },
     *   ]
     *   }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                                   //失败标识
     *   "status": "error",                           //失败状态
     *   "message": "场馆暂时没有排课"                //失败原因
     * }
     */
    public function actionGetAllPrivateClass()
    {
        $params                  = \Yii::$app->request->queryParams;
        // 请求标志 （是否只获取前两条）
        $params["requestSign"] = isset($params["requestSign"])&&!empty($params["requestSign"])?$params["requestSign"]:null;

        $params["versionNum"]  = (!isset($params["versionNum"])||empty($params["versionNum"]))?null:$params["versionNum"];
        $group       = new ChargeClass();
        if(isset($params['requestType']) && $params['requestType'] == 'ios')
        {
            unset($params['requestType']);
            $data    = $group->search($params);
            if($data=="upgrade"){
                return ['code'=>0,'status'=>'error','message'=>'请从应用市场更新应用'];
            }
            if(!empty($data))
            {
                return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data,"endPage"=>$group->endPage];
            }else{
                return ['code'=>0,'status'=>'error','message'=>'场馆暂时没有排课'];
            }
        }else{
            $data        = $group->search($params);
            return $data;
        }
    }
    /**
     * @api {get} /v1/api-private/get-all-private-course   所有私课课程
     * @apiVersion  1.7.3
     * @apiName        所有私课课程
     * @apiGroup       chargeClass
     * @apiPermission 管理员
     * @apiParam  {int}            venueId            场馆id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-private/get-all-private-course
     *   {
     *        "venueId":59             //场馆id
     *   }
     * @apiDescription    所有私课课程
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/06/06
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/api-private/get-all-private-course
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     *{
     *   "code": 1,                                      //请求成功标识
     *   "status": "success",                            //请求成功状态
     *   "message": "请求成功",                          //请求成功信息
     *   "data": [
     *   {
     *      "id": "1",                                   //产品id
     *      "name": "私课套餐",                          //产品名称
     *      "type": 1,                                   // 1 多课程 2 是单课程
     *      "pic": "http://oo0oj2qm.com/x1.png",         //产品图片
     *      "classCount": "20",                          //产品总量
     *      "totalPrice": 150,                           //产品总价
     *      "charge": "减脂课程10节 /塑形课程10节 ",     //产品详细
     *      "score": 4,                                  //产品星级
     *      "scoreImg": {                                //产品星星
     *      "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=",
     *      "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=",
     *      "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=",
     *      "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=",
     *      "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e="
     *   },
     *   "tag": [                                       //产品特色
     *          "减脂",
     *          "特色"
     *      ]
     *   },
     *   ]
     *   }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                                   //失败标识
     *   "status": "error",                           //失败状态
     *   "message": "场馆暂时没有排课"                //失败原因
     * }
     */
    public function actionGetAllPrivateCourse()
    {
        $params  = \Yii::$app->request->queryParams;
        $group   = new ChargeClass();
        $data    = $group->getAllPrivateCourse($params);
        if(!empty($data))
        {
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$data];
        }else{
            return ['code'=>0,'status'=>'error','message'=>'场馆暂时没有排课'];
        }
    }
    /**
     * @api {get} /v1/api-private/get-private-class-info   私课产品详情
     * @apiVersion  1.0.0
     * @apiName        私课产品详情
     * @apiGroup       chargeClassDetail
     * @apiPermission 管理员
     * @apiParam  {int}           id                  产品id
     * @apiParam  {int}           memberId            会员id
     * @apiParam  {string}        [requestType]       请求类型（ios）
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-private/get-private-class-info
     *   {
     *        "id":10                  //产品id
     *        "memberId":10            //会员id
     *        "requestType":"ios"     //请求类型是ios
     *   }
     * @apiDescription    私课产品详情
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/3
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-private/get-private-class-info
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     * "code": 1,
     * "status": "success",
     * "message": "请求成功",
     * "data": {
     *    "id": "2",                                    //产品id
     *    "product_name": "私课单节",                   //产品名称
     *    "desc": "这是单节课",                        //产品描述
     *    "pic": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",     //产品描述
     *    "score": 4,                                   //产品级别
     *    "venue_id": "2",                              //场馆id
     *    "venueName": "大上海馆",                      //场馆名称
     *    "venueAddress": "",                           //场馆地址
     *    "venuePic": "http://oo0oj2qmr.bkt.cloudcom",  //场馆图片
     *    "intervalArr": [                              //价位区间（单节课时出现，套餐时不出现）false表示没有区间价
     *    {
     *          "intervalStart": "1",                   //区间开始数
     *          "intervalEnd": "10",                    //区间结束数
     *          "unitPrice": "10",                      //优惠单价
     *          "posPrice": "10"                        //pos机价
     *    },
     *    {
     *          "intervalStart": "11",                  //区间开始数
     *          "intervalEnd": "20",                    //区间结束数
     *          "unitPrice": "5",                       //优惠单价
     *          "posPrice": "5"                         //pos机价
     *    },
     *    ],
     *    "product": false,                             //true表示该课程（已购买）可以预约，false表示该课程（未购买）不能预约
    *     "newMember": false,                           // true是新会员（用posPrice价）, false不是新会员（unitPrice价）
     *    "memberCard":true,                            //true表示买过会员卡，false表示没买个
     *    "category": "2",                              //1表示是套餐，2表示是单节课程
     *    "orderId": false,                             //false会员没有买该课程（同上），当会员买个课时此字段的值就是订单id
     *    "money_amount": 10,                           //套餐的总价，单节课程的单节原价
     *    "course_amount": 1,                           //总量
     *    "memberCourseOrderDetails": [
     *    {
     *        "course_num": 1,                          //课量
     *        "course_id": "18",                        //课种id
     *        "id": "3",                                //课程套餐详情表id
     *        "charge_class_id": "2",                   //产品id
     *        "course_length": "100",                   //课时长
     *        "original_price": "10",                   //售卖单价
     *        "category": "2",                          //1表示多节2表示单节课程
     *        "name": "减脂"                            //课种名称
     *    }
     *    ]
     *  "score": 4,                                     //星级
    *   "scoreImg": {
    *   "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241W3S:",
    *   "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=14:-mWe",
    *   "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=14W3S:-",
    *   "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=14S:-mW",
    *   "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=14S:dh"
    *   },
    *   "tag": [                                       //特色
    *   "减脂",
    *   "特色"
    *   ]
     * }
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                                   //失败标识
     *   "status": "error",                           //失败状态
     *   "message": "未获取到数据"                   //失败原因
     *   }
     */
    public function actionGetPrivateClassInfo($id,$memberId,$requestType = '')
    {
        $group = new ChargeClass();
        if ($requestType == 'ios') {
            $data = $group->getPrivateClass($id,$memberId);
            if(!empty($data))
            {
                return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data];
            }else{
                return ['code' => 0, 'status' => 'error', 'message' => '未获取到数据'];
            }
        } else {
            $data = $group->getPrivateClass($id,$memberId);
            return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data];
        }
    }
    /**
     * @api {get} /v1/api-private/get-private-detail   产品中的课程详情
     * @apiVersion  1.0.0
     * @apiName        产品中的课程详情
     * @apiGroup       chargeClassDetail
     * @apiPermission 管理员
     * @apiParam  {int}           id                  产品id
     * @apiParam  {int}           memberId            会员id
     * @apiParam  {string}        [requestType]       请求类型（ios）
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-private/get-private-detail
     *   {
     *        "id":10                  //产品id
     *        "memberId":10            //会员id
     *        "requestType":"ios"     //请求类型是ios
     *   }
     * @apiDescription    私课产品详情
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/3
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-private/get-private-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     * "code": 1,               //成共标识
     * "status": "success",     //成功状态
     * "message": "请求成功",   //成功返回信息
     * "data": [
     *      {
     *      "id": "2",                                //产品id
     *      "product_name": "私课单节",               //产品名称
     *      "pic": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx", //产品图片
     *      "packageClass": [                         //产品详细
     *      {
     *          "name": "减脂",                       //课种名称
     *          "times": 1,                           //第几节
     *          "class_length": "100",                //课时长
     *          "sale_price": "10",                   //售卖单价
     *          "is_member": "1",                     //是不是会员
     *          "status": "1"                         //1表示上过，2表示没上过
     *      }
     *      ],
     *      "money_amount": 10,                      //总价
     *      "course_amount": 1,                      //总节数
     *      "score": 4,                              //级别
     *      "scoreImg": {                            //星星图片
     *          "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?",
     *          "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?",
     *          "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?",
     *          "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?",
     *          "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?"
     *      }
     *      }
     * ]
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,                                   //失败标识
     *   "status": "error",                           //失败状态
     *   "message": "未获取到数据"                   //失败原因
     *   }
     */
    public function actionGetPrivateDetail($id,$memberId,$requestType = '')
    {
        $model = new ChargeClass();
        $data = $model->GetPrivateDetail($id, $memberId);
        if ($requestType == 'ios') {
            if (!empty($data)) {
                return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => [$data]];
            } else {
                return ['code' => 0, 'status' => 'error', 'message' => '未获取到数据'];
            }
        }else{
            $data = $model->GetPrivateDetail($id, $memberId);
            return $data;
        }
    }
    /**
     * @api {get} /v1/api-private/set-member-class-info   买课
     * @apiVersion  1.0.0
     * @apiName        买课
     * @apiGroup       sellClass
     * @apiPermission 管理员
     * @apiParam  {int}           memberId            会员id
     * @apiParam  {int}           chargeId            产品id
     * @apiParam  {int}           amountMoney         金额
     * @apiParam  {int}           nodeNumber          课程节数（或者套餐数量）
     * @apiParam  {int}           payMethod           //1现金；2支付宝；3微信；4pos刷卡；
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-private/set-member-class-info
     *   {
     *        "memberId":10            //会员id
     *        "chargeId":10            //产品id
     *        "amountMoney":10         //金额
     *        "nodeNumber":10          //课程节数（或者套餐数量）
     *        "payMethod":2           ////1现金；2支付宝；3微信；4pos刷卡；
     *   }
     * @apiDescription    买课
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/7/11
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-private/set-member-class-info
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     *{
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message": "购买成功"   //成功信息
     *}
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *   "code": 0,             //失败标识
     *   "status": "error",     //失败标识
     *   "message": "购买失败", //失败信息
     *   "data": []             //空数组表示，失败原因不是save()报的错
     * }
     */
    public function actionSetMemberClassInfo()
    {
        $post      = \Yii::$app->request->post();
        $model     = new IosSellClassForm();
            if ($model->load($post, '') && $model->validate()) {
                $data = $model->saveCharge();
                if ($data === true) {
//                    $model->sendMessage();                             //售卡成功发送短信
                    return ['code'=>1,'status' => 'success', 'message' => '购买成功'];
                } else {
                    return ['code'=>0,'status' => 'error','message'=>'购买失败', 'data' => $model->errors];
                }
            } else {
                return ['code'=>0,'status' => 'error','message'=>'购买失败', 'data' => $model->errors];
            }
    }
}



