<?php

use yii\db\Migration;

/**
 * Class m180206_071330_create_feedback_type
 */
class m180206_071330_create_feedback_type extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = "COMMENT = '投诉反馈类型表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%feedback_type}}', [
            'id'    => $this->primaryKey()->comment('自增ID'),
            'name'  => $this->char(200)->notNull()->defaultValue('')->comment('类型名称'),
            'pid'   => $this->integer()->notNull()->defaultValue(0)->comment('父ID'),
            'child' => $this->smallInteger()->notNull()->defaultValue(0)->comment('是否有子类型0无1有'),
            'do'    => $this->smallInteger()->notNull()->defaultValue(0)->comment('此分类下是否可以进入提交内容页面0不可1可'),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable("{{%feedback_type}}");
    }
}
