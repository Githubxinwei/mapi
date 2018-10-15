<?php
/**
 * 跨店升级
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class CrossShopAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/crossShop/css/style.css",
    ];
    public $js = [
        "plugins/crossShop/js/crossShopCtrl.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\ResetCtrlAsset',
        'backend\assets\BootstrapSelectAsset',
        'backend\assets\InputCheckAsset',
    ];
}