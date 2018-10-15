<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/31
 * Time: 22:33
 * contant 时间插件
 */

/**
 * 表单向导插件引入
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class JqueryStepsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/jquerySteps/css/jquery.steps.css"
    ];
    public $js = [
        "plugins/jquerySteps/js/jquery.steps.min.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
    ];
}