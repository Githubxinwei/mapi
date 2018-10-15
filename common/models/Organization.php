<?php

namespace common\models;

class Organization extends \common\models\base\Organization
{
    /**
     * 组织架构 - 关联组织结构表
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/7/12
     * @return \yii\db\ActiveQuery
     */
    public function getCompany(){
        return $this->hasOne(Organization::className(),['id'=>'pid']);
    }
}