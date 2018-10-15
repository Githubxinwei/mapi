<?php

use yii\db\Migration;

class m180202_090733_create_matching_record extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '属性匹配记录表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%matching_record}}', [
            'id'                      =>   $this->bigPrimaryKey()->comment('自增Id'),
            'member_card_id'          =>   $this->bigInteger()->unsigned()->comment('老会员卡Id'),
            'member_category_id'      =>   $this->bigInteger()->unsigned()->comment('新卡种Id'),
            'create_at'               =>    $this->bigInteger()->unsigned()->comment('创建时间'),
            'note'                    =>    $this->text()->comment('备注'),
            'create_id'               =>    $this->bigInteger()->unsigned()->comment('操作人Id'),
            'status'                  =>    $this->smallInteger()->unsigned()->comment('状态'),
        ], $tableOptions);
        $this->addColumn('{{%matching_record}}','attribute_matching',"json NULL COMMENT '通店属性:1.卡的属性2.卡的类型3.是否带人4.通用场馆限制5.进馆时间限制6.团课套餐7.请假8.赠品9.转让10.合同'");
        $this->createIndex('index_member_card_id','{{%matching_record}}','member_card_id');
        $this->createIndex('index_member_category_id','{{%matching_record}}','member_category_id');
    }

    public function down()
    {
        $this->dropIndex('index_member_card_id', '{{%matching_record}}');
        $this->dropIndex('index_member_category_id', '{{%matching_record}}');
        $this->dropTable('{{%matching_record}}');
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
