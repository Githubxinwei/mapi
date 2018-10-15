<?php
/**
 * 用户行为轨迹
 */
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class BehaviorTrajectoryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "plugins/behaviorTrajectory/css/behaviorTrajectory.css",
    ];
    public $js = [

        "plugins/behaviorTrajectory/js/behaviorTrajectory.js",
    ];
    public $depends = [
        'backend\assets\JqueryStepsAsset',
        'backend\assets\AngularAsset',
        'backend\assets\LaddaAsset',
        'backend\assets\CommonAsset',
        'backend\assets\DatesAsset',
        'backend\assets\JqueryValidateAsset',
        'backend\assets\BootstrapSelectAsset'

    ];
}