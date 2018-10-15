<?php
namespace backend\models;
use common\models\relations\SeatTypeRelations;

class SeatType extends \common\models\SeatType
{
    use SeatTypeRelations;

    /**
     * 后台 - 场馆管理 - 获取所有座位排次
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/7/27
     * @return array
     */
    public function getSeatData($venueId)
    {
        $seat = SeatType::find()
            ->alias('seatType')
            ->joinWith(['classroom room'])
            ->joinWith(['seat seat'=>function($query){
                $query->where(['<>','seat.seat_number','0']);
            }])
            ->where(['room.venue_id' => $venueId])
            ->select(
                'seatType.id,
                seatType.name,
                seatType.classroom_id,
                seatType.total_rows,
                seatType.total_columns,
                room.name as roomName,
                room.classroom_area')
            ->asArray()
            ->all();
        return $seat;
    }

    /**
     * 后台 - 场馆管理 - 获取座位排次详情
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/7/27
     * @return array
     */
    public function seatDetails($seatTypeId)
    {
        $seat = SeatType::find()
            ->alias('seatType')
            ->joinWith(['classroom room'])
            ->joinWith(['seat seat'])
            ->where(['seatType.id' => $seatTypeId])
            ->asArray()
            ->one();
        return $seat;
    }

    /**
     * 后台 - 团课排课 - 获取某一教室下的座位排次
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/9
     * @return array
     */
    public function getSeatType($roomId)
    {
        $seatType = SeatType::find()->where(['classroom_id' => $roomId])->asArray()->all();
        return $seatType;
    }
}