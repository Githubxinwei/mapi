<?php

use yii\db\Migration;

class m170812_012411_add_venue extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_group_class表
     * @author lihuien <lihuien@itsport.club>
     * @create 2017/8/9
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%organization}}', 'identity', "tinyint default '1'  COMMENT '1. 普通 ，2:尊爵等'");
    }

    public function down()
    {
        $this->dropColumn('{{%organization}}', 'identity');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170812_012411_add_venue cannot be reverted.\n";

        return false;
    }
    */
}
