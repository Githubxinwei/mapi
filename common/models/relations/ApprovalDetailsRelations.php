<?php
namespace common\models\relations;


use backend\models\ApprovalComment;
use common\models\base\Employee;

trait ApprovalDetailsRelations
{
    /**
     * 后台 - 审核 - 审批评论表
     * @author 李慧恩 <zhumengke@itsports.club>
     * @create 2017/6/27
     * @return string
     */
    public function getApprovalComment()
    {
        return $this->hasMany(ApprovalComment::className(),['approval_detail_id'=>'id']);
    }
    /**
     * 后台 - 审核 - 审批评论表
     * @author 李慧恩 <zhumengke@itsports.club>
     * @create 2017/6/27
     * @return string
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(),['id'=>'approver_id']);
    }
}