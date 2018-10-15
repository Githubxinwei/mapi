<?php

use yii\db\Migration;

/**
 * Class m180122_032639_create_version
 */
class m180122_032639_create_version extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = "COMMENT = 'APP版本表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%version}}', [
            'id'      => $this->primaryKey()->comment('自增ID'),
            'name'    => $this->char(20)->notNull()->defaultValue('')->comment('产品代号[android_client->安卓会员端,ios_business->IOS管理端,ios_coach->IOS私教端,ios_group->IOS团教端]'),
            'version' => $this->char(10)->notNull()->defaultValue('')->comment('最新版本号'),
            'url'     => $this->string()->notNull()->defaultValue('')->comment('安装文件URL'),
            'must'    => $this->smallInteger()->notNull()->defaultValue(0)->comment('0不必须更新1必须更新'),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable("{{%version}}");
    }
}
