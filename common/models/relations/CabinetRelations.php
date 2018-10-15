<?php
namespace common\models\relations;
use backend\models\Organization;
use common\models\base\Admin;
use common\models\base\CabinetType;
use common\models\Member;
use common\models\MemberCabinet;

trait CabinetRelations
{
    /**
     * 后台会员管理 - 会员柜表查询 - 关联柜子类型表
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/18
     * @return \yii\db\ActiveQuery
     */
    public function getCabinetType()
    {
        return $this->hasOne(CabinetType::className(), ['id' => 'cabinet_type_id']);
    }
    /**
     * 后台会员管理 - 会员柜表查询 - 关联会员表
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/18
     * @return \yii\db\ActiveQuery
     */
    public function getMemberCabinet()
    {
        return $this->hasOne(MemberCabinet::className(), ['cabinet_id' => 'id']);
    }
    /**
     * 后台会员管理 - 会员柜表查询 - 关联柜子表
     * @author Houkaixin <Houkaixin@itsports.club>
     * @create 2017/5/3
     * @return \yii\db\ActiveQuery
     */
    public function getCabinet()
    {
        return $this->hasOne(CabinetType::className(), ['id' => 'cabinet_id']);
    }

    /**
     * 后台会员管理 - 会员柜表查询 - 关联经办人
     * @author Houkaixin <huanghua@itsports.club>
     * @create 2017/5/3
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(),['id' =>'creater_id']);
    }
    /**
     * 后台会员管理 - 会员柜表查询 - 关联客户表
     * @author Houkaixin <huanghua@itsports.club>
     * @create 2017/5/3
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }

    /**
     * 后台会员管理 - 柜子表 - 关联会员柜子表
     * @author Houkaixin <Houkaixin@itsports.club>
     * @create 2017/6/12
     * @return \yii\db\ActiveQuery
     */
    public function getMemCabinet()
    {
        return $this->hasOne(MemberCabinet::className(), ['cabinet_id' => 'id'])->onCondition(["!=","cloud_member_cabinet.status",4]);
    }
    /**
     * 柜子表 关联 组织架构表（pc）
     * @author Xin Wei<xinwei@itsports.club>
     * @create 2018/3/28
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization(){
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }
}