<?php

use yii\db\Migration;

class m170426_074809_add_classroom extends Migration
{
    /**
     * @数据库 - 教师表增加字段
     * @author Houkaixin <Houkaixin@itsport.club>
     * @create 2017/4/26
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%classroom}}', 'classroom_area', 'varchar(200) COMMENT "教室面积"');
    }

    public function down()
    {
        $this->dropColumn('{{%classroom}}', 'classroom_area');
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
