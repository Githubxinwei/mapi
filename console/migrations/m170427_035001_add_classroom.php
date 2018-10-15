<?php

use yii\db\Migration;

class m170427_035001_add_classroom extends Migration
{
    /**
     * @数据库 - 教室表增加字段
     * @author Houkaixin <Houkaixin@itsport.club>
     * @create 2017/4/27
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%classroom}}', 'classroom_describe', 'text COMMENT "教室描述"');
        $this->addColumn('{{%classroom}}', 'classroom_pic', 'varchar(255) COMMENT "教室图片"');

    }

    public function down()
    {
        $this->dropColumn('{{%classroom}}', 'classroom_describe');
        $this->dropColumn('{{%classroom}}', 'classroom_pic');
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
