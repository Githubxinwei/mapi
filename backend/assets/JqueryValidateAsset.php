<?php
/**
 * Created by PhpStorm.
 * User: 梁咳咳
 * Date: 2017/3/16
 * 验证插件引入
 * Time: 15:44
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class JqueryValidateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        "plugins/jqueryValidate/js/jquery.validate.min.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
    ];
}
