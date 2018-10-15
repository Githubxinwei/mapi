<?php

use yii\db\Migration;

class m170728_095425_add_member_card_order extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%order}}','deposit',"decimal(10,2) COMMENT '订单：定金'");
        $this->addColumn('{{%order}}','cash_coupon',"decimal(10,2) COMMENT '订单：代金券'");
        $this->addColumn('{{%order}}','net_price',"decimal(10,2) COMMENT '订单：实收价格'");
        $this->addColumn('{{%order}}','all_price',"decimal(10,2) COMMENT '订单：商品总价格'");
    }

    public function safeDown()
    {
        $this->dropColumn('{{%order}}','deposit');
        $this->dropColumn('{{%order}}','cash_coupon');
        $this->dropColumn('{{%order}}','net_price');
        $this->dropColumn('{{%order}}','all_price');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170728_095425_add_member_card_order cannot be reverted.\n";

        return false;
    }
    */
}
