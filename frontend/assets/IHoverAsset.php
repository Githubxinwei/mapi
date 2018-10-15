<?php
/**
 * Created by PhpStorm.
 * User: 程丽明
 * email:chengliming@itporsts.club
 * Date: 2017/12/5
 * Time: 10:13
 * content:IHover鼠标悬浮效果插件
 */
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class IHoverAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/iHover/css/iHover.css",
    ];
    public $js = [
    ];
    public $depends = [
    ];
}