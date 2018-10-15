<?php

use yii\db\Migration;

class m170420_024631_add_member_cabinet extends Migration
{

    /**
     * @数据库 - 会员柜表 -  添加字段
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/4/20
     */
    public function up()
    {
        $this->addColumn('{{%member_cabinet}}', 'cabinet_id', "bigint(20) UNSIGNED COMMENT '柜子ID'");

    }

    public function down()
    {
        $this->dropColumn('{{%member_cabinet}}', 'cabinet_id');
        
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
