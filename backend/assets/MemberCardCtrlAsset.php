<?php
/**
 * Created by PhpStorm.
 * User: 李广杨
 * Date: 2017/3/20
 * Time: 10:47
 * 卡种管理 主页
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class  MemberCardCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        /**
         * 主页css
         */
        "plugins/memberCard/css/index.css",
    ];
    public $js = [
        "plugins/memberCard/js/indexController.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\DatesAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}