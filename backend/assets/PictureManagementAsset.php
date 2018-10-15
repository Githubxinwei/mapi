<?php
/**
 * Created by PhpStorm.
 * User: 李广杨
 * Date: 2017/8/11
 * Time: 14:22
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PictureManagementAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/pictureManagement/css/style.css"
    ];
    public $js = [
        "plugins/pictureManagement/js/pictureManagementController.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\ResetCtrlAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}