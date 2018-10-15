<?php

use yii\db\Migration;

class m170824_110916_add_course extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_course表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/24
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%course}}', 'coach_id', 'json COMMENT "教练id"');
    }

    public function down()
    {
        $this->dropColumn('{{%course}}', 'coach_id');
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
