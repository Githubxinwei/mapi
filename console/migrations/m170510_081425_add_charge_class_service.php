<?php

use yii\db\Migration;

class m170510_081425_add_charge_class_service extends Migration
{
    public function up()
    {
        $this->addColumn('{{%charge_class_service}}', 'service_num', 'int COMMENT "服务数量"');
        $this->addColumn('{{%charge_class_service}}', 'gift_num', 'int COMMENT "赠品数量"');
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class_service}}', 'service_num');
        $this->dropColumn('{{%charge_class_service}}', 'gift_num');
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
