<?php
namespace backend\modules\v1\controllers;

use common\models\FeedbackType;
use Yii;
use yii\data\ActiveDataProvider;

class FeedbackTypeController extends BaseController
{
    public $modelClass = 'common\models\FeedbackType';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'],$actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    /**
     * @api {get} /v1/feedback-type  反馈类型列表
     * @apiName        1反馈类型列表
     * @apiGroup       feedback
     * @apiParam  {string}            pid                   父ID,默认0
     * @apiParam  {string}            fields                可选,选择显示字段(例:fields=id,type_name,name,create_name,create_at)
     * @apiParam  {string}            page                  页码（可选，默认1）
     * @apiParam  {string}            per-page              每页显示数（可选，默认20）
     * @apiDescription   反馈类型列表
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/02/06
     * @apiSampleRequest  http://qa.aixingfu.net/v1/feedback-type
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
        "message": "",
        "code": 1,
        "status": 200,
        "data": {
            "items": [{
                "id": 1,
                "name": "场馆投诉",
                "pid": 0,
                "child": 0,//是否有子ID
                "do": 1//0进入子分类页面1进入提交内容页面
            }, {
                "id": 2,
                "name": "APP反馈",
                "pid": 0,
                "child": 1,
                "do": 0
            }],
            "_links": {
                "self": {
                    "href": "http://127.0.0.2/v1/feedback-type/index?page=1"
                }
            },
            "_meta": {
                "totalCount": 2,
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
        $pid = Yii::$app->request->get('pid', 0);

        $query = FeedbackType::find()->where(['pid'=>$pid]);

        $query->orderBy('id asc');
        return new ActiveDataProvider(['query' => $query]);
    }
    /**
     * @api {get} /v1/feedback-type/feed-back-type-list  APP反馈类型列表
     * @apiName        APP反馈类型列表
     * @apiGroup       feedbacklist
     * @apiDescription   反馈类型列表
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsports.club
     * <span><strong>创建时间：</strong></span>2018/05/30
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/feedback-type
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
    "message": "请求成功！",
    "code": 1,
    "status": 200,
    "data": [
    "闪退、卡顿",
    "请求服务异常",
    "账号",
    "其他",
    "闪退",
    "卡顿",
    "黑屏",
    "死机",
    "页面错位"
    ]
    }
     * @apiSuccessExample {json}返回值详情（失败）
    {
    "message": "",
    "code": 0,
    "status": 422,
    "data": NULL;
    }
     */
    public function actionFeedBackTypeList()
    {
        $query = FeedbackType::find()
            ->where(['pid' => [2,3]])
            ->andWhere(['<>','name','闪退、卡顿'])
            ->select('name')
            ->distinct()
            ->asArray()
            ->all();
        if ($query) {
            $tmpArr = [];
            for ($i = 1; $i < count($query); $i++){
                for ($n = 0; $n < count($query)-1; $n++){
                    if(mb_strlen($query[$n]['name']) > mb_strlen($query[$n+1]['name'])){
                        $tmpArr[0] = $query[$n];
                        $query[$n] = $query[$n+1];
                        $query[$n+1] = $tmpArr[0];
                    }
                }
            }
            foreach ($query as $k => $v){
                if ($v['name'] == '其他'){
                    array_splice($query,$k,1);
                    array_push($query,$v);
                }
            }
            return $query;
        }
        return NULL;
    }
}
