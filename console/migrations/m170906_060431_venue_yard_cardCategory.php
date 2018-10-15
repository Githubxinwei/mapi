<?php

use yii\db\Migration;

class m170906_060431_venue_yard_cardCategory extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'COMMENT="场地适用卡种" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%venue_yard_cardCategory}}', [
            'id'                   => $this->bigPrimaryKey()->comment('自增ID'),
            'yard_id'             => $this->bigInteger()->unsigned()->comment('场地id'),
            'card_category_id'   => $this->bigInteger()->unsigned()->comment('场地适用卡种id'),
            "create_at"           => $this->bigInteger()->unsigned()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_yard_id',
            '{{%venue_yard_cardCategory}}',
            'yard_id'
        );
        $this->createIndex(
            'index_card_category_id',
            '{{%venue_yard_cardCategory}}',
            'card_category_id'
        );
    }

    public function down()
    {
         $this->dropTable("{{%venue_yard_cardCategory}}");
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
