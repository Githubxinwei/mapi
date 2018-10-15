<?php

use yii\db\Migration;

class m170701_034728_update_card_category extends Migration
{
    /**
     * @数据库 - 修改字段 - cloud_card_category
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/1
     */
    public function up()
    {
        $this->dropIndex('card_name','{{%card_category}}');
    }

    public function down()
    {
        $this->createIndex('card_name','{{%card_category}}','card_name');
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
