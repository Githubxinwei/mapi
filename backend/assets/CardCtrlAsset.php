<?php
/**
 * Created by PhpStorm.
 * User: 李广杨
 * Date: 2017/3/23
 * Time: 16:47
 * 卡种管理  次卡
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class CardCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/memberCard/css/numberCard.css",
     
        "plugins/memberCard/css/contract.css"
    ];
    public $js = [
        /**
         * 引入自定义的js文件
         */
        "plugins/memberCard/js/numberCardController.js",
        "plugins/memberCard/js/numberCard.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\JqueryStepsAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\AngularAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\JqueryValidateAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}