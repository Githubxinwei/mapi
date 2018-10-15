<?php

use yii\db\Migration;

class m170902_085346_add_goods_change extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 新增字段 - cloud_goods_change表
         * @author huanghua <huanghua@itsport.club>
         * @create 2017/9/2
         * @inheritdoc
         */
        $this->addColumn('{{%goods_change}}', 'unit_price', "decimal(10,2)  COMMENT '商品单价'");
    }

    public function down()
    {
        $this->dropColumn('{{%goods_change}}','unit_price');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
