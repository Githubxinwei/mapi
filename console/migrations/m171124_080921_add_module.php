<?php

use yii\db\Migration;

class m171124_080921_add_module extends Migration
{
    public function up()
    {
        $this->addColumn('{{%module}}','is_show',"tinyint(4) COMMENT '是否显示1.显示,2不显示'");
    }

    public function down()
    {
        $this->dropColumn('{{%module}}','is_show');
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
