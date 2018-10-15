<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/16
 * Time: 9:22
 * Desc:会员合同记录表
 */
namespace common\models\relations;

use backend\models\Member;
use backend\models\MemberCard;
use backend\models\MemberDetails;
use backend\models\Organization;
use backend\models\MemberCourseOrderDetails;

trait  MemberDealRecordRelations
{
    /**
     * 会员端 - 会员合同记录 - 关联会员表
     * @author xinwei <xniwei@itsports.club>
     * @create 2018/07/16
     * @return mixed
     */
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id']);
    }
    /**
     * 会员端 - 会员合同记录 - 关联会员详细信息表
     * @author xinwei <xniwei@itsports.club>
     * @create 2018/07/16
     * @return mixed
     */
    public function getMemberDetails(){
        return $this->hasOne(MemberDetails::className(),['member_id'=>'member_id']);
    }
    /**
     * 会员端 - 会员合同记录 - 关联会员卡表
     * @author xinwei <xniwei@itsports.club>
     * @create 2018/07/16
     * @return mixed
     */
    public function getMemberCard(){
        return $this->hasOne(MemberCard::className(),['id'=>'type_id']);
    }
    /**
     * 会员端 - 会员合同记录 - 会员购买私课订单详情表
     * @author xinwei <xniwei@itsports.club>
     * @create 2018/07/16
     * @return mixed
     */
    public function getMemberCourseOrderDetails(){
        return $this->hasOne(MemberCourseOrderDetails::className(),['id'=>'type_id']);
    }
    /**
     * 会员端 - 会员合同记录 - 关联组织架构表
     * @author xinwei <xniwei@itsports.club>
     * @create 2018/07/16
     * @return mixed
     */
    public function getOrganization(){
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }
}