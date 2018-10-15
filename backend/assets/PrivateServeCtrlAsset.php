<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/4/17
 * Time: 19:35
 */
/**
 * 私课 添加私教页面
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PrivateServeCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        /**
         * 因为 这个页面的表单向导自定义的css 需要和 会员卡管理的页面重用  所以要引入
         */
        "plugins/privateTeach/css/add.css",
    ];
    public $js = [
        /**
         *  添加私教课程页面 自定义 js文件
         */
        "plugins/privateTeach/js/addServe.js",

    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\ResetCtrlAsset',
    ];
}