<?php
namespace backend\modules\v1\models;

use common\models\Func as commonFunc;
use common\models\MemberCourseOrder;

class ChargeClassNumber extends \backend\models\ChargeClassNumber
{
    public function fields()
    {
        $memberId = \Yii::$app->request->get('memberId', 0);
        return [
            'id',

            'class_number',

            'sell_number',

            'surplus',

            'total_class_num',

            'attend_class_num',

            'valid_time',

            'sale_num',

            'surplus_sale_num',

            'venue_name'=> function ($model) {
                return commonFunc::getRelationVal($model, 'organization', 'name');
            },

            'venue_address'=> function ($model) {
                return commonFunc::getRelationVal($model, 'venue', 'address');
            },

            'people_least' => function ($model) {
                return commonFunc::getRelationVal($model, 'chargeClassPeople', 'people_least');
            },

            'people_most' => function ($model) {
                return commonFunc::getRelationVal($model, 'chargeClassPeople', 'people_most');
            },

            'least_number' => function ($model) {
                return commonFunc::getRelationVal($model, 'chargeClassPeople', 'least_number');
            },

            'charge_class_type' => function ($model) {
                return commonFunc::getRelationVal($model, 'chargeClass', 'type');
            },

            'charge_class_pic' => function ($model) {
                return commonFunc::getRelationVal($model, 'chargeClass', 'pic');
            },

            'charge_class_name' => function ($model) {
                return commonFunc::getRelationVal($model, 'chargeClass', 'name');
            },

            'charge_class_describe' => function ($model) {
                return commonFunc::getRelationVal($model, 'chargeClass', 'describe');
            },

            'deal_id' => function ($model) {
                return commonFunc::getRelationVal($model, 'chargeClass', 'deal', 'id');
            },

            'deal_name' => function ($model) {
                return commonFunc::getRelationVal($model, 'chargeClass', 'deal', 'name');
            },

            'course_package' => function ($model) {
                return CoursePackageDetail::find()->where(['charge_class_id'=>$model->chargeClass->id])->all();
            },

            'price',

            'had_buy' => function ($model) use ($memberId) {
                return MemberCourseOrder::findOne(['member_id'=>$memberId, 'class_number_id'=>$model->id]) ? 1 : 0;
            },
        ];
    }

    public function getPrice()
    {
        switch (commonFunc::getRelationVal($this, 'chargeClass', 'type')){
            case 1:
                return commonFunc::getRelationVal($this, 'chargeClassPeople', 'unit_price') ?: '999999999.00';
                break;
            case 2:
                return number_format($this->total_class_num * $this->chargeClassPeople->unit_price, 2, '.', '');
                break;
            default:
                return '999999998.00';
                break;
        }
    }

    public function getLeast_number()
    {
        return commonFunc::getRelationVal($this, 'chargeClassPeople', 'least_number');
    }
}