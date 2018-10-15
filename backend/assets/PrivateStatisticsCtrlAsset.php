<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/8/10
 * Time: 16:20
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PrivateStatisticsCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/operation/css/loaders.css",
        "plugins/privateStatistics/css/privateStatistics.css"
    ];
    public $js = [
        'plugins/admin/js/echarts.js',
        "plugins/privateStatistics/js/privateStatisticsCtrl.js"
    ];
    public $depends = [
        'backend\assets\ResetCtrlAsset',
        'backend\assets\DatesAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}