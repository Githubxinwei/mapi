<?php
namespace common\models\relations;

use common\models\base\Course;
use common\models\base\Goods;
use common\models\base\Server;
use common\models\base\ChargeClass;
use common\models\GiftRecord;

trait BindMemberCardRelations
{
    /**
     * 会员管理 - 会员卡详情 - 关联类型表
     * @create 2017/8/3
     * @author zhumengke <zhumengke@itsports.club>
     * @return bool
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(),['id'=>'polymorphic_id']);
    }
    /**
     * 会员管理 - 会员卡详情 - 关联服务表
     * @create 2017/8/3
     * @author zhumengke <zhumengke@itsports.club>
     * @return bool
     */
    public function getServer()
    {
        return $this->hasOne(Server::className(),['id'=>'polymorphic_id']);
    }
    /**
     * 后台会员管理 - 会员信息查询 - 关联收费课程表
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/9/29
     * @return \yii\db\ActiveQuery
     */
    public function getChargeClass()
    {
        return $this->hasOne(ChargeClass::className(), ['id'=>'polymorphic_id']);
    }
    /**
     * 后台会员管理 - 会员信息查询 - 关联套餐表关联赠品表
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/10/25
     * @return \yii\db\ActiveQuery
     */
    public function getGift()
    {
        return $this->hasOne(GiftRecord::className(), ['id'=>'polymorphic_id']);
    }
    /**
     * 后台会员管理 - 套餐 - 关联商品表
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/10/25
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsAll(){
        return $this->hasOne(Goods::className(), ['id'=>'polymorphic_id']);
    }
}