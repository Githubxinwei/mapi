<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LaddaAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
//    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $css = [
        'plugins/common/ladda/ladda-themeless.min.css'
    ];
    public $js = [
        'plugins/common/ladda/spin.min.js',
        'plugins/common/ladda/ladda.min.js',
        'plugins/common/ladda/angular-ladda.min.js',
    ];
    public $depends = [
    ];
}
