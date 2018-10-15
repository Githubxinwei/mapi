<?php

namespace backend\modules\v1\controllers;

use backend\modules\v1\models\Member;
use backend\modules\v1\models\MemberCard;
use common\models\Func;
use yii\rest\ActiveController;
use dosamigos\qrcode\QrCode;
use yii\web\Response;

class ApiQrCodeController extends ActiveController
{
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
    /**
     * @api {get} /v1/api-qr-code/qr-code   设置二维码
     * @apiVersion  1.0.0
     * @apiName        设置二维码
     * @apiGroup       qrCode
     * @apiPermission 管理员
     * @apiParam  {int}           memberId          会员id
     * @apiParam  {string}        requestType       请求类型（ios）
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-qr-code/qr-code
     *   {
     *        "memberId":1,           // 会员id
     *        "requestType":"ios"     //请求类型是ios
     *   }
     * @apiDescription   设置二维码
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/17
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-qr-code/qr-code
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     *  "code": 1,                  //成功标识
     *  "status": "success",        //成功状态
     *  "message": "请求成功",      //成功信息
     *  "data": {
     *    "id": "107",              //会员id
     *    "username": "zhangsan",   //会员姓名
     *    "pic": null,              //会员图片
     *    "img": "<img src=\"http://qa.uniwlan.com/v1/api-qr-code/render-html?memberId=107\" style=\"display:block;width: 100%;margin: 0 auto;\">" //二维码
     *  }
     * }
     */
    public function actionQrCode($memberId = 9,$requestType = '')
    {
        $member     = new Member();
        $detail     = $member->getMemberDetail($memberId);
        if(Func::autoGeneration()){
            $img        = 'http://qa.aixingfu.net/v1/api-qr-code/render-html?memberId='.$memberId;
        }else{
            $img        = 'http://product.aixingfu.net/v1/api-qr-code/render-html?memberId='.$memberId;
        }
        $detail['img'] = $this->renderPartial('index',['memberId'=>$img]);
        if($requestType == 'ios')
        {
            return ['code'=>1,'status'=>'success','message'=>'请求成功','data'=>$detail];
        }else{
            return $detail;
        }
    }
    public function actionRenderHtml($memberId)
    {
        $memberCard = new MemberCard();
        $card       = $memberCard->getMemberCardNum($memberId);
        $text       = isset($card['card_number'])?$card['card_number']:'error';
        return QrCode::png($text);    //调用二维码生成方法
    }
}
