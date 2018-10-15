<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PhoneTotalAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/phoneTotal/css/phoneTotal.css"
    ];
    public $js = [
        "plugins/phoneTotal/js/phoneTotal.js"

    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\DatesAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}
