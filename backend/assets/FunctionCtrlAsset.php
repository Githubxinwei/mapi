<?php
/**
 * Created by PhpStorm.
 * User: liangkeke
 * Date: 2017/5/11
 * Time: 14:47
 * 卡种详情页主页
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class  FunctionCtrlAsset extends AssetBundle
{
    /**
     * @var string 权限功能管理
     */
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/function/css/function.css",
    ];
    public $js = [
        "plugins/function/js/functionCtrl.js",
    ];
    public $depends = [
        'backend\assets\ResetCtrlAsset',
];
}