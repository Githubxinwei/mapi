<?php

use yii\db\Migration;

class m170328_100424_create_coach_replace_class extends Migration
{
    /**
     * @数据库 - 创建者 - cloud_server_combo 教练替课表
     * @author Hou Kaixin<houkaixin@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '教练替课表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%coach_replace_class}}', [
            'id'                 =>   $this->bigPrimaryKey()->comment("id自增"),
            'coach_id'           =>   $this->bigInteger()->unsigned()->notNull()->comment("教练id"),
            'replace_id'         =>   $this->bigInteger()->unsigned()->notNull()->comment("替课人id"),
            'class_arrange_id'   =>  $this->bigInteger()->unsigned()->notNull()->comment("课程安排id"),
            'create_at'           =>  $this->bigInteger()->unsigned()->notNull()->comment("创建时间"),
        ], $tableOptions);
        $this->createIndex(
            'index_coach_id',
            '{{%coach_replace_class}}',
            'coach_id'
        );
        $this->createIndex(
            'index_replace_id',
            '{{%coach_replace_class}}',
            'replace_id'
        );
        $this->createIndex(
            'index_class_arrange_id',
            '{{%coach_replace_class}}',
            'class_arrange_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_coach_id','{{%coach_replace_class}}');
        $this->dropIndex('index_replace_id','{{%coach_replace_class}}');
        $this->dropIndex('index_class_arrange_id','{{%coach_replace_class}}');
        $this->dropTable('{{%coach_replace_class}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
