<?php

use yii\db\Migration;

class m170419_024036_create_cabinet_type extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_cabinet_type 柜子类型表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/4/19
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '柜子类型表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%cabinet_type}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'type_name' => $this->string(200)->notNull()->comment('类型名称'),
            'sex' => $this->smallInteger()->unsigned()->notNull()->comment('状态：1男，2女'),
            'created_at' => $this->bigInteger()->unsigned()->comment('创建时间'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%cabinet_type}}');
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
