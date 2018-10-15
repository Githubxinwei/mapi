<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/2
 * Time: 13:58
 * Desc:用户手机通讯录
 */
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\AppUserContact;

use Yii;

class AppUserContactController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\AppUserContact';
    /**
     * @api {post} /v1/app-user-contact/generate-user-contact  生成用户通讯录
     * @apiName        生成用户通讯录
     * @apiGroup       app-user-contact
     * @apiParam  {int}             memberId             会员ID
     * @apiParam  {int}             companyId            公司ID
     * @apiParam  {json}            callInfo             用户通讯录信息(name,phone,note)
     * @apiDescription   生成用户通讯录
     * <br/>
     * <span><strong>作    者：</strong></span>辛威<br/>
     * <span><strong>邮    箱：</strong></span>xinwei@itsports.club
     * <span><strong>创建时间：</strong></span>2018/07/02
     * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/app-user-contact/generate-user-contact
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
        {
        "code": 1,
        "status": success,
        "message": "保存成功！",
        }
         * @apiSuccessExample {json}返回值详情（失败）
        {
        "code": 0,
        "status": error,
        "message": "网络错误，请稍后重试！",
        }
     */
    public function actionGenerateUserContact()
    {
        $post = \Yii::$app->request->post();
        $model = new AppUserContact();
        if ($model->load($post, '') && $model->validate()) {
            $data = $model->GenerateUserContact();
            return $data;
        }
    }
}