<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/3 0003
 * Time: 下午 5:14
 */
namespace common\models\relations;
use common\models\base\ChargeClassPeople;
use common\models\base\ChargeClassPrice;
use common\models\ChargeGroupClass;
use common\models\base\MemberCourseOrder;
use common\models\Organization;

trait ChargeClassNumberRelations
{
    /**
     * 私课编号表 - 关联私课产品价格表
     * @return string
     * @auther 朱梦珂
     * @create 2018/01/03
     */
    public function getChargeClassPrice()
    {
        return $this->hasOne(ChargeClassPrice::className(),['id'=>'class_price_id']);
    }
    /**
     * 私课编号表 - 关联会员课程订单表
     * @return string
     * @auther 朱梦珂
     * @create 2018/01/03
     */
    public function getMemberCourseOrder()
    {
        return $this->hasOne(MemberCourseOrder::className(),['class_number_id'=>'id']);
    }
    /**
     * 私课编号表 - 关联私课表
     * @return string
     * @auther 朱梦珂
     * @create 2018/01/05
     */
    public function getChargeClass()
    {
        return $this->hasOne(\common\models\ChargeClass::className(),['id'=>'charge_class_id']);
    }
    /**
     * 私课编号表 - 关联私课表
     * @return string
     * @auther 朱梦珂
     * @create 2018/01/05
     */
    public function getChargeGroupClass()
    {
        return $this->hasOne(ChargeGroupClass::className(),['class_number_id'=>'id']);
    }
    /*
     * 私课编号表 - 私课人数区间表
     * */
    public function getChargeClassPeople()
    {
        return $this->hasOne(ChargeClassPeople::className(),['id'=>'class_people_id']);
    }

    public function getVenue()
    {
        return $this->hasOne(Organization::className(), ['id'=>'venue_id']);
    }
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id'=>'venue_id']);
    }
}