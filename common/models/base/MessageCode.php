<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%message_code}}".
 *
 * @property string $id
 * @property string $mobile
 * @property string $code
 * @property string $create_at
 */
class MessageCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'code', 'create_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'mobile' => '手机号',
            'code' => '验证码',
            'create_at' => '创建时间',
        ];
    }
}
