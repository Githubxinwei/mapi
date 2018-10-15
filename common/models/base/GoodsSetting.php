<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/9
 * Time: 18:00
 * Desc:商品设置
 */
namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%goods_setting}}".
 *
 * @property string $id 自增ID
 * @property string $goods_id 商品表ID
 * @property string $meal_time 取餐时间段
 * @property string $meal_place 取餐地点
 * @property string $preset_day 提前接受预定天数
 * @property string $preset_time_begin 每天几点开始接受预定时分秒
 * @property string $preset_time_end 每天几点结束接受预定时分秒
 * @property int $valid_type 订单有效类型 1天 2小时
 * @property string $valid_time 订单有效时间
 * @property int $refund_type 申请退款时间类型 1天 2小时
 * @property string $refund_time 停止接受预定前多久允许用户申请退款
 * @property string $create_id 创建人ID
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property string $company_id 公司ID
 * @property int $is_delete 软删标记 0 未删除 1 已删除
 */
class GoodsSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'preset_day', 'valid_type', 'valid_time', 'refund_type', 'refund_time', 'create_id', 'company_id', 'is_delete'], 'integer'],
            [['meal_time', 'meal_place'], 'string'],
            [['preset_time_begin', 'preset_time_end', 'create_at', 'update_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'goods_id' => '商品表ID',
            'meal_time' => '取餐时间段',
            'meal_place' => '取餐地点',
            'preset_day' => '提前接受预定天数',
            'preset_time_begin' => '每天几点开始接受预定时分秒',
            'preset_time_end' => '每天几点结束接受预定时分秒',
            'valid_type' => '订单有效类型 1天 2小时',
            'valid_time' => '订单有效时间',
            'refund_type' => '申请退款时间类型 1天 2小时',
            'refund_time' => '停止接受预定前多久允许用户申请退款',
            'create_id' => '创建人ID',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'company_id' => '公司ID',
            'is_delete' => '软删标记 0 未删除 1 已删除',
        ];
    }
}