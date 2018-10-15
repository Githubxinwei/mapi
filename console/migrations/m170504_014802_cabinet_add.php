<?php

use yii\db\Migration;

class m170504_014802_cabinet_add extends Migration
{
    /**
     * @数据库 - 修改表 - cloud_cabinet创建场馆id
     * @author Houkaixin <Houkaixin @itsport.club>
     * @create 2017/5/4
     */
    public function up()
    {
        $this->addColumn('{{%cabinet}}', 'venue_id', "bigint(20) UNSIGNED COMMENT '场馆id'");
    }

    public function down()
    {
        $this->dropColumn('{{%cabinet}}', 'venue_id');
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
