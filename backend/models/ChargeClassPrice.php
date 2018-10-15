<?php
namespace backend\models;


use common\models\relations\ChargeClassPriceRelations;

class ChargeClassPrice extends \common\models\ChargeClassPrice
{
    use ChargeClassPriceRelations;

    /**
 * 云运动 - 购买私课 -  获取单价课程优惠单价
 * @author huangpengju <huangpengju@itsports.club>
 * @create 2017/5/20
 * @param $chargeId                           //私课id
 * @param $totalNum                           //购买数量
 * @return array|null|\yii\db\ActiveRecord   //优惠单价
 */
    public function getAlonePrice($chargeId,$totalNum)
    {
        $data =  ChargeClassPrice::find()
            ->where(['and',['charge_class_id'=>$chargeId],['<=','intervalStart',$totalNum],['>=','intervalEnd',$totalNum]])
            ->orWhere(['and',['charge_class_id'=>$chargeId],['<=','intervalStart',$totalNum],['=','intervalEnd',0]])
//                                ->orWhere(['and',['charge_class_id'=>$chargeId],['=','intervalStart',0],['=','intervalEnd',0]])
            ->select('unitPrice,posPrice')
            ->asArray()
            ->one();
        return $data;
    }
    /**
     * 云运动 - 购买私课 -  获取最高区间课程优惠单价
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/7/12
     * @param $chargeId                           //私课id
     * @param $totalNum                           //购买数量
     * @return array|null|\yii\db\ActiveRecord   //优惠单价
     */
    public function getAloneEndPrice($chargeId,$totalNum)
    {
        $data =  ChargeClassPrice::find()
            ->where(['and',['charge_class_id'=>$chargeId],['<=','intervalEnd',$totalNum]])
            ->select('unitPrice,posPrice')
            ->orderBy(['intervalEnd'=>SORT_DESC])
            ->asArray()
            ->one();
        return $data;
    }
}