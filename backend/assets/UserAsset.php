<?php
/**
 * 会员管理
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class UserAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [


        "plugins/user/css/userStyle.css",
        //指纹css
        "plugins/user/css/box.css",
        "plugins/user/css/dhtmlx.css",

    ];
    public $js = [

//        指纹js
        "plugins/user/js/main.js",
        "plugins/user/js/baseMoth.js",
        "plugins/user/js/dhtmlxCommon.js",
        "plugins/user/js/user.js",
        "plugins/user/js/userController.js",
        "plugins/user/js/fingerprint.js",

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