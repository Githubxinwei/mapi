<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/16
 * Time: 9:34
 * Desc:会员合同记录
 */
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\MemberDealRecord;
use Yii;
use yii\data\ActiveDataProvider;

class MemberDealRecordController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\MemberDealRecord';
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'],$actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }
    /**
     * @api {get} /v1/member-deal-record 会员购买协议列表
     * @apiName        会员购买协议详情
     * @apiGroup       deal
     * @apiParam  {int}            accountId             账户id
     * @apiDescription   会员购买协议详情
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsports.club
     * <span><strong>创建时间：</strong></span>2018/07/16
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/member-deal-record
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
    "message": "",
    "code": 1,
    "status": 200,
    "data": {
    "items": [
    {
    "id": "5696",
    "deal_number": "sp153077851272195",
    "create_at": "1530778512"
    },
    {
    "id": "5747",
    "deal_number": "sp153153738346498",
    "create_at": "1531537383"
    },
    {
    "id": "5748",
    "deal_number": "sp153170395589536",
    "create_at": "1531703955"
    }
    ],
    "_links": {
    "self": {
    "href": "http://192.168.6.199/v1/member-deal-record/index?accountId=72125&page=1"
    }
    },
    "_meta": {
    "totalCount": 3,
    "pageCount": 1,
    "currentPage": 1,
    "perPage": 20
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
    public function prepareDataProvider()
    {
        $accountId = Yii::$app->request->get('accountId', 0);
        $model = new MemberDealRecord();
        $query = $model->getMemberDealRecord($accountId);
        if ($query) {
            return new ActiveDataProvider(['query' => $query]);
        }
        return '';
    }
}