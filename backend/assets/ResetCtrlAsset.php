<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/4/13
 * Time: 9:58
 * content:自定义的页面初始Css样式
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ResetCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/reset/css/reset.css",
        "plugins/reset/css/bootstrap-select.min.css"
    ];
    public $js = [
        "plugins/reset/js/bootstrap-select.min.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}