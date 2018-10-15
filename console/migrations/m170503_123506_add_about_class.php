<?php

use yii\db\Migration;

class m170503_123506_add_about_class extends Migration
{
    /**
     * @数据库 - 添加字段
     * @author lihuien <lihuien@itsport.club>
     * @create 2017/5/3
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%about_class}}', 'cancel_time', 'bigint(20) COMMENT "取消预约时间"');
    }
    /**
     * @数据库 - 回滚字段
     * @author lihuien <lihuien@itsport.club>
     * @create 2017/5/3
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%about_class}}', 'cancel_time');
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
