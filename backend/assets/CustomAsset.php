<?php
/**
 * Created by PhpStorm.
 * User: 梁可可
 * Date: 2017/4/17
 * 编译器插件
 * Time: 11:10
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class CustomAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/custom/css/summernote.css",
//        "plugins/custom/css/summernote-bs3.css"
    ];
    public $js = [
        "plugins/custom/js/custom.js",
        "plugins/custom/js/summernote.min.js",
        "plugins/custom/js/summernote-zh-CN.js",

    ];
    public $depends = [
        'backend\assets\AdminAsset',

    ];
}
