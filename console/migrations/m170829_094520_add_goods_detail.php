<?php

use yii\db\Migration;

class m170829_094520_add_goods_detail extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_goods_detail表
     * @author huanghua <huanghua@itsport.club>
     * @create 2017/8/29
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%goods_detail}}', 'note', "varchar(200)  COMMENT '备注'");
        $this->addColumn('{{%goods_detail}}', 'unit_price', "decimal(10,2)  COMMENT '商品单价'");
    }

    public function down()
    {
        $this->dropColumn('{{%goods_detail}}', 'note');
        $this->dropColumn('{{%goods_detail}}','unit_price');
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
