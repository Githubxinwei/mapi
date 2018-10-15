<?php
/**
 * Created by PhpStorm.
 * User: 梁咳咳
 * 插件
 * Date: 2017/3/16
 * Time: 15:44
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PaceAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        "plugins/pace/js/pace.min.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
    ];
}
