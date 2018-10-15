<?php

use yii\db\Migration;

class m170410_094130_update_course extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%course}}', 'create_at');
        $this->addColumn('{{%course}}', 'create_id', "bigint NOT NULL  COMMENT '创建人id'");
    }

    public function down()
    {
        $this->addColumn('{{%course}}', 'create_at', "bigint NOT NULL  COMMENT '创建人id'");
        $this->dropColumn('{{%course}}', 'create_id');
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
