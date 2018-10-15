<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/21
 * Time: 11:10
 * content:团课管理页面
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LeagueCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //团课管理页面中css
        "plugins/league/css/league.css"
    ];
    public $js = [
//        团课管理页面中的js和控制器
        "plugins/league/js/leagueCtrl.js",
        "plugins/league/js/league.js",
        "plugins/league/js/updateCourse.js",
        "plugins/league/js/leagueDetail.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
         'backend\assets\ResetCtrlAsset',
    ];
}
