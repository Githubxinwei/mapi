<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/13
 * Time: 20:29
 */

namespace common\models\relations;

use common\models\MemberCard;
use common\models\Employee;

trait TransferRecordRelations
{
    /**
     * 后台会员管理 - 转卡记录表 - 关联会员表
     * @author huanghua <huanghua@itsports.club>
     * @create 2018/3/9
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(\backend\models\Member::className(), ['id'=>'to_member_id']);
    }
    /**
     * 后台会员管理 - 转卡记录表 - 关联会员表
     * @author huanghua <huanghua@itsports.club>
     * @create 2018/3/9
     * @return \yii\db\ActiveQuery
     */
    public function getMemberS()
    {
        return $this->hasOne(\backend\models\Member::className(), ['id'=>'from_member_id']);
    }
    /**
     * 后台会员管理 - 转卡记录表 - 关联会员卡表
     * @author huanghua <huanghua@itsports.club>
     * @create 2018/3/9
     * @return \yii\db\ActiveQuery
     */
    public function getMemberCard()
    {
        return $this->hasOne(MemberCard::className(), ['id'=>'member_card_id']);
    }
    /**
     * 后台会员管理 - 转卡记录表 - 关联员工表
     * @author huanghua <huanghua@itsports.club>
     * @create 2018/3/9
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'operator_id']);
    }
}