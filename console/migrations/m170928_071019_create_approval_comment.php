<?php

use yii\db\Migration;

class m170928_071019_create_approval_comment extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_approval_comment   审核评论表
         * @author 李慧恩<lihuien@itsports.club>
         * @create 2017/8/22
         * @inheritdoc
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '审核评论表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%approval_comment}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'approval_detail_id' => $this->bigInteger()->unsigned()->comment('审核详情表ID'),
            'content'            => $this->text()->comment('评论内容'),
            'reviewer_id'        => $this->bigInteger()->unsigned()->comment('评论人ID'),
            'create_at'          => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'          => $this->bigInteger()->unsigned()->comment('修改时间'),
        ], $tableOptions);
        $this->createIndex('index_approval_detail_id', '{{%approval_comment}}', 'approval_detail_id');
        $this->createIndex('index_reviewer_id', '{{%approval_comment}}', 'reviewer_id');

    }

    public function down()
    {
        $this->dropTable('{{%approval_comment}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170928_071019_create_approval_comment cannot be reverted.\n";

        return false;
    }
    */
}
