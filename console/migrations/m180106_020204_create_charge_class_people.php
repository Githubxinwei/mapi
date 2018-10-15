<?php

use yii\db\Migration;

class m180106_020204_create_charge_class_people extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '私教人数区间表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%charge_class_people}}', [
            'id'              => $this->bigPrimaryKey()->comment('自增ID'),
            'charge_class_id' => $this->bigInteger()->unsigned()->comment('私课id'),
            'class_price_id' => $this->bigInteger()->unsigned()->comment('私课节数区间id'),
            'people_least'   => $this->integer()->comment('最少人数'),
            'people_most'    => $this->integer()->comment('最多人数'),
            'least_number'   => $this->integer()->comment('最低开课人数'),
            'unit_price'     => $this->decimal(10,2)->comment('优惠单价'),
            'pos_price'      => $this->decimal(10,2)->comment('pos价'),
        ], $tableOptions);

        $this->createIndex('index_class_price_id', '{{%charge_class_people}}', 'class_price_id');
    }

    public function down()
    {
        $this->dropIndex('index_class_price_id', '{{%charge_class_people}}');
        $this->dropTable('{{%charge_class_people}}');
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
