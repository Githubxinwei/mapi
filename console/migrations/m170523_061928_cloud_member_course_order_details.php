<?php

use yii\db\Migration;

class m170523_061928_cloud_member_course_order_details extends Migration
{
    /**
     * @数据库 - 添加 会员买课订单详情表
     * @author 黄鹏举 <huangpengju@itsports.club>
     * @create 2017/5/23
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员购买私课订单详情表'  CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%member_course_order_details}}', [
            'id'                       => $this->bigPrimaryKey()->comment('主键自增'),
            'course_order_id'          => $this->bigInteger(20)->notNull()->comment('课程订单表id'),
            'course_id'                => $this->bigInteger(20)->notNull()->comment('课种id'),
            'course_num'               => $this->integer(11)->comment('课量'),
            'course_length'            => $this->integer(11)->comment('有效天数'),
            'original_price'           => $this->decimal(10,2)->comment('单节原价'),
            'sale_price'               => $this->decimal(10,2)->comment('单节售价'),
            'pos_price'                => $this->decimal(10,2)->comment('单节pos售价'),
            'type'                     => $this->smallInteger(6)->comment('订单类型：1私课2团课'),
            'category'                 => $this->smallInteger(6)->comment('课程类型：1多课程2单课程'),
        ], $tableOptions);
        $this->createIndex('index_course_order_id','{{%member_course_order_details}}','course_order_id');
        $this->createIndex('index_course_id','{{%member_course_order_details}}','course_id');
    }
    /**
     * @数据库 - 回滚表
     * @author 黄鹏举 <huangpengju@itsports.club>
     * @create 2017/5/23
     */
    public function down()
    {
        $this->dropTable('{{%member_course_order_details}}');
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
