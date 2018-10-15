<?php

use yii\db\Migration;

/**
 * Class m180118_023245_add_member_base
 */
class m180118_023245_add_member_base extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_base}}','member_account_id',"bigint(20)  COMMENT '账号ID'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_base}}','member_account_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180118_023245_add_member_base cannot be reverted.\n";

        return false;
    }
    */
}
