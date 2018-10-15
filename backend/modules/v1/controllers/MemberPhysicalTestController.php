<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/25
 * Time: 12:01
 * Desc:体侧信息
 */
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\MemberPhysicalTest;

use Yii;

class MemberPhysicalTestController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\MemberPhysicalTest';
    /**
     * @api {post} /v1/member-physical-test/get-member-physical-test-info  会员体测信息
     * @apiName        会员体测信息
     * @apiGroup       member-physical-test
     * @apiParam  {int}             accountId             账户ID
     * @apiParam  {int}             type                 类型(1 体测, 2 体适能)
     * @apiParam  {datetime}        testDate            体测日期(2018-06-27)
     * @apiDescription   会员体测信息
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsports.club
     * <span><strong>创建时间：</strong></span>2018/06/25
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/member-physical-test/get-member-physical-test-info
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
        "code": 1,
        "status": "success",
        "message": "请求成功！",
        "data": {
        "chart": [
        {
        "id": "2",
        "pid": "0",
        "title": "内脏脂肪等级",
        "unit": "级",
        "type": "1",
        "physical_value": "50",
        "create_at": "2018-07-05"
        },
        {
        "id": "1",
        "pid": "0",
        "title": "健康评估",
        "unit": "分",
        "type": "1",
        "physical_value": "22",
        "create_at": "2018-07-05"
        }
        ],
        "ptTitle": [
        "身体成分"
        ],
        "physicalValue": [
        {
        "id": "3",
        "pid": "0",
        "title": "身体成分",
        "unit": "kg",
        "type": "0",
        "physical_value": "0",
        "create_at": "2018-07-05",
        "data": [
        {
        "id": "4",
        "pid": "3",
        "title": "体重",
        "unit": "kg",
        "type": "0",
        "physical_value": "50",
        "create_at": "2018-07-05"
        },
        {
        "id": "5",
        "pid": "3",
        "title": "骨骼肌",
        "unit": "kg",
        "type": "0",
        "physical_value": "21",
        "create_at": "2018-07-05"
        }
        ]
        }
        ],
        "testDate": "2018-07-05"
        }
    }
     * @apiSuccessExample {json}返回值详情（失败）
    {
        "message": "",
        "code": 0,
        "status": error,
        "data": []
    }
     */
    public function actionGetMemberPhysicalTestInfo()
    {
        $post = \Yii::$app->request->post();
        $model = new MemberPhysicalTest();
        if ($model->load($post, '') && $model->validate()) {
            $data = $model->getMemberPhysicalTestInfo();
            if ($data) {
                return ['code' => 1, 'status' => 'success', 'message' => '请求成功！','data' => $data];
            } else {
                return ['code' => 0, 'status' => 'error', 'message' => '暂无数据！','data' => NULL];
            }
        }
    }
    /**
     * @api {post} /v1/member-physical-test/get-member-physical-test-date  获取会员体测日期
     * @apiName        获取会员体测日期
     * @apiGroup       member-physical-test
     * @apiParam  {int}             accountId             账户ID
     * @apiDescription   获取会员体测日期
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsports.club
     * <span><strong>创建时间：</strong></span>2018/06/27
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/member-physical-test/get-member-physical-test-date
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
    "code": 1,
    "status": "success",
    "message": "请求成功！",
    "data": [
    {
    "create_at": "2018-06-25"
    }
    ]
    }
     * @apiSuccessExample {json}返回值详情（失败）
    {
    "code": 0,
    "status": "error",
    "message": "暂无数据！",
    "data": []
    }
     */
    public function actionGetMemberPhysicalTestDate()
    {
        $post = \Yii::$app->request->post();
        $model = new MemberPhysicalTest();
        if ($model->load($post, '')) {
            $data = $model->getMemberPhysicalTestDate();
            if ($data) {
                return ['code' => 1, 'status' => 'success', 'message' => '请求成功！','data' => $data];
            } else {
                return ['code' => 0, 'status' => 'error', 'message' => '暂无数据！','data' => []];
            }
        }
    }
}