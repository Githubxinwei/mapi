<?php

use yii\db\Migration;

class m170328_102832_create_cloud_seat extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_seat 座位表
         * @author Zhu Mengke <zhumengke@itsports.club>
         * @create 2017/3/28
         * @inheritdoc
         */

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '座位表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%seat}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'classroom_id' => $this->bigInteger()->unsigned()->notNull()->comment('教室id'),

            'seat_type' => $this->integer()->unsigned()->defaultValue(1)->comment('类型：1普通，2VIP，3贵族'),
            'seat_number' => $this->string(200)->unsigned()->notNull()->comment('座位号'),
        ], $tableOptions);
        $this->createIndex(
            'index_classroom_id',
            '{{%seat}}',
            'classroom_id'
        );
    }
    public function down()
    {
        $this->dropIndex('index_classroom_id', '{{%seat}}');
        $this->dropTable('{{%seat}}');
    }
}
