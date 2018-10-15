<?php

/**
 * 全局加载的插件
 */
namespace backend\common;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class BaseAsset extends AssetBundle
{
    public $sourcePath = '@webroot/plugins';
    public $baseUrl = '@web';
}
