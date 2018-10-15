<?php
/**
 * Created by PhpStorm.
 * User: 李广杨
 * Date: 2017/3/23
 * Time: 14:47
 * 卡种管理  时间卡
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class TimeCardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/memberCard/css/timeCard.css",
        
        /**
         * 因为 表单向导分为五个页面 只有第一个页面不一样  别的地方可以重用  所以分别写4个文件
         */
        "plugins/memberCard/css/formulate.css",
        "plugins/memberCard/css/venueRestrictions.css",
        "plugins/memberCard/css/bindPackages.css",
        "plugins/memberCard/css/contract.css"
    ];
    public $js = [
        /*
         * 引入自定义的js脚本文件
        */
        "plugins/memberCard/js/timeCardController.js",
        /**
         * 引入表单向导的js文件
         */

    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\JqueryStepsAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\ValidationAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',

    ];
}