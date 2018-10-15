<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * author:程丽明
 * Date: 2017/8/28
 * Time: 14:33
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class StoreManagementCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/storeManagement/css/storeManagement.css"
    ];
    public $js = [
        "plugins/storeManagement/js/storeManagementCtrl.js"
    ];
    public $depends = [
        'backend\assets\ResetCtrlAsset',
        'backend\assets\DatesAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}