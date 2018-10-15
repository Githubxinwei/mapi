<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2017/12/5
 * Time: 12:04
 * email:chengliming@itporsts.club
 * content:css初始化
 */
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ResetAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/reset/css/reset.css",
    ];
    public $js = [
    ];
    public $depends = [
    ];
}