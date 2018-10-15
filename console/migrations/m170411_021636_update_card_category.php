<?php

use yii\db\Migration;

class m170411_021636_update_card_category extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%member_card}}', 'card_category_id');
        $this->dropColumn('{{%member_card}}', 'member_id');
        $this->addColumn('{{%member_card}}', 'card_category_id', "bigint(20) UNSIGNED NOT NULL COMMENT '卡种ID'");
        $this->addColumn('{{%member_card}}', 'member_id', "bigint(20) UNSIGNED NOT NULL COMMENT '会员ID'");
        $this->addColumn('{{%card_category}}', 'active_time', "bigint(20)  COMMENT '激活期限'");
        $this->addColumn('{{%card_category}}', 'status', "tinyint(4) DEFAULT 1 COMMENT '状态1：正常2：冻结3.过期'");
        $this->addColumn('{{%card_category}}', 'transfer_price', "int(11) NULL   COMMENT '转让金额'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}', 'card_category_id');
        $this->dropColumn('{{%member_card}}', 'member_id');
        $this->addColumn('{{%member_card}}', 'card_category_id', "bigint(20) UNSIGNED NOT NULL COMMENT '卡种ID'");
        $this->addColumn('{{%member_card}}', 'member_id', "bigint(20) UNSIGNED NOT NULL COMMENT '会员ID'");
        $this->createIndex('index_card_category_id', '{{%member_card}}', 'card_category_id');
        $this->createIndex('index_member_id', '{{%member_card}}', 'member_id');
        $this->dropColumn('{{%card_category}}', 'active_time');
        $this->dropColumn('{{%card_category}}', 'status');
        $this->dropColumn('{{%card_category}}', 'transfer_price');
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
