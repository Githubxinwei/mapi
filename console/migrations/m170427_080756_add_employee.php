<?php

use yii\db\Migration;

class m170427_080756_add_employee extends Migration
{
    public function up()
    {
        $this->addColumn('{{%employee}}', 'is_check', 'smallint(10) COMMENT "是否需要审核:1需要,0不需要"');
        $this->addColumn('{{%employee}}', 'is_pass', 'smallint(10) COMMENT "是否通过审核:1通过,0未通过"');
    }

    public function down()
    {
        $this->dropColumn('{{%employee}}', 'is_check');
        $this->dropColumn('{{%employee}}', 'is_pass');
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
