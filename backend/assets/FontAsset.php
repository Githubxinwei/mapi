<?php
/**
 * Created by PhpStorm.
 * User: 梁可可
 * Date: 2017/4/17
 * 字体库插件
 * Time: 11:10
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class FontAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/font/css/font-awesome.css",
       
    ];
    public $js = [
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\CustomAsset',
    ];
}
