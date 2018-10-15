<?php

use yii\db\Migration;

class m171117_070226_add_charge_class extends Migration
{
    public function up()
    {
        $this->addColumn('{{%charge_class}}','product_type',"tinyint(4) COMMENT '1常规PT,2特色课,3游泳课'");
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}','product_type');
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
