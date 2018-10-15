<?php

use yii\db\Migration;

class m170902_061213_add_employee extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_employee表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/9/2
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%employee}}', 'fingerprint', 'text COMMENT "指纹"');
    }

    public function down()
    {
        $this->dropColumn('{{%employee}}', 'fingerprint');
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
