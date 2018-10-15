<?php

use yii\db\Migration;

/**
 * Class m171213_084649_add_leave_record_is_read
 */
class m171213_084649_add_leave_record_is_read extends Migration
{
    public function up()
    {
        $this->addColumn('{{%leave_record}}','is_read',"tinyint(1) default 0 COMMENT '是否已读'");
    }

    public function down()
    {
        $this->dropColumn('{{%leave_record}}','is_read');
    }
}
