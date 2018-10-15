<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/21
 * Time: 11:10
 * content:服务套餐管理页面 - 服务页面
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ServePlanCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        服务管理页面css
        "plugins/servePlan/css/index.css"
    ];
    public $js = [
//        服务页面 控制器.js
        "plugins/servePlan/js/serveCtrl.js",
        "plugins/servePlan/js/index.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
//        'backend\assets\ServerIndexCtrlAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',

    ];
}
