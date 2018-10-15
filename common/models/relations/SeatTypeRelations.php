<?php
namespace common\models\relations;

use common\models\base\AboutClass;
use common\models\base\Classroom;
use common\models\base\Seat;

trait SeatTypeRelations
{
    /**
     * 后台 - 座位排次 - 关联教室表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/7/28
     */
    public function getClassroom()
    {
        return $this->hasOne(Classroom::className(),['id'=>'classroom_id']);
    }

    /**
     * 后台 - 座位排次 - 关联座位表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/7/28
     */
    public function getSeat()
    {
        return $this->hasMany(\common\models\Seat::className(),['seat_type_id'=>'id']);
    }
}