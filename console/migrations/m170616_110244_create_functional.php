<?php

use yii\db\Migration;

class m170616_110244_create_functional extends Migration
{
    /**
     * @数据库 - 创建表 - functional 功能表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/16
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '功能表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%functional}}', [
            'id'        =>  $this->bigPrimaryKey()->comment("自增ID"),
            'name'      =>  $this->string(200)->notNull()->comment("功能名称"),
            'note'      =>  $this->string(200)->comment("说明"),
            'e_name'    =>  $this->string(200)->comment("功能英文名"),
            'create_id' => $this->bigInteger()->comment("创建人"),
            'create_at' => $this->bigInteger()->comment("创建时间"),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%functional}}');
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
