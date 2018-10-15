<?php
/**
 * Created by PhpStorm.
 * User: 苏雨
 * Date: 2017/6/6
 * Time: 10:42
 * 销售统计管理
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class SellIndexAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/operation/css/loaders.css",
        "plugins/sellIndex/css/style.css"

    ];
    public $js = [
        'plugins/admin/js/echarts.js',
        "plugins/sellIndex/js/sellIndexController.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\FlotAsset',
        'backend\assets\PaceAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}