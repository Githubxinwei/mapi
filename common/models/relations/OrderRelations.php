<?php
namespace common\models\relations;

use backend\modules\v1\models\MemberCourseOrderDetails;
use common\models\base\Employee;
use common\models\base\Member;
use common\models\base\LeaveRecord;
use common\models\base\MemberCard;
use common\models\CardCategory;
use common\models\base\MemberDetails;
use common\models\base\Organization;
/*use common\models\Organization;*/
use common\models\MemberCourseOrder;
use common\models\MemberMealRecord;
use common\models\MemberTakeMealRecord;

trait OrderRelations
{
    /**
     * 会员端 - 会员健康餐取餐记录查询 - 关联会员健康餐取餐记录表
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/07/11
     * @return \yii\db\ActiveQuery
     */
    public function getMemberTakeMealRecord()
    {
        return $this->hasOne(MemberTakeMealRecord::className(), ['id'=>'id']);
    }
    /**
     * 会员端 - 会员健康餐预约记录查询 - 关联会员健康餐预约记录表
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/07/11
     * @return \yii\db\ActiveQuery
     */
    public function getMemberMealRecord()
    {
        return $this->hasOne(MemberMealRecord::className(), ['order_id'=>'id']);
    }
    /**
     * 后台会员管理 - 会员信息查询 - 关联会员表
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(\backend\models\Member::className(), ['id'=>'member_id']);
    }

    /**
     * 后台会员管理 - 会员信息查询 - 关联组织架构表
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id'=>'venue_id']);
    }

    /**
     * 后台会员管理 - 会员信息查询 - 关联组织架构表
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/6/25
     * @return mixed
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(),['id'=>'create_id']);
    }
    public function getEmployeeS()
    {
        return $this->hasOne(Employee::className(),['id'=>'sell_people_id']);
    }
    public function getEmployeeM()
    {
        return $this->hasOne(Employee::className(),['admin_user_id'=>'create_id']);
    }


    /**
     * 财务管理 - 卡种收入 - 关联卡种表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/22
     * @return mixed
     */
    public function getCardCategory()
    {
        return $this->hasOne(CardCategory::className(),['id'=>'card_category_id']);
    }
    /**
     * 财务管理 - 分摊收入 - 关联卡种表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/22
     * @return mixed
     */
    public function getMemberCard()
    {
        return $this->hasOne(\backend\models\MemberCard::className(),['id'=>'card_category_id']);
    }
    public function getMemberCards()
    {
        return $this->hasOne(\backend\models\MemberCard::className(),['member_id'=>'member_id']);
    }
    /**
     * 后台会员管理 - 会员详细信息查询 - 关联会员表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/23
     * @return \yii\db\ActiveQuery
     */
    public function getMemberDetails()
    {
        return $this->hasOne(\backend\models\MemberDetails::className(), ['member_id'=>'member_id']);
    }
    /**
     * 财务管理 - 分摊收入 - 关联请假表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/23
     * @return mixed
     */
    public function getLeaveRecord()
    {
        return $this->hasOne(LeaveRecord::className(),['leave_employee_id'=>'member_id']);
    }

    public function getCompany()
    {
        return $this->hasOne(\common\models\Organization::className(), ['id'=>'company_id']);
    }


    public function getMemberCourseOrder()
    {
        return $this->hasOne(MemberCourseOrder::className(),['id'=>'consumption_type_id']);
    }
    public function getMemberCourseOrderDetails()
    {
        return $this->hasOne(MemberCourseOrderDetails::className(),['course_order_id'=>'id']);
    }

}