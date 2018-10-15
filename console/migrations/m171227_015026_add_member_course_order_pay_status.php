<?php

use yii\db\Migration;

/**
 * Class m171227_015026_add_member_course_order_pay_status
 */
class m171227_015026_add_member_course_order_pay_status extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_course_order}}','pay_status',"tinyint(4) default 1  COMMENT '1:已付款，2:已退款'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_course_order}}','pay_status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171227_015026_add_member_course_order_pay_status cannot be reverted.\n";

        return false;
    }
    */
}
