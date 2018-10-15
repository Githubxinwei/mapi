<?php

use yii\db\Migration;

class m170904_094705_add_goods_change extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_goods_change表
     * @author huanghua <huanghua@itsport.club>
     * @create 2017/9/2
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%goods_change}}', 'surplus_amount', 'bigint COMMENT "剩余数量"');
        $this->addColumn('{{%goods_change}}', 'type', 'smallint COMMENT "类型:1剩余2不足"');
    }

    public function down()
    {
        $this->dropColumn('{{%goods_change}}', 'surplus_amount');
        $this->dropColumn('{{%goods_change}}', 'type');
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
