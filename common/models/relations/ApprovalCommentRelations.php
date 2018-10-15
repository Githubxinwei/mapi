<?php
namespace common\models\relations;


use common\models\base\Employee;

trait ApprovalCommentRelations
{
    /**
     * 后台 - 审核 - 审批评论人表
     * @author 李慧恩 <zhumengke@itsports.club>
     * @create 2017/6/27
     * @return string
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(),['id'=>'reviewer_id']);
    }
}