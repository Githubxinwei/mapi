<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2017/9/6
 * Time: 11:31
 * content: 赠卡页面css、js引入
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class GiftCardCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/giftCard/css/giftCard.css"
    ];
    public $js = [
        "plugins/giftCard/js/giftCardCtrl.js"
    ];
    public $depends = [
        'backend\assets\ResetCtrlAsset',
        'backend\assets\DatesAsset',
        'backend\assets\BootstrapSelectAsset'
    ];
}