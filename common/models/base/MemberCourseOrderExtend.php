<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/8/2
 * Time: 10:16
 * Desc:会员购买私课订单详情扩展表
 */
namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_course_order_extend}}".
 *
 * @property string $id 主键ID
 * @property string $pic 私课课程图片
 * @property string $desc 私课课程详情
 * @property string $details_id 关联cloud_member_course_order_details表
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property int $is_delete 软删标记（0-未删除，1-删除）
 */
class MemberCourseOrderExtend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_course_order_extend}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pic', 'desc'], 'string'],
            [['details_id', 'is_delete'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键ID',
            'pic' => '私课课程图片',
            'desc' => '私课课程详情',
            'details_id' => '关联cloud_member_course_order_details表',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'is_delete' => '软删标记（0-未删除，1-删除）',
        ];
    }
}