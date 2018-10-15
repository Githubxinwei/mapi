<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2017/4/13
 * Time: 14:00
 * content: 充值卡页面
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class  RechargeableCardCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //充值卡页面css
        "plugins/rechargeableCard/css/rechargeableCard.css",
    ];
    public $js = [
        // 充值卡页面js
        "plugins/rechargeableCard/js/rechargeableCard.js",
        "plugins/rechargeableCard/js/rechargeCardCtrl.js",
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