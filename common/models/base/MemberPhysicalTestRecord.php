<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/25
 * Time: 15:03
 * Desc:体侧信息预约记录
 */

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_physical_test_record}}".
 *
 * @property string $id 自增ID
 * @property string $member_id 会员id
 * @property int $status 1-已预约，2-已体检
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property int $is_delete 软删标记（0-未删除，1-已删除）
 */
class MemberPhysicalTestRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_physical_test_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'status', 'is_delete'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'member_id' => '会员id',
            'status' => '1-已预约，2-已体检',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'is_delete' => '软删标记（0-未删除，1-已删除）',
        ];
    }
}