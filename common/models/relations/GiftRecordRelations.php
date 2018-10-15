<?php
namespace common\models\relations;

use backend\models\GiftDay;
use common\models\base\Member;
use common\models\base\MemberCard;
use common\models\base\ServicePay;
trait  GiftRecordRelations
{
    /**
     * 后台 - 赠品管理 - 关联会员表
     * @return string
     * @author 黄华
     * @create 2017-4-21
     * @param
     */
    public function getMember(){
        return $this->hasOne(Member::className(), ['id'=>'member_id']);
    }
    /**
     * 后台 - 赠品管理 - 关联会员卡表
     * @return string
     * @author 黄华
     * @create 2017-4-21
     * @param
     */
    public function getMemberCard(){
        return $this->hasOne(MemberCard::className(), ['id'=>'member_card_id']);
    }
    /**
     * 后台 - 赠品管理 - 关联收费项目表
     * @return string
     * @auther 黄华
     * @create 2017-4-21
     * @param
     */
    public function getServicePay(){
        return $this->hasOne(ServicePay::className(), ['id'=>'service_pay_id']);
    }
    /**
     * 后台 - 赠品管理 - 关联会员表
     * @return string
     * @author 黄华
     * @create 2017-4-21
     * @param
     */
    public function getGiftDay(){
        return $this->hasOne(GiftDay::className(), ['id'=>'service_pay_id']);
    }

}