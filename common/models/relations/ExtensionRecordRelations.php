<?php
namespace common\models\relations;
use common\models\Admin;
use common\models\base\MemberCourseOrder;
use common\models\Employee;

trait  ExtensionRecordRelations
{
    /**
     * 后台 - 会员管理 - 关联会员课程订单表
     * @return string
     * @author 黄华
     * @create 2017-10-13
     * @param
     */
    public function getMemberCourseOrder(){
        return $this->hasOne(MemberCourseOrder::className(), ['id'=>'course_order_id']);
    }
    public function getEmployee(){
        return $this->hasOne(Employee::className(), ['id'=>'create_id']);
    }

    /**
     * @desc: 业务后台 - 私教课延期 - 管理管理员表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/03/14
     * @return mixed
     */
    public function getAdmin(){
        return $this->hasOne(Admin::className(),['id'=>'create_id']);
    }
}