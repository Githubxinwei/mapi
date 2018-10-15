<?php

use yii\db\Migration;

class m170928_035533_add_order extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}','purchase_num',"int COMMENT '购买数量(1:私课节数2:卡数量)'");
    }

    public function down()
    {
        $this->dropColumn('{{%order}}','purchase_num');
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
