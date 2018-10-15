<?php

use yii\db\Migration;

class m170713_020639_create_member_beahavior_record extends Migration
{
    /**
     * @数据库 - 创建表 - 会员行为轨迹表
     * @author houkaixin<houkaixin@itsport.club>
     * @create 2017/7/13
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'COMMENT="会员行为轨迹" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%member_behavior_trail}}', [
            'id'              => $this->bigPrimaryKey()->comment('自增ID'),
            'employee_id'    =>$this->bigInteger()->unsigned()->comment('员工id'),
            'behavior'       => $this->smallInteger()->comment('1:浏览 2:编辑 3:修改 4:查看 5:删除'),
            'module_id'      => $this->bigInteger()->unsigned()->comment('操作模块id'),
            'create_at'      => $this->bigInteger()->unsigned()->comment('创建时间'),
            'behavior_ip'    => $this->string(255)->comment('行为ip'),
            'behavior_intro' => $this->text()->comment('行为描述'),
        ], $tableOptions);
        $this->createIndex(
            'index_employee_id',
            '{{%member_behavior_trail}}',
            'employee_id'
        );
        $this->createIndex(
            'index_module_id',
            '{{%member_behavior_trail}}',
            'module_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%member_behavior_trail}}');
    }
}
