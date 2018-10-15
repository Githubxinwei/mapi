<?php

use yii\db\Migration;

class m170829_074837_add_goods extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_goods表
     * @author huanghua <huanghua@itsport.club>
     * @create 2017/8/29
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%goods}}', 'store_id', "bigint  COMMENT '仓库id'");
        $this->createIndex(
            'index_store_id',
            '{{%goods}}',
            'store_id'
        );
    }

    public function down()
    {
        $this->dropColumn('{{%goods}}', 'store_id');
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
