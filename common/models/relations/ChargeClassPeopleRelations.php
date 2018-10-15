<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8 0008
 * Time: 13:37
 */

namespace common\models\relations;


use backend\models\ChargeClass;
use backend\models\ChargeClassPrice;

trait ChargeClassPeopleRelations
{
    /*
     * 私课人数区间表 - 关联私课价格表
     * */
    public function getChargeClassPrice()
    {
        return $this->hasOne(ChargeClassPrice::className(),['id'=>'class_price_id']);
    }

    public function getChargeClass()
    {
        return $this->hasOne(ChargeClass::className(),['id'=>'charge_class_id']);
    }
}