<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/4/9
 * Time: 11:06
 */
namespace backend\modules\v1\models;

class CoachClassRecord extends \common\models\base\CoachClassRecord
{
    public function getCoachStatus($id)
    {
        $coach = CoachClassRecord::find()->where(['id'=>$id])->asArray()->one();
        if ($coach && !empty($coach)) {
            $coach_status = $coach['status'];
            return $coach_status;
        }
    }
}