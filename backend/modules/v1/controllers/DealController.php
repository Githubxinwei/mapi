<?php
namespace backend\modules\v1\controllers;

class DealController extends BaseController
{
    public $modelClass = 'common\models\Deal';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'],$actions['delete']);
        return $actions;
    }
    /**
     * @api {get} /v1/deal/view  协议详情
     * @apiName        2协议详情
     * @apiGroup       private-group
     * @apiParam  {string}            id                   协议ID
     * @apiDescription   消息详情
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/02/06
     * @apiSampleRequest  http://qa.aixingfu.net/v1/deal/view
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
        "message": "",
        "code": 1,
        "status": 200,
        "data": {
            "id": "100795", //协议ID
            "name": "通用版私教协议",//协议名称
            "intro": "<p class="MsoNormal" align="center" style="text-align:center;mso-line-height-alt:12pt;"><b><span style="font-family: 宋体; color: rgb(0, 0, 0); font-size: 15pt;"><font face="宋体">聘请私人教练服务合约</font></span></b><b><span style="font-family: 宋体; color: rgb(0, 0, 0); font-size: 15pt;"><o:p></o:p></span></b></p><p c",//协议内容
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

}
