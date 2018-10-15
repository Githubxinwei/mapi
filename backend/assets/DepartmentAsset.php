<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * Date: 2017/3/21
 * Time: 11:10
 * content:组织架构 - 场地 -控制器js文件
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class DepartmentAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
//        组织架构管理 场地页面控制器
        "plugins/main/js/departmentMain.js"
    ];
    public $depends = [
        'backend\assets\MainCtrlAsset',
         'backend\assets\DatesAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}
