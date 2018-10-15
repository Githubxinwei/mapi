<?php
/**
 * Created by PhpStorm.
 * User: 梁咳咳
 * 主页的插件引入
 * Date: 2017/3/16
 * Time: 15:44
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ScriptAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        "plugins/index/js/script.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
    ];
}