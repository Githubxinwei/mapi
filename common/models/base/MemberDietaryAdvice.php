<?php
namespace common\models\base;


use Yii;

/**
 * This is the model class for table "cloud_member_dietary_advice".
 *
 * @property string $id
 * @property string $coach_id
 * @property string $member_id
 * @property string $about_class_id
 * @property string $dietary_advice
 * @property string $create_at
 */
class MemberDietaryAdvice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cloud_member_dietary_advice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coach_id', 'member_id', 'about_class_id'], 'integer'],
            [['dietary_advice'], 'required'],
            [['dietary_advice'], 'string'],
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
            'coach_id' => 'Coach ID',
            'member_id' => 'Member ID',
            'about_class_id' => 'About Class ID',
            'dietary_advice' => 'Dietary Advice',
            'create_at' => 'Create At',
        ];
    }
}
