<?php

use yii\db\Migration;

class m170712_090521_add_order extends Migration
{
    public function Up()
    {
        $this->addColumn('{{%order}}','merchant_order_number',"varchar(255) COMMENT '商户单号'");
    }

    public function Down()
    {
        $this->dropColumn('{{%order}}','merchant_order_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170712_090521_add_order cannot be reverted.\n";

        return false;
    }
    */
}
