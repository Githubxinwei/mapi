<?php
/**
 * 私课 预约模块 子页面  预约
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PrivateAppointmentCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        /**
         * 预约 css
         */
        "plugins/privateTeach/css/appointment.css",
    ];
    public $js = [
        /**
         * 预约 js
         */
        "plugins/privateTeach/js/appointmentController.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}