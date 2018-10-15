<?php

namespace common\models\relations;

trait StoreHouseRelations
{
    /**
     * 后台 - 仓库管理 - 关联商品表
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/8/28
     */
    public function getGoods()
    {
        return $this->hasOne(\backend\models\Goods::className(),['store_id'=>'id']);
    }
    /**
     * 后台 - 仓库管理 - 关联组织架构表
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/8/28
     */
    public function getOrganization()
    {
        return $this->hasOne(\backend\models\Organization::className(),['id'=>'venue_id']);
    }
}