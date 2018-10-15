<?php
/**
 * Created by PhpStorm.
 * User: 梁咳咳
 * Date: 2017/3/16
 * 登录自定义引入
 * Time: 15:44
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LoginCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/login/css/index.css",
    ];
    public $js = [
        "plugins/login/js/loginController.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\LoginAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}
