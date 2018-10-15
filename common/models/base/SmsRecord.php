<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%sms_record}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $mobile
 * @property string $send_code
 * @property integer $status
 * @property integer $type
 * @property string $content
 * @property string $created_at
 * @property string $create_id
 * @property string $company_id
 * @property string $venue_id
 * @property string $var
 */
class SmsRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sms_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'status', 'type', 'created_at', 'create_id', 'company_id', 'venue_id'], 'integer'],
            [['content', 'var'], 'string'],
            [['mobile', 'send_code'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'member_id' => '会员Id',
            'mobile' => '手机号',
            'send_code' => '发送码',
            'status' => '状态:1已发送2发送失败',
            'type' => '类型:1售卡2生日3上下课',
            'content' => '内容',
            'created_at' => '创建时间',
            'create_id' => '创建人ID',
            'company_id' => '公司ID',
            'venue_id' => '场馆ID',
            'var' => '发送的自定义参数',
        ];
    }
}
