<?php
/**
 * 全局插件引入
 */
namespace backend\assets;
use yii\web\AssetBundle;
/**
 * Main backend application asset bundle.
 */
class IndexAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/index/css/index.css",
    ];
    public $js = [
        "plugins/index/js/index.js",
        "plugins/index/js/hAdmin.js?v=4.1.0",
        'plugins/newIndex/js/adminCtrl.js',
        'plugins/admin/js/adminCtrl.js',
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\FlotAsset',
    ];
}
