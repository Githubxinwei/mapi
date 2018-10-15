<?php

use yii\db\Migration;

/**
 * Class m180206_071325_create_feedback
 */
class m180206_071325_create_feedback extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = "COMMENT = '投诉反馈表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%feedback}}', [
            'id'          => $this->primaryKey()->comment('自增ID'),
            'type_id'     => $this->integer()->notNull()->defaultValue(0)->comment('类型ID'),
            'from'        => $this->char(50)->notNull()->defaultValue('')->comment('来源产品代号[android_customer->安卓会员端,ios_business->IOS管理端,ios_coach->IOS私教端,ios_group->IOS团教端]'),
            'company_id'  => $this->integer()->notNull()->defaultValue(0)->comment('公司ID'),
            'venue_id'    => $this->integer()->notNull()->defaultValue(0)->comment('场馆ID'),
            'user_id'     => $this->integer()->notNull()->defaultValue(0)->comment('用户ID(member_id或employee_id)'),
            'content'     => $this->text()->comment('内容'),
            'occurred_at' => $this->bigInteger()->notNull()->defaultValue(0)->comment('故障发生时间'),
            'created_at'  => $this->bigInteger()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at'  => $this->bigInteger()->notNull()->defaultValue(0)->comment('更新时间'),
        ], $tableOptions);
        $this->addColumn('{{%feedback}}','pics',"json COMMENT '多张图片'");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable("{{%feedback}}");
    }
}
