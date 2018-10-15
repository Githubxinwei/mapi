<?php
namespace common\models\relations;
use common\models\CardCategory;
use common\models\CardCategoryType;
use common\models\MemberCard;
trait GiftCardListRelations
{
    /**
     * @desc: 关联卡种表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/02/08
     * @return mixed
     */
    public function getCardCategory()
    {
        return $this->hasOne(CardCategory::className(), ['id'=>'card_category_id']);
    }

    /**
     * @desc: 关联卡种类型表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/02/08
     * @return mixed
     */
    public function getCardCategoryType()
    {
        return $this->hasOne(CardCategoryType::className(),['id'=>'category_type_id']);
    }

    /**
     * @desc: 关联会员卡表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/02/10
     * @return mixed
     */
    public function getMemberCard(){
        return $this->hasOne(MemberCard::className(),['card_number'=>'card_number']);
    }
}