<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/13
 * Time: 20:29
 */

namespace common\models\relations;
use backend\models\AboutYard;
use backend\models\EntryRecord;
use backend\models\VenueYard;
use common\models\AboutClass;
use common\models\Member;
use common\models\MemberCard;
use common\models\Organization;

trait AboutYardRelations
{
    /**
     * 后台会员管理 - 约课信息查询 - 关联课程表
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/4/11
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id'=>'member_id']);
    }

    public function getEntryRecord(){
        return $this->hasMany(EntryRecord::className(), ['member_id'=>'member_id'])->onCondition(["between","entry_time",constant("classDate"),constant("classStart")]);
    }

    public function getVenueYard(){
        return $this->hasOne(VenueYard::className(),['id'=>'yard_id']);
    }

    /**
     * 会员端-关联会员卡
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/14
     * @return mixed
     */
    public function getMemberCard()
    {
        return $this->hasOne(MemberCard::className(), ['id' => 'member_card_id']);
    }
}
?>