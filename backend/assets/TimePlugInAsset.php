<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/31
 * Time: 15:44
 * contant 时间插件
 */
/**
 * 时间插件
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class TimePlugInAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/timePlugIn/css/clockpicker.css"
    ];
    public $js = [
        "plugins/timePlugIn/js/clockpicker.js",
        "plugins/timePlugIn/js/index.js"
        
];
    public $depends = [
        'backend\assets\AdminAsset',
    ];
}
