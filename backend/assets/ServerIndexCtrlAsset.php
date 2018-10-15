<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/21
 * Time: 11:10
 * content:服务管理 - 主页 - 主页面的js和css
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ServerIndexCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        服务套餐管理页面css
        "plugins/servePlan/css/index.css"
    ];
    public $js = [
//        服务套餐管理页面js和套餐页面的js
        "plugins/servePlan/js/packageCtrl.js",
        "plugins/servePlan/js/index.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}
