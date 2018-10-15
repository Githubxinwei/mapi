<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * author:程丽明
 * Date: 2017/9/28
 * Time: 10:20
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ApprovalManagementAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/approvalManagement/css/approvalManagement.css"
    ];
    public $js = [
        "plugins/approvalManagement/js/approvalManagementCtrl.js"
    ];
    public $depends = [
        'backend\assets\ResetCtrlAsset',
        'backend\assets\DatesAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}