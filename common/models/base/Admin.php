<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property string $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $level
 * @property string $company_id
 * @property string $venue_id
 */
class Admin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['status', 'created_at', 'updated_at', 'level', 'company_id', 'venue_id'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID自增',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态10审核20通过',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'level' => '管理权限级别',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
        ];
    }
}
