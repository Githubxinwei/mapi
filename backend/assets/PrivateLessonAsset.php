<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/21
 * 组织架构管理插件
 * Time: 11:10
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PrivateLessonAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/privateLesson/css/index.css",
        "plugins/privateLesson/css/scheduleDetail.css",

        //指纹css
        "plugins/privateLesson/css/box.css",
        "plugins/privateLesson/css/dhtmlx.css",
    ];
    public $js = [
        "plugins/privateLesson/js/privateLesson.js",
        "plugins/privateLesson/js/privateLessonCtrl.js",

//        指纹js
        "plugins/privateLesson/js/main.js",
        "plugins/privateLesson/js/fingerprint.js",
        "plugins/privateLesson/js/baseMoth.js",
        "plugins/privateLesson/js/dhtmlxCommon.js",

    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\AngularAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\IcheckAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\ResetCtrlAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}
