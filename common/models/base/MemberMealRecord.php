<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/11
 * Time: 11:20
 * Desc:会员健康餐预约记录
 */
namespace common\models\base;

use Yii;
/**
 * This is the model class for table "{{%member_meal_record}}".
 *
 * @property string $id 自增ID
 * @property string $company_id 公司ID
 * @property string $venue_id 场馆ID
 * @property string $member_id 会员ID
 * @property string $goods_id 商品ID(关联商品表goods)
 * @property string $order_id 订单ID(关联order表)
 * @property string $name 名称
 * @property string $spec 规格
 * @property string $unit_price 单价
 * @property string $total_price 总价
 * @property int $num 数量
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property int $is_delete 软删标记 (0 未删除, 1 已删除)
 */
class MemberMealRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_meal_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'venue_id', 'member_id', 'goods_id', 'order_id', 'num', 'is_delete'], 'integer'],
            [['unit_price', 'total_price'], 'number'],
            [['create_at', 'update_at'], 'safe'],
            [['name'], 'string', 'max' => 20],
            [['spec'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'company_id' => '公司ID',
            'venue_id' => '场馆ID',
            'member_id' => '会员ID',
            'goods_id' => '商品ID(关联商品表goods)',
            'order_id' => '订单ID(关联order表)',
            'name' => '名称',
            'spec' => '规格',
            'unit_price' => '单价',
            'total_price' => '总价',
            'num' => '数量',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'is_delete' => '软删标记 (0 未删除, 1 已删除)',
        ];
    }
}