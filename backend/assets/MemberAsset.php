<?php
/**
 * 会员管理
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MemberAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [


        "plugins/member/css/userStyle.css",
        //指纹css
        "plugins/member/css/box.css",
        "plugins/member/css/dhtmlx.css",

    ];
    public $js = [

//        指纹js
        "plugins/member/js/main.js",
        "plugins/member/js/baseMoth.js",
        "plugins/member/js/dhtmlxCommon.js",
        "plugins/member/js/memberCheck.js",
        "plugins/member/js/memberListController.js",
        "plugins/member/js/fingerprint.js",

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
        'backend\assets\SweetAlertAsset',
        'backend\assets\InputCheckAsset',
    ];
}