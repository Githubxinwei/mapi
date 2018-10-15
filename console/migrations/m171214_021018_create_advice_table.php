<?php

use yii\db\Migration;

/**
 * Handles the creation of table `advice`.
 */
class m171214_021018_create_advice_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = "COMMENT = '移动端反馈表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%advice}}',[
            "id"                 => $this->bigPrimaryKey()->comment('自增Id'),
            "admin_id"           => $this->bigInteger()->comment('登陆用户id'),
            "content"            => $this->string(200)->comment('反馈内容'),
            "create_at"          => $this->bigInteger()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex('index_admin_id','{{%advice}}','admin_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable("{{%advice}}");
    }
}
