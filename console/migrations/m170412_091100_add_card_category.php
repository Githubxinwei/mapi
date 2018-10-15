<?php

use yii\db\Migration;

class m170412_091100_add_card_category extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%card_category}}', 'recharge_ent_time');
        $this->addColumn('{{%card_category}}', 'recharge_end_time', "varchar(200) COMMENT '按时段消费结束时间'");
    }

    public function down()
    {
        $this->dropColumn('{{%card_category}}', 'recharge_end_time');
        $this->addColumn('{{%card_category}}', 'recharge_ent_time', "varchar(200) COMMENT '按时段消费结束时间'");
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
