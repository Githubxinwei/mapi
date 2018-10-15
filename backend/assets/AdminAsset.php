<?php

/**
 * 全局加载的插件
 */
namespace backend\assets;
use yii\web\AssetBundle;


/**
 * Main backend application asset bundle.
 */
class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        "plugins/admin/css/font-awesome.min.css?v=4.4.0",
        "plugins/admin/css/style.css",
        "plugins/admin/css/admin.css",
        "plugins/admin/css/animate.min.css"
    ];
    public $js = [
        'plugins/admin/js/jquery.metisMenu.js',
        'plugins/admin/js/jquery.slimscroll.min.js',
        'plugins/admin/js/layer.min.js',
        'plugins/admin/js/admin.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',//css 文件
        'yii\bootstrap\BootstrapPluginAsset',//js 文件
        'backend\assets\ContentAsset',
    ];
}
