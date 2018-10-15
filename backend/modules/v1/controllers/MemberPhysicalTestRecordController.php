<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/25
 * Time: 15:00
 * Desc:体侧信息预约记录
 */
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\MemberPhysicalTestRecord;

use Yii;

class MemberPhysicalTestRecordController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\MemberPhysicalTestRecord';
    /**
     * @api {post} /v1/member-physical-test-record/set-member-physical-test  预约体测
     * @apiName        预约体测
     * @apiGroup       member-physical-test
     * @apiParam  {int}             memberId             会员ID
     * @apiDescription   预约体测
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsports.club
     * <span><strong>创建时间：</strong></span>2018/06/25
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/member-physical-test/set-member-physical-test
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
    "code": 1,
    "status": success,
    "message": "预约成功！",
    }
     * @apiSuccessExample {json}返回值详情（失败）
    {
     "code": 0,
    "status": error,
    "message": "网络错误，请稍后重试！",
    }
     */
    public function actionSetMemberPhysicalTest()
    {
        $post = \Yii::$app->request->post();
        $model = new MemberPhysicalTestRecord();
        if ($model->load($post, '')) {
            $data = $model->setMemberPhysicalTest();
            return $data;
        }
    }
    /**
     * @api {get} /v1/member-physical-test-record/get-member-physical-test-record  查询会员最近一个月是否有体测
     * @apiName        查询会员最近一个月是否有体测
     * @apiGroup       member-physical-test
     * @apiParam  {int}             accountId             账户ID
     * @apiDescription   查询会员最近一个月是否有体测
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsports.club
     * <span><strong>创建时间：</strong></span>2018/06/25
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/member-physical-test/get-member-physical-test-record
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
    "code": 1,
    "status": "success",
    "message": "会员最近一个月有体测！"
    }
     * @apiSuccessExample {json}返回值详情（失败）
    {
    "code": 0,
    "status": "error",
    "message": "会员最近一个月无体测！"
    }
     */
    public function actionGetMemberPhysicalTestRecord()
    {
        $accountId   = \Yii::$app->request->get('accountId','0');
        $model = new MemberPhysicalTestRecord();
        $data = $model->getMemberPhysicalTestRecord($accountId);
        if ($data) {
            return ['code' => 1, 'status' => 'success', 'message' => '会员最近一个月有体测！'];
        } else {
            return ['code' => 0, 'status' => 'error', 'message' => '会员最近一个月无体测！'];
        }
    }
}