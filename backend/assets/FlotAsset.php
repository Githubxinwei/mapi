<?php
/**
 * Created by PhpStorm.
 * User: 梁咳咳
 * 全局左侧插件引入
 * Date: 2017/3/16
 * Time: 15:44
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class FlotAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        "plugins/index/css/font-awesome.css?v=4.4.0",
    ];
    public $js = [
//        "plugins/flot/js/jquery.flot.js",
//        "plugins/flot/js/jquery.flot.resize.js",
//        "plugins/flot/js/jquery.flot.tooltip.min.js",
//        "plugins/flot/js/jquery.flot.pie.js",

    ];
    public $depends = [
        'backend\assets\AdminAsset',
    ];
}
