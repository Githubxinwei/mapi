<?php
/**
 * Created by PhpStorm.
 * User: 梁咳咳
 * Date: 2017/3/16
 * 登录插件引入
 * Time: 15:44
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/login/css/login.css",
        "plugins/login/css/style.css",
        "plugins/login/css/drag.css",
    ];
    public $js = [
        "plugins/login/js/drag.js",
        "plugins/login/js/login.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}
