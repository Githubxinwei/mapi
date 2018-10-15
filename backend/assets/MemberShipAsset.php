<?php
/**
 * 会员入会
 * 苏雨
 * 2017-10-23
 */
namespace backend\assets;

use yii\web\AssetBundle;
/**
 * Main backend application asset bundle.
 */
class MemberShipAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/operation/css/loaders.css",
        "plugins/memberShip/css/style.css"
    ];
    public $js = [
        "plugins/memberShip/js/qrcode.min.js",
        "plugins/memberShip/js/memberShipController.js"
    ];
    public $depends = [
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\ResetCtrlAsset',
        'backend\assets\InputCheckAsset'
    ];
}