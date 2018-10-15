<?php

use yii\db\Migration;

class m170328_093437_create_class_server_detail extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_card_category_type 课程套餐明细表添加
     * @author Huang Pengju<huangpengju@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '课程套餐明细表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%class_server_detail}}', [
            'id'                 =>  $this->bigPrimaryKey()->comment('自增ID'),
            'class_server_id'   =>  $this->bigInteger()->unique()->unsigned()->notNull()->comment('套餐名'),
            'class_id'           =>  $this->bigInteger()->unsigned()->notNull()->comment('课程ID'),
            'total'              =>  $this->integer()->unsigned()->notNull()->comment('总节数'),
            'create_at'          =>  $this->bigInteger()->unsigned()->null()->comment('添加时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_class_server_id',
            '{{%class_server_detail}}',
            'class_server_id'
        );
        $this->createIndex(
            'index_class_id',
            '{{%class_server_detail}}',
            'class_id'
        );
        $this->addColumn('{{%class_server_detail}}','number','JSON COMMENT \'次数(json["day"=>1,"week"=>1,"quarter"=>1,"year"=>1  -1代表不限制次数])\' ');
    }
    /**
     * @数据库 - 创建表 - cloud_card_category_type 课程套餐明细表
     * @author Huang Pengju<huangpengju@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%class_server_detail}}');
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
