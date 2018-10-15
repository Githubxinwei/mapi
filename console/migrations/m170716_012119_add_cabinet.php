<?php

use yii\db\Migration;

class m170716_012119_add_cabinet extends Migration
{
    /**
     * @数据库 - 柜子管理 - 新增押金字段
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/7/13
     */
    public function up()
    {
        $this->addColumn('{{%cabinet}}','deposit',"varchar(255) COMMENT '押金'");
    }

    public function down()
    {
        $this->dropColumn('{{%cabinet}}','deposit');
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
