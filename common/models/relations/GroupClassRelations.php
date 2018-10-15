<?php
namespace common\models\relations;

use backend\models\ClassRoom;
use backend\models\GroupClass;
use Codeception\Lib\Generator\Shared\Classname;
use common\models\base\AboutClass;
use common\models\base\MissAboutSet;
use common\models\base\CoachClassRecord;
use common\models\base\Course;
use common\models\base\Employee;
use common\models\base\Organization;
use common\models\base\ClassRecord;
use common\models\Seat;
use common\models\SeatType;
use common\models\Config;


use common\models\Evaluate;

trait  GroupClassRelations
{
    /**
     * 后台 - 团课管理 - 关联course数据表
     * @return string
     * @auther 侯凯新
     * @create 2017-4-13
     * @param
     */
    public function getCourse(){
        return $this->hasOne(Course::className(), ['id'=>'course_id']);
    }
    /**
     * 后台 - 团课管理 - 关联coach数据表
     * @return string
     * @auther 侯凯新
     * @create 2017-4-13
     * @param
     */
    public function getEmployee(){
        return $this->hasOne(Employee::className(), ['id'=>'coach_id']);
    }
    /**
     * 后台 - 团课管理 - 关联classroom数据表
     * @return string
     * @auther 侯凯新
     * @create 2017-4-13
     * @param
     */
    public function getClassroom(){
        return $this->hasOne(ClassRoom::className(), ['id'=>'classroom_id']);
    }
    /**
     * 后台 - 团课管理 - 关联course数据表
     * @return string
     * @author 李慧恩
     * @create 2017-4-13
     * @param
     */
    public function getAboutClass()
    {
        return $this->hasMany(\backend\models\AboutClass::className(),['class_id'=>'id']);
    }
    /**
     * 后台 - 团课管理 - 关联 预约管理表（附带预约状态）
     * @return string
     * @author 侯凯新
     * @create 2017-12-22
     * @param
     */
    public function getTheAboutClass(){
        return $this->hasMany(\backend\models\AboutClass::className(),['class_id'=>'id'])
            ->onCondition(["aboutClass.status"=>constant("memberStatus"),"aboutClass.type"=>2]);
    }
    
    /**
     * 后台 - 团课管理 - 教室表关联场馆表
     * @return string
     * @author 侯凯新
     * @create 2017-4-14
     * @param
     */
    public function getOrganization(){
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }
    /**
     * 后台 - 会员详情团课管理 - 关联上课记录表
     * @return string
     * @author 黄华
     * @create 2017-4-19
     * @param
     */
    public function getClassRecord()
    {
        return $this->hasMany(ClassRecord::className(),['multiple_id'=>'id']);
    }
    /**
     * 后台 - 团课管理 - 关联教练上课记录表
     * @return string
     * @author 侯凯新
     * @create 2017-7-11
     */
    public function getCoachClass(){
        return $this->hasOne(CoachClassRecord::className(),['class_id'=>'id']);
    }
    /**
     * 后台 - 团课管理 - 关联座位类型表（排次表）
     * @return string
     * @author 侯凯新
     * @create 2017-8-11
     */
    public function getSeatType(){
        return $this->hasOne(SeatType::className(),['id'=>'seat_type_id']);
    }

    /**
     * 后台 - 团课管理 - 关联座位类型表（排次表）
     * @return string
     * @author 侯凯新
     * @create 2017-8-11
     */
    public function getSeatTypeS(){
        return $this->hasOne(SeatType::className(),['id'=>'seat_type_id']);
    }

    public function getSeat(){
        return $this->hasMany(Seat::className(),['seat_type_id'=>'seat_type_id'])->onCondition(["!=","seat_type",0]);
    }
    /**
     * 后台 - 团课管理 - 团课表关联团课表
     * @return string
     * @author 侯凯新
     * @create 2017-8-11
     */
    public function getGroupCourse(){
        return $this->hasMany(GroupClass::className(),['class_date'=>'class_date']);
    }

    /**
     * 后台 - 团课管理 - 团课表关联团课表
     * @return string
     * @author 焦冰洋
     * @create 2018-01-30
     */
    public function getSeatS(){
        return $this->hasMany(Seat::className(),['seat_type_id'=>'seat_type_id']);
    }
    /**
     * 移动端 - 预约设置 - 打印小票设置
     * @return string
     * @author 辛威
     * @create 2018-04-14
     */
    public function getConfig()
    {
        return $this->hasOne(Config::className(),['venue_id' => 'venue_id','company_id' => 'company_id']);
    }
    /**
     * 移动端 - 团课管理 - 团课爽约设置
     * @return string
     * @author 辛威
     * @create 2018-04-19
     */
    public function getMissAboutSet()
    {
        return $this->hasOne(MissAboutSet::className(),['venue_id'=>'venue_id','company_id' => 'company_id']);
    }

     /* @return mixed
     */
    public function getTuanEvaluate()
    {
        return $this->hasOne(Evaluate::className(), ['consumption_type_id' => 'id'])->onCondition(['te.consumption_type' => 'teamClass']);

    }

}