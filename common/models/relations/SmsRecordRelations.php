<?php

namespace common\models\relations;
use common\models\base\Organization;

trait SmsRecordRelations
{
    /**
     * 后台 - 短信管理 - 关联会员表
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/9/8
     */
    public function getMember()
    {
        return $this->hasOne(\backend\models\Member::className(),['id'=>'member_id']);
    }
    /**
     * 后台 - 短信管理 - 关联组织架构表
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/9/8
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }
}