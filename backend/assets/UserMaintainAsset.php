<?php
/**
 * Created by PhpStorm.
 * User: 王康
 * Date: 2017/11/24时
 * Time: 10:42
 * 会员复测推送 信息
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class UserMaintainAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/userMaintain/css/style.css"
    ];
    public $js = [
        "plugins/userMaintain/js/userMaintainCtrl.js"

    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}