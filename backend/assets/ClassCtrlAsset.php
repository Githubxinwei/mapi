<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/21
 * 组织架构管理插件
 * Time: 11:10
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ClassCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/league/css/league.css",
        //课种页面css
        "plugins/class/css/index.css"
    ];
    public $js = [
        "plugins/class/js/classController.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}
