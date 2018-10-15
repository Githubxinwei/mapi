<?php
/**
 * 柜子的管理
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class CabinetCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/cabinet/css/style.css",
    ];
    public $js = [
        "plugins/cabinet/js/cabinet.js",
        "plugins/cabinet/js/cabinetCtrl.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}