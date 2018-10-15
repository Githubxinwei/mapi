<?php

use yii\db\Migration;

class m171208_053149_add_admin_accesstoken extends Migration
{
    public function up()
    {
        $this->addColumn('{{%admin}}','accesstoken',"varchar(255) COMMENT 'accesstoken'");
    }

    public function down()
    {
        $this->dropColumn('{{%admin}}','accesstoken');
    }

}
