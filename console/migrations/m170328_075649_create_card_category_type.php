<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cloud_card_category_type`.
 */
class m170328_075649_create_card_category_type extends Migration
{

    /**
     * @数据库 - 创建表 - cloud_card_category_type 卡类别表添加
     * @author Huang Pengju<huangpengju@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '卡类别表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%card_category_type}}', [
            'id'          =>  $this->bigPrimaryKey()->comment('自增ID'),
            'type_name'  =>  $this->string(200)->unique()->notNull()->comment('类别名（时间卡、次卡）'),
            'create_at'  =>  $this->bigInteger()->unsigned()->null()->comment('创建时间'),
            'update_at'  =>  $this->bigInteger()->unsigned()->null()->comment('更新时间'),
        ], $tableOptions);
    }

    /**
     * @数据库 - 创建表 - cloud_card_category_type 卡类别表
     * @author Huang Pengju<huangpengju@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%card_category_type}}');
      
    }
}
