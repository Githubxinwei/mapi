<?php
/**
 * 菜单管理
 */
/**
 * Created by PhpStorm.
 * User: suyu
 * Date: 2017/6/17
 * Time: 9:58
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MenuAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/operation/css/loaders.css",
        "plugins/menu/css/style.css"
    ];
    public $js = [
        "plugins/menu/js/menuController.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\ResetCtrlAsset',
    ];
}
