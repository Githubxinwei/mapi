<?php

use yii\db\Migration;

class m171020_025558_add_bink_pack_polymorphic_ids extends Migration
{
    public function up()
    {
        $this->addColumn('{{%bind_pack}}','polymorphic_ids',"json COMMENT '多选JSON存储id'");
    }

    public function down()
    {
        $this->dropColumn('{{%bind_pack}}','polymorphic_ids');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171020_025558_add_bink_pack_polymorphic_ids cannot be reverted.\n";

        return false;
    }
    */
}
