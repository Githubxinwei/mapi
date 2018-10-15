<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member}}".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $mobile
 * @property string $register_time
 * @property string $password_token
 * @property string $hash
 * @property string $update_at
 * @property string $last_time
 * @property string $last_fail_login_time
 * @property string $times
 * @property integer $status
 * @property string $lock_time
 * @property string $params
 * @property string $counselor_id
 * @property integer $member_type
 * @property string $venue_id
 * @property integer $is_employee
 * @property string $company_id
 * @property string $fixPhone
 * @property integer $private_id
 * @property string $member_account_id
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'mobile', 'register_time'], 'required'],
            [['register_time', 'update_at', 'last_time', 'last_fail_login_time', 'times', 'status', 'lock_time', 'counselor_id', 'member_type', 'venue_id', 'is_employee', 'company_id', 'private_id', 'member_account_id'], 'integer'],
            [['params'], 'string'],
            [['username', 'password', 'mobile', 'password_token', 'hash', 'fixPhone'], 'string', 'max' => 200],
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
            'register_time' => '注册时间',
            'password_token' => 'Password Token',
            'hash' => 'Hash',
            'update_at' => '修改时间',
            'last_time' => '最近一次登录时间',
            'last_fail_login_time' => '上次登录失败时间',
            'times' => '登录失败次数',
            'status' => '状态：0待审核，1正常，2禁用',
            'lock_time' => '锁定时长',
            'params' => '扩展(json) ',
            'counselor_id' => '顾问ID',
            'member_type' => '会员类型（1：普通会员 2：潜在会员）',
            'venue_id' => '场馆ID',
            'is_employee' => '是不是员工：1 是',
            'company_id' => '公司id',
            'fixPhone' => '固定电话',
            'private_id' => '用于会员购卡未购课时分配私教以促进销售',
            'member_account_id' => '账户表',
        ];
    }
}
