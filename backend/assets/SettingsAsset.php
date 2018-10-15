<?php
/**
 * 主页的css文件引入
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class SettingsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/newIndex/css/style.css",
        "https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css"
    ];
    public $js = [
        "plugins/newIndex/js/settingsController.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}