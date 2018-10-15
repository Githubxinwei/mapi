<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 * 全局左侧插件引入
 */
class IconAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [


    ];
    public $js = [
        'plugins/icon/js/echarts-all.js',
        'plugins/icon/js/echarts-demo.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}