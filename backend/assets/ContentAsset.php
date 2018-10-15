<?php
/**
 * 全局content.js
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ContentAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        "plugins/admin/js/content.js",

    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}