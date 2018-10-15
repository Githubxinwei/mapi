<?php

use yii\db\Migration;

class m170522_064025_add_cabinetKey extends Migration
{
    /**
     * @数据库 - 柜子表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/22
     */
    public function up()
    {
        $this->createIndex(
            'index_id',
            '{{%cabinet}}',
            'id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id', '{{%cabinet}}');
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
