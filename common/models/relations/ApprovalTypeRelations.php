<?php
namespace common\models\relations;


use common\models\base\ApprovalRole;

trait ApprovalTypeRelations
{
    /**
     * 后台 - 审核 - 审批评论表
     * @author 李慧恩 <zhumengke@itsports.club>
     * @create 2017/6/27
     * @return string
     */
    public function getApprovalRole()
    {
        return $this->hasOne(ApprovalRole::className(),['approval_type_id'=>'id']);
    }
}