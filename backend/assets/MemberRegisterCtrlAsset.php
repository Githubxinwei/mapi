<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2017/7/6
 * Time: 11:45
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MemberRegisterCtrlAsset extends AssetBundle
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
        /*会员登记控制器*/
        "plugins/purchaseCard/js/memberRegisterCtrl.js"
    ];
    public $depends = [
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
//        'backend\assets\AppDateAsset',
        'backend\assets\ResetCtrlAsset'
    ];
}