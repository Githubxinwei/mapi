<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/2
 * Time: 17:11
 * Desc:体适能评估表
 */
namespace common\models;

use common\models\relations\FitnessAssessmentRelations;

class FitnessAssessment extends \common\models\base\FitnessAssessment
{
    use FitnessAssessmentRelations;

}