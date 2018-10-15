<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/20 0020
 * Time: 上午 9:34
 */
namespace common\models\relations;
use common\models\base\Organization;

trait  VenueLimitTimesRelations{
    /**
     * 后台 - 进场馆次数核算表 关联 场馆表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/12/20
     */
    public function  getOrganization(){
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }
}
?>