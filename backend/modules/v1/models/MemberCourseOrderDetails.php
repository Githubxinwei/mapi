<?php
namespace backend\modules\v1\models;


class MemberCourseOrderDetails extends \common\models\MemberCourseOrderDetails
{
    public function fields()
    {
        return [
            'id',

            'product_name',

            'course_name',

            'class_length',

            'times' => function(){
         
            }
        ];
    }

    public function getTimes()
    {

    }

}