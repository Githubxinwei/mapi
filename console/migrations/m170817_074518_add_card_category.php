<?php

use yii\db\Migration;

class m170817_074518_add_card_category extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_card_category表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/17
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%card_category}}', 'single', 'float COMMENT "单数"');
    }

    public function down()
    {
        $this->dropColumn('{{%card_category}}', 'single');
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
