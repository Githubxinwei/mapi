<?php

use yii\db\Migration;

class m170526_031429_create_add_consumption_history extends Migration
{
    public function up()
    {
        $this->addColumn('{{%consumption_history}}', 'category',"varchar(200) COMMENT '消费类型状态 如：升级 续费'");
    }

    public function down()
    {
        $this->dropColumn('{{%consumption_history}}', 'category');
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
