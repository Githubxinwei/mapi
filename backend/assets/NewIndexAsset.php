<?php
/**
 * 主页的css文件引入
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class NewIndexAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/newIndex/css/style.css",
    ];
    public $js = [
        "plugins/newIndex/js/newIndex.js"

    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\FlotAsset',
        'backend\assets\PaceAsset',
        'backend\assets\ScriptAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}