<?php

use yii\db\Migration;

/**
 * Class m171226_070702_create_help
 */
class m171226_070702_create_help extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = "COMMENT = '帮助中心表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%help}}', [
            'id'        => $this->bigPrimaryKey()->comment('自增ID'),
            'type_id'   => $this->integer()->comment('类型ID'),
            'question'  => $this->string(200)->notNull()->comment('问题'),
            'content'   => $this->text()->notNull()->comment('内容'),
            'create_at' => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at' => $this->bigInteger()->unsigned()->comment('修改时间'),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable("{{%help}}");
    }

}
