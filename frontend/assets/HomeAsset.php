<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class HomeAsset extends AssetBundle
{
    public $basePath  = '@webroot';
    public $baseUrl   = '@web';
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $css = [
        'home/css/style.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}