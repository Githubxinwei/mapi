<?php

use yii\db\Migration;

class m170328_090408_create_service_rule extends Migration
{
    /**
     * @数据库 - 创建者 - cloud_service_rule 服务收费规则表
     * @author Hou Kaixin<houkaixin@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '服务收费规则表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%service_rule}}', [
            'id'              => $this->bigPrimaryKey()->comment("id自增"),
            'venue_id'       => $this->bigInteger()->unsigned()->notNull()->comment("场馆id(组织架构表的)"),
            'pay_service_id' => $this->bigInteger()->unsigned()->notNull()->comment("收费项目id"),
        ], $tableOptions);

        $this->createIndex(
            'index_venue_id',
            '{{%service_rule}}',
            'venue_id'
        );
        $this->createIndex(
            'index_pay_service_id',
            '{{%service_rule}}',
            'pay_service_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_venue_id','{{%service_rule}}');
        $this->dropIndex('index_pay_service_id','{{%service_rule}}');
        $this->dropTable('{{%service_rule}}');
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
