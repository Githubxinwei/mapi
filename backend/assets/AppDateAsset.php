<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 * app移动端日历插件
 */
class AppDateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/appDate/css/mobiscroll.frame.css",
        "plugins/appDate/css/mobiscroll.frame.android-holo.css",
        "plugins/appDate/css/mobiscroll.scroller.css",
        "plugins/appDate/css/mobiscroll.android-holo-light.css"
    ];
    public $js = [
        "plugins/appDate/js/zepto.min.js",
        "plugins/appDate/js/mobiscroll.zepto.js",
        "plugins/appDate/js/mobiscroll.core.js",
        "plugins/appDate/js/mobiscroll.frame.js",
        "plugins/appDate/js/mobiscroll.scroller.js",
        "plugins/appDate/js/mobiscroll.util.datetime.js",
        "plugins/appDate/js/mobiscroll.datetimebase.js",
        "plugins/appDate/js/mobiscroll.datetime.js",
        "plugins/appDate/js/mobiscroll.android-holo-light.js",
        "plugins/appDate/js/i18n/mobiscroll.i18n.zh.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}