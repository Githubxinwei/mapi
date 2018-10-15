<?php

use yii\db\Migration;

/**
 * Class m180314_054803_add_matching_record
 */
class m180314_054803_add_matching_record extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%matching_record}}','type',"smallint(6) default 1 COMMENT '1.卡种匹配2.会员卡匹配'");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%matching_record}}','type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180314_054803_add_matching_record cannot be reverted.\n";

        return false;
    }
    */
}
