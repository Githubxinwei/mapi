<?php

use yii\db\Migration;

class m170705_074241_add_role extends Migration
{
    /**
     * @数据库 - 角色表 - 状态
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/5
     */
    public function up()
    {
        $this->addColumn('{{%role}}','status',"smallint COMMENT '状态：1正常；2停用'");
    }

    public function down()
    {
        $this->dropColumn('{{%role}}','status');
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
