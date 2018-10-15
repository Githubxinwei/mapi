<?php

/**
 * 验卡管理
 */
namespace backend\assets;

use yii\web\AssetBundle;

class CheckCardCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        验卡页面css
        "plugins/checkCard/css/style.css",
        "plugins/checkCard/css/detail.css",
        "plugins/checkCard/css/courseList.css",

    ];
    public $js = [
//        验卡页面js
        "plugins/checkCard/js/chectCard.js",
        "plugins/checkCard/js/checkCardCtrl.js",
        "plugins/checkCard/js/checkCourseCtrl.js", 
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\ResetCtrlAsset',
    ];
}