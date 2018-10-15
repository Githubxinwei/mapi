<?php

use yii\db\Migration;

class m170408_032100_update_course extends Migration
{
    /**
     * @数据库 - 修改表 - cloud_course 修改创建人 字段
     * @author Hou kaixin<houkaixin@itsport.club>
     * @create 2017/4/8
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('{{%course}}', 'create_person');
        $this->addColumn('{{%course}}', 'create_at', "bigint NOT NULL  COMMENT '创建人id'");
        $this->createIndex('index_create_at', '{{%course}}', 'create_at');
    }

    public function down()
    {
        $this->addColumn('{{%course}}','create_person',"varchar(255) COMMENT '创建人'");
        $this->dropColumn('{{%course}}', 'create_at');
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
