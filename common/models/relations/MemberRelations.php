<?php

namespace common\models\relations;

use backend\models\AboutYard;
use common\models\base\Employee;
use common\models\base\EntryRecord;
use common\models\base\FitnessProgramSend;
use common\models\base\LeaveRecord;
use backend\models\MemberCard;
use common\models\base\MemberAccount;
use common\models\base\MemberDeposit;
use common\models\base\ClassRecord;
use common\models\base\ConsumptionHistory;
use common\models\base\MemberFitnessProgram;
use common\models\Organization;
use common\models\Order;

trait MemberRelations
{
    /**
     * 后台会员管理 - 会员信息查询 - 关联会员卡表
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/3/29
     * @return \yii\db\ActiveQuery
     */
    public function getMemberCard()
    {
        return $this->hasMany(\backend\models\MemberCard::className(), ['member_id'=>'id']);
    }
    /**
     * 后台会员管理 - 会员信息查询 - 关联会员详细信息表
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/3/30
     * @return \yii\db\ActiveQuery
     */
    public function getMemberDetails()
    {
        return $this->hasOne(\common\models\MemberDetails::className(),['member_id'=>'id']);
    }
    /**
 * 后台会员管理 - 会员信息查询 - 关联员工表
 * @author Huang hua <huanghua@itsports.club>
 * @create 2017/4/11
 * @return \yii\db\ActiveQuery
 */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'counselor_id']);
    }
    /**
     * 后台会员管理 - 会员信息查询 - 关联上课记录表
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/19
     * @return \yii\db\ActiveQuery
     */
    public function getClassRecord()
    {
        return $this->hasOne(ClassRecord::className(), ['member_id'=>'id']);
    }
    /**
     * 后台会员管理 - 会员信息查询 - 关联消费记录表
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/19
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveRecord()
    {
        return $this->hasOne(LeaveRecord::className(), ['leave_employee_id'=>'id']);
    }
    /**
     * 后台会员管理 - 会员信息查询 - 关联消费记录表
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/19
     * @return \yii\db\ActiveQuery
     */
    public function getConsumptionHistory()
    {
        return $this->hasOne(ConsumptionHistory::className(), ['member_id'=>'id']);
    }

    /**
     * 后台会员管理 - 会员信息查询 - 关联进场记录表
     * @author Huang pengju <huangpengju@itsports.club>
     * @create 2017/5/23
     * @return mixed
     */
    public function getEntryRecord()
    {
        return $this->hasOne(EntryRecord::className(),['member_id'=>'id'])->orderBy(['id'=>SORT_DESC]);
    }
    public function getEntryRecords()
    {
        return $this->hasMany(EntryRecord::className(),['member_id'=>'id']);
    }

    /**
     * 潜在会员 - 约课信息 - 查询
     * @author Huang pengju <huangpengju@itsports.club>
     * @create 2017/5/25
     * @return mixed
     */
    public function getAboutClass()
    {
        return $this->hasOne(\backend\models\AboutClass::className(),['member_id'=>'id']);
    }
    /**
     * 潜在会员 - 约课信息 - 查询
     * @author Huang hua <huangpengju@itsports.club>
     * @create 2017/5/25
     * @return mixed
     */
    public function getMemberCourseOrder()
    {
        return $this->hasOne(\backend\models\MemberCourseOrder::className(),['member_id'=>'id']);
    }

    /**
     * 后台会员管理 - 会员信息查询 - 关联请假记录表
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/4/19
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveRecordS()
    {
        return $this->hasOne(LeaveRecord::className(), ['leave_employee_id'=>'id'])->orderBy(["id"=>SORT_DESC])->limit(1);
    }

    /**
     * 后台会员管理 - 会员信息查询 - 关联请假记录表
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/4/19
     * @return \yii\db\ActiveQuery
     */
    public function getMemberDeposit()
    {
        return $this->hasMany(MemberDeposit::className(), ['member_id'=>'id']);
    }
    /**
     * 潜在会员管理 - 关联组织架构表
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/7/12
     * @return \yii\db\ActiveQuery
     */
    public function getVenue(){
        return $this->hasOne(Organization::className(), ['id'=>'venue_id']);
    }
    /**
     * 会员维护 - 关联健身计划发送表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/11/27
     * @return \yii\db\ActiveQuery
     */
    public function getFitnessProgramSend(){
        return $this->hasOne(FitnessProgramSend::className(), ['member_id'=>'id']);
    }
    /**
     * 会员维护 - 关联会员健身计划表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/11/27
     * @return \yii\db\ActiveQuery
     */
    public function getMemberFitnessProgram(){
        return $this->hasOne(\backend\models\MemberFitnessProgram::className(), ['member_id'=>'id']);
    }
    /**
     * 潜在会员表 - 关联场地预约表
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/11/13
     * @return \yii\db\ActiveQuery
     */
    public function getYardAbout(){
        return $this->hasOne(AboutYard::className(), ['member_id'=>'id'])->onCondition(["yardAbout.status"=>1]);
    }
    /**
     * 潜在会员表 -  关联场地预约表
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/11/14
     * @return \yii\db\ActiveQuery
     */
    public function getAboutYard(){
        return $this->hasOne(AboutYard::className(), ['member_id'=>'id'])->onCondition(["aboutYard.status"=>1]);
    }
    /**
     * 潜在会员表 -  关联账号表
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/11/14
     * @return \yii\db\ActiveQuery
     */
    public function getMemberAccount()
    {
        return $this->hasOne(MemberAccount::className(), ['id'=>'member_account_id']);
    }

    /**
     * 潜在会员管理 - 关联组织架构表
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/7/12
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(){
        return $this->hasOne(Organization::className(), ['id'=>'company_id']);
    }
    public function getMemberCards()
    {
        return $this->hasOne(\backend\models\MemberCard::className(), ['member_id'=>'id']);
    }
    /**
     * 会员端 - 关联订单表
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/07/16
     * @return \yii\db\ActiveQuery
     */
    public function getOrder(){
        return $this->hasOne(Order::className(), ['member_id'=>'id']);
    }
}