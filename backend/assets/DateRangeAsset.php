<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/31
 * Time: 15:44
 * contant 日期范围插件
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class DateRangeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/dateRange/css/datepicker3.css"
    ];
    public $js = [
        "plugins/dateRange/js/bootstrap-datepicker.js",
        "plugins/dateRange/js/cropper.min.js",
        "plugins/dateRange/js/index.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
    ];
}
