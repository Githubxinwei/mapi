<?php

use yii\db\Migration;

/**
 * Class m180301_014023_add_sign_to_order
 */
class m180301_014023_add_sign_to_order extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}','sign',"VARCHAR(255) DEFAULT '' COMMENT '签名'");
    }

    public function down()
    {
        $this->dropColumn('{{%order}}','sign');
    }
}
