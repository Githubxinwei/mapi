<?php
namespace common\models\relations;
use common\models\base\Role;
trait  GiftDayRelations
{
    /**
     * 后台 - 卡种赠送表 - 关联角色表
     * @return string
     * @author 黄华
     * @create 2017-10-13
     * @param
     */
    public function getRole(){
        return $this->hasOne(Role::className(), ['id'=>'role_id']);
    }

}