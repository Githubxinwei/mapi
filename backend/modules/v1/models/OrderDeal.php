<?php
namespace backend\modules\v1\models;

use Yii;
use common\models\Func;
use common\models\MemberCourseOrder;
use common\models\Order;

class OrderDeal extends Order
{
    public function fields()
    {
        return [
            'id',

            'product_name',

            'order_number',

            'pay_money_time',

            'venue_name' => function ($model) {
                return Func::getRelationVal($model, 'organization', 'name');
            },

            'deal_name' => function ($model) {
                return Func::getRelationVal($model, 'deal', 'name');
            },

            'view_url' => function ($model) {
                return Yii::$app->request->hostInfo . '/v1/default/order-deal?id=' . $model->id;
            },

            'down_url' => function ($model) {
                return Yii::$app->request->hostInfo . '/v1/default/order-deal?id=' . $model->id . '&pdf=1';
            },
        ];
    }

    public function getProduct_name()
    {
        switch ($this->consumption_type){
            case 'card':
                return Func::getRelationVal($this, 'memberCard', 'card_name');
                break;
            case 'charge':
            case 'chargeGroup':
                $course_order = MemberCourseOrder::findOne($this->consumption_type_id);
                return Func::getRelationVal($course_order, 'product_name');
                break;
            default:
                return 'blank';
                break;
        }
    }

    public function getDeal()
    {
        switch ($this->consumption_type){
            case 'card':
                return Func::getRelationVal($this, 'memberCard', 'deal');
                break;
            case 'charge':
            case 'chargeGroup':
                $course_order = MemberCourseOrder::findOne($this->consumption_type_id);
                return Func::getRelationVal($course_order, 'chargeClass', 'deal');
                break;
            default:
                return 'blank';
                break;
        }
    }
}



