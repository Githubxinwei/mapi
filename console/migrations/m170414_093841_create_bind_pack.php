<?php

use yii\db\Migration;

class m170414_093841_create_bind_pack extends Migration
{
    /**
     * @数据库 - 创建表 -  卡种绑定套餐、服务、项目等...表
     * @author Huang Pengju <huangpengju@itsport.club>
     * @create 2017/4/14
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="卡种绑定套餐、服务、收费项目...表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%bind_pack}}', [
            'id'                => $this->bigPrimaryKey()->comment('自增ID'),
            'card_category_id'  => $this->bigInteger(20)->unsigned()->notNull()->comment('卡种id'),
            'polymorphic_id'    => $this->bigInteger(20)->unsigned()->notNull()->comment('多态id(课程id、服务id、扣费项目id、赠品id)'),
            'polymorphic_type'  => $this->string(200)->comment('多态类型（比如：表名字）'),
            'number'            => $this->integer(11)->comment('数量'),
            'status'            => $this->smallInteger(4)->comment('状态1:课程 2：服务 3：扣费 4:赠品'),
        ], $tableOptions);
    }
    /**
     * @数据库 - 回滚表 -  卡种绑定套餐、服务、项目等...表
     * @author Huang Pengju <huangpengju@itsport.club>
     * @create 2017/4/14
     */
    public function down()
    {
        $this->dropTable('{{%bind_pack}}');
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
