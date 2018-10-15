<?php

use yii\db\Migration;

class m170913_032838_add_about_yard extends Migration
{
    public function up()
    {
        $this->addColumn('{{%about_yard}}','about_start',"bigint COMMENT '预约开始时间'");
        $this->addColumn('{{%about_yard}}','about_end',"bigint COMMENT '预约结束时间'");
    }

    public function down()
    {
        $this->dropColumn('{{%about_yard}}','about_start');
        $this->dropColumn('{{%about_yard}}','about_end');
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
