<?php
/**
 * Created by PhpStorm.
 * User: 苏雨
 * Date: 2017/5/10
 * 主页
 * Time: 16:02
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class IndexUpgradeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/indexUpgrade/css/style.css"
    ];
    public $js = [
        "plugins/indexUpgrade/js/index.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\FlotAsset',
        'backend\assets\PaceAsset',
//        'backend\assets\ScriptAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}
