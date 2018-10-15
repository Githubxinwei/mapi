<?php

namespace common\models\relations;

use common\models\base\ApprovalType;
use common\models\base\Employee;
use common\models\base\Role;

trait ApprovalRoleRelations
{
    /**
     * 后台 - 卡种审核 - 关联员工表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/9/29
     * @return string
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(),['id'=>'employee_id']);
    }
    /**
     * 后台 - 卡种审核 - 关联角色表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/9/29
     * @return string
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(),['id'=>'role_id']);
    }
    /**
     * 后台 - 卡种审核 - 关联角色表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/9/29
     * @return string
     */
    public function getApprovalType()
    {
        return $this->hasOne(ApprovalType::className(),['id'=>'approval_type_id']);
    }
}