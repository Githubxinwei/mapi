<?php

use yii\db\Migration;

class m170330_014850_create_member_details extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员详细信息表'  CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%member_details}}', [
            'id'                       => $this->bigPrimaryKey()->comment('主键自增'),
            'member_id'                => $this->bigInteger()->notNull()->comment('会员ID'),
            'name'                     => $this->string(200)->null()->comment('姓名'),
            'sex'                      => $this->smallInteger(4)->null()->defaultValue(1)->comment('性别1:男2：女'),
            'pic'                      => $this->string(200)->comment('头像'),
            'id_card'                  => $this->string(200)->null()->comment('身份证号'),
            'birth_date'               => $this->date()->null()->comment('生日'),
            'email'                    =>  $this->string(200)->comment('邮箱'),
            'profession'               => $this->string(255)->comment('职业'),
            'family_address'           => $this->text()->null()->comment('家庭住址'),
            'hobby'                    => $this->string(200)->comment('喜好'),
            'month_income'             => $this->decimal(10,2)->null()->comment('月收入'),
            'way_to_shop'              => $this->string(200)->null()->comment('来电途径'),
            'recommend_member_id'      => $this->bigInteger(20)->unsigned()->notNull()->comment('推荐人ID'),
            'motto'                    => $this->string(255)->defaultValue(0)->comment('座右铭'),
            'created_at'               => $this->bigInteger()->null()->comment('创建时间'),
            'updated_at'               => $this->bigInteger()->null()->comment('更新时间'),
        ], $tableOptions);
        $this->createIndex('index_member_id','{{%member_details}}','member_id');
    }

    public function down()
    {
        $this->dropIndex('index_member_id', '{{%member_details}}');
        $this->dropTable('{{%member_details}}');
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
