<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/21
 * Time: 11:10
 */
/**
 * 组织架构管理引入资源
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MainCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/main/css/main.css"
    ];
    public $js = [
//        组织架构自定义angularJs
        "plugins/main/js/mainController.js",
        "plugins/main/js/main.js",
        "plugins/main/js/addVenue.js",
        "plugins/main/js/addCompany.js",
        "plugins/main/js/addSiteCtrl.js",
        "plugins/main/js/addDepartment.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
         'backend\assets\UserAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}
