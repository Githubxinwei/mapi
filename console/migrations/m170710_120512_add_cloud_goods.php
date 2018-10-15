<?php

use yii\db\Migration;

class m170710_120512_add_cloud_goods extends Migration
{
    /**
     * @数据库 - 商品订单 -  新增商品类型
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/7/9
     */
    public function up()
    {
        $this->addColumn('{{%goods}}','goods_attribute',"tinyint(6) COMMENT '1:商品 2:赠品'");
    }

    public function down()
    {
        $this->dropColumn('{{%goods}}','goods_attribute');
    }
}
