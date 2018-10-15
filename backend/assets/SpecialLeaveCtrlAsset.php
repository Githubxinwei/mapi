<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * author :程丽明
 * Date: 2017/8/24
 * Time: 15:12
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class SpecialLeaveCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/specialLeave/css/specialLeave.css"
    ];
    public $js = [
        "plugins/specialLeave/js/specialLeaveCtrl.js"
    ];
    public $depends = [
        'backend\assets\ResetCtrlAsset',
        'backend\assets\DatesAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}