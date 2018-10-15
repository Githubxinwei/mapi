<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/11
 * Time: 11:26
 * Desc:会员健康餐取餐记录
 */
namespace common\models\base;

/**
 * This is the model class for table "{{%member_take_meal_record}}".
 *
 * @property string $oper_id 操作人ID
 * @property string $meal_code 取餐码
 * @property int $code_status 取餐码是否有效 (0无效, 1 有效 2.过期)
 * @property string $meal_date 预约取餐日期
 * @property string $meal_time 预约取餐时间段
 * @property string $receive_time 领取时间
 * @property string $evaluate_time 评价时间
 * @property string $cancle_time 取消时间
 * @property int $is_evalaute 是否评价(0 未评价, 1 已评价)
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property int $is_delete 软删标记 (0 未删除, 1 已删除)
 * @property string $id 存储order_id
 */
class MemberTakeMealRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_take_meal_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oper_id', 'code_status', 'is_evalaute', 'is_delete', 'id'], 'integer'],
            [['meal_date', 'receive_time', 'evaluate_time', 'cancle_time', 'create_at', 'update_at'], 'safe'],
            [['meal_code', 'meal_time'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'oper_id' => '操作人ID',
            'meal_code' => '取餐码',
            'code_status' => '取餐码是否有效 (0无效, 1 有效 2.过期)',
            'meal_date' => '预约取餐日期',
            'meal_time' => '预约取餐时间段',
            'receive_time' => '领取时间',
            'evaluate_time' => '评价时间',
            'cancle_time' => '取消时间',
            'is_evalaute' => '是否评价(0 未评价, 1 已评价)',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'is_delete' => '软删标记 (0 未删除, 1 已删除)',
            'id' => '存储order_id',
        ];
    }
}
