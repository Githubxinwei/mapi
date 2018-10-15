<?php

use yii\db\Migration;

/**
 * Class m180302_090549_add_app_card_discount
 */
class m180302_090549_add_app_card_discount extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%app_card_discount}}','status',"smallint COMMENT '状态:1审核中2已通过3未通过4已撤销'");
        $this->addColumn('{{%app_card_discount}}', 'frozen', "smallint default 2 COMMENT '冻结:1是2否'");
    }

    public function down()
    {
        $this->alterColumn('{{%app_card_discount}}','status',"smallint COMMENT '状态:1审核中2已通过3未通过4已撤销'");
        $this->dropColumn('{{%app_card_discount}}', 'frozen');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180302_090549_add_app_card_discount cannot be reverted.\n";

        return false;
    }
    */
}
