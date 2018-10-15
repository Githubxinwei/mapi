<?php

namespace common\models\relations;


use backend\models\Order;

trait MemberDepositRelations
{
    /**
     * 后台会员管理 - 定金表 - 关联订单表
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/12/29
     * @return mixed
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(),['consumption_type_id'=>'id']);
    }

}