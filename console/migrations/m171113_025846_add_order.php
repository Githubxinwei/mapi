<?php

use yii\db\Migration;

class m171113_025846_add_order extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_order表
     * @author huanghua <huanghua@itsport.club>
     * @create 2017/11/13
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%order}}', 'new_note', "varchar(200)  COMMENT '新备注'");
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'new_note');
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
