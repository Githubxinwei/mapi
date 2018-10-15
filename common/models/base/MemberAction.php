<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "cloud_member_action".
 *
 * @property string $id 自增ID
 * @property string $template_id 模板ID
 * @property string $action_id 动作ID
 * @property string $url 会员动作图片
 * @property string $action_number 会员动作组数
 * @property string $content 动作信息备注
 */
class MemberAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cloud_member_action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'action_id'], 'integer'],
            [['url', 'action_number'], 'string'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'template_id' => '模板ID',
            'action_id' => '动作ID',
            'url' => '会员动作图片',
            'action_number' => '会员动作组数',
            'content' => '动作信息备注',
        ];
    }
}
