<?php
namespace common\models\relations;


use backend\models\ChargeClass;
use common\models\base\ChargeClassPeople;
use common\models\base\Course;
use backend\models\CoursePackageDetail;

trait ChargeClassPriceRelations
{
    /**
     * 后台 - 私教课程单节价格 - 关联私教课程详情表
     * @return string
     * @auther 黄鹏举
     * @create 2017-5-19
     * @param
     */
    public function getCoursePackageDetail()
    {
        return $this->hasOne(CoursePackageDetail::className(),['id'=>'course_package_detail_id']);
    }
    /**
     * 后台 - 私教课程单节价格 - 关联课种表
     * @return string
     * @auther 黄鹏举
     * @create 2017-5-19
     * @param
     */
    public function getCourse(){
        return $this->hasOne(Course::className(), ['id'=>'course_id']);
    }

    /**
     * 后台 - 私教课程区间 - 关联产品表
     * @return mixed
     */
    public function getChargeClass()
    {
        return $this->hasOne(ChargeClass::className(),['id'=>'charge_class_id']);
    }

    /*
     * 后台 - 私教课程区间 - 私教人数区间表
     * */
    public function getChargeClassPeople()
    {
        return $this->hasOne(ChargeClassPeople::className(),['id'=>'class_price_id']);
    }

}