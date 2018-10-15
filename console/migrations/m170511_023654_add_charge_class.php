<?php

use yii\db\Migration;

class m170511_023654_add_charge_class extends Migration
{
    public function up()
    {
        $this->addColumn('{{%charge_class}}', 'type', 'smallint COMMENT "类型:1多课程，2单课程"');
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}', 'type');
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
