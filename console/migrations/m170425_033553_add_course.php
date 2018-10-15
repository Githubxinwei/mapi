<?php

use yii\db\Migration;

class m170425_033553_add_course extends Migration
{
    /**
     * @数据库 - 课种表 -  添加字段
     * @author 朱梦珂 <zhumengke@itsport.club>
     * @create 2017/4/25
     */
    public function up()
    {
        $this->addColumn('{{%course}}', 'update_at','bigint(20) COMMENT "修改时间"');
    }

    public function down()
    {
        $this->dropColumn('{{%course}}', 'update_at');
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
