<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/6/17
 * Time: 16:38
 */

namespace common\models\relations;

use common\models\base\Functional;
use backend\models\Module;
trait ModuleFunctionalRelations
{
    /**
     * 业务后台 - 菜单管理 - 关联模块表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/17
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(Module::className(), ['id'=>'modular_id']);
    }

    /**
     * 业务后台 - 菜单管理 - 关联功能表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/17
     * @return \yii\db\ActiveQuery
     */
    public function getFunctional()
    {
        return $this->hasOne(Functional::className(), ['id'=>'functional_id']);
    }
}