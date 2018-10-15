<?php

use yii\db\Migration;

class m180103_070641_add_charge_class extends Migration
{
    public function up()
    {
        $this->addColumn('{{%charge_class}}','group',"smallint default 1 COMMENT '1单人私课,2多人私课'");
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}','group');
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
