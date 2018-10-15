<?php

use yii\db\Migration;

class m170918_123310_add_mobilise extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_mobilise表
     * @author huanghua <huanghua@itsport.club>
     * @create 2017/9/18
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%mobilise}}', 'be_venue_id', 'int COMMENT "被调拨的场馆ID"');
    }

    public function down()
    {
        $this->dropColumn('{{%mobilise}}', 'be_venue_id');
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
