<?php
/**
 * Created by PhpStorm.
 * User: 苏雨
 * Date: 2017/08/22
 * Time: 14:12
 * content:财务统计
 */

namespace backend\assets;

use yii\web\AssetBundle;

class FinanceAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/finance/css/style.css"
    ];
    public $js = [
        "plugins/finance/js/financeController.js",
        "plugins/finance/js/classController.js",
        "plugins/finance/js/otherController.js",
        "plugins/finance/js/sellController.js",
        "plugins/finance/js/shareController.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\CommonAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}