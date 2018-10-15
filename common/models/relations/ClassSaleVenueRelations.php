<?php
namespace common\models\relations;

use common\models\base\Organization;
trait  ClassSaleVenueRelations
{
    /**
     * 后台 - 私教课程管理 - 组织架构表
     * @return string
     * @auther 朱梦珂
     * @create 2017-4-26
     * @param
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }
}