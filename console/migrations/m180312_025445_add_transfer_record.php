<?php

use yii\db\Migration;

/**
 * Class m180312_025445_add_transfer_record
 */
class m180312_025445_add_transfer_record extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%transfer_record}}', 'operator_id', "int(11)  COMMENT '操作人id'");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%transfer_record}}', 'operator_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180312_025445_add_transfer_record cannot be reverted.\n";

        return false;
    }
    */
}
