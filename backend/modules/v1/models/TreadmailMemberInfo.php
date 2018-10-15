<?php

namespace backend\modules\v1\models;

use Yii;

/**
 * 跑步机相关操作
 * This is the model class for table "{{%treadmail_member_info}}".
 *
 * @property string $id 自增ID
 * @property string $random_string 随机字符串
 * @property string $create_at 添加时间
 * @property string $update_at 更新时间
 * @property int $is_delete 软删标记
 * @property string $member_id 会员ID
 */
class TreadmailMemberInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%treadmail_member_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_at', 'update_at'], 'safe'],
            [['is_delete', 'member_id'], 'integer'],
            [['random_string'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'random_string' => 'Random String',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'is_delete' => 'Is Delete',
            'member_id' => 'Member ID',
        ];
    }

}
