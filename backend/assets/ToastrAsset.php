<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 * 提示框 就是那个弹框控件
 */
class ToastrAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'plugins/common/toastr/toastr.min.css',
        'plugins/common/sweetalert/sweetalert.min.css'
    ];
    public $js = [
        'plugins/common/sweetalert/sweetalert.min.js',
        ['plugins/common/ng-file-upload/dist/ng-file-upload.min.js','position'=>\yii\web\View::POS_HEAD],
        ['plugins/common/ng-file-upload/dist/ng-file-upload-shim.js','position'=>\yii\web\View::POS_HEAD],
        'plugins/common/toastr/toastr.min.js',
    ];
    public $depends = [

    ];
}
