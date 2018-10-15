<?php

use yii\db\Migration;

class m180123_065349_add_member_card extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_card}}','type',"smallint COMMENT '卡类型1瑜伽,2健身,3舞蹈,4综合'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}','type');
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
