<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/31
 * Time: 22:33
 */

/**
 * checkbox插件加载
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class InputCheckAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/inputCheck/css/custom.css"
    ];
    public $js = [
        "plugins/authority/libs/js/icheck.min.js",
        "plugins/inputCheck/js/index.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
    ];
}
