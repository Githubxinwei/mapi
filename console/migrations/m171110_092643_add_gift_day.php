<?php

use yii\db\Migration;

class m171110_092643_add_gift_day extends Migration
{
    public function up()
    {
        $this->addColumn('{{%gift_day}}','category_id',"json COMMENT '卡种id'");
    }

    public function down()
    {
        $this->dropColumn('{{%gift_day}}','category_id');
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
