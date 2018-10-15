<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%treadmail_member_info}}".
 *
 * @property string $id 自增ID
 * @property string $random_string 随机字符串
 * @property string $create_at 添加时间
 * @property string $update_at 更新时间
 * @property int $is_delete 软删标记
 * @property string $member_id 会员ID
 */
class TreadmailMemberInfo extends \common\models\base\TreadmailMemberInfo
{

}
