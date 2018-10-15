<?php

use yii\db\Migration;

class m170814_013154_add_charge_class extends Migration
{
    public function up()
    {
        $this->addColumn('{{%charge_class}}', 'month_up_num', 'tinyint COMMENT "每月上课数量"');
        $this->addColumn('{{%member_course_order}}', 'month_up_num', 'tinyint COMMENT "每月上课数量"');
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}', 'month_up_num');
        $this->dropColumn('{{%member_course_order}}', 'month_up_num');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170814_013154_add_charge_class cannot be reverted.\n";

        return false;
    }
    */
}
