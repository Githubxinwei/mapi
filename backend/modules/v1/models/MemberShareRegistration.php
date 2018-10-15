<?php
namespace backend\modules\v1\models;


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
class MemberShareRegistration extends \common\models\MemberShareRegistration
{

}
