<?php
namespace common\models\relations;
use common\models\base\Admin;
use common\models\base\Auth;
use common\models\base\Organization;
use backend\models\Role;

trait RoleRelations
{
    /**
     * 角色管理 - 角色列表查询 - 关联组织架构表
     * @author huanghua<huanghua@itsports.club>
     * @create 2017/6/17
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id'=>'company_id']);
    }
    /**
     * 角色管理 - 角色列表查询 - 关联组织架构表
     * @author huanghua<huanghua@itsports.club>
     * @create 2017/6/17
     * @return \yii\db\ActiveQuery
     */
    public function getAuth()
    {
        return $this->hasOne(Auth::className(), ['role_id'=>'id']);
    }
    /**
     * 角色管理 - 角色列表查询 - 关联组织架构表
     * @author huanghua<huanghua@itsports.club>
     * @create 2017/6/17
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasMany(Admin::className(), ['level'=>'id']);
    }
}