<?php
/**
 * Created by PhpStorm.
 * User: 李广杨
 * Date: 2017/8/29
 * Time: 9:25
 * content:团课统计
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LeagueChartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/leagueStatistics/css/LeagueChart.css"
    ];
    public $js = [
        'plugins/admin/js/echarts.js',
        "plugins/leagueStatistics/js/LeagueChartCtrl.js",
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