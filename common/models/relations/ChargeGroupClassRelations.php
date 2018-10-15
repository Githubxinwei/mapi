<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4 0004
 * Time: 下午 3:42
 */
namespace common\models\relations;
use common\models\base\AboutClass;
use common\models\base\Employee;

trait ChargeGroupClassRelations
{
    /**
     * 私教多人课程安排表 - 关联私课编号表
     * @return string
     * @auther 朱梦珂
     * @create 2018/01/04
     */
    public function getChargeClassNumber()
    {
        return $this->hasOne(\backend\models\ChargeClassNumber::className(),['id'=>'class_number_id']);
    }
    /**
     * 私教多人课程安排表 - 关联员工表
     * @return string
     * @auther 朱梦珂
     * @create 2018/01/04
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(),['id'=>'coach_id']);
    }
    /**
     * 私教多人课程安排表 - 关联约课表
     * @return string
     * @auther 朱梦珂
     * @create 2018/01/04
     */
    public function getAboutClass()
    {
        return $this->hasMany(AboutClass::className(),['class_id'=>'id']);
    }
}