<?php

use yii\db\Migration;

class m170419_024140_create_member_cabinet extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_member_cabinet 会员柜表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/4/19
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员柜表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%member_cabinet}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'member_id' => $this->bigInteger()->unsigned()->notNull()->comment('会员ID'),
            'price' => $this->decimal()->comment('金额'),
            'start_rent' => $this->bigInteger(20)->unsigned()->comment('起租日'),
            'end_rent' => $this->bigInteger(20)->unsigned()->comment('到期日'),
            'back_rent' => $this->bigInteger(20)->unsigned()->comment('退租日期'),
            'status' => $this->smallInteger()->unsigned()->notNull()->defaultValue(1)->comment('状态：1未到期，2快到期，3到期，4逾期'),
            'creater_id' => $this->bigInteger(20)->unsigned()->notNull()->comment('经办人ID'),
            'create_at' => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at' => $this->bigInteger()->unsigned()->comment('更新时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_member_id',
            '{{%member_cabinet}}',
            'member_id'
        );
        $this->createIndex(
            'index_creater_id',
            '{{%member_cabinet}}',
            'creater_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_member_id', '{{%member_cabinet}}');
        $this->dropIndex('index_creater_id', '{{%member_cabinet}}');
        $this->dropTable('{{%member_cabinet}}');
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
