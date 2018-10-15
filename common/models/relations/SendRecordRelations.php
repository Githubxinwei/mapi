<?php

namespace common\models\relations;


use common\models\base\Member;
use common\models\base\MemberCard;

trait SendRecordRelations
{
    /**
     * 后台 - 座位排次 - 关联会员表
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/7/28
     */
    public function getCoverMember()
    {
        return $this->hasOne(\backend\models\Member::className(),['id'=>'cover_member_id']);
    }
    /**
     * 后台 - 座位排次 - 关联会员表
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/7/28
     */
    public function getMemberCard()
    {
        return $this->hasOne(MemberCard::className(),['id'=>'member_card_id']);
    }
}