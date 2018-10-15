<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/7/5
 * Time: 11:55
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class SuperJurisdictionSetCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/superJurisdictionSet/css/index.css",
        "plugins/common/ui-switch/angular-ui-switch.min.css",
    ];
    public $js = [
        "plugins/superJurisdictionSet/js/superJurisdictionCtrl.js",
        "plugins/superJurisdictionSet/js/jurisdictionSetCtrl.js",
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