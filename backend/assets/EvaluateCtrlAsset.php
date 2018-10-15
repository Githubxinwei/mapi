<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/21
 * Time: 11:10
 * content: 评价管理页面
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class EvaluateCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        评价管理页面中的css
        "plugins/evaluate/css/evaluate.css",
    ];
    public $js = [
//        评价管理页面中的ctrl和js
        "plugins/evaluate/js/evaluateCtrl.js",
        "plugins/evaluate/js/evaluate.js",
    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\DatesAsset',
        'backend\assets\NewDateAsset',
        'backend\assets\TimePlugInAsset',
        'backend\assets\InputCheckAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}