<?php
/**
 * Created by PhpStorm.
 * User: liangkeke
 * Date: 2017/5/15
 * Time: 10:42
 * 售卡管理
 */
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/21
 * 售卡
 * Time: 11:10
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class SellingAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/selling/css/index.css"
    ];
    public $js = [
        "plugins/selling/js/sellingController.js",
        "plugins/selling/js/sellingCtrl.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}
