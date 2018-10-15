<?php

use yii\db\Migration;

class m170413_085007_create_about_class extends Migration
{
    /**
     * @数据库 - 创建表 -  创建约课记录
     * @author lihuien <lihuien@itsport.club>
     * @create 2017/4/12
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="约课记录表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%about_class}}', [
            'id'                => $this->bigPrimaryKey()->comment('自增ID'),
            'member_card_id'    => $this->bigInteger(20)->unsigned()->notNull()->comment('会员卡ID'),
            'class_id'          => $this->bigInteger(20)->unsigned()->notNull()->comment('课程Id'),
            'status'            => $this->smallInteger(4)->defaultValue(1)->comment('状态1:正常 2：取消 3：过期'),
            'cancel_reason'     => $this->string(20)->comment('取消原因'),
            'type'              => $this->string(200)->comment('类型:例如团课私课'),
            'create_at'         => $this->bigInteger(20)->comment('创建时间'),
            'seat_id'           => $this->bigInteger(20)->comment('座位号ID'),
            'limit_time'        => $this->integer(11)->comment('上课前多长时间不能取消预约'),
        ], $tableOptions);
    }
    /**
     * @数据库 - 删除表 -  删除约课记录
     * @author lihuien <lihuien@itsport.club>
     * @create 2017/4/12
     */
    public function down()
    {
        $this->dropTable('{{%about_class}}');
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
