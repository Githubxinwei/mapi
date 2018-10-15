<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/21
 * 员工管理插件引入
 * Time: 11:10
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PersonalCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/personal/css/index.css",
        //指纹css
        "plugins/personal/css/box.css",
        "plugins/personal/css/dhtmlx.css",
        //图片剪裁插件css
        "plugins/common/ng-file-upload/demo/src/main/webapp/js/ng-img-crop.css",
    ];
    public $js = [
//        自定义员工管理angularJs
        "plugins/personal/js/personalController.js",

//        指纹js
        "plugins/personal/js/main.js",
        "plugins/personal/js/fingerprint.js",
        "plugins/personal/js/baseMoth.js",
        "plugins/personal/js/dhtmlxCommon.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\UserAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\ResetCtrlAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}
