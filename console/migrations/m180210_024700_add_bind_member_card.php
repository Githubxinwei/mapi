<?php

use yii\db\Migration;

class m180210_024700_add_bind_member_card extends Migration
{
    public function up()
    {
        $this->addColumn('{{%bind_member_card}}', 'company_id', "int(11)  COMMENT '公司Id'");
        $this->addColumn('{{%bind_member_card}}', 'venue_id', "int(11)  COMMENT '场馆Id'");
        $this->addColumn('{{%bind_member_card}}', 'polymorphic_ids', "JSON  COMMENT '多选JSON存储Id'");
    }

    public function down()
    {
        $this->dropColumn('{{%bind_member_card}}','company_id');
        $this->dropColumn('{{%bind_member_card}}','venue_id');
        $this->dropColumn('{{%bind_member_card}}','polymorphic_ids');
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
