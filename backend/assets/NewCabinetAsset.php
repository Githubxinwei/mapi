<?php
/**
 * 柜子管理
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class NewCabinetAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    "plugins/newCabinet/css/style.css"
];
    public $js = [
    "plugins/newCabinet/js/newCabinetController.js",
];
    public $depends = [
    'backend\assets\AdminAsset',
    'backend\assets\LaddaAsset',
    'backend\assets\AngularAsset',
    'backend\assets\DatesAsset',
    'backend\assets\NewDateAsset',
    'backend\assets\TimePlugInAsset',
    'backend\assets\CommonAsset',
    'backend\assets\ResetCtrlAsset',
];
}