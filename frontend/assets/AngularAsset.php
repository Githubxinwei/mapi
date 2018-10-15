<?php
/**
 * angular加载插件
 */
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AngularAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];//让angularjs加载在最上面
    public $css = [
    ];
    public $js = [
        "plugins/common/angular/angular.min.js",
    ];
    public $depends = [
    ];
}
