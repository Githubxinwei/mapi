<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%fitness_program_send}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $name
 * @property string $content
 * @property string $send_time
 */
class FitnessProgramSend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fitness_program_send}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'send_time'], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'member_id' => '会员id',
            'name' => '名称',
            'content' => '内容',
            'send_time' => '发送时间',
        ];
    }
}
