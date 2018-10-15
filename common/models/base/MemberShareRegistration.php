<?php
namespace common\models\base;

use Yii;

/**
 * This is the model class for table "cloud_member_share_registration".
 *
 * @property string $id 自增ID
 * @property string $member_id 发起分享会员ID
 * @property string $name 报名者姓名
 * @property string $mobile 报名者手机号
 * @property string $coach_id 教练ID
 * @property string $interest 标识兴趣 (1:减脂,2:塑形,3:增肌,4:康复,5还没想法，不了解)
 * @property string $create_at 添加时间
 * @property int $is_delete 软删标记 (0 未删除, 1 已删除)
 */
class MemberShareRegistration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cloud_member_share_registration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'coach_id', 'is_delete'], 'integer'],
            [['interest'], 'string'],
            [['create_at'], 'safe'],
            [['name', 'mobile'], 'string', 'max' => 200],
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
            'name' => 'Name',
            'mobile' => 'Mobile',
            'coach_id' => 'Coach ID',
            'interest' => 'Interest',
            'create_at' => 'Create At',
            'is_delete' => 'Is Delete',
        ];
    }
}
