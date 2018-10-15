<?php
namespace common\models\relations;

use backend\models\GroupClass;
use backend\models\MemberCourseOrderDetails;
use common\models\base\Admin;
use common\models\base\DealType;
use common\models\base\Organization;
use common\models\base\UserActivityStatistics;
use common\models\Member;

trait  CourseRelations
{
    /**
     * 后台 - 课种管理 - 关联数据表admin
     * @return string
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017-4-8
     * @param 
     */
    public function getAdmin(){
        return $this->hasOne(\backend\models\Admin::className(), ['id'=>'create_id']);
    }
    /**
     * 后台 - 合同管理- 管理合同类型表
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/25
     * @return string
     */
    public function getDealType(){
        return $this->hasOne(DealType::className(), ['id'=>'deal_type_id']);
    }
    /**
     * 后台 - 合同管理- 管理合同类型表
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/25
     * @return string
     */
    public function getOrganization(){
        return $this->hasOne(Organization::className(), ['id'=>'pid']);
    }
    /**
     * 后台 - 团课管理 - course表关联Organization
     * @return string
     * @author 黄鹏举
     * @create 2017-6-12
     * @param
     */
    public function getOrganizations(){
        return $this->hasOne(\backend\models\Organization::className(),['id'=>'venue_id']);
    }
    /**
     * 后台 - 销售统计 - course表关联member_course_order_details
     * @return string
     * @author 焦冰洋
     * @create 2017-09-09
     * @param
     */
    public function getMemberCourseOrderDetails(){
        return $this->hasMany(MemberCourseOrderDetails::className(),['course_id'=>'id']);
    }
    /**
     * 后台 - 销售统计 - course表关联group_class
     * @return string
     * @author 焦冰洋
     * @create 2017-09-10
     * @param
     */
    public function getGroupClass(){
        return $this->hasMany(GroupClass::className(),['course_id'=>'id']);
    }
    /**
     * 后台 - 场馆管理 - 公司关联场馆
     * @return string
     * @author 侯凯新
     * @create 2017-09-28
     * @param
     */
    public function getTheOrganization(){
        $query = $this->hasMany(Organization::className(),['pid'=>'id']);
        if(empty(constant("isNotMB"))){
           $venueName = ["管理公司"];
         //  $query  = $query->onCondition(["allVenue.name"=>["大上海瑜伽健身馆","艾搏尊爵汇馆"]]);
           $query = $query->onCondition(["not like","allVenue.name",$venueName]);
        }
        return $query;
    }
    /**
     * 后台 - 用户分析统计 -
     * @return string
     * @author 侯凯新
     * @create 2018-01-06
     * @param
     */
    public function getActivityUsers(){
        return $this->hasMany(UserActivityStatistics::className(),['company_id'=>'id']);
    }

    public function getActivityUser(){
        return $this->hasMany(UserActivityStatistics::className(),['venue_id'=>'id']);
    }
    public function getVenue(){
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }
}