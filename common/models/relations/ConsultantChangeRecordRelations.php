<?php
namespace common\models\relations;
use common\models\base\Employee;

trait ConsultantChangeRecordRelations
{

    /**
     * 后台会员管理 - 会籍变更记录 - 关联员工表
     * @author Huang hua <huanghua@itsports.club>
     * @create 2018/2/26
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'consultant_id']);
    }

}