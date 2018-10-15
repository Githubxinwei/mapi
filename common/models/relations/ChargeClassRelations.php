<?php
namespace common\models\relations;

use common\models\AboutClass;
use common\models\base\ChargeClassNumber;
use common\models\base\ChargeClassService;
use backend\models\ChargeClassPrice;
use common\models\base\Course;
use backend\models\CoursePackageDetail;
use common\models\Deal;
use common\models\base\MemberCourseOrder;
use common\models\base\Organization;
use common\models\base\ClassSaleVenue;
use common\models\base\Employee;
trait  ChargeClassRelations
{
    /**
     * 后台 - 私教课程管理 - 关联组织架构表
     * @return string
     * @auther 黄华
     * @create 2017-4-10
     * @param
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }
    /**
     * 后台 - 私教课程管理 - 课程套餐详情表
     * @return string
     * @auther 朱梦珂
     * @create 2017-4-26
     * @param
     */
    public function getCoursePackageDetail()
    {
        return $this->hasMany(CoursePackageDetail::className(),['charge_class_id'=>'id']);
    }
    /**
     * 后台 - 私教课程管理 - 课程单节课
     * @return string
     * @auther 黄鹏举
     * @create 2017-5-20
     * @param
     */
    public function getCoursePackageDetails()
    {
        return $this->hasMany(CoursePackageDetail::className(),['charge_class_id'=>'id']);
    }
    /**
     * 后台 - 私教课程管理 - 私课售卖场馆表
     * @return string
     * @auther 朱梦珂
     * @create 2017-4-26
     * @param
     */
    public function getClassSaleVenue()
    {
        return $this->hasMany(ClassSaleVenue::className(),['charge_class_id'=>'id']);
    }
    public function getClassSaleVenueOne()
    {
        return $this->hasOne(ClassSaleVenue::className(),['charge_class_id'=>'id']);
    }
    /**
     * 后台 - 会员 - 会员详情私课
     * @return string
     * @auther 黄华
     * @create 2017-5-12
     * @param
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(),['id'=>'create_id']);
    }
    /**
     * 后台 - 私教课程管理 - 课程单节课
     * @return string
     * @auther 黄鹏举
     * @create 2017-5-20
     * @param
     */
    public function getChargeClassPrice()
    {
        return $this->hasMany(ChargeClassPrice::className(),['charge_class_id'=>'id']);
    }
    /**
     * 后台 - 私教课程管理 - 课程单节课
     * @return string
     * @auther 黄鹏举
     * @create 2017-7-7
     * @param
     */
    public function getChargeClassPriceAll()
    {
        return $this->hasMany(ChargeClassPrice::className(),['charge_class_id'=>'id']);
    }
    /**
     * 后台 - 私教课程管理 - 课程单节课
     * @return string
     * @auther 黄鹏举
     * @create 2017-7-7
     * @param
     */
    public function getCoursePackageDetailsAlone()
    {
        return $this->hasOne(CoursePackageDetail::className(),['charge_class_id'=>'id']);
    }
    /**
     * 后台 - 私教课程管理 - 课程单节课
     * @return string
     * @auther 黄鹏举
     * @create 2017-5-20
     * @param
     */
    public function getChargeClassService()
    {
        return $this->hasOne(ChargeClassService::className(),['charge_class_id'=>'id']);
    }
    /**
     * 后台 - 私教课程管理 - 获取课程名称
     * @return string
     * @auther 侯凯新
     * @create 2017-5-27
     * @param
     */
    public function getCourse(){
        return $this->hasOne(Course::className(),['id'=>'course_id']);
    }
    /**
     * 后台 - 私教课程管理 -  关联会员订单表
     * @return string
     * @auther 侯凯新
     * @create 2017-5-27
     * @param
     */
    public function getPrivateOrder(){
        return $this->hasMany(MemberCourseOrder::className(),['product_id'=>'id'])->onCondition(["and",[">","deadline_time",time()],[">","overage_section",0]]);
    }

    /**
     * 后台 - 私教课程 - 关联合同表
     * @return string
     * @auther 朱梦珂
     * @create 2017/11/04
     */
    public function getDeal()
    {
        return $this->hasOne(Deal::className(),['id'=>'deal_id']);
    }

    /**
     * @desc: 后台-多人私课-关联编号表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/15
     * @return mixed
     */
    public function getChargeClassNumber()
    {
        return $this->hasMany(ChargeClassNumber::className(),['charge_class_id'=>'id']);
    }

    /**
     * @desc: 后台-多人私课-关联编号表
     * @author: 焦冰洋 <jiaobingyang@itsports.club>
     * @create: 2018/01/15
     * @return mixed
     */
    public function getChargeClassNumbers()
    {
        return $this->hasMany(\backend\models\ChargeClassNumber::className(),['charge_class_id'=>'id']);
    }

    /**
     * @desc: 后台-多人私课-关联私教人数区间表
     * @author: 焦冰洋 <jiaobingyang@itsports.club>
     * @create: 2018/01/15
     * @return mixed
     */
    public function getChargeClassPeople()
    {
        return $this->hasMany(\backend\models\ChargeClassPeople::className(),['charge_class_id'=>'id']);
    }
}