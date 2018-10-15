<?php
namespace backend\modules\v1\controllers;
use backend\components\alipay\aop\AopClient;
use backend\components\alipay\aop\MbAopClient;
use backend\components\appPay\WePay;
use backend\components\appPay\WxNotifyVerify;
use backend\models\Order;
use backend\modules\v1\models\AlipayOpenPublicTemplateMessageIndustryModifyRequest;
use backend\modules\v1\models\AlipaySystemOauthTokenRequest;
use backend\modules\v1\models\AlipayUserInfoAuthRequest;
use backend\modules\v1\models\AlipayUserUserinfoShareRequest;
use backend\modules\v1\models\CabinetRentForm;
use backend\modules\v1\models\Payment;
use backend\modules\v1\models\PaymentCardForm;
use backend\modules\v1\models\PaymentForm;
use backend\modules\v1\models\ThawPaymentForm;
use common\models\Func;
use yii\rest\ActiveController;
use yii\web\Response;
class ApiPaymentController extends ActiveController
{
    public $modelClass = 'backend\modules\v1\models\Payment';
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
     * @api {post} /v1/api-payment/sell-card ios支付接口(微信)
     * @apiVersion 1.0.0
     * @apiName ios支付接口
     * @apiGroup api-payment
     * @apiPermission 管理员
     * @apiParam {int}    typeId        类型Id
     * @apiParam {int}    type          类型:私课:'charge' 卡种:'card' 健身餐:'meal'
     * @apiParam {int}    [amountMoney] 金额
     * @apiParam {int}    memberId      会员ID
     * @apiParam {int}    paymentType   支付类型
     * @apiParam {int}    num           课程节数
     * @apiParam {int}    coachId       教练id
     * @apiParamExample {json} Request Example
     * {
     *      "typeId"        => 1,
     *      "type"          => 'charge',card
     *       "amountMoney"  => '1000'
     *      "memberId"      => 1,
     *      "paymentType"   => "1",
     *      'num'           =>'1000',
     *      "coachId"       =>12     
     * }
     * @apiDescription ios支付接口<br/>
     * <span><strong>作    者：</strong></span>李慧恩<br>
     * <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br>
     * <span><strong>创建时间：</strong></span>2017/6/6<br>
     * <span><strong>调用方法：</strong></span>/api-payment/sell-card
     *
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-payment/sell-card
     * @apiSuccess (返回值) {string} status 返回状态
     * @apiSuccess (返回值) {string} data   返回状态的数据
     *
     * @apiSuccessExample {json} 成功示例:
     *{
     * "code": "1",
     * "status": "success",
     * "message": "成功",
     * "data": {
     * "appid": "wxsajdhuuasjuhdjashb2",
     * "partnerid": "1485481212432",
     * "prepayid": "wx201707dwsadasjidahsidhass15718",
     * "noncestr": "con0S2GmUA7AZdCu",
     * "timestamp": 1500262115,
     * "package": "Sign=WXPay",
     * "sign": "AD03A38528F5FFFFFASDWS12324112DC52F4"
     * }
     * }
     * @apiErrorExample {json} 错误示例:
     * {
     * "status": "error",
     * "message": "失败",
     * "data": {
     * "name": [
     * "请填写姓名"
     * ]
     * }
     * }
     */
    public function actionSellCard()
    {
        $post  = \Yii::$app->request->post();
        $model = new PaymentForm();
        if ($model->load($post, '') && $model->validate()) {
            if (!isset($post['orderId'])) {
                $data = $model->paymentCard();
            }
            if (isset($data->id) || isset($post['orderId'])) {
                if (isset($data->id)) {
                    $post['attach']       = $data->id;
                    $post['out_trade_no'] = $data->order_number;
                    $post['price']        = $data->total_price*100;
                } else {
                    $res = $model->getOrderInfo($post['orderId']);
                    $post['attach']       = $res['id'];
                    $orderNumber = Func::setOrderNumber();
                    $post['out_trade_no'] = $orderNumber;
                    $post['price']        = $res['total_price']*100;
                }
                $venues  = Payment::getWxConfig($post);
                $appPay  = new WePay();
                $appData = $appPay->getWxContentSign($venues);
                if (isset($data->id)) {
                    return ['code'=>'1','status' => 'success', 'message' => '成功','data'=>$appData,'price'=>$data->total_price,'orderId'=>$data->id];
                } else {
                    $result = $model->updateOrderNumber($orderNumber,$post['orderId'],$payMoneyMode = 3);
                    if (!$result) {
                        return ['code' => '0','status' => 'error', 'message' => '网络错误,请稍后重试!', 'data' => $result];
                    }
                    return ['code'=>'1','status' => 'success', 'message' => '成功','data'=>$appData,'price'=>$res['total_price'],'orderId'=>$res['id']];
                }
            } else {
                if ($post['type'] == 'meal') {
                    return ['code'=>'0','status' => 'error', 'message' => $data['message'], 'data' => $data];
                } else {
                    return ['code'=>'0','status' => 'error', 'message' => '失败', 'data' => $data];
                }
            }
        } else {
            $result = ['status' => 'error', 'message' => '失败', 'data' => $model->errors];
        }
        return $result;
    }
    /**
     * 业务后台 - 微信支付 - 获取调用支付参数
     * @return string
     */
    public function actionAppSellCard()
    {
        $post  = \Yii::$app->request->post();
        $model = new PaymentForm();
        if ($model->load($post, '') && $model->validate()) {
            $data = $model->paymentCard();
            if (isset($data->id)) {
                $post['attach']       = $data->id;
                $post['out_trade_no'] = $data->order_number;
                $post['price']         = $data->total_price*100;
                $venues  = Payment::getMbWxConfig($post);
                $appPay  = new WePay();
                $appData = $appPay->getWxContentSign($venues,"MB");
                return ['code'=>'1','status' => 'success', 'message' => '成功','data'=>$appData,'price'=>$data->total_price];
            } else {
                return ['code'=>'0','status' => 'error', 'message' => '失败', 'data' => $data];
            }
        } else {
            $result = ['status' => 'error', 'message' => '失败', 'data' => $model->errors];
        }
        return $result;
    }
    /**
     * @api {post} /v1/api-payment/app-thaw-member-card    app团课被冻结解冻
     * @apiVersion 1.0.0
     * @apiName  团课罚金解冻
     * @apiGroup  api-thaw-payment
     * @apiPermission 管理员
     * @apiParam {int}    cardId        卡id
     * @apiParam {int}    courseType    类型  1代表私课 2代表团课
     * @apiParam {int}    amountMoney   金额
     * @apiParam {string}  venueType   场馆类型   是:"maiBu" 不是："notMaiBu"
     * @apiParamExample {json} Request Example
     * {
     *      "cardId"        => 2345,        // 卡id
     *      "courseType"    => 1,          // 1私课 2团课
     *      "amountMoney"   => '1000'       // 缴纳金额
     *      "venueType"     =>"maiBu"      //  是否是迈步的app 是:"maiBu" 不是："notMaiBu"
     * }
     * @apiDescription  app团课被冻结解冻缴纳罚金<br/>
     * <span><strong>作    者：</strong></span>侯凯新<br>
     * <span><strong>邮    箱：</strong></span>侯凯新@itsports.club<br>
     * <span><strong>创建时间：</strong></span>2017/10/6<br>
     * <span><strong>调用方法：</strong></span>/v1/api-payment/app-thaw-member-card
     *
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-payment/app-thaw-member-card
     * @apiSuccess (返回值) {string} status 返回状态
     * @apiSuccess (返回值) {string} data   返回状态的数据
     *
     * @apiSuccessExample {json} 成功示例:
     *{
     * "code": "1",
     * "status": "success",
     * "message": "成功",
     * "data": {
     * "appid": "wxsajdhuuasjuhdjashb2",
     * "partnerid": "1485481212432",
     * "prepayid": "wx201707dwsadasjidahsidhass15718",
     * "noncestr": "con0S2GmUA7AZdCu",
     * "timestamp": 1500262115,
     * "package": "Sign=WXPay",
     * "sign": "AD03A38528F5FFFFFASDWS12324112DC52F4"
     * }
     * }
     * @apiErrorExample {json} 错误示例:
     * {
     * "status": "error",
     * "message": "失败",
     * "data": {
     * "name": [
     * "请填写姓名"
     * ]
     * }
     * }
     */
    public function  actionAppThawMemberCard(){
        $post  = \Yii::$app->request->post();
        $model = new ThawPaymentForm();
        $scenario = ($post["courseType"]==1)?"groupThaw":"private";
        $model->setScenario($scenario);
        if ($model->load($post, '') && $model->validate()) {
            $data = $model->thawPayment();
            if($data["result"]===true){
                $post['attach']        = $data["data"]->id;
                $post['out_trade_no'] = $data["data"]->order_number;
                $post['price']         = $data["data"]->total_price*100;
                $venues  =  (isset($post["venueType"])&&($post["venueType"]=="maiBu"))?Payment::getMbThawWxConfig($post):Payment::getThawWxConfig($post);
                $appPay  = new WePay();
                $appData = $appPay->getWxContentSign($venues,"thawCard");
                return ['code'=>'1','status' => 'success', 'message' => '成功','data'=>$appData,'price'=>$data["data"]->total_price];
            }else {
                return ['code'=>'0','status' => 'error', 'message' => '失败', 'data' => $data["data"]];
            }
        }
        return ['code'=>'0','status' => 'error', 'message' => '失败', 'data' =>$model->errors];
    }
    /**
     * @api {post} /v1/api-payment/we-chat-cabinet-order  微信 柜子租赁下订单
     * @apiVersion 1.0.0
     * @apiName   柜子租赁下订单
     * @apiGroup  cabinet
     * @apiPermission 管理员
     * @apiParam {int}    venueId        场馆id
     * @apiParam {int}    companyId      公司id
     * @apiParam {int}    memberId       会员id
     * @apiParam {int}    cabinetId      柜子id
     * @apiParam {int}    price          价格
     * @apiParam {string} startRent     租用开始时间
     * @apiParam  {string} endRent      租用结束时间
     * @apiParam {string}  venueType   场馆类型   是:"maiBu" 不是："notMaiBu"
     * @apiParamExample {json} Request Example
     * {
     *      "venueId"       => 12,        // 卡id
     *      "companyId"    => 13,          // 1私课 2团课
     *      "memberId"   => 1000          // 会员id
     *      “cabinetId” =>76            // 柜子id
     *       "startRent"  =>"2017-01-12"   // 租用开始时间
     *       "endRent"    => "2017-06-12"  // 柜子租用的结束时间
     *       "price"=>55                  // 价格55元
     *      "venueType" =>"maiBu"         //  是否是迈步的app 是:"maiBu" 不是："notMaiBu"
     * }
     * @apiDescription  柜子租赁下订单<br/>
     * <span><strong>作    者：</strong></span>侯凯新<br>
     * <span><strong>邮    箱：</strong></span>侯凯新@itsports.club<br>
     * <span><strong>创建时间：</strong></span>2017/01/08<br>
     * <span><strong>调用方法：</strong></span>/v1/api-payment/we-chat-cabinet-order
     *
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-payment/we-chat-cabinet-order
     * @apiSuccess (返回值) {string} status 返回状态
     * @apiSuccess (返回值) {string} data   返回状态的数据
     * @apiSuccessExample {json} 成功示例:
     *{
     * "code": "1",
     * "status": "success",
     * "message": "成功",
     * "data": {
     * "appid": "wxsajdhuuasjuhdjashb2",
     * "partnerid": "1485481212432",
     * "prepayid": "wx201707dwsadasjidahsidhass15718",
     * "noncestr": "con0S2GmUA7AZdCu",
     * "timestamp": 1500262115,
     * "package": "Sign=WXPay",
     * "sign": "AD03A38528F5FFFFFASDWS12324112DC52F4"
     * }
     * }
     * @apiErrorExample {json} 错误示例:
     * {
     * "status": "error",
     * "message": "失败",
     * "data": {
     * "name": [
     * "请填写姓名"
     * ]
     * }
     * }
     */
    public function actionWeChatCabinetOrder(){
        $post  = \Yii::$app->request->post();
        $model = new CabinetRentForm();
        if ($model->load($post, '') && $model->validate()){
            $data = $model->rentCabinet(3);
            if($data["status"]==="success"){
                $post['attach']        = $data["data"]->id;
                $post['out_trade_no'] = $data["data"]->order_number;
                $post['price']         = $data["data"]->total_price*100;
                $venues  =  (isset($post["venueType"])&&($post["venueType"]=="maiBu"))?Payment::getMbThawWxConfig($post):Payment::getCabinetRentConfig($post);
                $appPay  = new WePay();
                $appData = $appPay->getWxRentCabinetContentSign($venues);
                return ['code'=>'1','status' => 'success', 'message' => '成功','data'=>$appData,'price'=>$data["data"]->id];
            }else {
                return ['code'=>'0','status' => 'error', 'message' => '失败', 'data' => $data["data"]];
            }
        }
        return ['code'=>'0','status' => 'error', 'message' => '失败', 'data' =>$model->errors];
    }
    /**
     * @api {post} /v1/api-payment/ali-cabinet-order  支付宝柜子租赁订单
     * @apiVersion 1.0.0
     * @apiName   支付宝柜子租赁下订单
     * @apiGroup  cabinet
     * @apiPermission 管理员
     * @apiParam {int}    venueId        场馆id
     * @apiParam {int}    companyId      公司id
     * @apiParam {int}    memberId       会员id
     * @apiParam {int}    cabinetId      柜子id
     * @apiParam {int}    price          价格
     * @apiParam {string} startRent     租用开始时间
     * @apiParam  {string} endRent      租用结束时间
     * @apiParam {string}  venueType   场馆类型   是:"maiBu" 不是："notMaiBu"
     * @apiParamExample {json} Request Example
     * {
     *      "venueId"       => 12,        // 卡id
     *      "companyId"    => 13,          // 1私课 2团课
     *      "memberId"   => 1000          // 会员id
     *      “cabinetId” =>76            // 柜子id
     *       "startRent"  =>"2017-01-12"   // 租用开始时间
     *       "endRent"    => "2017-06-12"  // 柜子租用的结束时间
     *       "price"=>55                  // 价格55元
     *      "venueType" =>"maiBu"         //  是否是迈步的app 是:"maiBu" 不是："notMaiBu"
     * }
     * @apiDescription  柜子租赁下订单<br/>
     * <span><strong>作    者：</strong></span>侯凯新<br>
     * <span><strong>邮    箱：</strong></span>侯凯新@itsports.club<br>
     * <span><strong>创建时间：</strong></span>2017/01/08<br>
     * <span><strong>调用方法：</strong></span>/v1/api-payment/we-chat-cabinet-order
     *
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-payment/ali-cabinet-order
     * @apiSuccess (返回值) {string} status 返回状态
     * @apiSuccess (返回值) {string} data   返回状态的数据
     * @apiSuccessExample {json} 成功示例:
     *{
     * "code": "1",
     * "status": "success",
     * "message": "成功",
     * "data": {
     * "appid": "wxsajdhuuasjuhdjashb2",
     * "partnerid": "1485481212432",
     * "prepayid": "wx201707dwsadasjidahsidhass15718",
     * "noncestr": "con0S2GmUA7AZdCu",
     * "timestamp": 1500262115,
     * "package": "Sign=WXPay",
     * "sign": "AD03A38528F5FFFFFASDWS12324112DC52F4"
     * }
     * }
     * @apiErrorExample {json} 错误示例:
     * {
     * "status": "error",
     * "message": "失败",
     * "data": {
     * "name": [
     * "请填写姓名"
     * ]
     * }
     * }
     */
    public function actionAliCabinetOrder(){
        $post  = \Yii::$app->request->post();
        $model = new CabinetRentForm();
        if ($model->load($post, '') && $model->validate()) {
            $data = $model->rentCabinet(2);
            if($data["status"]==="success"){
                $payment = new Payment();
                $ali     =  (isset($post["venueType"])&&($post["venueType"]=="maiBu"))?new MbAopClient():new AopClient();
                $post['total_amount']   = $data["data"]->total_price;
                $post['attach']         = $data["data"]->id;
                $post['body']            = $data["data"]->id;
                $post['out_trade_no']   = $data["data"]->order_number;
                $venues  = (isset($post["venueType"])&&($post["venueType"]=="maiBu"))?$payment->getMbAliPayThawConfig($post):$payment->gainAliPayCabinetOrder($post);
                $preStr  = $ali->getSignContent($venues);
                $sign    = $ali->generateSign($venues,'RSA');
                $str     = $preStr.'&sign='.urlencode($sign);//传给支付宝接口的数据
                return ['code'=>'1','status' => 'success', 'message' => '成功','data'=>$str,'price'=>$data["data"]->total_price];
            } else {
                return ['code'=>'0','status' => 'error', 'message' => '失败', 'data' => $data];
            }
        }else {
            $result = ['status' => 'error', 'message' => '失败', 'data' => $model->errors];
        }
        return $result;
    }


    /**
     * 业务后台 - 微信支付 - 微信罚金支付业务回调
     * @return string
     */
    public function  actionThawNotify(){
        $notify = new WxNotifyVerify();
        $res    = $notify->getThawNotifySignData();
        echo  $res;
    }

    /**
     * @api {post} /v1/api-payment/ali-pay-sell-card ios支付宝接口
     * @apiVersion 1.0.0
     * @apiName ios支付宝接口
     * @apiGroup api-payment
     * @apiPermission 管理员
     * @apiParam {int}    cardId        卡id
     * @apiParam {int}    courseType    类型  1代表私课 2代表团课
     * @apiParam {int}    amountMoney   金额
     * @apiParamExample {json} Request Example
     * {
     *      "cardId"        => 2345,        // 卡id
     *      "courseType"    => 1,          // 1私课 2团课
     *      "amountMoney"  => '1000'       // 缴纳金额
     * }
     * @apiDescription ios支付接口<br/>
     * <span><strong>作    者：</strong></span>侯凯新<br>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br>
     * <span><strong>创建时间：</strong></span>2017/10/10<br>
     * <span><strong>调用方法：</strong></span>/v1/api-payment/ali-pay-sell-card
     *
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-payment/ali-pay-sell-card
     * @apiSuccess (返回值) {string} status 返回状态
     * @apiSuccess (返回值) {string} data   返回状态的数据
     *
     * @apiSuccessExample {json} 成功示例:
     *{
     * "code"  :１，
     * "status": "success",
     * "message": "成功"，
     * "data"   :{
     *  'appid'       => '123456457',
     *  'secret'      => 私钥,
     *  'service'     => 接口名称
     * ‘input_charset’ => 编码类型
     *   'subject'       =>商品标题
     *  'price'         =>  金额
     *  'out_trade_no'   => '12356423514241234',订单号
     *  'attach'   => '1',订单ID
     *  'body'        => '商品介绍',
     * ‘sign’      => 签名
     *  ‘sign_type’ => 签名算法类型
     * 'callback'    => 'http://qa.aixingfu.net/payment/notify',
     * }
     * }
     * @apiErrorExample {json} 错误示例:
     * {
     * "status": "error",
     * "message": "失败",
     * "data": {
     * "name": [
     * "请填写姓名"
     * ]
     * }
     * }
     */
    public function actionAliPaySellCard()
    {
        $post  = \Yii::$app->request->post();
        $model = new PaymentForm();
        if ($model->load($post, '') && $model->validate()) {
            if (!isset($post['orderId'])) {
                $data = $model->paymentCard();
            }
            if (isset($data->id) || isset($post['orderId'])) {
                $payment = new Payment();
                $ali     = new AopClient();
                if (isset($data->id)) {
                    $post['total_amount']   = $data->total_price;
                    $post['attach']         = $data->id;
                    $post['body']            = $data->id;
                    $post['out_trade_no']   = $data->order_number;
                } else {
                    $res = $model->getOrderInfo($post['orderId']);
                    $post['total_amount']   = $res['total_price'];
                    $post['attach']         = $res['id'];
                    $post['body']            = $res['id'];
                    $post['out_trade_no']   = $res['order_number'];
                }
                $venues                   = $payment->getAliPayConfig($post);
//                $paraFilter  = $payment->paraFilter($venues);
//                $paraSort    = $payment->argSort($paraFilter);
//                $preStr      = $payment->createLinkString($paraSort);
                $preStr  = $ali->getSignContent($venues);   
//                $sign        = $payment->createSign($preStr);
                $sign    = $ali->generateSign($venues,'RSA');
                //                $sign        = $ali->alonersaSign($preStr,'','RSA',true,2);
                $str     = $preStr.'&sign='.urlencode($sign);//传给支付宝接口的数据
                if (isset($data->id)) {
                    return ['code'=>'1','status' => 'success', 'message' => '成功','data'=>$str,'price'=>$data->total_price,'orderId'=>$data->id];
                } else {
                    $result = $model->updateAliPayMoneyMode($post['orderId'],$payMoneyMode = 2);
                    if (!$result) {
                        return ['code' => '0','status' => 'error', 'message' => '网络错误,请稍后重试!', 'data' => $result];
                    }
                    return ['code'=>'1','status' => 'success', 'message' => '成功','data'=>$str,'price'=>$res['total_price'],'orderId'=>$res['id']];
                }
            } else {
                if ($post['type'] == 'meal') {
                    return ['code'=>'0','status' => 'error', 'message' => $data['message'], 'data' => $data];
                } else {
                    return ['code'=>'0','status' => 'error', 'message' => '失败', 'data' => $data];
                }
            }
        } else {
            $result = ['status' => 'error', 'message' => '失败', 'data' => $model->errors];
        }
        return $result;
    }

    public function actionAliPayAppSellCard()
    {
        $post  = \Yii::$app->request->post();
        $model = new PaymentForm();
        if ($model->load($post, '') && $model->validate()) {
            $data = $model->paymentCard();
            if (isset($data->id)) {
                $payment = new Payment();
                $ali     = new MbAopClient();
                $post['total_amount']   = $data->total_price;
                $post['attach']         = $data->id;
                $post['body']            = $data->id;
                $post['out_trade_no']   = $data->order_number;
                $venues                   = $payment->getAliPayAppConfig($post);
//                $paraFilter  = $payment->paraFilter($venues);
//                $paraSort    = $payment->argSort($paraFilter);
//                $preStr      = $payment->createLinkString($paraSort);
                $preStr  = $ali->getSignContent($venues);
//                $sign        = $payment->createSign($preStr);
                $sign    = $ali->generateSign($venues,'RSA');
                //                $sign        = $ali->alonersaSign($preStr,'','RSA',true,2);
                $str     = $preStr.'&sign='.urlencode($sign);//传给支付宝接口的数据
                return ['code'=>'1','status' => 'success', 'message' => '成功','data'=>$str,'price'=>$data->total_price];
            } else {
                return ['code'=>'0','status' => 'error', 'message' => '失败', 'data' => $data];
            }
        } else {
            $result = ['status' => 'error', 'message' => '失败', 'data' => $model->errors];
        }
        return $result;
    }

    /**
     * @api {post} /v1/api-payment/ali-pay-thaw-card ios支付宝接口(罚金)
     * @apiVersion 1.0.0
     * @apiName ios支付宝接口(罚金)
     * @apiGroup api-payment
     * @apiPermission 管理员
     * @apiParam {int}    cardId        卡id
     * @apiParam {int}    courseType    类型  1代表私课 2代表团课
     * @apiParam {int}    amountMoney   金额
     * @apiParam {string} venueType     是否是迈步场馆 如果是：maiBu 不是:notMaiBu
     * @apiParamExample {json} Request Example
     * {
     *      "cardId"        => 1,
     *       "amountMoney"  => '1000'
     *      "paymentType"   => "1",
     *       "venueType"    => "maiBu"    // 场馆类型 是迈步场馆"maiBu"  不是迈步 "notMaiBu"
     * }
     * @apiDescription ios支付接口<br/>
     * <span><strong>作    者：</strong></span>侯凯新<br>
     * <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br>
     * <span><strong>创建时间：</strong></span>2017/6/6<br>
     * <span><strong>调用方法：</strong></span>/v1/api-payment/ali-pay-thaw-card
     *
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-payment/ali-pay-thaw-card
     * @apiSuccess (返回值) {string} status 返回状态
     * @apiSuccess (返回值) {string} data   返回状态的数据
     *
     * @apiSuccessExample {json} 成功示例:
     *{
     * "code"  :１，
     * "status": "success",
     * "message": "成功"，
     * "data"   :{
     *  'appid'       => '123456457',
     *  'secret'      => 私钥,
     *  'service'     => 接口名称
     * ‘input_charset’ => 编码类型
     *   'subject'       =>商品标题
     *  'price'         =>  金额
     *  'out_trade_no'   => '12356423514241234',订单号
     *  'attach'   => '1',订单ID
     *  'body'        => '商品介绍',
     * ‘sign’      => 签名
     *  ‘sign_type’ => 签名算法类型
     * 'callback'    => 'http://qa.aixingfu.net/payment/notify',
     * }
     * }
     * @apiErrorExample {json} 错误示例:
     * {
     * "status": "error",
     * "message": "失败",
     * "data": {
     * "name": [
     * "请填写姓名"
     * ]
     * }
     * }
     */
    public function actionAliPayThawCard(){
        $post  = \Yii::$app->request->post();
        $model = new ThawPaymentForm();
        $scenario = ($post["courseType"]==1)?"groupThaw":"private";
        $model->setScenario($scenario);
        if ($model->load($post, '') && $model->validate()) {
            $data = $model->thawPayment("zfb");
            if($data["result"]===true){
                $payment = new Payment();
                $ali     =  (isset($post["venueType"])&&($post["venueType"]=="maiBu"))?new MbAopClient():new AopClient();
                $post['total_amount']   = $data["data"]->total_price;
                $post['attach']         = $data["data"]->id;
                $post['body']            = $data["data"]->id;
                $post['out_trade_no']   = $data["data"]->order_number;
                $venues  = (isset($post["venueType"])&&($post["venueType"]=="maiBu"))?$payment->getMbAliPayThawConfig($post):$payment->getAliPayThawConfig($post);
                $preStr  = $ali->getSignContent($venues);
                $sign    = $ali->generateSign($venues,'RSA');
                $str     = $preStr.'&sign='.urlencode($sign);//传给支付宝接口的数据
                return ['code'=>'1','status' => 'success', 'message' => '成功','data'=>$str,'price'=>$data["data"]->total_price];
            } else {
                return ['code'=>'0','status' => 'error', 'message' => '失败', 'data' => $data];
            }
        }else {
            $result = ['status' => 'error', 'message' => '失败', 'data' => $model->errors];
        }
        return $result;
    }
    /**
     * 业务后台 - 支付宝 - 回调地址
     * @return string
     */
    public function actionAliPayNotify()
    {
        \Yii::info(\Yii::$app->request->post(), 'notify');
        $aop           = new AopClient;
        $flag          = $aop->rsaCheckV1(\Yii::$app->request->post(), NULL, "RSA");
        $success       = strtolower('SUCCESS');
        $out_trade_no = $_POST['out_trade_no'];
        $order = Order::findOne(['order_number'=>$out_trade_no]);
        $pay_params = json_decode($order->pay_params,true);
        $pay_params["notify_params"] = $_POST;
        $params = json_encode($pay_params);
        $secretKey = 'xingfufit';
        $pay_params = json_encode(base64_encode(\Yii::$app->security->encryptByPassword($params,$secretKey)));
        Order::updateAll(['pay_params' => $pay_params,'merchant_order_number' => $_POST['trade_no']],['id' => $order->id]);
        if($flag&&($_POST["subject"]=="会员卡解冻")){
            $out_trade_no = $_POST['out_trade_no'];
            $trade_status = $_POST['trade_status'];
            $order = Order::findOne(['order_number'=>$out_trade_no]);
            if($trade_status == 'TRADE_FINISHED') {
                $payment = new PaymentCardForm();
                $pay     = $payment->thawMemberCard($order->id);
                $payment->sendMessage();
                if($pay === 'SUCCESS'){
                    echo $success;
                    exit;
                }
            }
            else if ($trade_status == 'TRADE_SUCCESS') {
                $payment = new PaymentCardForm();
                $pay     = $payment->thawMemberCard($order->id);
                $payment->sendMessage();
                if($pay === 'SUCCESS'){
                    echo $success;
                    exit;
                }
            }
            echo $success;		//请不要修改或删除
            exit;
        }
     //   file_put_contents('log.txt',"...\n".$flag."....\n",FILE_APPEND);
        if($flag&&($_POST["subject"]!="会员卡解冻")) {
            $out_trade_no = $_POST['out_trade_no'];
            $trade_status = $_POST['trade_status'];
            $body         = $_POST['body'];
      //      file_put_contents('log.txt',"...\n".$body."....\n",FILE_APPEND);
            $order = Order::findOne(['merchant_order_number'=>$out_trade_no]);
            if(!empty($order)){
                echo $success;
                exit;
            }
            if($trade_status == 'TRADE_FINISHED') {
                $payment = new PaymentCardForm();
                $pay     = $payment->setMemberCardInfo($body,$out_trade_no);
                $payment->sendMessage();
                if($pay === 'SUCCESS'){
                    echo $success;
                    exit;
                }
            }
            else if ($trade_status == 'TRADE_SUCCESS') {
                $payment = new PaymentCardForm();
                $pay     = $payment->setMemberCardInfo($body,$out_trade_no);
                $payment->sendMessage();
                if($pay === 'SUCCESS'){
                    echo $success;
                    exit;
                }
            }
            echo $success;		//请不要修改或删除
        }
        else {
            //验证失败
            echo "FAIL";
        }
    }



    /**
     * 业务后台 - 支付宝 - 回调地址(迈步回调地址)
     * @return string
     */
    public function actionAliPayAppNotify()
    {
        $aop           = new AopClient;
        $flag          = $aop->rsaCheckV1(\Yii::$app->request->post(), NULL, "RSA");
        $success       = strtolower('SUCCESS');
        if($flag&&($_POST["subject"]=="会员卡解冻")){
            $out_trade_no = $_POST['out_trade_no'];
            $trade_status = $_POST['trade_status'];
            $order = Order::findOne(['order_number'=>$out_trade_no]);
            if($trade_status == 'TRADE_FINISHED') {
                $payment = new PaymentCardForm();
                $pay     = $payment->thawMemberCard($order->id);
                $payment->sendMessage();
                if($pay === 'SUCCESS'){
                    echo $success;
                    exit;
                }
            }
            else if ($trade_status == 'TRADE_SUCCESS') {
                $payment = new PaymentCardForm();
                $pay     = $payment->thawMemberCard($order->id);
                $payment->sendMessage();
                if($pay === 'SUCCESS'){
                    echo $success;
                    exit;
                }
            }
            echo $success;		//请不要修改或删除
            exit;
        }
        //   file_put_contents('log.txt',"...\n".$flag."....\n",FILE_APPEND);
        if($flag&&($_POST["subject"]!="会员卡解冻")) {
            $out_trade_no = $_POST['out_trade_no'];
            $trade_status = $_POST['trade_status'];
            $body         = $_POST['body'];
            //      file_put_contents('log.txt',"...\n".$body."....\n",FILE_APPEND);
            $order = Order::findOne(['merchant_order_number'=>$out_trade_no]);
            if(!empty($order)){
                return $success;
            }
            if($trade_status == 'TRADE_FINISHED') {
                $payment = new PaymentCardForm();
                $pay     = $payment->setMemberCardInfo($body,$out_trade_no);
                $payment->sendMessage();
                if($pay === 'SUCCESS'){
                    echo $success;
                }
            }
            else if ($trade_status == 'TRADE_SUCCESS') {
                $payment = new PaymentCardForm();
                $pay     = $payment->setMemberCardInfo($body,$out_trade_no);
                $payment->sendMessage();
                if($pay === 'SUCCESS'){
                    echo $success;
                }
            }
            echo $success;		//请不要修改或删除
        }
        else {
            //验证失败
            echo "FAIL";
        }
    }



    /**
     * 业务后台 - 支付宝 - 支付宝购买柜子
     * @return string
     */
    public function actionAliCabinetOrderNotify()
    {
        $aop           = new AopClient;
        $flag          = $aop->rsaCheckV1(\Yii::$app->request->post(), NULL, "RSA");
        $success       = strtolower('SUCCESS');
//        file_put_contents('log.txt',"...\n".$flag."....\n",FILE_APPEND);

        if($flag){
//            file_put_contents('log.txt',"...\n".$_POST['trade_status']."....\n",FILE_APPEND);
            $out_trade_no = $_POST['out_trade_no'];
            $trade_status = $_POST['trade_status'];
            $order = Order::findOne(['order_number'=>$out_trade_no]);
            if($trade_status == 'TRADE_FINISHED') {
                $payment = new CabinetRentForm();
                $pay     = $payment->rentCabinetLogic($order->id);
                if($pay === 'SUCCESS'){
                    echo $success;
                    exit;
                }
            }
            else if ($trade_status == 'TRADE_SUCCESS') {
//                file_put_contents('log.txt',"...\n".$flag."....\n",FILE_APPEND);
                $payment = new CabinetRentForm();
                $pay     = $payment->rentCabinetLogic($order->id);
                if($pay === 'SUCCESS'){
                    echo $success;
                    exit;
                }
            }
            echo $success;
            exit;
        }else{
//            file_put_contents('log.txt',"...\n".$_POST."....\n",FILE_APPEND);
            echo $success;		//请不要修改或删除
            exit;
        }
    }

    /**
     * 业务后台 - 支付宝 - 回调地址
     * @return string
     */
    public function actionNotify()
    {
        \Yii::info(file_get_contents('php://input'), 'notify');
        $notify = new WxNotifyVerify();
        $res    = $notify->getNotifySignData();
        echo  $res;
    }

    /**
     * 业务后台 - 支付宝 - 回调地址
     * @return string
     */
    public function actionSetCharge($id)
    {
        $payment = new PaymentCardForm();
        $pay     = $payment->setMemberCardInfo($id);
        var_dump($pay);exit();
    }


    /**
     * 业务后台 - 微信 - 微信支付业务回调逻辑
     * @return string
     */
    public function actionCabinetRentNotify(){
        $notify = new WxNotifyVerify();
        $res    = $notify->CabinetRent();
        echo  $res;
    }




    /**
     * @api {get} /v1/api-payment/text-notify  柜子回调业务测试
     * @apiVersion 1.0.0
     * @apiName  柜子回调接口测试
     * @apiGroup cabinet
     * @apiPermission 管理员
     * @apiParam {int}    orderId
     * @apiParamExample {json} Request Example
     * {
     *      "orderId"        => 5567,   //订单id
     * }
     * @apiDescription 柜子回调业务测试
     * <span><strong>作    者：</strong></span>侯凯新<br>
     * <span><strong>邮    箱：</strong></span>houkaixn@itsports.club<br>
     * <span><strong>创建时间：</strong></span>2018/01/09<br>
     * <span><strong>调用方法：</strong></span>/v1/api-payment/text-notify
     *
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-payment/text-notify
     * @apiSuccess (返回值) {string} status 返回状态
     * @apiSuccess (返回值) {string} data   返回状态的数据
     *
     * @apiSuccessExample {json} 成功示例:
     *{
     * "code"  :１，
     * "status": "success",
     * "message": "成功"，
     * "data"   :{
     *  'appid'       => '123456457',
     *  'secret'      => 私钥,
     *  'service'     => 接口名称
     * ‘input_charset’ => 编码类型
     *   'subject'       =>商品标题
     *  'price'         =>  金额
     *  'out_trade_no'   => '12356423514241234',订单号
     *  'attach'   => '1',订单ID
     *  'body'        => '商品介绍',
     * ‘sign’      => 签名
     *  ‘sign_type’ => 签名算法类型
     * 'callback'    => 'http://qa.aixingfu.net/payment/notify',
     * }
     * }
     * @apiErrorExample {json} 错误示例:
     * {
     * "status": "error",
     * "message": "失败",
     * "data": {
     * "name": [
     * "请填写姓名"
     * ]
     * }
     * }
     */
    public function actionTextNotify($orderId){
        $payment = new CabinetRentForm();
        $pay     = $payment->rentCabinetLogic($orderId);
        return json_encode(["status"=>$pay]);
    }

    /**
     * @param $pid
     * @param $app_id
     * @param $target_id
     * @return array
     * 张宇写的
     */
    public function aliAuthInfo($pid,$app_id,$target_id){
        $array = [
            'app_id'=>$app_id,
            'pid'=>$pid,
            'apiname'=>'com.alipay.account.auth',
            'app_name'=>'mc',
            'biz_type'=>'openservice',
            'product_id'=>'APP_FAST_LOGIN',
            'scope'=>'kuaijie',
            'target_id'=>$target_id,
            'auth_type'=>'AUTHACCOUNT',
            'sign_type'=>'RSA',
        ];
      return $array;
     }

    /**
     * @return bool|mixed|\SimpleXMLElement|string
     * @throws \yii\base\Exception
     * 支付宝快捷登录
     */
    public function actionAllogin(){
        $venues = $this->aliAuthInfo('2088621615019481','2017070707675504',\Yii::$app->security->generateRandomString());
        $ali     = new AopClient();
        $preStr  = $ali->getSignContent($venues);
        $sign    = $ali->generateSign($venues,'RSA');
        $str     = $preStr.'&sign='.urlencode($sign);//传给支付宝接口的数据
        return ['code'=>1,'str'=>$str];
    }
    public function actionMallogin(){
        $venues = $this->aliAuthInfo('2088821094741420','2017091108675479',\Yii::$app->security->generateRandomString());
        $ali    =  new MbAopClient();
        $preStr  = $ali->getSignContent($venues);
        $sign    = $ali->generateSign($venues,'RSA');
        $str     = $preStr.'&sign='.urlencode($sign);//传给支付宝接口的数据
        return ['code'=>1,'str'=>$str];
    }
    






}
