<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/13 0013
 * Time: 上午 9:52
 */
namespace common\models\relations;
use common\models\base\Organization;

trait  AppCardDiscountRelations
{
    /**
     * 后台 - 手机折扣 - 组织架构表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2018/2/13
     * @return string
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(),['id' => 'venue_id']);
    }
}