<?php
namespace backend\models;

use common\models\base\ConsumptionHistory;
use common\models\base\MemberCourseOrder;
use common\models\base\MemberCourseOrderDetails;
use common\models\relations\CoursePackageDetailRelations;
use common\models\base\Order;
class CoursePackageDetail extends \common\models\CoursePackageDetail
{
    use CoursePackageDetailRelations;
   
    /**
     * 后台 - 会员管理 - 私教列表数据删除
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/18
     * @update huangpengju
     * @update 2017/6/7
     * @return bool
     */
    public  function  getChargeClassDel($id)
    {

        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $order      = MemberCourseOrder::findOne(['id'=>$id]);
            $order      = $order->delete();
            if(empty($order)){
                return false;
            }
            $orderDetails = MemberCourseOrderDetails::find()->where(['course_order_id'=>$id])->one();
            $orderDetails = $orderDetails->delete();
            if(empty($orderDetails))
            {
                return false;
            }
            $consumption = ConsumptionHistory::find()->where(['consumption_type' => 'charge','consumption_type_id' => $id])->one();
            if(!empty($consumption))
            {
                $consumption->delete();
            }
            $order = Order::find()->where(['consumption_type' => 'charge','consumption_type_id' => $id])->one();
            if(!empty($order))
            {
                $order->delete();
            }

            if($transaction->commit()){
                return false;
            }else{
                return true;
            }
        }catch (\Exception $e)
        {
            $transaction->rollBack();                                                                                        //事务回滚
            return  $e->getMessage();
        }
    }

    /**
     * 会员管理 - 私课信息 - 批量删除私教课
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/12/09
     * @return bool|string
     */
    public function batchDelCourse($data)
    {
        $idArr = $data['courseIdArr'];
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            MemberCourseOrder::deleteAll(['id' => $idArr]);
            MemberCourseOrderDetails::deleteAll(['course_order_id' => $idArr]);
            ConsumptionHistory::deleteAll(['consumption_type' => 'charge','consumption_type_id' => $idArr]);
            Order::deleteAll(['consumption_type' => 'charge','consumption_type_id' => $idArr]);

            if($transaction->commit() == null){
                return true;
            }else{
                return false;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();   //事务回滚
            return  $e->getMessage();
        }
    }

    /**
     * 云运动 - 购买私课 -  获取课程详细信息
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/20
     * @param $chargeId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getClassPrice($chargeId)
    {
        return CoursePackageDetail::find()->where(['charge_class_id'=>$chargeId])->asArray()->all();
    }
}