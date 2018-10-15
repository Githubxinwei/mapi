<?php

use yii\db\Migration;

class m171124_021918_update_course_package_detail extends Migration
{
    /**
     * @数据库 - 修改字段 - cloud_course_package_detail 修改单节原价金额数据类型
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/11/24
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('{{%course_package_detail}}','sale_price',"decimal(10,2)	  COMMENT '单节售价'");
        $this->alterColumn('{{%course_package_detail}}','pos_price',"decimal(10,2)	  COMMENT '单节pos价'");
    }

    public function down()
    {
        $this->alterColumn('{{%course_package_detail}}','sale_price',"decimal(10,2)	  COMMENT '单节售价'");
        $this->alterColumn('{{%course_package_detail}}','pos_price',"decimal(10,2)	  COMMENT '单节pos价'");
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
