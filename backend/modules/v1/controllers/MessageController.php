<?php
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\Feedback;
use backend\modules\v1\models\Message;
use backend\modules\v1\models\Member;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class MessageController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\Message';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'],$actions['delete'],$actions['view']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    /**
     * @api {get} /v1/message  消息列表(老版本)
     * @apiName        1消息列表
     * @apiGroup       private-group
     * @apiParam  {string}            accountId              账户ID
     * @apiParam  {string}            fields                可选,选择显示字段(例:fields=id,type_name,name,create_name,create_at)
     * @apiParam  {string}            page                  页码（可选，默认1）
     * @apiParam  {string}            per-page              每页显示数（可选，默认20）
     * @apiDescription   消息列表
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/01/31
     * @apiSampleRequest  http://qa.aixingfu.net/v1/message
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
     {
        "message": "",
        "code": 1,
        "status": 200,
        "data": {
            "items": [
                {
                    "id": "100795", //预约消息ID
                    "status": 8,//8待预约，9预约失败
                    "type": "3",//3小团体课程，4小团体服务
                    "coach": "高陈静",//教练
                    "start": "1517454000",//开课时间
                    "is_read": 0,//0未读 1已读
                    "create_at":"1517454000",//排课时间
                    "had_about_num":"3",//已预约人数
                    "class_info": {//小团体课详情
                        "id": "4",
                        "class_number": "01311507318918",
                        "sell_number": "3",
                        "surplus": "0",
                        "total_class_num": "6",
                        "attend_class_num": 6,
                        "valid_time": null,
                        "sale_num": "80",
                        "surplus_sale_num": "80",
                        "venue_address": "东太康路大上海城3区6楼",
                        "people_least": 2,
                        "people_most": 3,
                        "least_number": 2,
                        "charge_class_type": 2,
                        "charge_class_pic": "http://oo0oj2qmr.bkt.clouddn.com/4521511517382430.jpg?e=1517386030&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:IYCqq51VqzwyTqcZet1yOofnF-U=",
                        "charge_class_name": "多人课程",
                        "charge_class_describe": "如何",
                        "course_package": [
                            {
                                "id": "556",
                                "course_num": null,
                                "course_length": "55",
                                "original_price": "100.00",
                                "course_name": "PT常规课"
                            }
                        ],
                        "price": "1800.00",
                        "had_buy": 0
                    }
                }
            ],
            "_links": {
                "self": {
                    "href": "http://127.0.0.2/v1/message/index?memberId=95337&page=1"
                }
            },
            "_meta": {
                "totalCount": 1,
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
        return  $this->error('请前往公众号或小米/华为商店下载I Love Sports YF最新版App');
        $accountId = Yii::$app->request->get('accountId', 0);
        if(empty($accountId)) return $this->error('参数缺失');
        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        if ($members) {
            $ids = [];
            foreach ($members as $member) {
                $ids[] = $member['id'];
            }
            $query = Message::find()->where(['member_id'=>$ids, 'type'=>[3,4],'status'=>[8,9]])
                    ->orWhere(['and',['=','type',2],['not in', 'status', [8,9]],['>','in_time',0]]);
            
            $query->orderBy('id desc');
            return new ActiveDataProvider(['query' => $query]);
        } else {
            return NULL;
        }
    }
    /**
     * @api {get} /v1/message/news  消息列表(手环签到)
     * @apiName        1消息列表
     * @apiGroup       private-group
     * @apiParam  {string}            accountId              账户ID
     * @apiParam  {string}            fields                可选,选择显示字段(例:fields=id,type_name,name,create_name,create_at)
     * @apiParam  {string}            page                  页码（可选，默认1）
     * @apiParam  {string}            per-page              每页显示数（可选，默认20）
     * @apiDescription   消息列表
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/01/31
     * @apiSampleRequest  http://qa.aixingfu.net/v1/message
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
    "message": "",
    "code": 1,
    "status": 200,
    "data": {
    "items": [
    {
    "id": "100795", //预约消息ID
    "status": 8,//8待预约，9预约失败
    "type": "3",//3小团体课程，4小团体服务
    "coach": "高陈静",//教练
    "start": "1517454000",//开课时间
    "is_read": 0,//0未读 1已读
    "create_at":"1517454000",//排课时间
    "had_about_num":"3",//已预约人数
    "class_info": {//小团体课详情
    "id": "4",
    "class_number": "01311507318918",
    "sell_number": "3",
    "surplus": "0",
    "total_class_num": "6",
    "attend_class_num": 6,
    "valid_time": null,
    "sale_num": "80",
    "surplus_sale_num": "80",
    "venue_address": "东太康路大上海城3区6楼",
    "people_least": 2,
    "people_most": 3,
    "least_number": 2,
    "charge_class_type": 2,
    "charge_class_pic": "http://oo0oj2qmr.bkt.clouddn.com/4521511517382430.jpg?e=1517386030&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:IYCqq51VqzwyTqcZet1yOofnF-U=",
    "charge_class_name": "多人课程",
    "charge_class_describe": "如何",
    "course_package": [
    {
    "id": "556",
    "course_num": null,
    "course_length": "55",
    "original_price": "100.00",
    "course_name": "PT常规课"
    }
    ],
    "price": "1800.00",
    "had_buy": 0
    }
    }
    ],
    "_links": {
    "self": {
    "href": "http://127.0.0.2/v1/message/index?memberId=95337&page=1"
    }
    },
    "_meta": {
    "totalCount": 1,
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
    public function actionNews()
    {
        $accountId = Yii::$app->request->get('accountId', 0);
        if(empty($accountId)) return $this->error('参数缺失');
        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        if ($members) {
            $ids = [];
            foreach ($members as $member) {
                $ids[] = $member['id'];
            }
            $query = Message::find()->alias('m')
                ->joinWith('groupClass gc')
                ->where(['m.member_id'=>$ids])
                ->andWhere(['and',['=','m.type',2],['not in', 'm.status', [8,9]],['>','m.in_time',0]])
                ->andwhere(['NOT', ['gc.id' => null]])->orderBy('id desc');
            return new ActiveDataProvider(['query' => $query]);
        }
        return NULL;
    }
    /**
     * @api {get} /v1/message/info  消息列表(小团体课)
     * @apiName        1消息列表
     * @apiGroup       private-group
     * @apiParam  {string}            accountId              账户ID
     * @apiParam  {string}            fields                可选,选择显示字段(例:fields=id,type_name,name,create_name,create_at)
     * @apiParam  {string}            page                  页码（可选，默认1）
     * @apiParam  {string}            per-page              每页显示数（可选，默认20）
     * @apiDescription   消息列表
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/01/31
     * @apiSampleRequest  http://qa.aixingfu.net/v1/message
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
    "message": "",
    "code": 1,
    "status": 200,
    "data": {
    "items": [
    {
    "id": "100795", //预约消息ID
    "status": 8,//8待预约，9预约失败
    "type": "3",//3小团体课程，4小团体服务
    "coach": "高陈静",//教练
    "start": "1517454000",//开课时间
    "is_read": 0,//0未读 1已读
    "create_at":"1517454000",//排课时间
    "had_about_num":"3",//已预约人数
    "class_info": {//小团体课详情
    "id": "4",
    "class_number": "01311507318918",
    "sell_number": "3",
    "surplus": "0",
    "total_class_num": "6",
    "attend_class_num": 6,
    "valid_time": null,
    "sale_num": "80",
    "surplus_sale_num": "80",
    "venue_address": "东太康路大上海城3区6楼",
    "people_least": 2,
    "people_most": 3,
    "least_number": 2,
    "charge_class_type": 2,
    "charge_class_pic": "http://oo0oj2qmr.bkt.clouddn.com/4521511517382430.jpg?e=1517386030&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:IYCqq51VqzwyTqcZet1yOofnF-U=",
    "charge_class_name": "多人课程",
    "charge_class_describe": "如何",
    "course_package": [
    {
    "id": "556",
    "course_num": null,
    "course_length": "55",
    "original_price": "100.00",
    "course_name": "PT常规课"
    }
    ],
    "price": "1800.00",
    "had_buy": 0
    }
    }
    ],
    "_links": {
    "self": {
    "href": "http://127.0.0.2/v1/message/index?memberId=95337&page=1"
    }
    },
    "_meta": {
    "totalCount": 1,
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
    public function actionInfo()
    {
        $accountId = Yii::$app->request->get('accountId', 0);
        if(empty($accountId)) return $this->error('参数缺失');
        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        if ($members) {
            $ids = [];
            foreach ($members as $member) {
                $ids[] = $member['id'];
            }
            $query = Message::find()->where(['member_id'=>$ids, 'type'=>[3,4], 'status'=>[8,9]]);

            $query->orderBy('id desc');
            return new ActiveDataProvider(['query' => $query]);
        } else {
            return NULL;
        }
    }

    /**
     * @api {get} /v1/message/view  消息详情
     * @apiName        2消息详情
     * @apiGroup       private-group
     * @apiParam  {string}            id                   预约消息ID
     * @apiDescription   消息详情
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/01/31
     * @apiSampleRequest  http://qa.aixingfu.net/v1/message/view
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
        "message": "",
        "code": 1,
        "status": 200,
        "data": {
            "id": "100795", //预约消息ID
            "status": 8,//8待预约，1已预约
            "type": "3",//3小团体课程，4小团体服务
            "coach": "高陈静",//教练
            "start": "1517454000",//开课时间
            "is_read": 0,//0未读 1已读
            "class_info": {//小团体课详情
                "id": "4",
                "class_number": "01311507318918",
                "sell_number": "3",
                "surplus": "0",
                "total_class_num": "6",
                "attend_class_num": 6,
                "valid_time": null,
                "sale_num": "80",
                "surplus_sale_num": "80",
                "venue_address": "东太康路大上海城3区6楼",
                "people_least": 2,
                "people_most": 3,
                "least_number": 2,
                "charge_class_type": 2,
                "charge_class_pic": "http://oo0oj2qmr.bkt.clouddn.com/4521511517382430.jpg?e=1517386030&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:IYCqq51VqzwyTqcZet1yOofnF-U=",
                "charge_class_name": "多人课程",
                "charge_class_describe": "如何",
                "course_package": [
                    {
                        "id": "556",
                        "course_num": null,
                        "course_length": "55",
                        "original_price": "100.00",
                        "course_name": "PT常规课"
                    }
                ],
                "price": "1800.00",
                "had_buy": 0
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
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->is_read = 1;
        return $model->save() ? $model : $model->errors;
    }

    /**
     * @api {get} /v1/message/about  预约小团体课
     * @apiName        3预约小团体课
     * @apiGroup       private-group
     * @apiParam  {string}            id                   预约消息ID
     * @apiDescription   预约小团体课
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/01/31
     * @apiSampleRequest  http://qa.aixingfu.net/v1/message/about
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
        "message": "",
        "code": 1,
        "status": 200,
        "data": {
            "id": "100795", //预约消息ID
            "status": 8,//8待预约，1已预约
            "type": "3",//3小团体课程，4小团体服务
            "coach": "高陈静",//教练
            "start": "1517454000",//开课时间
            "is_read": 0,//0未读 1已读
            "class_info": {//小团体课详情
                "id": "4",
                "class_number": "01311507318918",
                "sell_number": "3",
                "surplus": "0",
                "total_class_num": "6",
                "attend_class_num": 6,
                "valid_time": null,
                "sale_num": "80",
                "surplus_sale_num": "80",
                "venue_address": "东太康路大上海城3区6楼",
                "people_least": 2,
                "people_most": 3,
                "least_number": 2,
                "charge_class_type": 2,
                "charge_class_pic": "http://oo0oj2qmr.bkt.clouddn.com/4521511517382430.jpg?e=1517386030&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:IYCqq51VqzwyTqcZet1yOofnF-U=",
                "charge_class_name": "多人课程",
                "charge_class_describe": "如何",
                "course_package": [
                    {
                        "id": "556",
                        "course_num": null,
                        "course_length": "55",
                        "original_price": "100.00",
                        "course_name": "PT常规课"
                    }
                ],
                "price": "1800.00",
                "had_buy": 0
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
    public function actionAbout($id)
    {
        $model = $this->findModel($id);
        if($model->status == 8) $model->status = 1;

        return $model->save() ? $model : $model->errors;
    }

    private function findModel($id)
    {
        $model = Message::findOne($id);
        if(!$model) throw new NotFoundHttpException();
        return $model;
    }
    /**
     * @api {get} /v1/message/message  消息投诉反馈消息
     * @apiVersion  1.0.0
     * @apiName        消息投诉反馈消息
     * @apiParam  {string}            accountId                   账号ID
     * @apiGroup       message
     * @apiPermission 管理员
     * @apiParamExample {json} 请求参数
     *   GET /coach/message/index?accesstoken=666
     * @apiDescription   消息首页
     * <br/>
     * <span><strong>作    者：</strong></span>王亮亮<br/>
     * <span><strong>邮    箱：</strong></span>wangliangliang@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/12/13
     * @apiSampleRequest  http://qaserviceapi.xingfufit.cn/service/message/index?accesstoken=666
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
    "message": "",
    "code": 1,
    "status": 200,
    -"data": {
    -"feedback": {
    "title": "投诉反馈",
    "content": "您于04月16日 19:38提交的反馈收到回复了!"
    }
    }
    }
     * @apiSuccessExample {json}返回值详情（失败）
     *  {
     *      "code": 0,                   //失败表示
     *      "status": "error",           //请求状态
     *      "message": ""    //失败原因
     *  }
     */
    public function actionMessage($accountId){
        $memberId = array_column(Member::find()->where(['member_account_id'=>$accountId])->all(),'id');
        $feedback = Feedback::find()->where(['user_id'=>$memberId])->andWhere(['NOT',['reply_time'=>null]])->orderBy('is_read ASC ,created_at desc, id desc')->one();
        if($feedback){
            $data['feedback']['title']='投诉反馈';
            $time = date('m月d日 H:i', $feedback->created_at);
            $data['feedback']['count']=  (int) Feedback::find()->where(['user_id'=>$memberId,'is_read'=>0])->andWhere(['NOT',['reply_time'=>null]])->count();
            $data['feedback']['content'] = "您于".$time.'提交的反馈收到回复了!';
        }else{
            $data['feedback']['title']='投诉反馈';
            $data['feedback']['count']=0;
            $data['feedback']['content'] = '';
        }
        return $data;
    }


}
