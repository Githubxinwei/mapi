<?php

use yii\db\Migration;

class m170524_141737_create_add_member extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member}}', 'venue_id',"bigint(20) COMMENT '场馆ID'");
        $this->dropColumn('{{%venue_limit_times}}', 'total_times');
        $this->dropColumn('{{%venue_limit_times}}', 'overplus_times');
        $this->addColumn('{{%venue_limit_times}}', 'total_times',"int(11) COMMENT '总次数'");
        $this->addColumn('{{%venue_limit_times}}', 'overplus_times',"int(11) COMMENT '剩余次数'");
    }

    public function down()
    {
        $this->dropColumn('{{%member}}', 'venue_id');
        $this->dropColumn('{{%venue_limit_times}}', 'total_times');
        $this->dropColumn('{{%venue_limit_times}}', 'overplus_times');
        $this->addColumn('{{%venue_limit_times}}', 'total_times',"int(11) COMMENT '总次数'");
        $this->addColumn('{{%venue_limit_times}}', 'overplus_times',"int(11) COMMENT '剩余次数'");
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
