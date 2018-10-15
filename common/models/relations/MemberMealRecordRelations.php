<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/11
 * Time: 11:22
 * Desc:会员健康餐预约记录
 */
namespace common\models\relations;

use backend\models\Member;
use backend\models\Goods;
use common\models\GoodsStandard;
use common\models\Organization;

trait MemberMealRecordRelations
{
    /**
     * 会员端 - 会员健康餐预约记录 - 关联会员表
     * @author xinwei <xniwei@itsports.club>
     * @create 2018/07/11
     * @return mixed
     */
    public function getMember(){
        return $this->hasOne(Member::className(),['id' => 'member_id']);
    }
    /**
     * 会员端 - 会员健康餐预约记录 - 关联商品表
     * @author xinwei <xniwei@itsports.club>
     * @create 2018/07/20
     * @return mixed
     */
    public function getGoods(){
        return $this->hasMany(Goods::className(),['id' => 'goods_id']);
    }
    /**
     * 会员端 - 会员健康餐预约记录 - 关联商品表
     * @author xinwei <xniwei@itsports.club>
     * @create 2018/07/20
     * @return mixed
     */
    public function getGoodsStandard(){
        return $this->hasOne(GoodsStandard::className(),['goods_id' => 'goods_id']);
    }
    /**
     * 会员端 - 会员健康餐预约记录 - 组织架构表
     * @author xinwei <xniwei@itsports.club>
     * @create 2018/07/20
     * @return mixed
     */
    public function getOrganization(){
        return $this->hasOne(Organization::className(),['id' => 'venue_id']);
    }
}