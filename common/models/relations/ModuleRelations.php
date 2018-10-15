<?php
namespace common\models\relations;

use backend\models\Module;
use backend\models\ModuleFunctional;

trait ModuleRelations
{
    /**
     * 业务后台 - 菜单管理 - 关联模块表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/17
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasMany(Module::className(), ['pid'=>'id']);
    }
    /**
     * 业务后台 - 菜单管理 - 关联模块表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/17
     * @return \yii\db\ActiveQuery
     */
    public function getModuleFunctional()
    {
        return $this->hasOne(ModuleFunctional::className(), ['modular_id'=>'id']);
    }
}