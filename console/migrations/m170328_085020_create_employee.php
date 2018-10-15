<?php

use yii\db\Migration;

class m170328_085020_create_employee extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_employee  员工
     * @author LiHuiEn <lihuien@itsports.club>
     * @create 2017/3/28
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '员工表'  CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%employee}}', [
            'id'             => $this->bigPrimaryKey()->comment('主键自增'),
            'name'           => $this->string(200)->notNull()->comment('姓名'),
            'age'            => $this->smallInteger(4)->null()->comment('年龄'),
            'sex'            => $this->smallInteger(4)->null()->defaultValue(1)->comment('性别'),
            'mobile'         => $this->string(200)->unique()->comment('手机号'),
            'email'          => $this->string(200)->null()->comment('邮箱'),
            'birth_time'     => $this->date()->null()->comment('生日'),
            'organization_id'=> $this->bigInteger(20)->unsigned()->notNull()->comment('组织ID'),
            'position'       => $this->string(200)->null()->comment('职务'),
            'status'         => $this->smallInteger(2)->defaultValue(1)->comment('状态：1在职 2离职'),
            'entry_date'     => $this->date()->null()->comment('任职日期'),
            'leave_date'     => $this->date()->null()->comment('离职日期'),
            'create_id'      => $this->bigInteger(20)->unsigned()->notNull()->comment('创建人ID'),
            'salary'         => $this->decimal(10,2)->defaultValue(0)->comment('薪资'),
            'admin_user_id'  => $this->bigInteger(2)->null()->comment('管理员'),
            'created_at'     => $this->bigInteger()->null()->comment('创建时间'),
            'updated_at'     => $this->bigInteger()->null()->comment('更新时间'),
        ], $tableOptions);
        $this->addColumn('{{%employee}}','params',"json NULL COMMENT '课时=>,基础课量等自定义参数'");
        $this->createIndex('index_organization_id','{{%employee}}','organization_id');
        $this->createIndex('index_create_id','{{%employee}}','create_id');
    }
    /**
     * @数据库 - 创建表 - cloud_employee  员工
     * @author LiHuiEn <lihuien@itsports.club>
     * @create 2017/3/28
     */
    public function down()
    {
        $this->dropIndex('index_organization_id', '{{%employee}}');
        $this->dropIndex('index_create_id', '{{%employee}}');
        $this->dropTable('{{%employee}}');
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
