<?php

use yii\db\Migration;

/**
 * Class m171212_041040_add_about_class_is_read
 */
class m171212_041040_add_about_class_is_read extends Migration
{
    public function up()
    {
        $this->addColumn('{{%about_class}}','is_read',"tinyint(1) default 0 COMMENT '是否已读'");
    }

    public function down()
    {
        $this->dropColumn('{{%about_class}}','is_read');
    }

}