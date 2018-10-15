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
class LeagueStatisticsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/leagueStatistics/css/leagueStatistics.css"
    ];
    public $js = [
        "plugins/leagueStatistics/js/leagueStatisticsCtrl.js",
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