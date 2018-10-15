<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11 0011
 * Time: 16:48
 */

namespace backend\models;


use common\models\relations\ChargeClassPeopleRelations;

class ChargeClassPeople extends \common\models\base\ChargeClassPeople
{
    use ChargeClassPeopleRelations;

    /**
     * @desc: 私课小团体-购买私课-获取产品表和编号表信息
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/12
     * @param $peopleId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChargeInfo($peopleId)
    {
        $data = ChargeClassPeople::find()
            ->alias('ccl')
            ->select('ccl.*,cc.*')
            ->where(['ccl.id'=>$peopleId])
            ->joinWith('chargeClass cc',false)
            //->createCommand()->getRawSql();
            ->asArray()->all();
        return $data;
    }
}