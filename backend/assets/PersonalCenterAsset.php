<?php
/**
 * Created by PhpStorm.
 * User: 杨大侠
 * Date: 2017/7/6
 * Time: 11:18
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PersonalCenterAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/personalCenter/css/index.css",
    ];
    public $js = [
        "plugins/personalCenter/js/index.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}