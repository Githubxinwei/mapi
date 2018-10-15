<?php
namespace common\models\relations;

use common\models\base\MemberCard;
use common\models\base\Employee;
use backend\models\MemberCourseOrder;
use common\models\base\MemberCourseOrderDetails;

trait ConsumptionHistoryRelations
{
    /**
     * 后台 - 私教课程管理 - 组织架构表
     * @return string
     * @auther 朱梦珂
     * @create 2017-4-26
     * @param
     */
    public function getMemberCourseOrder()
    {
        return $this->hasOne(MemberCourseOrder::className(),['id'=>'consumption_type_id']);
    }

    /**
     * 后台 - 会员卡表 - 关联消费记录表
     * @return string
     * @auther 朱梦珂
     * @create 2017-5-26
     * @param
     */
    public function getMemberCard()
    {
        return $this->hasOne(MemberCard::className(),['id'=>'consumption_type_id']);
    }

    /**
     * 后台 - 员工表 - 关联消费记录表
     * @return string
     * @auther 朱梦珂
     * @create 2017-5-27
     * @param
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(),['id'=>'seller_id']);
    }

    /**
     * 后台 - 消费记录表 - 关联订单详情表
     * @return string
     * @auther 黄鹏举
     * @create 2017-6-5
     * @return mixed
     */
    public function getMemberOrderDetails()
    {
        return $this->hasOne(MemberCourseOrderDetails::className(),['course_order_id'=>'consumption_type_id']);
    }
}