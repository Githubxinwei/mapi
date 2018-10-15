<?php
namespace common\models\relations;

trait  ConfigRelations
{
    /**
     * 后台会员管理 - 会员信息查询 - 关联会员详细信息表
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/3/30
     * @return \yii\db\ActiveQuery
     */
    public function getMemberDetails()
    {
        return $this->hasOne(\common\models\MemberDetails::className(),['member_id'=>'id']);
    }
}