<?php
/**
 * Created by PhpStorm.
 * User: 梁咳咳
 * Date: 2017/3/16
 * Time: 15:44
 */
/**
 * 验证插件
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ValidationAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [

        "plugins/validation/js/messages_zh.min.js",
        "plugins/validation/js/form-validate-demo.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\JqueryValidateAsset',
    ];
}
