<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/2
 * Time: 13:12
 * Desc:用户手机通讯录
 */
namespace common\models\relations;

trait AppUserContactRelations
{
    /**
     * 会员端 - 用户手机通讯录 - 关联会员表
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/07/02
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(\common\models\Member::className(), ['id' => 'member_id']);
    }
}