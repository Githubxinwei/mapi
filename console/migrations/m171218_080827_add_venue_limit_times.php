<?php

use yii\db\Migration;

class m171218_080827_add_venue_limit_times extends Migration
{
    public function up()
    {
        $this->addColumn('{{%venue_limit_times}}','apply_start',"bigint COMMENT '通店开始时间'");
        $this->addColumn('{{%venue_limit_times}}','apply_end',"bigint COMMENT '通店结束时间'");
    }

    public function down()
    {
        $this->dropColumn('{{%venue_limit_times}}','apply_start');
        $this->dropColumn('{{%venue_limit_times}}','apply_end');
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
