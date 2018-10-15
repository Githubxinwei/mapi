<?php
/**
 * Created by PhpStorm.
 * User: 李广杨
 * Date: 2017/8/26
 * Time: 10:49
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class RefundApprovalAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/refundApproval/css/style.css"
    ];
    public $js = [
        "plugins/refundApproval/js/refundApprovalController.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\BootstrapSelectAsset'

    ];
}
