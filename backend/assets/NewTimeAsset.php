<?php
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */

/**
 * Class NewDateAsset
 * describe：这是一个范围日历插件
 */
class  NewTimeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/newtime/jquery.datetimepicker.css",
    ];
    public $js = [
//        "plugins/newtime/jquery.js",
        "plugins/newtime/build/jquery.datetimepicker.full.js",
        "plugins/newtime/js/time.js",
       
    ];
    public $depends = [
        'backend\assets\AdminAsset',

    ];
}