<?php
/**
 * Created by PhpStorm.
 * User: 李广杨
 * Date: 2017/4/14
 * Time: 14:47
 * 卡种管理  时间卡
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class newTimeCardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/memberCard/css/newTimeCard.css",

        "plugins/memberCard/css/contract.css"
    ];
    public $js = [
        /*
         * 引入自定义的js脚本文件
        */
        "plugins/memberCard/js/timeCardController.js",

    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\JqueryStepsAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\ResetCtrlAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}