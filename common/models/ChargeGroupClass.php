<?php

namespace common\models;

use common\models\relations\ChargeGroupClassRelations;

class ChargeGroupClass extends \common\models\base\ChargeGroupClass
{
    use ChargeGroupClassRelations;

    /**
     * 第几节课
     */
    public function getTimes()
    {
        $times = self::find()->where(['class_number_id'=>$this->class_number_id])->andWhere(['<=', 'start', $this->start])->andWhere(['<>', 'status', 5])->count();
        $details = MemberCourseOrderDetails::find()->joinWith('memberCourseOrder mco')->where(['mco.class_number_id'=>$this->class_number_id])->orderBy('id asc')->all();
        if(!$details) return ['times'=>0,'name'=>''];

        foreach ($details as $detail){
            if($times < $detail->course_num){
                return ['times'=>$times, 'name'=>$detail->course_name,'length'=>$detail->class_length];
            }
            $times -= $detail->course_num;
        }
    }
}
