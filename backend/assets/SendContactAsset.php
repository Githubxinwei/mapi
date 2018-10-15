<?php
/**
* Created by PhpStorm.
* User: 苏雨
* Date: 2017/6/7
* Time: 9:12
* content:合同
*/

namespace backend\assets;

use yii\web\AssetBundle;

/**
* Main backend application asset bundle.
*/
class SendContactAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        "plugins/selling/js/sellingController.js",
        "plugins/selling/js/sellingCtrl.js",
        "plugins/potentialMembers/js/potentialController.js",
        "plugins/user/js/userController.js"
    ];
    public $depends = [
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\ResetCtrlAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}