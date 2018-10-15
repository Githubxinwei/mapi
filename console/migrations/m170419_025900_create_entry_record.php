<?php

use yii\db\Migration;

class m170419_025900_create_entry_record extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="进场记录表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%entry_record}}', [
            'id'                => $this->bigPrimaryKey()->comment('自增ID'),
            'member_card_id'   => $this->bigInteger(20)->unsigned()->notNull()->comment('会员卡id'),
            'venue_id'         => $this->bigInteger(20)->unsigned()->comment('场馆id'),
            'entry_time'       => $this->bigInteger(11)->comment('进场时间'),
            'create_at'        => $this->bigInteger(11)->comment('创建时间'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%entry_record}}');
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
