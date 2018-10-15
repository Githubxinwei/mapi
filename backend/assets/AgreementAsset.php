<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/21
 * 组织架构管理插件
 * Time: 11:10
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AgreementAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/agreement/css/index.css",
    ];
    public $js = [
        "plugins/agreement/js/agreementCtrl.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}
