<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * content: 座位排次页面css，js
 * Date: 2017/7/26
 * Time: 17:58
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class VenueManagementCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/venueManagement/css/venueManagement.css"
    ];
    public $js = [
        "plugins/venueManagement/js/venueManagementCtrl.js"
    ];
    public $depends = [
        'backend\assets\ResetCtrlAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}