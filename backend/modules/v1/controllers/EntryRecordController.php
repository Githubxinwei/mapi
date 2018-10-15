<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/11
 * Time: 16:48
 * Desc: 进馆记录
 */
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\EntryRecord;

class EntryRecordController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\EntryRecord';
    /**
     * @api {get} /v1/entry-record/get-sports-days  获取运动天数
     * @apiName        获取运动天数
     * @apiGroup       EntryRecord
     * @apiParam  {string}            accountId             账户ID
     * @apiDescription   获取运动天数
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsports.club
     * <span><strong>创建时间：</strong></span>2018/06/11
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/entry-record/get-sports-days
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
     *  {
    "message": "",
    "code": 1,
    "status": 200,
    "data": {
    "enrtyTime": [
    {
    "entry_time": "1527752782"
    },
    {
    "entry_time": "1528097360"
    },
    {
    "entry_time": "1528702483"
    },
    {
    "entry_time": "1528767534"
    }
    ],
    "persistenceDays": 4,
    "sustainedDays": 2
    }
     * @apiSuccessExample {json}返回值详情（失败）
    {
    "message": "",
    "code": 0,
    "status": 422,
    "data": NULL
    }
     */
    public function actionGetSportsDays($accountId)
    {
        $model = new EntryRecord();
        $data = $model->getMotionDays($accountId);
        if ($data) {
            return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data];
        } else {
            return ['code' => 0, 'status' => 'error', 'message' => '没有数据','data' => NULL];
        }
    }
}