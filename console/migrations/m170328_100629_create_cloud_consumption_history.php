<?php

use yii\db\Migration;

class m170328_100629_create_cloud_consumption_history extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_consumption_history 消费记录表
         * @author Zhu Mengke <zhumengke@itsports.club>
         * @create 2017/3/28
         * @inheritdoc
         */

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '消费记录表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%consumption_history}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'member_id' => $this->bigInteger()->unsigned()->notNull()->comment('会员id '),

            'consumption_type' => $this->string(200)->notNull()->comment('消费类型'),
            'consumption_type_id' => $this->bigInteger()->unsigned()->notNull()->comment('消费项目id'),

        ], $tableOptions);
        $this->createIndex(
            'index_member_id',
            '{{%consumption_history}}',
            'member_id'
        );
        $this->createIndex(
            'index_consumption_type_id',
            '{{%consumption_history}}',
            'consumption_type_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_member_id', '{{%consumption_history}}');
        $this->dropIndex('index_consumption_type_id', '{{%consumption_history}}');
        $this->dropTable('{{%consumption_history}}');
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
