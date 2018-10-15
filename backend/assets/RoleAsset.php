<?php
/**
 * Created by PhpStorm.
 * User: 李广杨
 * Date: 2017/6/17
 * Time: 10:42
 * 角色管理
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class RoleAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/role/css/role.css"
    ];
    public $js = [
        "plugins/role/js/role.js"

    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}