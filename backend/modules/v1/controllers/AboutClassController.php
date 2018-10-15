<?php
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\Evaluate;
use backend\modules\v1\models\EvaluateView;
use common\models\AboutClass;
use common\models\Func;
use Yii;
use backend\modules\v1\models\MyAboutClass;
use backend\modules\v1\models\MeAboutClass;
use backend\modules\v1\models\Member;
use yii\data\ActiveDataProvider;
use yii\rest\Serializer;
use yii\web\NotFoundHttpException;

class AboutClassController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\MyAboutClass';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'],$actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    /**
     * @api {get} /v1/about-class  预约列表
     * @apiName        1消息列表
     * @apiGroup       private-group
     * @apiParam  {string}            accountId             账户ID
     * @apiParam  {string}            fields                可选,选择显示字段(例:fields=id,type_name,name,create_name,create_at)
     * @apiParam  {string}            page                  页码（可选，默认1）
     * @apiParam  {string}            per-page              每页显示数（可选，默认20）
     * @apiDescription   消息列表
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/02/02
     * @apiSampleRequest  http://qa.aixingfu.net/v1/about-class
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
                    "is_evaluate" 1评价 0 未评价
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
                },
    {  私教课
        "id": "67360",
        "status": 1,
        "type": "1",
        "coach": "唐成",
        "start": "1517569200",
        "end": "1517571600",
        "is_read": 0,
        "create_at": "1517553294",
        "cancel_time": null,
        "class_info": {
        "type": "charge",
        "productName": "PT游泳课",
        "courseName": "PT游泳课",
        "classLength": "40",
        "category": "2",
        "courseNum": "3",
        "courseAmount": "3",
        "pic": "http://oo0oj2qmr.bkt.clouddn.com/5291961504247513.png?e=1504251113&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:aKmWbdmTWBWfBSAQR3PPl0QR5fg=",
        "originalPrice": "360.00",
        "totalPrice": "暂无数据",
        "venue_id": "76",
        "venue_name": "艾搏尊爵汇馆",
        "venue_address": "郑州市二七万达广场",
        "chargeNum": 1,
        "score": 4,
        "scoreImg": {
        "one": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
        "two": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
        "three": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
        "four": "http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=",
        "five": "http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4="
        },
        "packageClass": [
        {
        "coachName": "唐成",
        "name": "PT游泳课",
        "times": 1,
        "course_length": "40",
        "sale_price": "暂无数据",
        "is_member": "1",
        "status": "1"
        },
        {
        "name": "PT游泳课",
        "times": 2,
        "course_length": "40",
        "sale_price": "暂无数据",
        "is_member": "1",
        "status": "2"
        },
        {
        "name": "PT游泳课",
        "times": 3,
        "course_length": "40",
        "sale_price": "暂无数据",
        "is_member": "1",
        "status": "2"
        }
        ],
        "courseFlag": true,
        "limit": false
        }
    },
    {    团教课
        "id": "67359",
        "status": 1,
        "type": "2",
        "coach": "王小璐",
        "start": "1517720400",
        "end": "1517724000",
        "is_read": 0,
        "create_at": "1517546584",
        "cancel_time": null,
        "class_info": {
        "id": "12739",
        "course_pic": "",
        "course_name": "基础瑜伽",
        "venue_id": "76",
        "venue_name": "艾搏尊爵汇馆",
        "venue_address": "郑州市二七万达广场"
        },
        "class_room": "1号厅",
        "seat_number": "28"
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
        $accountId = Yii::$app->request->get('accountId', 0);
        if(empty($accountId)) return $this->error('参数缺失');
        $status = Yii::$app->request->get('status', 0);
        $type = Yii::$app->request->get('type', 0);

        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        if ($members) {
            $ids = [];
            foreach ($members as $member) {
                $ids[] = $member['id'];
            }
        $query = MyAboutClass::find()->where(['member_id'=>$ids])->andWhere(['not in', 'status', [8,9]]);
        if($status) $query->andWhere(['status'=>$status]);
        if($type) $query->andWhere(['type'=>$type]);
        $query->orderBy('id desc');
        return new ActiveDataProvider(['query' => $query]);
        }
        return NULL;
    }
    /**
     * @api {get} /v1/about-class/list  待评价预约列表
     * @apiName        待评价预约列表
     * @apiGroup       private-group
     * @apiParam  {string}            accountId             账户ID
     * @apiParam  {string}            fields                可选,选择显示字段(例:fields=id,type_name,name,create_name,create_at)
     * @apiParam  {string}            page                  页码（可选，默认1）
     * @apiParam  {string}            per-page              每页显示数（可选，默认20）
     * @apiDescription   消息列表
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/02/02
     * @apiSampleRequest  http://qa.aixingfu.net/v1/about-class
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
     *  {
    "message": "",
    "code": 1,
    "status": 200,
    "data": {
    "items": []}
     * @apiSuccessExample {json}返回值详情（失败）
    {
    "message": "",
    "code": 0,
    "status": 422,
    "data": []
    }
     */

    public function actionList()
    {
        $accountId = Yii::$app->request->get('accountId', 0);
        if(empty($accountId)) return $this->error('参数缺失');


        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        foreach ($members as $member) {
            $ids[] = $member['id'];
        }
        $query = MeAboutClass::find()->alias('mc')
                ->joinWith(['memberCourseOrderDetails mcod' => function($q){
                    $q->joinWith(['siEvaluate se']);
                }],false)
               ->joinWith(['groupClass gc' => function($q){
                   $q->joinWith(['tuanEvaluate te']);
               }],false)
               ->where(['mc.member_id' => $ids,'mc.type'=>[2,3]])
               ->andWhere(['in', 'mc.status', [4]])
               ->andWhere(['and', ['se.id' => null], ['te.id' => null]]);
        $query =  $query->orderBy('mc.id desc');
        return new ActiveDataProvider(['query' => $query]);
    }
    /**
     * @api {get} /v1/about-class  12以评价预约列表
     * @apiName        2以评价预约列表
     * @apiGroup       private-group
     * @apiParam  {string}            accountId             账户ID
     * @apiParam  {string}            fields                可选,选择显示字段(例:fields=id,type_name,name,create_name,create_at)
     * @apiParam  {string}            page                  页码（可选，默认1）
     * @apiParam  {string}            per-page              每页显示数（可选，默认20）
     * @apiDescription   消息列表
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/02/02
     * @apiSampleRequest  http://qa.aixingfu.net/v1/about-class
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
     *  {
    "message": "",
    "code": 1,
    "status": 200,
    "data": {
    "items": []}
     * @apiSuccessExample {json}返回值详情（失败）
    {
    "message": "",
    "code": 0,
    "status": 422,
    "data": []
    }
     */
    public function actionLists()
    {
        $accountId = Yii::$app->request->get('accountId', 0);
        if(empty($accountId)) return $this->error('参数缺失');
        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        foreach ($members as $member) {
            $ids[] = $member['id'];
        }
        $query = EvaluateView::find()->where(['member_id'=>$ids,'consumption_type'=>['teamClass','smallClass']]);
        return new ActiveDataProvider(['query' => $query]);
    }
    public function actionDetails()
    {
        $id = Yii::$app->request->get('id', 0);
        if(empty($id)) return $this->error('参数缺失');
        $query = EvaluateView::find()->where(['id'=>$id]);
        return new ActiveDataProvider(['query' => $query]);
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
            "is_evaluate"1评价 2未评价 0 不满足条件
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

        if($model->save()){
            return $model;
        }
        return $model->errors;
    }

    /**
     * 私教预约表
     * @param $coachId
     * @param $memberId
     * @param $start
     * @param $end
     * @return array
     */
    public function actionTable($coachId,$memberId,$start,$end)
    {
        if($end-$start>3600*24*10) $this->error('时间跨度太大');
        $abouts = AboutClass::find()->where(['coach_id'=>$coachId, 'type'=>1])
            ->andWhere(['not in', 'status', [2]])
            ->andWhere(['between', 'start', $start, $end])->orderBy('start')->all();
        $data = [];
        for($stage=$start; $stage<=$end; $stage+=3600*24){
            $date = date('Y-m-d', $stage);
            $date_data = [];
            foreach ($abouts as $about){
                if($date == $about->class_date){
                    $date_data[] = [
                        'type' => $about->member_id == $memberId ? 1 : 2,
                        'start' => $about->start,
                        'end' => $about->end,
                    ];
                }
            }
            $data[] = $date_data;
        }
        
        return $data;
    }

    private function findModel($id)
    {
        $model = MyAboutClass::findOne($id);
        if(!$model) throw new NotFoundHttpException();
        return $model;
    }
    /**
     * @api {get} /v1/about-class/total-number  上课节数和运动天数
     * @apiName        上课节数和运动天数
     * @apiGroup       about-class
     * @apiParam  {string}            accountId             账户ID
     * @apiDescription   消息列表
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsports.club
     * <span><strong>创建时间：</strong></span>2018/06/13
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/about-class/total-number
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
     *  {
    "message": "",
    "code": 1,
    "status": 200,
    "data": {
    "lessonNumbers": "1",
    "sportsDays": "4",
    }
     * @apiSuccessExample {json}返回值详情（失败）
    {
    "message": "",
    "code": 0,
    "status": 422,
    "data":   NULL
    }
     */
    public function actionTotalNumber($accountId)
    {
        $model = new MyAboutClass();
        $data = $model->getTotalNumber($accountId);
        if ($data) {
            return ['code' => 1, 'status' => 'success', 'message' => '请求成功', 'data' => $data];
        } else {
            return ['code' => 0, 'status' => 'error', 'message' => '没有数据','data' => NULL];
        }
    }
}
