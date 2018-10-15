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
class SwiperAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/swiper/css/swiper.min.css",
    ];
    public $js = [
        "plugins/swiper/js/swiper.min.js",
    ];
    public $depends = [
    ];
}