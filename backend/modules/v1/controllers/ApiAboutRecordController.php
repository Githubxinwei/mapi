<?php

namespace backend\modules\v1\controllers;

use backend\modules\v1\models\AboutClass;
use backend\modules\v1\models\AboutRecordForm;
use backend\modules\v1\models\Member;
use backend\modules\v1\models\MemberCard;
use backend\modules\v1\models\MemberComplaintForm;
use yii\rest\ActiveController;
use Yii;

class ApiAboutRecordController extends ActiveController
{
    public $modelClass = 'backend\modules\v1\models\AboutRecordFrom';
    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * @api {post} /v1/api-about-record/set-about-record   约课接口
     * @apiVersion  1.0.0
     * @apiName        约课接口
     * @apiGroup       aboutClass
     * @apiPermission 管理员
     * @apiParam  {int}            memberId       会员id
     * @apiParam  {int}            coachId        教练id
     * @apiParam  {int}            classId        预约id
     * @apiParam  {string}         classDate      约课时间
     * @apiParam {string}         memberCardId   会员卡id
     * @apiParam  {int}            seatId        座位id(团课用)
     * @apiParam  {string}         classType     课程类型（charge表示私课，group表示团课）
     * @apiParam  {string}          aboutType     预约类型：团课app  私课 mobile
     * @apiParam  {int}             venueId       场馆id
     * @apiParamExample {json} 请求参数(私课)
     *   POST /v1/api-about-record/set-about-record
     *   {
     *        "memberId":107，              //会员id
     *        "coachId":107，               //教练id
     *        "classId":10，                //预约id
     *        "classDate":2017-06-17 15:00，//会员id
     *        "classType":"group"，          //group表示团课，charge表示私课
     *        "aboutType":"mobile"，          //mobile表示手机自助预约
     *   }
     * @apiParamExample {json} 请求参数(团课)
     *   POST /v1/api-member/login
     *   {
     *        "memberId":107，              //会员id
     *        "coachId":107，               //教练id
     *        "classId":10，                //预约id
     *        "classDate":2017-06-17 15:00，//会员id
     *        "classType":"group"，          //group表示团课，charge表示私课
     *        "aboutType":"mobile"，          // 团课预约：app 私课预约 mobile
     *        "seatId":12,                  //座位id
     *   }
     * @apiDescription   用户约课
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-about-record/set-about-record
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     * "code": 1,                //成功返回标识
     *  "status": "success",     //成功返回标识
     *  "message": "预约成功",   //成功返回信息
     *  "data": "50"             //预约记录id
     *  "isClass" : 1            // 1 上课中 3 已使用 2待使用
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * ｛
     *   "code": 0,             //失败标识
     *   "status": "error",     //失败标识
     *   "message": "预约失败", //失败信息
     *   "data": "not repeat"  //重复预约
     * ｝
     */
    public function actionSetAboutRecord()
    {
        $post     = \Yii::$app->request->post();
        // 会员卡id（判断会员卡是否被冻结或则异常）
        if(isset($post["memberCardId"])&&!empty($post["memberCardId"])){
            $model = new MemberCard();
            //更新会员卡状态,如果团课爽约次数达到设置的次数，冻结此会员卡
            $memberCardId = $post["memberCardId"];
            $member_account_id = $model->getAccountId($memberCardId);
            if ($member_account_id) {
                $accountId = $member_account_id['member_account_id'];
                if ($accountId) {
                    $url = $_SERVER['HTTP_HOST']."/v1/about-class?accountId=".$accountId;
                    $model->getHttpRequest($url);
                }
            }
            $cardStatus = $model->getTheMemberCard($post["memberCardId"]);
            //1：卡正常---1 2：卡未激活---4  3：没有卡 不拦截
            if ($cardStatus == 4) {//未激活
                return ['code'=>10,'status'=>'error','message'=>'会员卡未激活请去前台验卡','cardStatus'=>$cardStatus];
            }

            if($cardStatus!=1&&$cardStatus!=4&&$cardStatus!="noCard"){
                $result = ['code'=>10,'status'=>'error','message'=>'预约失败','cardStatus'=>$cardStatus];
                return $result;
            }
        }

        if(isset($post['type']) && $post['type'] == 'weixin' && !empty($post['type']))
        {
            $model = new Member();
            $data  = $model->getMemberDetail($post['memberId']);                //查询会员手机号是否和输入的相等
            if($data['mobile'] != $post['mobile'])
            {
              return  $result = ['status'=>'error','message'=>'预约被阻止','data'=>'您输入的手机号与会员手机号不符'];
            }
        }
        $type     = $post['classType'];                                         //接收课程类型
        $scenario = $type == 'charge' ? 'charge':'group';

        $about    = new AboutRecordForm([],$scenario);
        if($about->load($post,'')&& $about->validate()){
            if (isset($post["memberCardId"])&&!empty($post["memberCardId"])) {
                $totalNum = $about->getVenueLimitTimes($post['memberCardId'],$post['venueId']);             //查询馆次数核算表（获取总次数）
                $num      = $about->getEntryRecord($post['memberCardId'],$post['memberId'],$post['venueId']);                 //统计会员卡进场次数
                $cardTime = $about->getCardTime($post['memberCardId'],$post['venueId']);
                if ($cardTime === false) {
                    return ['code' => 0,'status' => 'error','message' => '您的会员卡在当前时间段不能使用!'];
                }
                if ($type == 'group') {
                    if ($num >= $totalNum  && $totalNum != -1) {
                        return ['code' => 0,'status' => 'error','message' => '会员卡本月的使用次数已用完'];
                    }
                }
            }
            if($type == 'charge'){
                $save = $about->saveAbout();
            }else{
                $save = $about->saveGroupAbout();
            }
            if(isset($save->id)){
                $result = ['code'=>1,'status'=>'success','message'=>'预约成功','data'=>$save->id,"isClass"=>$about->isClass];
                $message = ($type=="charge")?$about->sendPrivateMessage($post["venueId"]):"";                                  //发送预约成功短信
                return $result;
            }else if(is_array($save)&&$save["status"]=="endAboutLimit"){
                $message = ($save["endClassLimit"]==0)?"对不起,该课程已经开课了":'只有开课前'.$save["endClassLimit"]."分钟可以约课";
                $result  = ['code'=>0,'status'=>'error','message'=>$message,'data'=>$save];
                return $result;
            }else if($save == 'limitOne'){
                $result = ['code'=>0,'status'=>'error','message'=>'您还未买卡，只能预约一节课','data'=>$save];
                return $result;
            }else if($save == 'not class'){
                $result = ['code'=>0,'status'=>'error','message'=>'课程已上完','data'=>$save];
                return $result;
            }else if($save == 'noClass'){
                $result = ['code'=>0,'status'=>'error','message'=>'该卡不能预约课程','data'=>$save];
                return $result;
            }else if($save == 'noBindClass'){
                $result = ['code'=>0,'status'=>'error','message'=>'该卡没有绑定此课程','data'=>$save];
                return $result;
            }elseif($save=="engross"){
                $result = ['code'=>0,'status'=>'error','message'=>'对不起,该座位已被占用','data'=>$save];
                return $result;
            }elseif($save=="classOver"){
                $result = ['code'=>0,'status'=>'error','message'=>'该课程每日预约节数已经用完','data'=>$save];
                return $result;
            }elseif($save=="not repeat"){
                $result = ['code'=>0,'status'=>'error','message'=>'不能重复预约课程','data'=>$save];
                return $result;
            }elseif($save=="hadClass"){
                $result = ['code'=>0,'status'=>'error','message'=>'该时间段你已约过课了','data'=>$save];
                return $result;
            }elseif($save=="hadCrossClass"){
                $result = ['code'=>0,'status'=>'error','message'=>'该时间段与该教练其他课程冲突','data'=>$save];
                return $result;
            }elseif($save=="expired"){
                $result = ['code'=>0,'status'=>'error','message'=>'会员卡已过期','data'=>$save];
                return $result;
            }else{
                $result = ['code'=>0,'status'=>'error','message'=>'预约失败','data'=>$save];
                return $result;
            }
        }else{
           $result = ['code'=>0,'status'=>'error','message'=>'预约失败','data'=>$about->errors];
           return $result;
        }
    }
    /**
     * @api {get} /v1/api-about-record/cancel-about  取消约课接口
     * @apiVersion  1.0.0
     * @apiName        取消约课接口
     * @apiGroup       cancelClass
     * @apiPermission 管理员
     * @apiParam  {int}            aboutId       会员约课记录id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-about-record/cancel-about
     *   {
     *        "aboutId":107，              会员约课记录id
     *   }
     * @apiDescription   用户取消约课
     * <br/>
     * <span><strong>作    者：</strong></span>黄鹏举<br/>
     * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/16
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-about-record/cancel-about
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     *  "code":1,               //成功标识            
     *  "status": "success",    //成功状态
     *  "message": "取消成功"   //取消信息
     * }
     */
    public function actionCancelAbout()
    {
        $id   = \Yii::$app->request->get('aboutId');
        $type = \Yii::$app->request->get('type');
        $about  = new AboutClass();
        $aboutMember  = \backend\models\AboutClass::find()->alias("aboutClass")->joinWith(['member member'])->where(['aboutClass.id'=>$id])->asArray()->one();           //查询约课信息
        $update = $about->updateStatus($id,$aboutMember['member']['venue_id'],$type);
        if($update === true){
            return ['code'=>1,'status'=>'success','message'=>'取消成功'];
        }else{
            return ['code'=>0,'status'=>'limit','message'=>$update];
        }
    }
    /**
     * @api {post} /v1/api-about-record/member-complaint   会员投诉提交接口
     * @apiVersion     1.0.0
     * @apiName        会员投诉提交接口
     * @apiGroup       complaint
     * @apiPermission  管理员
     * @apiParam  {int}   venueId             场馆id
     * @apiParam  {int}   departmentId        部门id
     * @apiParam  {int}   memberId            会员id
     * @apiParam  {int}   complaintType       举报类别
     * @apiParam  {int}   complaintContent    举报内容
     * @apiParamExample {json} 请求参数
     * POST  /v1/api-about-record/cancel-about
     *   {
     *        "venueId":2，              // 场馆id
     *        "departmentId"，           // 部门id
     *        "complaintType",           // 投诉类型
     *        "complaintContent",        // 投诉内容
     *   }
     * @apiDescription  会员投诉提交接口
     * <br/>
     * <span><strong>作    者：</strong></span>侯凯新<br/>
     * <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/8/22
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-about-record/member-complaint
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
     * {
     *  "code":1,               //成功标识
     *  "status": "success",    //成功状态
     *  "message": "成功信息",  //成功信息
     * }
     * @apiSuccessExample {json}返回值详情（失败）
     * {
     *  "code":0,               //失败标识
     *  "status": "error",    //成功状态
     *  "message": "失败信息", //失败信息
     * }
     */
    public function actionMemberComplaint(){
        $data         = \Yii::$app->request->post();
        $complaint    = new MemberComplaintForm();
        if($complaint->load($data,'')&& $complaint->validate()){
             $save =  $complaint->saveMessage();
            if($save === true){
                return ['code'=>1,'status'=>'success','message'=>'投诉成功'];
            }else{
                return ['code'=>0,'status'=>'error','message'=>$complaint->errors];
            }
        }
        $error = $complaint->getErrorMessage($complaint->errors);
        return ['code'=>0,'status'=>'error','message'=>$error];
    }
}
