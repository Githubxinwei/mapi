<?php

use yii\db\Migration;

class m170509_093323_update_visitors_column extends Migration
{
    /**
     * @数据库 - 创建访客表 - 修改字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/2
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('{{%visitors}}', 'visitor_mobile', "varchar(200)   COMMENT '访客手机号'");
        $this->alterColumn('{{%visitors}}', 'referrer_mobile', "varchar(200)   COMMENT '推荐人手机号'");
    }
    /**
     * @数据库 - 创建访客表 - 回滚字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/2
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn('{{%visitors}}', 'visitor_mobile', "int(11)   COMMENT '访客手机号'");
        $this->alterColumn('{{%visitors}}', 'referrer_mobile', "int(11)   COMMENT '推荐人手机号'");
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
