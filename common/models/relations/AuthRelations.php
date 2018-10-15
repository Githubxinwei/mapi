<?php
namespace common\models\relations;

use common\models\base\Organization;
use common\models\base\Role;
trait  AuthRelations
{
    /**
     * 后台 - 权限管理- 角色表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/6
     * @return string
     */
    public function getRole()
    {
        return $this->hasOne(\backend\models\Role::className(),['id'=>'role_id']);
    }
    public function getRoles()
    {
        return $this->hasOne(\backend\models\Role::className(),['id'=>'sync_role_id']);
    }
}