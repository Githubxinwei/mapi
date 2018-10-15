<?php

use yii\db\Migration;

class m170413_080013_add_group_class extends Migration
{
    /**
     * @数据库 - 修改表 - cloud_group_class 创建课程难度、描述、图片、约课、取消、开课人数字段
     * @author Huang Pengju <Huang Pengju@itsport.club>
     * @create 2017/4/13
     */
    public function up()
    {
        $this->addColumn('{{%group_class}}', 'difficulty', "int(11) UNSIGNED  NULL DEFAULT 1 COMMENT '课程难度（1低，2中，3高）'");
        $this->addColumn('{{%group_class}}', 'desc', "varchar(200)   NULL COMMENT '团课课程描述'");
        $this->addColumn('{{%group_class}}', 'pic', "varchar(255) NULL  COMMENT '团课课程图片'");
        $this->addColumn('{{%group_class}}', 'class_limit_time', "int(11) NULL  COMMENT '开课前多少分钟不能约课'");
        $this->addColumn('{{%group_class}}', 'cancel_limit_time', "int(11) NULL  COMMENT '开课前多少分钟不能取消约课'");
        $this->addColumn('{{%group_class}}', 'least_people', "int(11) NULL  COMMENT '最少开课人数'");
    }
    /**
     * @数据库 - 修改表 - cloud_group_class 回滚课程难度、描述、图片、约课、取消、开课人数字段
     * @author Huang Pengju <Huang Pengju@itsport.club>
     * @create 2017/4/13
     */
    public function down()
    {
        $this->dropColumn('{{%group_class}}', 'difficulty');
        $this->dropColumn('{{%group_class}}', 'desc');
        $this->dropColumn('{{%group_class}}', 'pic');
        $this->dropColumn('{{%group_class}}', 'class_limit_time');
        $this->dropColumn('{{%group_class}}', 'cancel_limit_time');
        $this->dropColumn('{{%group_class}}', 'least_people');
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
