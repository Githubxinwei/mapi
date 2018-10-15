<?php
/**
 * 约课管理
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class JurisdictionCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/jurisdiction/css/jurisdiction.css",
        "plugins/common/ui-switch/angular-ui-switch.min.css",
    ];
    public $js = [
        "plugins/jurisdiction/js/jurisdiction.js",
        "plugins/jurisdiction/js/jurisdictionCtrl.js",
    ];
    public $depends = [
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\IcheckAsset',
        'backend\assets\ResetCtrlAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}