<?php

namespace backend\modules\v1\controllers;

use backend\modules\v1\models\CardCategory;
use backend\modules\v1\models\Member;
use backend\modules\v1\models\MemberDealRecord;
use common\models\CardCategoryType;
use common\models\MemberDetails;
use kartik\mpdf\Pdf;
use Yii;
use yii\web\Controller;
use common\models\Func;

/**
 * Default controller for the `v1` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionOrderDeal($id)
    {
        $model = \backend\modules\v1\models\OrderDeal::findOne($id);
        $name = MemberDetails::findOne(['member_id'=>$model->member_id])->name; 
        $mobile = Member::find()->where(['id'=>$model->member_id])->select('mobile')->asArray()->all()[0]['mobile'];
        $card_name = CardCategory::find()->where(['card_name'=>$model->product_name])->select('card_type')->asArray()->all();
        $card_name =  isset($card_name[0]['card_type']) ? $card_name[0]['card_type'] :'';
        $deal_name = Func::getRelationVal($model, 'deal', 'name');
        if ($card_name == 1) $card_name ='瑜伽';
        if ($card_name == 2) $card_name ='健身';
        if ($card_name == 3) $card_name ='舞蹈';
        if ($card_name == 4) $card_name ='综合';
        if ($card_name == 5) $card_name ='体验';
        $content = $this->render('order-deal', ['model'=>$model,'mobile'=>$mobile,'card_name'=>$card_name,'name'=>$name]);
        if(Yii::$app->request->get('pdf',0)){
            $pdf = new Pdf([
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'options' => [
                    'title' => '中文',
                    'autoLangToFont' => true,
                    'autoScriptToLang' => true,
                    'autoVietnamese' => true,
                    'autoArabic' => true,
                ],
            ]);
            $mpdf = $pdf->api;
            $mpdf->WriteHtml($content);
            echo $mpdf->Output("{$deal_name}_{$model->order_number}.pdf", 'D');
        }
        return $content;
    }
    /**
     * @api {get} /v1/default/member-deal-record-details  会员购买协议详情
     * @apiName        会员购买协议详情
     * @apiGroup       default
     * @apiParam  {int}            memberDealRecordId               会员合同记录ID
     * @apiDescription   会员购买协议详情
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsports.club
     * <span><strong>创建时间：</strong></span>2018/07/16
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/default/member-deal-record-details
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
    "message": "",
    "code": 1,
    "status": 200,
    "data": {
    "id": "100795", //协议ID
    "name": "通用版私教协议",//协议名称
    "intro": "<p class="MsoNormal" align="center" style="text-align:center;mso-line-height-alt:12pt;"><b><span style="font-family: 宋体; color: rgb(0, 0, 0); font-size: 15pt;"><font face="宋体">聘请私人教练服务合约</font></span></b><b><span style="font-family: 宋体; color: rgb(0, 0, 0); font-size: 15pt;"><o:p></o:p></span></b></p><p c",//协议内容
    }
    }
    }
     * @apiSuccessExample {json}返回值详情（失败）
    {
    "message": "",
    "code": 0,
    "status": 422,
    "data": []
    }
     */
    public function actionMemberDealRecordDetails($memberDealRecordId)
    {
        $model = new MemberDealRecord();
        $arr = $model->getMemberDealRecordDetails($memberDealRecordId);
        $content = $this->render('member-deal-record-details', ['arr'=>$arr]);
        if(Yii::$app->request->get('pdf',0)){
            $pdf = new Pdf([
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'options' => [
                    'title' => '中文',
                    'autoLangToFont' => true,
                    'autoScriptToLang' => true,
                    'autoVietnamese' => true,
                    'autoArabic' => true,
                ],
            ]);
            $mpdf = $pdf->api;
            $mpdf->WriteHtml($content);
            echo $mpdf->Output("{$arr['name']}_{$arr['order_number']}.pdf", 'D');
        }
        return $content;
    }
}
