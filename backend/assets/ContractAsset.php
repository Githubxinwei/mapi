<?php
/**
 * 合同管理自定义
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ContractAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/contract/css/index.css",
    ];
    public $js = [
        "plugins/contract/js/contractController.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\CustomAsset',
//        'backend\assets\BootstrapAsset',
//        'backend\assets\FontAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}