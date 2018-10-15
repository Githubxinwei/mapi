<?php

namespace backend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class BaseController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                // restrict access to
                'Access-Control-Request-Method' => ['*'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];
        $behaviors['contentNegotiator']['formats'] = ['application/json' => Response::FORMAT_JSON];
        return $behaviors;
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
            $response = $event->sender;
            if(!isset($response->data['code'])){
                $message = '';
                if(isset($response->data['message'])){
                    $message = $response->data['message'];
                }elseif(!$response->isSuccessful && isset($response->data[0]['message'])){
                    $message = $response->data[0]['message'];
                }
                $response->data = [
                    'message' => $message,
                    'code' => $response->isSuccessful ? 1 : 0,
                    'status' => $response->statusCode,
                    'data' => $response->data ?: [],
                ];
            }
            $response->statusCode = 200;
        });
        return $action;
    }

    public $serializer = [
        'class' => '\yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    protected function error($msg)
    {
        throw new NotFoundHttpException($msg);
    }

    protected function modelError($errors)
    {
        Yii::$app->response->statusCode = 422;
        $info = [];
        foreach ($errors as $field => $msgs)
            $info[] = ['field'=>$field, 'message'=>$msgs[0]];
        return $info;
    }
    /**
     * 格式化错误信息
     * @author zhangxiaobing <zhangxiaobing@itsports.club>
     * @create 2017/12/08
     * @param $msg 错误信息
     * @param array $data 数据
     * @param int $code 错误代码
     * @return array
     */
    protected function errors($msg, $data=[], $code=0)
    {
        $return = ['code'=>$code, 'status'=>'error', 'message'=>$msg, 'data' => $data];
        return $return;
    }

    /**
     * 格式化成功信息
     * @author zhangxiaobing <zhangxiaobing@itsports.club>
     * @create 2017/12/08
     * @param array $data 数据
     * @param string $msg 提示信息
     * @param int $code 代码
     * @return array
     */
    protected function success($data=[], $msg='', $code=1)
    {
        return ['code'=>$code, 'status'=>'success', 'message'=>$msg, 'data'=>$data];
    }

}