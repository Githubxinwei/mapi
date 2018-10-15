<?php
namespace backend\modules\v1\models;


class CoursePackageDetail extends \backend\models\CoursePackageDetail
{
    public function fields()
    {
        return [
            'id',

            'course_num',

            'course_length',

            'original_price',

            'course_name' => function ($model) {
                return \common\models\Func::getRelationVal($model, 'course', 'name');
            },

        ];
    }

}