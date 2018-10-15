<?php
namespace common\models\relations;

use common\models\base\ChargeClass;
use common\models\base\Course;
use common\models\base\Organization;

trait  CoursePackageDetailRelations
{
    /**
     * 后台 - 会员管理 - 关联数据收费课程表
     * @return string
     * @auther 黄华
     * @create 2017-4-22
     * @param
     */
    public function getChargeClass(){
        return $this->hasOne(ChargeClass::className(), ['id'=>'charge_class_id']);
    }
    /**
     * 后台 - 会员管理 - 关联数据收费课程表
     * @return string
     * @auther 黄华
     * @create 2017-4-22
     * @param
     */
    public function getCourse(){
        return $this->hasOne(\backend\models\Course::className(), ['id'=>'course_id']);
    }
    /**
     * 后台 - 会员管理 - 关联数据收费课程表
     * @return string
     * @auther 黄鹏举
     * @create 2017-5-20
     * @param
     */
    public function getCourses(){
        return $this->hasMany(Course::className(), ['id'=>'course_id']);
    }
}