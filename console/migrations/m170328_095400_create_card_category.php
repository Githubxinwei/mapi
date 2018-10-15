<?php

use yii\db\Migration;

class m170328_095400_create_card_category extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_card_category_type 卡种表添加
     * @author Huang Pengju<huangpengju@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '卡种表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%card_category}}', [
            'id'                       =>   $this->bigPrimaryKey()->comment('自增ID'),
            'category_type_id'             =>   $this->bigInteger()->unsigned()->notNull()->comment('类别表ID'),
            'card_name'               =>    $this->string(200)->unique()->notNull()->comment('卡名'),
            'create_at'               =>    $this->bigInteger()->unsigned()->null()->comment('添加时间'),
            'class_server_id'         =>   $this->smallInteger()->unsigned()->null()->comment('课程套餐ID'),
            'server_combo_id'         =>   $this->smallInteger()->unsigned()->null()->comment('服务套餐ID'),
            'times'                    =>   $this->integer()->unsigned()->null()->defaultValue(0)->comment('次数，用于次卡'),
            'count_method'            =>    $this->bigInteger()->unsigned()->null()->defaultValue(0)->comment('计次方式：时间段、计次'),
            'sell_start_time'         =>    $this->bigInteger()->unsigned()->null()->comment('售卖开始时间'),
            'sell_end_time'           =>    $this->bigInteger()->unsigned()->null()->comment('售卖结束时间'),
            'attributes'              =>    $this->smallInteger()->unsigned()->null()->defaultValue(1)->comment('属性：1个人2公司3团体'),
            'total_store_times'      =>    $this->integer()->unsigned()->null()->defaultValue(0)->comment('总通电次数：-1不限'),
            'venue_id'                =>    $this->bigInteger()->unsigned()->notNull()->comment('办卡场馆'),
            'payment'                 =>    $this->smallInteger()->unsigned()->null()->comment('付款类型：1全款2分期'),
            'payment_months'          =>    $this->smallInteger()->unsigned()->null()->defaultValue(0)->comment('付款总月数:用于分期'),
            'total_circulation'       =>    $this->bigInteger()->unsigned()->null()->comment('总发行量'),
            'sex'                      =>    $this->smallInteger()->defaultValue(-1)->comment('限制性别：1男2女-1不限'),
            'age'                      =>    $this->smallInteger()->null()->defaultValue(-1)->comment('限制年龄：-1不限'),
            'transfer_number'         =>    $this->smallInteger()->unsigned()->null()->defaultValue(0)->comment('可转让次数'),
            'create_id'                =>    $this->bigInteger()->unsigned()->notNull()->comment('创建人：对应员工表中有权限的管理者'),
            'regular_renew_time'      =>    $this->date()->null()->comment('规定续约时间'),
            'regular_transform_time'  =>    $this->date()->null()->comment('规定转让时间'),
            'original_price'           =>    $this->decimal()->null()->comment('一口原价'),
            'sell_price'               =>    $this->decimal()->null()->comment('一口价售价'),
            'max_price'                =>    $this->decimal()->null()->comment('最高价'),
            'min_price'                =>    $this->decimal()->null()->comment('最低价'),
            'sales_mode'               =>    $this->integer()->unsigned()->null()->defaultValue(1)->comment('销售方式：1实体店2网络店3不限'),
            'missed_times'             =>    $this->smallInteger()->null()->defaultValue(-1)->comment('未赴约次数限制：-1不限'),
            'limit_times'              =>    $this->smallInteger()->null()->defaultValue(-1)->comment('限制约课天数：限制多长时间内不能约课/-1不限'),
        ], $tableOptions);
        $this->createIndex(
            'index_category_type_id',
            '{{%card_category}}',
            'category_type_id'
        );
        $this->createIndex(
            'index_class_server_id',
            '{{%card_category}}',
            'class_server_id'
        );
        $this->createIndex(
            'index_server_combo_id',
            '{{%card_category}}',
            'server_combo_id'
        );
        $this->addColumn('{{%card_category}}','duration','JSON COMMENT \'次数(json["秒"=>1,"分钟"=>"小时"=>"天"=>"周"=>"月"=>"季度"=>"年"=>])\'');
    }
    /**
     * @数据库 - 创建表 - cloud_card_category_type 卡种表
     * @author Huang Pengju<huangpengju@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%card_category}}');
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
