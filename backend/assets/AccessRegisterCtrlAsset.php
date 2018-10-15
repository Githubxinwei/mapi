<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/7/13
 * Time: 20:42
 * content:进馆登记
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AccessRegisterCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        /*
         *
         */
        "plugins/purchaseCard/css/icon.css",
        "plugins/purchaseCard/css/weui.css",
        "plugins/purchaseCard/css/weui2.css",
        "plugins/purchaseCard/css/weui3.css",
        /*会员登记页面css*/
        "plugins/purchaseCard/css/purchaseCompanyCard.css"
    ];
    public $js = [
        /*页面 js*/
        "plugins/purchaseCard/js/zepto.min.js",
        "plugins/purchaseCard/js/select.js",
        /*提示信息插件js*/
        "plugins/purchaseCard/js/server.js",
        "plugins/purchaseCard/js/purchaseCard.js",
        /*进馆登记控制器*/
        "plugins/purchaseCard/js/accessRegister.js"
    ];
    public $depends = [
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
//        'backend\assets\AppDateAsset',
        'backend\assets\ResetCtrlAsset'
    ];
}