<?php
namespace common\models\relations;
use backend\models\Cabinet;

trait CabinetTypeRelations
{
    /**
     *  柜子类型表 - 关联 柜子表
     * @author Houkaixin <Houkaixin@itsports.club>
     * @create 2017/6/4
     * @return \yii\db\ActiveQuery
     */
    public function getCabinet()
    {
        return $this->hasMany(Cabinet::className(), ['cabinet_type_id' =>'id']);
    }

}