<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_account}}".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $mobile
 * @property string $last_time
 * @property string $company_id
 * @property string $create_at
 */
class MemberAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['last_time', 'company_id', 'create_at'], 'integer'],
            [['username', 'mobile'], 'string', 'max' => 200],
            [['password'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'username' => '用户名',
            'password' => '密码',
            'mobile' => '手机号',
            'last_time' => '最后登录时间',
            'company_id' => '公司ID',
            'create_at' => '常见时间',
        ];
    }
}
