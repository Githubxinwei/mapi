<?php

use yii\db\Migration;

class m171017_123139_add_limit_card_times_venue_ids extends Migration
{
    public function up()
    {
        $this->addColumn('{{%limit_card_number}}','venue_ids',"json  COMMENT '数组通店场馆数据'");
        $this->addColumn('{{%venue_limit_times}}','venue_ids',"json  COMMENT '数组通店场馆数据'");
    }

    public function down()
    {
        $this->dropColumn('{{%limit_card_number}}','venue_ids');
        $this->dropColumn('{{%venue_limit_times}}','venue_ids');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171017_123139_add_limit_card_times_venue_ids cannot be reverted.\n";

        return false;
    }
    */
}
