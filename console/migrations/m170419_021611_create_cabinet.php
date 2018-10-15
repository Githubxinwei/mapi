<?php

use yii\db\Migration;

class m170419_021611_create_cabinet extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_cabinet 柜子表
     * @author 朱梦珂 <zhumengke@itsport.club>
     * @create 2017/4/19
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '柜子表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%cabinet}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'cabinet_type_id' => $this->bigInteger()->unsigned()->notNull()->comment('柜子类型ID'),
            'cabinet_number' => $this->string(200)->notNull()->comment('柜子编号'),
            'status' => $this->smallInteger()->unsigned()->notNull()->defaultValue(1)->comment('状态：1未租，2已租，3维修中'),
            'creater_id' => $this->bigInteger()->unsigned()->notNull()->comment('创建人ID'),
            'created_at' => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at' => $this->bigInteger()->unsigned()->comment('更新时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_cabinet_type_id',
            '{{%cabinet}}',
            'cabinet_type_id'
        );
        $this->createIndex(
            'index_creater_id',
            '{{%cabinet}}',
            'creater_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_cabinet_type_id', '{{%cabinet}}');
        $this->dropIndex('index_creater_id', '{{%cabinet}}');
        $this->dropTable('{{%cabinet}}');
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
