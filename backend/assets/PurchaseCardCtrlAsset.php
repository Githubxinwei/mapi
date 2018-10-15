<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/6/7
 * Time: 9:12
 * content:购卡
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PurchaseCardCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/purchaseCard/css/icon.css",
        "plugins/purchaseCard/css/weui.css",
        "plugins/purchaseCard/css/weui2.css",
        "plugins/purchaseCard/css/weui3.css",
//        "plugins/purchaseCard/css/weui.min.css",
        "plugins/purchaseCard/css/purchaseCard.css"
    ];
    public $js = [
        "plugins/purchaseCard/js/zepto.min.js",
//        "plugins/purchaseCard/js/picker.js",
        "plugins/purchaseCard/js/select.js",
        "plugins/purchaseCard/js/server.js",
        "plugins/purchaseCard/js/purchaseCard.js",
        "plugins/purchaseCard/js/purchaseCardCtrl.js"
    ];
    public $depends = [
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\ResetCtrlAsset'
    ];
}