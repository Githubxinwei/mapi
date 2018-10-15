<?php

use yii\db\Migration;

class m170828_061947_add_module extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_module表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/28
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%module}}', 'number', 'int COMMENT "序号"');
    }

    public function down()
    {
        $this->dropColumn('{{%module}}', 'number');
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
