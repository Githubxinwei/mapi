<?php
/**
 * 权限管理页面的资源加载
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class IcheckAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/authority/libs/css/switchery.css",
    ];
    public $js = [
        "plugins/authority/libs/js/switchery.js",//开关的插件
       
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
