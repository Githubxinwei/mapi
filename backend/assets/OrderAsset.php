<?php
/**
 * Created by PhpStorm.
 * User: 梁咳咳
 * Date: 2017/3/16
 * 登录插件引入
 * Time: 15:44
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class OrderAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/order/css/index.css",
    ];
    public $js = [
        "plugins/order/js/orderCtrl.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\DatesAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
    ];
}
