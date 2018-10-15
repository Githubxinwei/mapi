<?php

use yii\db\Migration;

class m170504_022830_drop_cabinet extends Migration
{

    /**
     * @数据库 - 删除cabinet 里边的venue_id 在cabinet_type 增加venue_id  - cloud_cabinet创建场馆id
     * @author Houkaixin <Houkaixin @itsport.club>
     * @create 2017/5/4
     */
    public function up()
    {
        $this->dropColumn('{{%cabinet}}', 'venue_id');
        $this->addColumn('{{%cabinet_type}}', 'venue_id', "bigint(20) UNSIGNED COMMENT '场馆id'");
    }

    public function down()
    {
        $this->addColumn('{{%cabinet}}', 'venue_id', "bigint(20) UNSIGNED COMMENT '场馆id'");
        $this->dropColumn('{{%cabinet_type}}', 'venue_id');
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
