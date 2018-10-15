<?php

use yii\db\Migration;

class m180102_081620_add_organization extends Migration
{
    public function up()
    {
        $this->addColumn('{{%organization}}','venue_type',"smallInt COMMENT '场馆类型:1 综合 2:瑜伽 3:舞蹈 4:搏击 5:舞蹈 6:健身'");
    }
    public function down()
    {
        $this->dropColumn('{{%organization}}','venue_type');
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
