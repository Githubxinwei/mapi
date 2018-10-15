<?php

use yii\db\Migration;

class m170616_061402_create_role extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_role 角色表添加
     * @author Huang hua<huanghua@itsport.club>
     * @create 2017/6/16
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="角色表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        }
        $this->createTable('{{%role}}', [
            'id'        => $this->bigPrimaryKey()->comment('自增ID'),
            'name'      => $this->string(200)->notNull()->comment('角色名称'),
            'company_id'=> $this->bigInteger()->comment('所属公司id'),
            'create_id' => $this->bigInteger()->comment('创建人'),
            'create_at' => $this->bigInteger()->comment('创建时间'),
        ], $tableOptions);

        $this->createIndex('index_company_id', '{{%role}}', 'company_id');

    }

        public function down()
    {
        $this->dropTable('{{%role}}');
    }
}
