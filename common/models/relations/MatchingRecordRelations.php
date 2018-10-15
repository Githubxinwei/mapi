<?php
namespace common\models\relations;

use common\models\base\MemberCard;
use common\models\base\CardCategory;
use common\models\base\Employee;

trait MatchingRecordRelations
{
    /**
     * 后台会员管理 - 会员卡表查询 - 关联会员卡表
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/2/2
     * @return \yii\db\ActiveQuery
     */
    public function getMemberCard()
    {
        return $this->hasOne(MemberCard::className(), ['id'=>'member_card_id']);
    }
    /**
     * 后台会员管理 - 会员柜表查询 - 关联员工表
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/2/2
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'create_id']);
    }
    /**
     * 后台会员管理 - 卡种表查询 - 关联卡种表
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/2/2
     * @return \yii\db\ActiveQuery
     */
    public function getCardCategory()
    {
        return $this->hasOne(CardCategory::className(), ['id'=>'member_category_id']);
    }
    public function getOldCardCategory()
    {
        return $this->hasOne(CardCategory::className(), ['id'=>'member_card_id']);
    }
}