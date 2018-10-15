<?php

use yii\db\Migration;

class m170328_102003_create_cloud_classroom extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_classroom 教室表
         * @author Zhu Mengke <zhumengke@itsports.club>
         * @create 2017/3/28
         * @inheritdoc
         */

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '教室表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%classroom}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'name' => $this->string(200)->notNull()->comment('名称 '),
            'venue_id' => $this->bigInteger()->unsigned()->notNull()->comment('场馆id'),

            'total_seat' => $this->integer()->unsigned()->comment('总座位数'),
        ], $tableOptions);
        $this->createIndex(
            'index_venue_id',
            '{{%classroom}}',
            'venue_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_venue_id', '{{%classroom}}');
        $this->dropTable('{{%classroom}}');
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
