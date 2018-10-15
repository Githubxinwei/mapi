<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * 公共管理 - 属性匹配 - 会员卡属性匹配
 * @author zhujunzhe@itsports.club
 * @create 2018/1/31 am
 */
class CardTypeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/cardType/css/style.css"
    ];
    public $js = [
        "plugins/cardType/js/cardType.js"

    ];
    public $depends = [
        'backend\assets\AdminAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\DatesAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}
