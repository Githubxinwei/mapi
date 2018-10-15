<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/13 0013
 * Time: 15:31
 */
namespace common\models\relations;
use common\models\Admin;
use common\models\Cabinet;
use common\models\Organization;

trait CabinetRentRelations
{
    /**
     * @desc: 业务后台 - 柜子消费记录表 - 关联柜子表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/03/13
     * @return mixed
     */
    public function getCabinet()
    {
        return $this->hasOne(Cabinet::className(),['id'=>"cabinet_id"]);
    }

    public function getAdmin()
    {
        return $this->hasOne(Admin::className(),["id"=>"create_id"]);
    }
}