<?php

use yii\db\Migration;

class m171202_073544_add_fitness_diet extends Migration
{
    public function up()
    {
        $this->addColumn('{{%fitness_diet}}','company_id',"bigint COMMENT '公司id'");
        $this->addColumn('{{%fitness_diet}}','venue_id',"bigint COMMENT '场馆id'");
    }

    public function down()
    {
        $this->dropColumn('{{%fitness_diet}}','company_id');
        $this->dropColumn('{{%fitness_diet}}','venue_id');
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
