<?php
namespace common\models\relations;

use backend\models\BindPackClass;
use common\models\base\ChargeClass;
use common\models\base\Course;
use backend\models\LimitCardNumber;
use common\models\base\Organization;
use common\models\base\Server;
use common\models\CardCategoryType;
use common\models\base\ServerCombo;
use common\models\base\ClassServer;
use common\models\base\CardTime;
use common\models\base\MemberCard;
use common\models\base\BindPack;
trait  CardCategoryRelations
{
    /**
     * 会员卡管理 - 会员卡 - 关联类型表
     * @create 2017/4/5
     * @author lihuien<lihuien@itsports.club>
     * @return bool
     */
    public function getCardCategoryType()
    {
         return $this->hasOne(CardCategoryType::className(),['id'=>'category_type_id']);
    }
    /**
     * 会员卡管理 - 会员卡 - 关联课程表
     * @create 2017/4/5
     * @author lihuien<lihuien@itsports.club>
     * @return bool
     */
    public function getClassServer()
    {
        return $this->hasOne(ClassServer::className(),['id'=>'class_server_id']);
    }
    /**
     * 会员卡管理 - 会员卡 - 关联服务表
     * @create 2017/4/5
     * @author lihuien<lihuien@itsports.club>
     * @return bool
     */
    public function getServerCombo()
    {
        return $this->hasOne(ServerCombo::className(),['id'=>'server_combo_id']);
    }
    /**
     * 会员信息管理 - 会员信息查询 - 关联卡时段表
     * @create 2017/4/6
     * @author huanghua<huanghua@itsports.club>
     * @return bool
     */
    public function getCardTime()
    {
        return $this->hasOne(CardTime::className(),['card_category_id'=>'id']);
    }
    /**
     * 会员信息管理 - 会员信息查询 - 关联会员卡表
     * @create 2017/4/6
     * @author huanghua<huanghua@itsports.club>
     * @return bool
     */
    public function getMemberCard()
    {
        return $this->hasOne(MemberCard::className(),['card_category_id'=>'id']);
    }
    /**
     * 会员信息管理 - 会员信息查询 - 关联组织架构表
     * @create 2017/4/8
     * @author huanghua<huanghua@itsports.club>
     * @return bool
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }
    /**
     * 会员信息管理 - 会员信息查询 - 关联卡种绑定套餐表
     * @create 2017/4/20
     * @author huanghua<huanghua@itsports.club>
     * @return bool
     */
    public function getBindPack()
    {
        return $this->hasMany(BindPack::className(),['card_category_id'=>'id']);
    }

    public function getBindPackClass()
    {
        return $this->hasMany(\backend\models\BindPack::className(),['card_category_id'=>'id']);
    }
    public function getBindPackServer()
    {
        return $this->hasMany(\backend\models\BindPack::className(),['card_category_id'=>'id']);
    }
    /**
     * 会员信息管理 - 会员信息查询 - 关联卡种限发量表
     * @create 2017/4/20
     * @author huanghua<huanghua@itsports.club>
     * @return bool
     */
    public function getLimitCardNumber()
    {
        return $this->hasOne(LimitCardNumber::className(),['card_category_id'=>'id']);
    }
    public function getLimitCardNumbers()
    {
        return $this->hasMany(LimitCardNumber::className(),['card_category_id'=>'id']);
    }
    /**
     * 会员信息管理 - 会员信息查询 - 关联课种信息
     * @create 2017/5/11
     * @author houkaixin<houkaixin@itsports.club>
     * @return bool
     */
    public function getBindPackCourse()
    {
        return $this->hasOne(Course::className(),['id'=>'polymorphic_id']);
    }
    /**
     * 验卡管理 - 验卡 - 获取服务信息
     * @create 2017/4/6
     * @author huanghua<huanghua@itsports.club>
     * @return bool
     */
    public function getServer()
    {
        return $this->hasOne(Server::className(),['id'=>"polymorphic_id"]);
    }
    /**
     * 验卡管理 - 验卡 - 获取服务信息
     * @create 2017/4/6
     * @author huanghua<huanghua@itsports.club>
     * @return bool
     */
    public function getChargeClass()
    {
        return $this->hasOne(ChargeClass::className(),['id'=>"polymorphic_id"]);
    }
    /**
     * 卡种 - 通店 - 获取所有通店场馆
     * @create 2017/7/6
     * @author huangpengju<huangpengju@itsports.club>
     * @return bool
     */
    public function getLimitCardNumberAll()
    {
        return $this->hasMany(\backend\models\LimitCardNumber::className(),['card_category_id'=>'id']);
    }
    /**
     * 卡种 - 通店 - 获取卡种所有绑定套餐
     * @create 2017/7/6
     * @author huangpengju<huangpengju@itsports.club>
     * @return bool
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(),['id'=>'polymorphic_id']);
    }
    /**
     * 关联折扣表
     * @create 2017/8/3
     * @author zhumengke <zhumengke@itsports.club>
     * @return bool
     */
    public function getCardDiscount()
    {
        return $this->hasMany(\common\models\base\CardDiscount::className(),['limit_card_id'=>'id']);
    }
    /**
     * 关联合同表
     * @create 2017/8/3
     * @author zhumengke <zhumengke@itsports.club>
     * @return bool
     */
    public function getDeal()
    {
        return $this->hasOne(\common\models\Deal::className(),['id'=>'deal_id']);
    }
    /**
     * 卡种表 关联  bindPack 中的 团课套餐
     * @create 2017/10/25
     * @author houkaixin<houkaixin@itsports.club>
     * @return bool
     */
    public function getLeagueBindPack(){
        return $this->hasMany(\backend\models\BindPack::className(),['card_category_id'=>'id'])->onCondition(["leagueBindPack.polymorphic_type"=>"class"]);
    }
    /**
     * 卡种表 关联  bindPack 私课套餐
     * @create 2017/10/25
     * @author houkaixin<houkaixin@itsports.club>
     * @return bool
     */
    public function getPrivateLessonPack(){
        return $this->hasMany(\backend\models\BindPack::className(),['card_category_id'=>'id'])->onCondition(["privateLessonPack.polymorphic_type"=>["hs","pt","birth"]]);
    }
    /**
     * 卡种表 关联  bindPack 赠品套餐
     * @create 2017/10/25
     * @author houkaixin<houkaixin@itsports.club>
     * @return bool
     */
    public function getGiftPack(){
        return $this->hasMany(\backend\models\BindPack::className(),['card_category_id'=>'id'])->onCondition(["giftPack.polymorphic_type"=>["gift"]]);
    }

    /**
     * 卡种表关联 卡种 卡通店表
     * @create 2017/4/20
     * @author houkaixin<houkaixin@itsports.club>
     * @return bool
     */
    public function getTheLimitCardNumber()
    {
        return $this->hasMany(LimitCardNumber::className(),['card_category_id'=>'id']);
    }

    /**
     * 卡种表关联 卡种 卡通店表
     * @create 2017/4/20
     * @author houkaixin<houkaixin@itsports.club>
     * @return bool
     */
    public function getBindPackBirth(){
        return $this->hasOne(BindPack::className(),['card_category_id'=>'id'])
                              ->onCondition(["and",
                                          //  ["polymorphic_id"=>constant("privateId")],
                                            ["polymorphic_type"=>"birth"],
                                          //  ["bindPackBirth.status"=>4]
                                          ]
                                            );

    }
}