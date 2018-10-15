<?php

use yii\db\Migration;

class m170528_020558_add_column extends Migration
{
    /**
     * @数据库 - 添加字段 - 会员买课订单表和订单详情表
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/5/28
     */
    public function up()
    {
        $this->addColumn('{{%member_course_order}}', 'product_name',"varchar(200) COMMENT '产品名称'");
        $this->addColumn('{{%member_course_order}}', 'type',"smallint(6) COMMENT '类型：(1：PT，2:HS)'");
        $this->addColumn('{{%member_course_order_details}}', 'product_name',"varchar(200) COMMENT '产品名称'");
        $this->addColumn('{{%member_course_order_details}}', 'course_name',"varchar(200) COMMENT '课种名称'");
        $this->addColumn('{{%member_course_order_details}}', 'class_length',"int(11) COMMENT '单节时长，一般默认为分钟'");
        $this->addColumn('{{%member_course_order_details}}', 'pic',"varchar(255) COMMENT '产品图片'");
        $this->addColumn('{{%member_course_order_details}}', 'desc',"varchar(255) COMMENT '产品描述'");

    }
    /**
     * @数据库 - 回滚字段 - 会员买课订单表和订单详情表
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/5/28
     */
    public function down()
    {
        $this->dropColumn('{{%member_course_order}}', 'product_name');
        $this->dropColumn('{{%member_course_order}}', 'type');
        $this->dropColumn('{{%member_course_order_details}}', 'product_name');
        $this->dropColumn('{{%member_course_order_details}}', 'course_name');
        $this->dropColumn('{{%member_course_order_details}}', 'class_length');
        $this->dropColumn('{{%member_course_order_details}}', 'pic');
        $this->dropColumn('{{%member_course_order_details}}', 'desc');
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
