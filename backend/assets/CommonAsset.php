<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class CommonAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $css = [

    ];
    public $js = [
 //开关js
        "plugins/common/ui-switch/angular-ui-switch.min.js",
//图片剪裁插件js
        "plugins/common/ng-file-upload/demo/src/main/webapp/js/ng-img-crop.js",
        'plugins/common/common.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
