<?php

use yii\db\Migration;

class m170509_085045_create_visitors extends Migration
{
    /**
     * @数据库 - 创建访客表
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/2
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'COMMENT="访客表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%visitors}}', [
            'id'                      => $this->bigPrimaryKey()->comment('自增ID'),
            'create_id'               => $this->bigInteger()->unsigned()->notNull()->comment('创建人id'),
            'visitor_name'            => $this->string(200)->comment('访客姓名'),
            'visitor_mobile'          => $this->integer(11)->comment('访客手机号'),
            'visitor_sex'             => $this->smallInteger()->comment('访客性别：1表示男；2表示女'),
            'reservation'             => $this->string(255)->comment('预约来店方式'),
            'alone'                   => $this->string(255)->comment('自访来店方式'),
            'activity'                => $this->string(255)->comment('活动来店方式'),
            'referrer_name'           => $this->string(200)->comment('推荐人姓名'),
            'referrer_mobile'         => $this->integer(11)->comment('推荐人手机号'),
            'transportation'          => $this->string(200)->comment('访客交通方式'),
            'visitor_sport_status'    => $this->string(200)->comment('访客运动状态'),
            'visitor_hope_time'       => $this->string(200)->comment('访客希望上课时间'),
            'visitor_hope_limit'      => $this->string(200)->comment('访客希望上课时段'),
            'visitor_like_equipment'  => $this->string(200)->comment('访客喜欢的健身设备'),
            'visitor_like_yoga'       => $this->string(200)->comment('访客喜欢的瑜伽'),
            'visitor_like_dance'      => $this->string(200)->comment('访客喜欢的舞蹈'),
            'visitor_like_course'     => $this->string(200)->comment('访客喜欢的课程'),
            'visitor_aims'            => $this->string(200)->comment('访客的健身目标'),
            'visitor_ready_time'      => $this->string(200)->comment('访客想完成健身目标有多长时间了，'),
            'visitor_fulfil'          => $this->string(200)->comment('访客希望达到目标的时间'),
            'create_at'               => $this->bigInteger()->comment('添加时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_create_id',
            '{{%visitors}}',
            'create_id'
        );
    }
    /**
     * @数据库 - 回滚 - 访客表
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/2
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%visitors}}');
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
