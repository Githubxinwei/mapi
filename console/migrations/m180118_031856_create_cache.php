<?php

use yii\db\Migration;

/**
 * Class m180118_031856_create_cache
 */
class m180118_031856_create_cache extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = "COMMENT = '缓存表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%cache}}', [
            'id'          => $this->char(128)->comment('ID'),
            'expire' => $this->bigInteger()->comment('过期时间'),
        ], $tableOptions);
        $this->addColumn('{{%cache}}', 'data', 'LONGBLOB COMMENT "缓存数据"');
        $this->addPrimaryKey('id', '{{%cache}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable("{{%cache}}");
    }
}
