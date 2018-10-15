<?php
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\MemberShareRegistration;
use backend\modules\v1\models\MemberTemplate;
use Yii;
class MemberTemplateController  extends BaseController
{
    public $modelClass = 'common\models\MemberTemplate';
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'],$actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }
    /**
     * @api {get} /v1/member-template  我的私教课详情
     * @apiVersion  1.0.0
     * @apiName        我的私教课详情
     * @apiGroup       member-template
     * @apiPermission 管理员
     * @apiParam  {int}           account_id              账号id
     * @apiParamExample {json} 请求参数
     *   GET /v1/api-venue/get-venue-detail
     *   {
     *        "id":1,                 // 约课记录id
     *        "member_id":""     //会员id
     *   }
     * @apiDescription   获取场馆详情
     * <br/>
     * <span><strong>作    者：</strong></span>王亮亮<br/>
     * <span><strong>邮    箱：</strong></span>wangliangliang@itsprts.club
     * <span><strong>创建时间：</strong></span>2017/6/17
     * @apiSampleRequest  http://qa.aixingfu.net/v1/api-venue/get-venue-detail
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
        "message": "",
        "code": 1,
        "status": 200,
        "data": {
            "id": "16",
            "class_id": "300891",
            "coach_info":教练名称及头像
            "memberName":会员名称
            "main": {
                "id": 16,//模板id
                "title": "32",模板名称
                "stages": [
                    {
                        "title": "423",//阶段id
                        "id": 45,//阶段名称
                        "main": [
                            {
                                "action_id": 7,//动作id
                                "title": "上斜哑铃划船",//动作名称
                                "ssentials": "爱国认为如果他",//动作描述
                                "unit": 1,//单位 1组数 2 秒 3 分
                                "number": 2, //个数 单位(unit2,3的情况下)energy卡路里
                                "mcontent": "",//教练评价内容
                                "murl": "",//教练 拍的照片
                                "action_number": "",//实际做的重量和组数
                                "url": [  //模板图片
                                    {
                                    "url": ""
                                    },
                                    {
                                    "url": ""
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
        "result": [
            {
                "complete": "1",//训练完成率
                "calorie": "48",//卡路里消耗
                "motion": "1",//运动方式
                "motion_qd": "45",/运动强度
                "everyday": "不牛逼牛逼",//每日评估
                "member_url": "http://oo0oj2qmr.bkt.clouddn.com/0519e37e08847308273d6ff5a7e1b1975b14f8ac956cc.png?e=1528104636&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:Y4gSxx5td4nWYnhxpMR4P8pmGVg="//会员签名
            }
        ]
        }
    }
     * @apiSuccessExample {json}返回值详情（失败）
     {
    "message": "",
    "code": 1,
    "status": 200,
    "data": []
    }
     */
    public function prepareDataProvider()
    {
        $id = Yii::$app->request->get('id');
        $mid = Yii::$app->request->get('member_id');
        $data = MemberTemplate::findOne(['member_id'=>$mid,'class_id'=>$id]);
        return $data;
    }
	/**
	 * @api {get} /v1/member-template/share-registration  分享报名
	 * @apiVersion  1.0.0
	 * @apiName        分享报名
	 * @apiGroup       member-template
	 * @apiPermission 管理员
	 * @apiParamExample {json} 请求参数
	 *   POST /v1/member-template/share-registration
	 *   {
	 *        "coach_id":1,                 // 私教id
	 *        "member_id":""     //分享会员id
	 *        "mobile"            //要报名人员手机号
	 *        "name"              //要报名人员姓名
	 *        "interest"         //兴趣标签
	 *   }
	 * @apiDescription   获取场馆详情
	 * <br/>
	 * <span><strong>作    者：</strong></span>黄鹏举<br/>
	 * <span><strong>邮    箱：</strong></span>huangpengju@itsprts.club
	 * <span><strong>创建时间：</strong></span>2017/6/17
	 * @apiSampleRequest  http://qa.aixingfu.net/v1/api-venue/get-venue-detail
	 * @apiSuccess (返回值) {string} data
	 * @apiSuccessExample {json}返回值详情（成功）
	{
	"code": 1,
	"status": "success",
	"message": "报名成功!"
	}
	 * @apiSuccessExample {json}返回值详情（失败）
	{
	"code": 0,
	"status": "error",
	"message": "报名失败!"
	}
	 */
    public function actionShareRegistration(){
    	$post = Yii::$app->request->post();
	    $model = new MemberShareRegistration();
	    if (empty($post['mobile'])) return ['code' => 0, 'status' => 'error', 'message' => '手机号不能为空!'];
	    if (empty($post['name'])) return ['code' => 0, 'status' => 'error', 'message' => '姓名不能为空!'];
	    if (empty($post['interest'])) return ['code' => 0, 'status' => 'error', 'message' => '标题不能为空!'];
	    foreach ($post as $key=>$value){
	    	if ($key == 'interest'){
	    		$model->$key = json_encode($value);
		    }else{
			    $model->$key = $value;
		    }
	    }
	    $save = $model->save();
//	    return $model->errors;
	    if ($save){
		    return ['code' => 1, 'status' => 'success', 'message' => '报名成功!'];
	    }
	    return ['code' => 0, 'status' => 'error', 'message' => '报名失败!'];
	
    }



}
