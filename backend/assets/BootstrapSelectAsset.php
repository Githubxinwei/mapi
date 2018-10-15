<?php
/**
 * Created by PhpStorm.
 * User: liangkeke
 * Date: 2017/5/15
 * Time: 10:42
 * 售卡管理
 */
/**
 * Created by PhpStorm.
 * User: 苏雨
 * Date: 2017/6/7
 * 下拉菜单插件
 * Time: 16:46
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class BootstrapSelectAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/bootstrapSelect/select2.css"
    ];
    public $js = [
        "plugins/bootstrapSelect/select2.full.js",
        "plugins/bootstrapSelect/select2.js"
    ];
    public $depends = [
        'backend\assets\AdminAsset'
    ];
}