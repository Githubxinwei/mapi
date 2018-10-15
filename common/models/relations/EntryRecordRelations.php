<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/20
 * Time: 13:18
 */

namespace common\models\relations;

use common\models\AboutClass;
use common\models\base\Organization;
use common\models\base\MemberCard;
use common\models\Member;

trait EntryRecordRelations
{
    public function getOrganization(){
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }

    /**
     * 后台 - 进场管理 - 关联会员卡表
     * @return string
     * @auther 黄华
     * @create 2017-4-21
     * @param
     */
    public function getMemberCard(){
        return $this->hasOne(\backend\models\MemberCard::className(), ['id'=>'member_card_id']);
    }
    /**
     * 后台 - 进场管理 - 关联会会员表
     * @return string
     * @auther 侯凯新
     * @create 2017-5-25
     * @param
     */
    public function getMember(){
        return $this->hasOne(\backend\models\Member::className(), ['id'=>'member_id']);
    }
    /**
     * 会员端 - 进馆记录 - 关联会会员表
     * @return string
     * @auther 辛威
     * @create 2018-06-12
     * @param
     */
    public function getMembers(){
        return $this->hasMany(Member::className(), ['id'=>'member_id']);
    }
    /**
     * 后台 -  会员到场记录  关联 会员约课表
     * @return string
     * @auther 侯凯新
     * @create 2017-5-25
     * @param
     */
    public function getAboutClass(){
        return $this->hasMany(\backend\models\AboutClass::className(),['member_id'=>'member_id']);
    }

    public function getMemberDetail(){
        return $this->hasOne(\common\models\MemberDetails::className(), ['member_id'=>'member_id']);
    }
}