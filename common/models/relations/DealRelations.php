<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/27
 * Time: 11:21
 * Desc:合同表
 */
namespace common\models\relations;

use common\models\DealType;

trait DealRelations
{
    /**
     * 会员端 - 合同 - 合同类型表
     * @author 辛威 <xinwei@itsports.club>
     * @create 2017/07/27
     * @return \yii\db\ActiveQuery
     */
    public function getDealType()
    {
        return $this->hasOne(DealType::className(), ['id' => 'deal_type_id']);
    }
}