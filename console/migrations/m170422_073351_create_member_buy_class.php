<?php

use yii\db\Migration;

class m170422_073351_create_member_buy_class extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_member_buy_class 会员卡表
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/4/22
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员买课表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%member_buy_class}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'member_card_id' => $this->bigInteger()->unsigned()->notNull()->comment('会员卡ID'),
            'class_id' => $this->bigInteger()->unsigned()->notNull()->comment('课程ID'),
            'type' =>$this->smallInteger()->notNull()->comment('类型:1私课 2团课'),
            'num' => $this->bigInteger()->unsigned()->comment('总节数'),
            'valid_day'=> $this->bigInteger()->unsigned()->comment('有效天数'),
            'buy_class_time'=> $this->bigInteger()->comment('买课时间'),
            'amount_money' => $this->decimal()->comment('金额'),
            'create_at'=>$this->bigInteger()->comment('创建时间')
        ], $tableOptions);
        $this->createIndex(
            'index_member_card_id',
            '{{%member_buy_class}}',
            'member_card_id'
        );
        $this->createIndex(
            'index_class_id',
            '{{%member_buy_class}}',
            'class_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_member_card_id', '{{%member_buy_class}}');
        $this->dropIndex('index_class_id', '{{%member_buy_class}}');
        $this->dropTable('{{%member_buy_class}}');
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
