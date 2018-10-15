<?php
/**
 * Created by PhpStorm.
 * User: 李广杨
 * Date: 2017/3/23
 * Time: 9:47
 * 卡种管理  混合卡
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class BlendCardCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/memberCard/css/blendCard.css",
        "plugins/memberCard/css/rechargeableCard.css",

    ];
    public $js = [
        /**
         * 混合卡 js
         */
        "plugins/memberCard/js/blendCardController.js",
    ];
    public $depends = [
        'backend\assets\TimePlugInAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\JqueryStepsAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\JqueryValidateAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}