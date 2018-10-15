<?php
namespace common\models\relations;

use common\models\base\Goods;
use common\models\base\Server;

trait  ChargeClassServiceRelations
{
    /**
     * 后台 - 私教课程 - 服务表
     * @return string
     * @auther 朱梦珂
     * @create 2017-11-04
     * @param
     */
    public function getServer()
    {
        return $this->hasMany(Server::className(),['id'=>'service_id']);
    }

    /**
     * 后台 - 私教课程 - 赠品表
     * @return string
     * @auther 朱梦珂
     * @create 2017-11-04
     * @param
     */
    public function getGoods()
    {
        return $this->hasMany(Goods::className(),['id'=>'gift_id']);
    }
}