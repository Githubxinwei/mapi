<?php
namespace common\models\relations;

use backend\models\MemberCourseOrder;
use backend\models\Order;
use backend\models\AboutClass;
use common\models\base\MemberBehaviorTrail;
use common\models\base\MemberDetails;
use common\models\base\Module;
use common\models\base\Organization;
use common\models\base\ConsumptionHistory;
use common\models\base\Admin;
use common\models\base\Position;
use common\models\Employee;
use common\models\base\PersonalityImages;

trait  EmployeeRelations
{
    /**
     * 后台 - 员工管理 - 关联组织架构表
     * @return string
     * @author huanghua
     * @create 2017-4-24
     * @param
     */
    public function getOrganization(){
        return $this->hasOne(\common\models\Organization::className(), ['id'=>'organization_id']);
    }

    /**
     * 后台 - 员工管理 - 关联消费记录表
     * @return string
     * @author 朱梦珂
     * @create 2017-5-26
     * @param
     */
    public function getConsumptionHistory(){
        return $this->hasOne(ConsumptionHistory::className(), ['seller_id'=>'id']);
    }
    /**
     * 员工 - 约课信息 - 查询
     * @author Huang pengju <huangpengju@itsports.club>
     * @create 2017/6/2
     * @return mixed
     */
    public function getAboutClass()
    {
        return $this->hasOne(AboutClass::className(),['employee_id'=>'id']);
    }
    /**
     * 员工 - 约课信息 - 查询
     * @author Huang pengju <huangpengju@itsports.club>
     * @create 2017/6/2
     * @return mixed
     */
    public function getAboutClassCoach()
    {
        return $this->hasOne(AboutClass::className(),['coach_id'=>'id']);
    }
    /**
     * 后台 - 员工详情 - 关联会员表
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/2
     * @return mixed
     */
    public function getMember()
    {
        return $this->hasOne(\backend\models\Member::className(),['counselor_id'=>'id']);
    }
    /**
     * 后台 - 角色管理 - 关联系统管理员表
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/2
     * @return mixed
     */
    public function getAdmin()
    {
        return $this->hasOne(\backend\models\Admin::className(),['id'=>'admin_user_id']);
    }
    /**
     * 后台 - 行为轨迹表 - 关联员工表
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/7/13
     * @return mixed
     */
    public function getEmployee(){
        return $this->hasOne(Employee::className(),['id'=>'employee_id']);
    }
    /**
     * 后台 - 行为轨迹表 - 关联员工表
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/7/13
     * @return mixed
     */
    public function getPersonalityImages(){
        return $this->hasOne(PersonalityImages::className(),['id'=>'employee_id']);
    }
    /**
     * 后台 - 行为轨迹表 - 模型id
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/7/13
     * @return mixed
     */
    public function getModule(){
        return $this->hasOne(Module::className(),['id'=>'module_id']);
    }
    /**
     * 后台 - 行为轨迹表 -
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/7/13
     * @return mixed
     */
    public function getAdminEmployee(){
        return $this->hasOne(\backend\models\Admin::className(),['id'=>'employee_id']);
    }
    /**
     * 后台 - 销售主页 - 关联订单表
     * @author 黄华<huanghua@itsports.club>
     * @create 2017/7/22
     * @return mixed
     */
    public function getOrder(){
        return $this->hasOne(Order::className(),['sell_people_id'=>'id']);
    }
    /**
     * 后台 - 会员课程订单表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/7/21
     * @return mixed
     */
    public function getMemberCourseOrder()
    {
        return $this->hasOne(MemberCourseOrder::className(),['private_id'=>'id']);
    }

    /**
     * 后台 - 组织架构表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/11
     * @return mixed
     */
    public function getOrganizations()
    {
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }
    /**
     * 后台 - 组织架构表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/11
     * @return mixed
     */
    public function getCompany()
    {
        return $this->hasOne(Organization::className(),['id'=>'company_id']);
    }

    /**
     * 财务 - 上课收入 - 关联约课表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/9/16
     * @return mixed
     */
    public function getAboutClasses()
    {
        return $this->hasMany(AboutClass::className(),['coach_id'=>'id']);
    }
    /**
     * 财务 - 上课收入 - 职位表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/9/16
     * @return mixed
     */
    public function getPosition()
    {
        return $this->hasMany(Position::className(),['id'=>'position']);
    }
}