<?php
/**
 * 私课主页
 * @var string
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PrivateTeachCtrlAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        /**
         * 私课css
         */
        "plugins/privateTeach/css/index.css",
    ];
    public $js = [
        /**
         * 私课js
         */
        "plugins/privateTeach/js/indexController.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}