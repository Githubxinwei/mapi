<?php

use yii\db\Migration;

class m170519_012848_create_bind_member_card extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_bind_member_card 会员卡绑定套餐表
         * @author 朱梦珂 <zhumengke@itsports.club>
         * @create 2017/5/19
         * @inheritdoc
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员卡绑定套餐表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%bind_member_card}}', [
            'id'                => $this->bigPrimaryKey()->comment('自增ID'),
            'member_card_id'   => $this->bigInteger(20)->unsigned()->notNull()->comment('会员卡id'),
            'polymorphic_id'   => $this->bigInteger(20)->unsigned()->comment('多态id(课程id、服务id、扣费项目id、赠品id)'),
            'polymorphic_type' => $this->string(200)->comment('多态类型（比如：表名字）'),
            'number'            => $this->integer(11)->comment('数量'),
            'status'            => $this->smallInteger(4)->comment('状态1:课程 2：服务 3：扣费 4:赠品'),
        ], $tableOptions);
        $this->createIndex(
            'index_member_card_id',
            '{{%bind_member_card}}',
            'member_card_id'
        );
        $this->createIndex(
            'index_polymorphic_id',
            '{{%bind_member_card}}',
            'polymorphic_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_member_card_id', '{{%bind_member_card}}');
        $this->dropIndex('index_polymorphic_id', '{{%bind_member_card}}');
        $this->dropTable('{{%bind_member_card}}');
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
