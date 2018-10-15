<?php
/**
 * 权限angularCtrl.js插件加载
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AuthCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/authority/css/authority.css",
        "plugins/authority/css/reset.css",
        "plugins/authority/css/style.css",
    ];
    public $js = [
        "plugins/authority/js/authorityCtrl.js",
         "plugins/authority/js/authority.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\IcheckAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}
