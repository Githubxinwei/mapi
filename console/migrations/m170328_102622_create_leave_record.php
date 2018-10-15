<?php

use yii\db\Migration;

class m170328_102622_create_leave_record extends Migration
{
    /**
     * @数据库 - 创建者 - cloud_server_combo  请假记录表
     * @author Hou Kaixin<houkaixin@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '请假记录表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%leave_record}}', [
            'id'                 =>  $this->bigPrimaryKey()->comment("id自增"),
            'leave_employee_id' =>  $this->bigInteger()->unsigned()->notNull()->comment("请假人id"),
            'note'               =>  $this->string(200)->comment("原因"),
            'is_approval'        =>  $this->boolean()->defaultValue(false)->comment("是否批准(true:批准，false：不批准)"),
            'create_at'          =>  $this->bigInteger()->unsigned()->notNull()->comment("创建时间"),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%leave_record}}');
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
