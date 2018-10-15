<?php

use yii\db\Migration;

class m170328_095413_create_server extends Migration
{
    /**
     * @数据库 - 创建者 - cloud_server_combo  服务表
     * @author Hou Kaixin<houkaixin@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '服务表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%server}}', [
            'id'             =>  $this->bigPrimaryKey()->comment("id自增"),
            'description'   =>  $this->text()->notNull()->comment("描述"),
            'name'           =>  $this->string(200)->notNull()->comment("名称"),
            'create_at'      =>  $this->bigInteger()->unsigned()->notNull()->comment("创建时间"),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%server}}');
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
