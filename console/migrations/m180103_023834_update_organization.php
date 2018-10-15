<?php

use yii\db\Migration;

class m180103_023834_update_organization extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%organization}}','venue_type',"smallInt COMMENT '场馆类型:1 综合馆 2:瑜伽馆 3:舞蹈馆 4:搏击馆 5:游泳馆 6:健身馆'");
    }

    public function down()
    {
        $this->alterColumn('{{%organization}}','venue_type',"smallInt COMMENT '场馆类型:1 综合馆 2:瑜伽馆 3:舞蹈馆 4:搏击馆 5:游泳馆 6:健身馆'");
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
