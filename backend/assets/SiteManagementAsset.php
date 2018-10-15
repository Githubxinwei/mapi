<?php
/**
 * Created by PhpStorm.
 * User: 杨大侠
 * Date: 2017/9/6
 * Time: 10:44
 * 场地管理
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class SiteManagementAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/siteManagement/css/style.css"
    ];
    public $js = [
        "plugins/siteManagement/js/siteManagementController.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\BootstrapSelectAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
    ];
}