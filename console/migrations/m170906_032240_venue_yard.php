<?php

use yii\db\Migration;

class m170906_032240_venue_yard extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_yard   场地表
     * @author houkaixin<houkaixin@itsport.club>
     * @create 2017/9/6
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'COMMENT="场馆场地表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%venue_yard}}', [
            'id'                   => $this->bigPrimaryKey()->comment('自增ID'),
            'venue_id'            => $this->bigInteger()->unsigned()->comment('场馆id'),
            "yard_name"           => $this->string(255)->comment('场地名称'),
            'people_limit'        => $this->bigInteger()->unsigned()->comment('人数限制'),
            'business_time'       => $this->string(255)->comment('每天营业时间段'),
            'active_duration'     => $this->integer()->unsigned()->comment('单次活动时长(分钟)'),
            "create_at"            => $this->bigInteger()->unsigned()->comment('场地创建时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_venue_id',
            '{{%venue_yard}}',
            'venue_id'
        );
    }

    public function down()
    {
         $this->dropTable("{{%venue_yard}}");
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
