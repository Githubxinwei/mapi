<?php
namespace common\models\relations;


use backend\models\Goods;

trait  GoodsChangeRelations
{
    /**
     * 后台 - 仓库管理 - 关联商品表
     * @return string
     * @author 黄华
     * @create 2017/8/30
     */
    public function getGoods(){
        return $this->hasOne(Goods::className(), ['id'=>'goods_id']);
    }

}