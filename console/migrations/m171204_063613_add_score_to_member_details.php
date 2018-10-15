<?php

use yii\db\Migration;

class m171204_063613_add_score_to_member_details extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_details}}','score',"int COMMENT '积分'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_details}}','score',"int COMMENT '积分'");
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
