<?php

use yii\db\Migration;

/**
 * Class m180110_013649_create_session
 */
class m180110_013649_create_session extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = "COMMENT = 'SESSION表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%session}}', [
            'id'          => $this->char(40)->comment('ID'),
            'expire' => $this->bigInteger()->comment('过期时间'),
        ], $tableOptions);
        $this->addColumn('{{%session}}', 'data', 'LONGBLOB COMMENT "数据"');
        $this->addPrimaryKey('id', '{{%session}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable("{{%session}}");
    }
}
