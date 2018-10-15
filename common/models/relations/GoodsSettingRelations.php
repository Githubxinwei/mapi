<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/9
 * Time: 18:38
 * Desc:商品设置表
 */
namespace common\models\relations;

use backend\models\Goods;
use common\models\Organization;

trait GoodsSettingRelations
{
    /**
     * 会员端 - 商品设置表 - 关联会员表
     * @author xinwei <xniwei@itsports.club>
     * @create 2018/07/09
     * @return mixed
     */
    public function getGoods(){
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }
    /**
     * 会员端 - 商品设置表 - 关联组织架构表
     * @author xinwei <xniwei@itsports.club>
     * @create 2018/07/18
     * @return mixed
     */
    public function getOrganization(){
        return $this->hasOne(Organization::className(), ['id' => 'company_id']);
    }
}