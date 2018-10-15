<?php

use yii\db\Migration;

class m170331_004706_add_admin extends Migration
{
    public function up()
    {
        $this->execute(" DELETE FROM {{%admin}} WHERE username = 'admin'");
        $password = '123456';
        $password = Yii::$app->security->generatePasswordHash($password);
        $this->batchInsert('{{%admin}}', [
            'id',
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email',
            'status',
            'created_at',
            'updated_at'
        ], [
            [
                null,
                'admin',
                'BAscckbHf91npOYsxfv6KCxY-nqIM5M3',
                $password,
                NULL,
                'yunyundong@itsports.club',
                20,
                time(),
                time()
            ],
        ]);
    }

    public function down()
    {
        $this->execute("
              DELETE FROM {{%admin}} WHERE username = 'admin'");
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
