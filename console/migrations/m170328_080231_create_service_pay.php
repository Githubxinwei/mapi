<?php

use yii\db\Migration;

class m170328_080231_create_service_pay extends Migration
{
    /**
     * @数据库 - 创建者 - cloud_service_pay 收费项目表
     * @author Hou Kaixin<houkaixin@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '收费项目表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%service_pay}}', [
            'id'             =>  $this->bigPrimaryKey()->comment("自增ID"),
            'name'           =>  $this->string(200)->notNull()->comment("名称"),
            'description'   =>  $this->string(200)->comment("描述"),
            'create_at'     =>  $this->bigInteger()->notNull()->comment("添加时间"),
            'category'      =>  $this->string(200)->notNull()->comment("类型(课程类、消耗类、服务类）"),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%service_pay}}');
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
