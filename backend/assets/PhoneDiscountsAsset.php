<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PhoneDiscountsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/phoneDiscounts/css/index.css"
    ];
    public $js = [
        "plugins/phoneDiscounts/js/phoneDiscountsController.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\BootstrapSelectAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\NewDateAsset',
    ];
}
