<?php

use yii\db\Migration;

class m170616_120912_add_moduleTwo extends Migration
{
    /**
     * @数据库 - 模块表 - 添加字段（图标,路由）
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/6/16
     */
    public function up()
    {
        $this->addColumn('{{%module}}', 'icon',"varchar(200) COMMENT '图标'");
        $this->addColumn('{{%module}}', 'url',"varchar(200) COMMENT '路由'");
    }

    public function down()
    {
        $this->dropColumn('{{%module}}', 'icon');
        $this->dropColumn('{{%module}}', 'url');
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
