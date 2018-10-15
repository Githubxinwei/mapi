<?php
namespace common\models\relations;

use backend\models\MemberCard;
use common\models\base\ConsumptionHistory;
use common\models\MemberDetails;
use common\models\base\CoursePackageDetail;
use common\models\base\Order;
use common\models\Organization;
use common\models\Evaluate;

trait  MemberCourseOrderRelations
{
    /**
     * 后台 - 会员私课订单 - 关联收费课程表
     * @return string
     * @auther huangpegnju
     * @create 2017-5-25
     * @param
     */
    public function getChargeClass()
    {
        return $this->hasOne(\backend\models\ChargeClass::className(), ['id' => 'product_id']);
    }

    /**
     * 后台 - 会员私课订单 - 关联订单详情表
     * @return string
     * @auther huangpegnju
     * @create 2017-6-1
     * @return mixed
     */
    public function getMemberCourseOrderDetails()
    {
        return $this->hasMany(\backend\models\MemberCourseOrderDetails::className(),['course_order_id'=>'id']);
    }
    /**
     * 后台 - 会员私课订单 - 关联订单详情表
     * @return string
     * @auther huangpegnju
     * @create 2017-6-1
     * @return mixed
     */
    public function getMemberCourseOrderDetailsOne()
    {
        return $this->hasOne(\backend\models\MemberCourseOrderDetails::className(),['course_order_id'=>'id']);
    }
    /**
     * 后台 - 会员私课订单 - 关联员工表
     * @return string
     * @auther 朱梦珂
     * @create 2017-6-2
     * @return mixed
     */
    public function getEmployee()
    {
        return $this->hasOne(\common\models\Employee::className(),['id'=>'seller_id']);
    }
    public function getEmployeeS()
    {
        return $this->hasOne(\common\models\Employee::className(),['id'=>'private_id']);
    }
    /**
     * 私课管理 - 私课排期 - 关联会员表
     * @return string
     * @author 黄华
     * @create 2017-6-2
     * @return mixed
     */
    public function getMember()
    {
        return $this->hasOne(\backend\models\Member::className(),['id'=>'member_id']);
    }
    /**
     * 销售主页 - 课程预约 - 关联课种表
     * @return string
     * @author 黄鹏举
     * @create 2017-6-14
     * @return mixed
     */
    public function getCourse()
    {
        return $this->hasOne(\backend\models\Course::className(),['id'=>'course_id']);
    }
    /**
     * 销售主页 - 课程预约 - 关联课种表
     * @return string
     * @author 黄鹏举
     * @create 2017-6-14
     * @return mixed
     */
    public function getCoursePackageDetail()
    {
        return $this->hasOne(CoursePackageDetail::className(),['charge_class_id'=>'product_id']);
    }
    /**
     *  订单表关联会员卡表
     * @return string
     * @author 侯凯新
     * @create 2017-8-7
     * @return mixed
     */
    public function getMemberCard(){
        return $this->hasMany(MemberCard::className(),['member_id'=>'member_id'])
                ->onCondition([">","invalid_time",time()])->orderBy(["id"=>SORT_DESC]);
    }
    /**
     *  订单表关联会员卡表
     * @return string
     * @author 朱梦珂
     * @create 2017-8-17
     * @return mixed
     */
    public function getMemberCardS()
    {
        return $this->hasOne(MemberCard::className(),['id'=>'member_card_id']);
    }
    /**
     * 会员私课订单表 关联 订单表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/9/5
     * @return \yii\db\ActiveQuery
     */
    public function getOrder(){
        return $this->hasOne(Order::className(), ['consumption_type_id'=>'id']);
    }

    /**
     * 会员私课订单表 关联 私教
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/11/10
     * @return \yii\db\ActiveQuery
     */
    public function getOrderList(){
        return $this->hasOne(\common\models\Order::className(),['consumption_type_id'=>'id'])->onCondition(["consumption_type"=>"charge"]);
    }

    /**
     * @desc: 财务管理-上课收入-关联会员信息表,获取会员姓名
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/26
     * @return mixed
     */
    public function getMemberDetails()
    {
        return $this->hasOne(MemberDetails::className(),['member_id'=>'member_id']);
    }

    /**
     * @desc: 财务管理-上课收入-关联会员信息表,获取会员姓名
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/26
     * @return mixed
     */
    public function getConsumptionHistory()
    {
        return $this->hasOne(ConsumptionHistory::className(),['consumption_type_id'=>'id']);
    }
    /**
     * @desc: APP会员端-我的私课列表-关联组织架构表
     * @author: Xin Wei<xinwei@itsports.club>
     * @create: 2018/03/29
     * @return mixed
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }

    /**
     * 关联评价表
     * @return mixed
     */
    public function getSiEvaluate()
    {
        return $this->hasOne(Evaluate::className(), ['consumption_type_id' => 'id'])->onCondition(['se.consumption_type' => 'privateClass']);
    }
}