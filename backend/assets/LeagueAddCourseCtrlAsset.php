<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2017/4/11
 * Time: 15:46
 * content:团课页面  新增课程页面 表单向导js添加
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LeagueAddCourseCtrlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        //新增课程页面中js
        "plugins/league/js/addCourse.js",
        "plugins/league/js/groupClassController.js"
    ];
    public $depends = [
        'backend\assets\JqueryStepsAsset',
        'backend\assets\LeagueCtrlAsset'
    ];
}
