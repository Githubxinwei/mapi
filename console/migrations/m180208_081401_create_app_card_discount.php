<?php

use yii\db\Migration;

class m180208_081401_create_app_card_discount extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '移动端卡种折扣表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%app_card_discount}}', [
            'id'        => $this->bigPrimaryKey()->comment('自增ID'),
            'venue_id'  => $this->bigInteger()->unsigned()->comment('场馆id'),
            'discount'  => $this->double()->comment('折扣'),
            'start'     => $this->bigInteger()->comment('售卖开始时间'),
            'end'       => $this->bigInteger()->comment('售卖结束时间'),
            'status'    => $this->smallInteger()->comment('状态:1审核中2已通过3未通过4冻结'),
            'create_at' => $this->bigInteger()->comment('创建时间'),
            'update_at' => $this->bigInteger()->comment('修改时间'),
        ], $tableOptions);
        $this->addColumn('{{%app_card_discount}}','no_discount_card',"json COMMENT '不打折卡种' ");
        $this->createIndex('index_venue_id', '{{%app_card_discount}}', 'venue_id');
    }

    public function down()
    {
        $this->dropIndex('index_venue_id', '{{%app_card_discount}}');
        $this->dropTable('{{%app_card_discount}}');
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
