<?php

use yii\db\Migration;

class m170512_083544_add_about_class_cloumn extends Migration
{
    /**
     * @数据库 - 约课记录 - 添加字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/12
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%about_class}}', 'is_print_receipt',"smallint(6) COMMENT '是否打印过小票（1有2没有）'");
        $this->addColumn('{{%about_class}}', 'about_type',"smallint(6) COMMENT '预约类型（1电话预约 2自助预约）'");
    }
    /**
     * @数据库 - 约课记录表 - 回滚字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/12
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%about_class}}', 'is_print_receipt');
        $this->dropColumn('{{%about_class}}', 'about_type');
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
