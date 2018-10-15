<?php

use yii\db\Migration;

class m180120_085404_add_venue_limit_times extends Migration
{
    public function up()
    {
        $this->addColumn('{{%venue_limit_times}}','about_limit',"smallint default 1 COMMENT '团课预约设置-1不受限制1受'");
    }

    public function down()
    {
        $this->dropColumn('{{%venue_limit_times}}','about_limit');
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
