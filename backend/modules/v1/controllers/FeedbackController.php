<?php
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\MemberCard;
use common\models\base\Member;
use Yii;
use backend\modules\v1\models\Feedback;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class FeedbackController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\Feedback';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'],$actions['delete']);
        return $actions;
    }

    /**
     * @api {post} /v1/feedback/create  提交反馈内容
     * @apiName        2提交反馈内容
     * @apiGroup       feedback
     * @apiParam  {string}            type_id              类型ID，必填
     * @apiParam  {string}            from                 来源，必填，可选项（android_customer->安卓会员端,ios_customer->IOS会员端）
     * @apiParam  {string}            venue_id             场馆ID，必填
     * @apiParam  {string}            user_id              会员ID，必填
     * @apiParam  {string}            content              内容，必填
     * @apiParam  {string}            occurred_at          故障发生时间，时间戳，到秒，可选
     * @apiParam  {file}              pics                 图片文件，数组形式传递, pics[]
     * @apiDescription   提交反馈内容
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/02/06
     * @apiSampleRequest  http://qa.aixingfu.net/v1/feedback/create
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
        "message": "",
        "code": 1,
        "status": 201,
        "data": {
            "type_id": "1",
            "from": "android_customer",
            "venue_id": "76",
            "user_id": "100",
            "content": "app",
            "company_id": 1,
            "created_at": 1517907110,
            "updated_at": 1517907110,
            "id": 2
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

    public function actionCreate()
    {
        $model = new Feedback();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if(isset($_FILES['pics'])) $model->pics = UploadedFile::getInstancesByName('pics');
        $uid =  MemberCard::find()->alias('md')->select('m.id ')->andWhere(['mc.id'=>null])->joinWith('member m')->where(['m.member_account_id'=>$model->user_id])->one();
        if (empty($uid)){
            $uid =  Member::find()->alias('m')->select('m.id ')->where(['m.member_account_id'=>$model->user_id])->One();
            $model->user_id =(int)$uid['id'];
        }else{
            $model->user_id =(int)$uid['id'];
        }
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }
    /**
     * @api {post} /v1/feedback/submit  反馈内容提交
     * @apiName        反馈内容提交
     * @apiGroup       feedBackSubmit
     * @apiParam  {string}            type_id              类型ID，必填默认为2
     * @apiParam  {string}            from                 来源，必填，可选项（android_customer->安卓会员端,ios_customer->IOS会员端）
     * @apiParam  {string}            venue_id             场馆ID，必填
     * @apiParam  {string}            user_id              会员ID，必填
     * @apiParam  {string}            tags                 标签
     * @apiParam  {string}            content              内容，必填
     * @apiParam  {file}              pics                 图片文件，数组形式传递, pics[]
     * @apiDescription   提交反馈内容
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsports.club
     * <span><strong>创建时间：</strong></span>2018/05/31
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/feedback/submit
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
    "message": "",
    "code": 1,
    "status": 201,
    "data": {
    ""id": 101,
    "type_id": null,
    "from": "ios_customer",
    "company_id": 1,
    "venue_id": "59",
    "user_id": "71030",
    "content": "adafa",
    "occurred_at": null,
    "created_at": "05月31日 10:51",
    "updated_at": 1527735116,
    "pics": null,
    "reply_time": "01月01日 08:00",
    "reply_content": null,
    "reply_person": null,
    "is_read": null
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

    public function actionSubmit()
    {
        $model = new Feedback();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if(isset($_FILES['pics'])) $model->pics = UploadedFile::getInstancesByName('pics');
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }
    /**
     * @api {get} /v1/feedback/list  投诉反馈列表
     * @apiName        投诉反馈列表
     * @apiGroup       feedback
     * @apiParam  {string}            accountId              账户ID
     * @apiDescription   提交反馈内容
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/02/06
     * @apiSampleRequest  http://qa.aixingfu.net/v1/feedback/create
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
    "message": "",
    "code": 1,
    "status": 201,
    "data": {
    "id": 68,
    "type_id": 4,
    "from": "android_customer",
    "company_id": 1,
    "venue_id": 59,
    "user_id": 99013,
    "content": "是",
    "occurred_at": "0",
    "created_at": "1524043410",
    "updated_at": "1524043410",
    "pics": null,
    "reply_time": null,   //回复时间
    "reply_content": "1212", //回复内容
    "reply_person": null   //回复内容
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

    public function actionList($accountId){
        $memberId = array_column(Member::find()->where(['member_account_id'=>$accountId])->all(),'id');
        $query = Feedback::find()->where(['user_id'=>$memberId])->andWhere(['NOT',['reply_time'=>null]])->orderBy('is_read ASC,created_at desc, id desc');
        return new ActiveDataProvider(['query'=>$query]);
    }

    /**
     * @api {get} /v1/feedback/list  详情投诉反馈
     * @apiName        详情投诉反馈
     * @apiGroup       feedback
     * @apiParam  {string}            id              投诉ID
     * @apiDescription   提交反馈内容
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/02/06
     * @apiSampleRequest  http://qa.aixingfu.net/v1/feedback/create
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
    "message": "",
    "code": 1,
    "status": 201,
    "data": {
    "id": 68,
    "type_id": 4,
    "from": "android_customer",
    "company_id": 1,
    "venue_id": 59,
    "user_id": 99013,
    "content": "是",
    "occurred_at": "0",
    "created_at": "1524043410",
    "updated_at": "1524043410",
    "pics": null,
    "reply_time": null,   //回复时间
    "reply_content": "1212", //回复内容
    "reply_person": null   //回复内容
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
    public function actionDetails($id){
        $feedback = Feedback::findOne($id);
        $feedback->is_read =1;
        $feedback->save();
        return $feedback;
    }

}
