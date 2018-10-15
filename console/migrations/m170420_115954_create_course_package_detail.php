<?php

use yii\db\Migration;

class m170420_115954_create_course_package_detail extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_course_package_detail 课程套餐详情表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/4/20
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '课程套餐详情表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%course_package_detail}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'charge_class_id' => $this->bigInteger()->unsigned()->notNull()->comment('收费课程ID'),
            'course_id' => $this->bigInteger()->unsigned()->notNull()->comment('课程ID'),
            'course_num' => $this->integer()->unsigned()->comment('课量'),
            'course_length' => $this->integer()->unsigned()->comment('时长'),
            'original_price' => $this->decimal()->comment('单节原价'),
            'sale_price' => $this->decimal()->comment('单节售价'),
            'pos_price' => $this->decimal()->comment('单节pos价'),
            'type' => $this->smallInteger()->unsigned()->notNull()->defaultValue(1)->comment('状态：1私课，2团课'),
            'create_at' => $this->bigInteger()->unsigned()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_charge_class_id',
            '{{%course_package_detail}}',
            'charge_class_id'
        );
        $this->createIndex(
            'index_course_id',
            '{{%course_package_detail}}',
            'course_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_charge_class_id', '{{%course_package_detail}}');
        $this->dropIndex('index_course_id', '{{%course_package_detail}}');
        $this->dropTable('{{%course_package_detail}}');
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
