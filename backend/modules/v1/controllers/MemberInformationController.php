<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/19
 * Time: 16:28
 * Desc:会员信息
 */
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\MemberInformation;
use Yii;

class MemberInformationController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\MemberInformation';
    /**
     * @api {post} /v1/member-information/modify-member-information   会员第一次登录个人资料保存
     * @apiVersion  1.7.3
     * @apiName        会员第一次登录个人资料保存
     * @apiGroup       memberInformation
     * @apiPermission 管理员
     * @apiParam  {int}          member_id           会员id
     * @apiParam  {smallint}     sex                 性别
     * @apiParam  {date}         birth_date          生日
     * @apiParam  {varchar}      profession          职业
     * @apiParam  {text}         now_address         城市
     * @apiParam  {tinyint}      height              身高
     * @apiParam  {decimal}      weight              体重
     * @apiParam  {tinyint}      fitness_foundation  健身基础（1-入门，2-初级，3-中级，4-高级）
     * @apiParam  {varchar}      fitness_goal        健身目标
     * @apiParamExample {json} 请求参数
     *   POST /v1/member-information/modify-member-information
     *   {
     *        "member_id":95530                                           //会员id
     *        "sex":1                                                    //性别 1男2女
     *        "birth_date" :"1998-10-26"                                //出生日期
     *        "profession" :"计算机/互联网/通信"                         //职业
     *        "now_address":"河南省-郑州市-金水区"                       //城市
     *        "height":"177"                                            //身高(cm)
     *        "weight":"76.5"                                           //体重(kg)
     *        "fitness_foundation"："1"                                 //健身基础（1-入门，2-初级，3-中级，4-高级）
     *        "fitness_goal" : "燃烧脂肪"                                       //燃烧脂肪
     *   }
     * @apiDescription   会员第一次登录个人资料保存
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/06/19
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/member-information/modify-member-information
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"保存成功！"    //成功提示信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,              //失败标识
     *  "status": "error",      //失败标识
     *  "message":"网络错误，请稍后重试!"    //失败提示信息
     * }
     */
    public function actionModifyMemberInformation()
    {
        $post = \Yii::$app->request->post();
        $model = new MemberInformation();
        if ($model->load($post, '') && $model->validate()) {
            $data = $model->modifyMember();
            return $data;
        }
    }
    /**
     * @api {get} /v1/member-information/get-member-information   会员是否弹出第一次登录个人资料保存
     * @apiVersion  1.8.0
     * @apiName        会员是否弹出第一次登录个人资料保存
     * @apiGroup       memberInformation
     * @apiPermission 管理员
     * @apiParam  {int}          accountId           账户id
     * @apiParamExample {json} 请求参数
     *   POST /v1/member-information/get-member-information
     *   {
     *        "accountId":40619                                           //账户id
     *   }
     * @apiDescription   会员是否弹出第一次登录个人资料保存
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
     * <span><strong>创建时间：</strong></span>2018/06/23
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/member-information/get-member-information
     * @apiSuccess (返回值) {string} data
     * @apiSuccessExample {json}返回值详情(成功时)
     * {
     *  "code": 1,              //成功标识
     *  "status": "success",    //成功标识
     *  "message":"会员未弹出第一次登录个人资料保存"    //成功提示信息
     * }
     * @apiSuccessExample {json}返回值详情(失败时)
     * {
     *  "code": 0,              //失败标识
     *  "status": "error",      //失败标识
     *  "message":"会员已弹出第一次登录个人资料保存!"    //失败提示信息
     * }
     */
    public function actionGetMemberInformation()
    {
        $accountId   = \Yii::$app->request->get('accountId','0');
        $model = new MemberInformation();
        $data = $model->getMemberInformation($accountId);
        if (!$data) {
            return ['code' => 1, 'status' => 'success', 'message' => '会员未弹出第一次登录个人资料保存！'];
        } else {
            return ['code' => 0, 'status' => 'error', 'message' => '会员已弹出第一次登录个人资料保存！'];
        }
    }
}