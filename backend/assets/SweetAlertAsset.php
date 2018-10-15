<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/30
 * Time: 16:46
 * Content:弹出框插件
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class SweetAlertAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/common/sweetalert/sweetalert.min.css",
    ];
    public $js = [
        "plugins/common/sweetalert/sweetalert.min.js",
        "plugins/common/sweetalert/angular-sweetalert.min.js",
    ];
    public $depends = [
    ];
}