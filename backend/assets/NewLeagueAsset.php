<?php
/**
 * Created by PhpStorm.
 * User: 李广杨
 * Date: 2017/5/18
 * Time: 14:47
 * 团课排课
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class NewLeagueAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/newLeague/css/style.css"
    ];
    public $js = [
        "plugins/newLeague/js/newLeague.js",
        "plugins/newLeague/js/newLeagueController.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\BootstrapSelectAsset'

    ];
}