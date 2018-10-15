<?php
/**
 * Created by PhpStorm.
 * User: 苏雨
 * Date: 2017/5/22
 * 潜在会员页面
 * Time: 13:30
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PotentialMembersAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/potentialMembers/css/style.css"
    ];
    public $js = [
        "plugins/potentialMembers/js/potentialController.js",
//        "plugins/potentialMembers/js/timeController.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\ResetCtrlAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}