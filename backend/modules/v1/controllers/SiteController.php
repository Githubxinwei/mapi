<?php
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\UploadForm;
use yii\web\UploadedFile;
use Yii;

class SiteController extends BaseController
{
    public $modelClass = 'common\models\Deal';

    /**
     * @api {get} /v1/site/upload  文件上传
     * @apiName        文件上传
     * @apiGroup       site
     * @apiParam  {file}            file                   文件
     * @apiDescription   文件上传
     * <br/>
     * <span><strong>作    者：</strong></span>张晓兵<br/>
     * <span><strong>邮    箱：</strong></span>zhangxiaobing@itsports.club
     * <span><strong>创建时间：</strong></span>2018/03/01
     * @apiSampleRequest  http://qa.aixingfu.net/v1/site/upload
     * @apiSuccess (返回值) {json} data
     * @apiSuccessExample {json}返回值详情（成功）
    {
        "message": "",
        "code": 1,
        "status": 200,
        "data": {
            "url": "http://oo0oj2qmr.bkt.clouddn.com/249ad65af7fcbd3c9655efd77228d6355a976075492c2.jpg?e=1519873669&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:U2WkjkOdeJuXzo84apR-nCzefgo="
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
    public function actionUpload()
    {
        $model = new UploadForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if(isset($_FILES['file'])) $model->file = UploadedFile::getInstanceByName('file');
        return $model->upload() ?: $this->modelError($model->errors);
    }

}
