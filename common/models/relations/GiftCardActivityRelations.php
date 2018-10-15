<?php
namespace common\models\relations;
use common\models\base\Organization;
use common\models\MemberCard;

trait GiftCardActivityRelations
{
    /**
     * @desc: 关联组织获取场馆名称
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/02/10
     * @return mixed
     */
    public function getOrganization(){
        return $this->hasOne(Organization::className(), ['id'=>'venue_id']);
    }

}