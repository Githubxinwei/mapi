<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/25
 * Time: 15:07
 * Desc:体侧信息预约记录
 */
namespace common\models\relations;

use common\models\Member;

trait MemberPhysicalTestRecordRelations
{
    /**
     * 会员管理 - 体测信息查询 - 关联会员表
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/06/25
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id'=>'member_id']);
    }
}