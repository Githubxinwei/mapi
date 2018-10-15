<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/14
 * Time: 10:33
 */
namespace backend\models;
use common\models\base\SeatType;
use common\models\relations\SeatRelations;
use backend\modules\v1\models\GroupClass;
class Seat extends \common\models\base\Seat
{
     use SeatRelations;

     /**
      * 云运动 - 后台 - 预约座位号
      * @author lihuien <lihuien@itsports.club>
      * @create 2017/5/15
      * @param  $id int      //团课课程Id
      * @return array|\yii\db\ActiveRecord[]
      */
     public function getSeatDetail($id)
     {
          $groupOne = GroupClass::find()->where(['id'=>$id])->asArray()->one(); //通过团课id 获团课数组
          $apiGroup = new GroupClass();
          $groupOne['seatDetail'] = $apiGroup->getSeatByClassId($groupOne['seat_type_id'],$id);//座位预约信息
          return $groupOne['seatDetail'];
     }

     /**
      * 后台 - 场馆管理 - 删除座位排次
      * @author zhumengke <zhumengke@itsports.club>
      * @create 2017/7/27
      * @return object     //返回添加数据成功与否结果
      */
     public function delSeat($seatTypeId)
     {
          $seatType = SeatType::findOne(['id' => $seatTypeId]);
          if(!empty($seatType)){
               $seat     = Seat::deleteAll(['seat_type_id' => $seatTypeId]);
               $seatType = $seatType->delete();
               if($seat && $seatType){
                    return true;
               }else{
                    return false;
               }
          }else{
               return false;
          }
     }
}