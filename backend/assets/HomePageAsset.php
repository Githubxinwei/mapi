<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/24
 * Time: 9:06
 */
namespace backend\assets;

use yii\web\AssetBundle;
class  HomePageAsset extends AssetBundle{
    /**
     * @var string homepage
     */
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        "plugins/homepage/js/homepageController.js",
    ];
    public $depends = [
        'backend\assets\ResetCtrlAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset'
    ];
}