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
class  DatesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/dates/daterangepicker-bs3.css",
    ];
    public $js = [
        "plugins/dates/moment.js",
        "plugins/dates/daterangepicker.js",
        "plugins/dates/js/date.js",

    ];
    public $depends = [
        'backend\assets\AdminAsset',

    ];
}