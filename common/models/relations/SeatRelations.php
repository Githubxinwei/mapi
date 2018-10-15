<?php
namespace common\models\relations;

use common\models\base\AboutClass;
use common\models\base\Classroom;
use common\models\base\Course;

trait SeatRelations
{
    public function getAboutClass()
    {
        return $this->hasOne(AboutClass::className(),['seat_id'=>'id']);
    }
    /**
     * 后台 - 教室表- 关联预约记录表
     * @author houkaixin<houkaixin @itsports.club>
     * @create 2017/8/17
     */
    public function getAboutClassIos(){
        return $this->hasOne(AboutClass::className(),['seat_id'=>'id'])->onCondition(["status"=>1]);
    }
    /**
     * 后台 - 教室场地 - 关联教室表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/7/28
     */
    public function getClassroom()
    {
        return $this->hasOne(Classroom::className(),['id'=>'classroom_id']);
    }
    /**
     * 后台 - 教室表 关联约课记录表
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/8/11
     */
    public function getAboutTheClass(){
        return $this->hasOne(AboutClass::className(),['seat_id'=>'id'])->onCondition(["status"=>1]);
    }

}