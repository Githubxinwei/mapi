<?php

use yii\db\Migration;

class m170805_072108_add_gift_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%gift_record}}','name',"varchar(255) COMMENT '物品名称'");
        $this->addColumn('{{%gift_record}}','create_at',"bigint COMMENT '创建时间'");
        $this->addColumn('{{%gift_record}}','get_day',"bigint COMMENT '领取时间'");
    }

    public function down()
    {
        $this->dropColumn('{{%gift_record}}','name');
        $this->dropColumn('{{%gift_record}}','create_at');
        $this->dropColumn('{{%gift_record}}','get_day');
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
