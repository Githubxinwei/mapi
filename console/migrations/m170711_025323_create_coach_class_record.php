<?php

use yii\db\Migration;

class m170711_025323_create_coach_class_record extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'COMMENT="教练上课记录" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%coach_class_record}}', [
            'id'               => $this->bigPrimaryKey()->comment('自增ID'),
            'sign_time'       => $this->bigInteger()->unsigned()->comment('签到时间'),
            "class_over_time"=> $this->bigInteger()->unsigned()->comment('下课时间'),
            'class_id'         => $this->bigInteger()->unsigned()->comment('排课记录表id'),
            'coach_id'         => $this->bigInteger()->unsigned()->comment('教练id'),
            "status"           =>$this->smallInteger()->unsigned()->comment('教练上课状态 1:未打卡 2:已打卡 3:已下课 4:未上课'),
        ], $tableOptions);
        $this->createIndex(
            'index_class_id',
            '{{%coach_class_record}}',
            'class_id'
        );
        $this->createIndex(
            'index_coach_id',
            '{{%coach_class_record}}',
            'coach_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%coach_class_record}}');
    }
}
