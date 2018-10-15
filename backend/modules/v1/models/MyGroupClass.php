<?php
namespace backend\modules\v1\models;

use common\models\Func;
use common\models\GroupClass;

class MyGroupClass extends GroupClass
{

    public function fields()
    {
        return [
            'id',

            'course_pic'=>function($model){
                return Func::getRelationVal($model, 'course', 'pic');
            },

            'course_name'=>function($model){
                return Func::getRelationVal($model, 'course', 'name');
            },

            'venue_id',

            'venue_name'=>function($model){
                return Func::getRelationVal($model, 'organization', 'name');
            },

            'venue_address'=>function($model){
                return Func::getRelationVal($model, 'organization', 'address');
            },
        ];
    }
}