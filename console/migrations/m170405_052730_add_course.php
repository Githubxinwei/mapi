<?php

use yii\db\Migration;

class m170405_052730_add_course extends Migration
{
    /**
     * @数据库 - 修改表 - cloud_course 添加场馆id ，总金额 ， 单节pos价字段
     * @author Huang hua<Huanghua@itsport.club>
     * @create 2017/4/5
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%course}}','pic','varchar(255) COMMENT "课种头像宣传图片" ');
        $this->addColumn('{{%course}}','create_person','varchar(255) COMMENT "创建人" ');
        $this->addColumn('{{%course}}','course_desrc','text COMMENT "课程介绍" ');
    }

    public function down()
    {
        $this->dropColumn('{{%course}}',"pic");
        $this->dropColumn('{{%course}}',"create_person");
        $this->dropColumn('{{%course}}',"course_desrc");
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
