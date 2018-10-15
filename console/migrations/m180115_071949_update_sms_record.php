<?php

use yii\db\Migration;

/**
 * Class m180115_071949_update_sms_record
 */
class m180115_071949_update_sms_record extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%sms_record}}','content',"text COMMENT '内容'");
    }

    public function down()
    {
        $this->alterColumn('{{%sms_record}}','content',"varchar(200) COMMENT '内容'");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180115_071949_update_sms_record cannot be reverted.\n";

        return false;
    }
    */
}
