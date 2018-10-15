<?php

use yii\db\Migration;

/**
 * Class m171226_070635_create_plan
 */
class m171226_070635_create_plan extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = "COMMENT = '方案表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%plan}}',[
            'id'          => $this->primaryKey()->comment('自增Id'),
            'name'        => $this->string(200)->notNull()->comment('名称'),
            'content'     => $this->text()->notNull()->comment('内容'),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable("{{%plan}}");
    }

}
