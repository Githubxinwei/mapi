<?php

use yii\db\Migration;

/**
 * Class m180316_105456_add_information_records
 */
class m180316_105456_add_information_records extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%information_records}}', 'old_time', "int(11)  COMMENT '赠卡修改前时间'");
        $this->addColumn('{{%information_records}}', 'new_time', "int(11)  COMMENT '赠卡修改后时间'");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%information_records}}', 'old_time');
        $this->dropColumn('{{%information_records}}', 'new_time');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180316_105456_add_information_records cannot be reverted.\n";

        return false;
    }
    */
}
