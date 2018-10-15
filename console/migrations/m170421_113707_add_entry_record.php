<?php

use yii\db\Migration;

class m170421_113707_add_entry_record extends Migration
{

    /**
     * @数据库 - 添加表字段 - entry_record
     * @author 黄华 <huanghua@itsport.club>
     * @create 2017/4/21
     * @inheritdoc
     */
    public function up()
    {

        $this->addColumn('{{%entry_record}}','leaving_time','bigint(20) COMMENT "离场时间" ');

    }

    public function down()
    {
        $this->dropColumn('{{%entry_record}}',"leaving_time");

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
