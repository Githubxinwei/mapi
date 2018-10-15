<?php

namespace common\models\relations;
use backend\models\Goods;
use common\models\base\MobiliseType;
use common\models\base\StoreHouse;

trait MobiliseRelations
{
    /**
     * 后台 - 仓库管理 - 关联调拨记录表
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/9/1
     */
    public function getMobiliseType()
    {
        return $this->hasOne(MobiliseType::className(),['mobilise_id'=>'id']);
    }
    /**
     * 后台 - 仓库管理 - 关联商品表
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/9/1
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }
    /**
     * 后台 - 仓库管理 - 关联新商品表
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/9/1
     */
    public function getNewGoods()
    {
        return $this->hasOne(Goods::className(),['store_id'=>'be_store_id']);
    }
    /**
     * 后台 - 仓库管理 - 关联仓库表
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/9/1
     */
    public function getStoreHouse()
    {
        return $this->hasOne(StoreHouse::className(),['id'=>'store_id']);
    }
    /**
     * 后台 - 仓库管理 - 关联仓库表
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/9/1
     */
    public function getBeStoreHouse()
    {
        return $this->hasOne(StoreHouse::className(),['id'=>'be_store_id']);
    }
}