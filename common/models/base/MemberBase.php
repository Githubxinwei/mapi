<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_base}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $qq_open_id
 * @property string $wx_open_id
 * @property string $wb_open_id
 * @property string $note
 * @property string $create_at
 * @property string $update_at
 * @property string $company_id
 * @property string $venue_id
 * @property string $mobile
 * @property integer $last_venue_id
 * @property string $member_account_id
 * @property string $mini_program_openid
 */
class MemberBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_base}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'create_at', 'update_at', 'company_id', 'venue_id', 'last_venue_id', 'member_account_id'], 'integer'],
            [['note'], 'string'],
            [['qq_open_id', 'wx_open_id', 'wb_open_id', 'mobile', 'mini_program_openid','alipay_open_id','mi_open_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'member_id' => '会员id',
            'qq_open_id' => 'qq登录唯一id',
            'wx_open_id' => '微信登录唯一id',
            'wb_open_id' => '微博登录唯一id',
            'note' => '备注',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
            'mobile' => '手机号',
            'last_venue_id' => '上次登录场馆id',
            'member_account_id' => '账号ID',
            'mini_program_openid' => '微信小程序openid',
            'alipay_open_id' =>'阿里登录唯一id',
            'mi_open_id' =>'小米登录唯一id'
        ];
    }
}
