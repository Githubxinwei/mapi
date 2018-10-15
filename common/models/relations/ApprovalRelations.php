<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/9/28
 * Time: 20:47
 */

namespace common\models\relations;

use common\models\base\ApprovalDetails;
use common\models\base\ApprovalType;
use backend\models\Employee;

trait ApprovalRelations
{
    /**
     * 后台 - 审核 - 审批评论表
     * @author 李慧恩 <zhumengke@itsports.club>
     * @create 2017/6/27
     * @return string
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(),['id'=>'create_id']);
    }
    /**
     * 后台 - 审核 - 审批评论表
     * @author 李慧恩 <zhumengke@itsports.club>
     * @create 2017/6/27
     * @return string
     */
    public function getApprovalType()
    {
        return $this->hasOne(ApprovalType::className(),['id'=>'approval_type_id']);
    }
    /**
     * 后台 - 审核 - 详情表
     * @author 李慧恩 <zhumengke@itsports.club>
     * @create 2017/6/27
     * @return string
     */
    public function getApprovalDetails()
    {
        return $this->hasOne(ApprovalDetails::className(),['approval_id'=>'id']);
    }
}