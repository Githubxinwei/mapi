<?php

use yii\db\Migration;

class m171113_021623_add_member_card extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_member_card表
     * @author huanghua <huanghua@itsport.club>
     * @create 2017/11/13
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%member_card}}', 'note', "varchar(200)  COMMENT '备注'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}', 'note');
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
