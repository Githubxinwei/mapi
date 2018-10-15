<?php

use yii\db\Migration;

class m171205_031129_add_index_about_class extends Migration
{
    public function up()
    {
        $this->createIndex('index_class_id', '{{%about_class}}', 'class_id');
    }

    public function down()
    {
        $this->dropIndex('index_class_id', '{{%about_class}}');
    }

}
