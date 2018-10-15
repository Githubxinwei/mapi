<?php

namespace backend\modules\v1\models;

use backend\models\MemberCourseOrder;
use common\models\Order;

class MemberPrivateGroupOrderForm extends \backend\models\MemberPrivateGroupOrderForm
{
    public $orderId;
    public $merchantOrderNumber;

    public function saveOrder()
    {
        $order = Order::findOne($this->orderId);
        $order->merchant_order_number = $this->merchantOrderNumber;
        $order->consumption_type_id = $this->memberCourseOrderId;
        $order->status = 2;
        if(!$order->save()) {
            return $order->errors;
        }
        return true;
    }

    public function saveMemberCourseOrder()
    {
        $model                          = new MemberCourseOrder();
        $model->course_amount           = $this->courseAmount;                      //课程总节数
        $model->overage_section         = $this->courseAmount;                      //剩余节数
        $model->create_at               = time();                                   //买课时间
        $model->money_amount            = $this->primePrice;                        //总金额
        $model->product_id              = $this->charge_class_id;                   //产品id
        $model->product_type            = 1;                                        //产品类型1私课,2团课
        $model->private_type          = '健身私教';
        $model->private_id              = $this->coachId;                           //销售私教id
        $model->present_course_number   = 0;                                        //赠课总次数
        $model->surplus_course_number   = $this->courseAmount;                      //剩余总课数
        $model->member_card_id          = $this->memberCardId;                      //会员卡ID
        $model->member_id               = $this->memberId;                          //会员id
        $model->business_remarks        = $this->saleType;
        $model->product_name            = $this->productName;                       //产品名称
        $model->activeTime              = time();                                   //激活时间
        $model->course_type             = 1;                                        //课程类型:1收费课,2免费课,3生日课
        $model->set_number              = $this->courseAmount;
        $model->type                    = 1;
        $model->seller_id               = isset(\Yii::$app->user->identity->id) ? \Yii::$app->user->identity->id : 0;
        $model->note                    = $this->buyNote;                           //备注
        $model->pay_status              = 1;                                        //1:已付款，2:已退款
        $model->class_number_id         = $this->numberId;                          //私课编号id
        $save                           = $model->save() ? $model : $model->errors;
        if (!isset($save->id)) {
            return $save->errors;
        }
        $this->memberCourseOrderId = $save->id;                                 //获取课程订单id
        return true;
    }

}