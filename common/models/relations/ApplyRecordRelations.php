<?php
namespace common\models\relations;

use common\models\base\Organization;
trait  ApplyRecordRelations
{
    /**
     * 后台 - 公司联盟- 组织架构表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/27
     * @return string
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(),['id'=>'apply_id']);
    }

    /**
     * 后台 - 公司联盟- 组织架构表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/27
     * @return string
     */
    public function getOrganizations()
    {
        return $this->hasOne(Organization::className(), ['id'=>'be_apply_id']);
    }
}