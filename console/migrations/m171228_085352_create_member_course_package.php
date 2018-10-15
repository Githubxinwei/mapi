<?php

use yii\db\Migration;

class m171228_085352_create_member_course_package extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员课程套餐详情表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%member_course_package}}', [
            'id'              => $this->bigPrimaryKey()->comment('自增ID'),
            'course_order_id' => $this->bigInteger()->unsigned()->notNull()->comment('课程订单表id'),
            'course_id'       => $this->bigInteger()->unsigned()->notNull()->comment('课程ID'),
            'course_num'      => $this->integer()->unsigned()->comment('课量'),
            'course_length'   => $this->integer()->unsigned()->comment('时长'),
            'original_price'  => $this->decimal()->comment('单节原价'),
            'sale_price'      => $this->decimal()->comment('单节售价'),
            'pos_price'       => $this->decimal()->comment('单节pos价'),
            'type'            => $this->smallInteger()->unsigned()->notNull()->defaultValue(1)->comment('状态：1私课，2团课'),
            'create_at'       => $this->bigInteger()->unsigned()->comment('创建时间'),
            'category'        => $this->smallInteger()->unsigned()->notNull()->comment('类型:1多课程，2单课程'),
            'app_original'    => $this->decimal()->comment('移动端单节原价'),
        ], $tableOptions);
        $this->createIndex(
            'index_course_order_id',
            '{{%member_course_package}}',
            'course_order_id'
        );
        $this->createIndex(
            'index_course_id',
            '{{%member_course_package}}',
            'course_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_course_order_id', '{{%member_course_package}}');
        $this->dropIndex('index_course_id', '{{%member_course_package}}');
        $this->dropTable('{{%member_course_package}}');
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
