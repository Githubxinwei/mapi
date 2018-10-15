<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2017/12/5
 * Time: 10:04
 * email:chengliming@itporsts.club
 * content:swiper轮播插件
 */
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class LazyLoadAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        "plugins/lazyload/js/html5shiv.js",
        "plugins/lazyload/js/jquery.lazyload.min.js",
        "plugins/lazyload/js/jquery.scrollstop.min.js",
    ];
    public $depends = [
    ];
}