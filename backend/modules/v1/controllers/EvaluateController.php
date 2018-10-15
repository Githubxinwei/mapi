<?php
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\Evaluate;
use backend\modules\v1\models\EvaluateForm;
use Yii;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use backend\modules\v1\models\Func;
use yii\data\ActiveDataProvider;
class EvaluateController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\Evaluate';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'],$actions['delete']);
        return $actions;
    }

    /**
     * @api {post} /v1/evaluate/create  课程评价
     * @apiName        2课程评价
     * @apiGroup       evaluate
     * @apiParam  {string}            member_id            会员ID，必填
     * @apiParam  {string}            consumption_type     标识：teamClass :团课,privateClass :私课,smallClass :小团体
     * @apiParam  {string}            display_status       匿名 : 1 ,非匿名: 2
     * @apiParam  {string}            venue_id             场馆id
     * @apiParam  {string}            company_id           公司id
     * @apiParam  {string}            content              内容
     * @apiParam  {string}            star_level           星评: 最高五颗星
     * @apiParam  {string}            label_id             标签id
     * @apiParam  {file}              enclosure            图片文件，数组形式传递, pics[]
     * @apiDescription   课程评价
     * <br/>
     * <span><strong>作    者：</strong></span>王亮亮<br/>
     * <span><strong>邮    箱：</strong></span>wangliangliang@itsports.club
     * <span><strong>创建时间：</strong></span>2018/04/07
     * @apiSampleRequest  http://qamember.xingfufit.cn/v1/evaluate/create
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
        $model = new Evaluate();
//        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
//        if(isset($_FILES['enclosure'])) $model->enclosure = UploadedFile::getInstancesByName('enclosure');
//        return $model;
//        if ($model->save()) {
//            $response = Yii::$app->getResponse();
//            $response->setStatusCode(201);
//        } elseif (!$model->hasErrors()) {
//            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
//        }

        $model = new EvaluateForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $url= null;
        if (isset($_FILES['enclosure'])){
            $count =  count($_FILES['enclosure']['name']);
            for ($i = 0 ;$i<$count ;$i++){
                $arr = explode(".",  $_FILES['enclosure']['name'][$i]);
                $Suffix = $arr[count($arr)-1];
                $imgName = uniqid(md5(microtime(true))) . '.' . $Suffix;
                $err = Func::uploadFile( $_FILES['enclosure']['tmp_name'][$i], $imgName);
                if(!empty($err)){
                    $this->addErrors(['enclosure'=>'上传失败']);
                    return false;
                }
                $url[] = Func::getImgUrl($imgName);
            }
        }
        $a= $model->saves($url);
        if ( $a=== true){
            $model['enclosure']=$url;
            $data = Evaluate::find()->where(['member_id'=>$model['member_id'],'consumption_type_id'=>$model['consumption_type_id']])->orderBy('create_at DESC')->one();
            return  ['code'=>1,'msg'=>'发布成功!','data'=>isset($data->id)?$data->id:''];
        }else{
            return $a;

        }
    }
    public function actionIndex()
    {

    }

    /**
     * 获取全部评价
     *type teamClass :团课,privateClass :私课
     * courseId 课程id
     * venueId
     */
    public function actionGetAllEvaluate(){
        $param = \YII::$app->request->get();
        $param_check = ['teamClass','privateClass'];
        if (empty($param['type']) || !in_array($param['type'],$param_check) || empty($param['venueId']) || empty($param['courseId'])) return ['code'=>0,'message'=>'参数错误','data'=>[]];
        $page = !empty($param['page']) ? $param['page'] : 1;//初始化当前页
        $pageSize = !empty($param['pageSize']) ? $param['pageSize'] : 20;//初始化一页多少条
        $evaluate = new Evaluate();
        $query =  $evaluate->getAllEvaluate($param['courseId'],$param['type'],$param['venueId'],$page,$pageSize);
        return $query;
//        return new ActiveDataProvider(['query' => $query]);
    }
}
