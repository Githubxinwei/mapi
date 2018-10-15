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
class  NewDateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/newDate/datetimepicker/bootstrap-datetimepicker.min.css",
    ];
    public $js = [
        "plugins/newDate/datetimepicker/bootstrap-datetimepicker.js",
        "plugins/newDate/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js",
        "plugins/newDate/js/index.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',

    ];
}