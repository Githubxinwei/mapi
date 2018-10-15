<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "cloud_member_class_before_question".
 *
 * @property string $id 自增ID
 * @property string $member_id 会员ID
 * @property string $about_class_id 约课记录表ID
 * @property string $answer_question 课前询问问题和答案
 * @property string $create_at 创建时间
 */
class MemberClassBeforeQuestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cloud_member_class_before_question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'about_class_id'], 'integer'],
            [['answer_question'], 'string'],
            [['create_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'about_class_id' => 'About Class ID',
            'answer_question' => 'Answer Question',
            'create_at' => 'Create At',
        ];
    }
}
