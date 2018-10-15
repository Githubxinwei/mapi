<?php

use yii\db\Migration;

class m170925_034500_add_card_category_card_type extends Migration
{
    public function up()
    {
        $this->addColumn('{{%card_category}}','card_type',"smallInt COMMENT '卡种类型，1:瑜伽,2:健身,3舞蹈,4:综合等'");
    }

    public function down()
    {
        $this->dropColumn('{{%card_category}}','card_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170925_034500_add_card_category_card_type cannot be reverted.\n";

        return false;
    }
    */
}
