<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/16
 * Time: 9:19
 * Desc:会员合同记录表
 */
namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_deal_record}}".
 *
 * @property string $id 自增ID
 * @property int $type 合同类型 (多态 1 购卡合同; 2 购课合同)
 * @property string $type_id 多态ID
 * @property string $member_id 会员ID
 * @property string $deal_number 合同编号
 * @property string $name 合同名称
 * @property string $type_name 合同类型名称
 * @property string $intro 合同内容
 * @property string $company_id 公司ID
 * @property string $venue_id 场馆ID
 * @property string $create_at 生成时间
 * @property string $pic 合同照片
 */
class MemberDealRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_deal_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'type_id', 'member_id', 'company_id', 'venue_id', 'create_at'], 'integer'],
            [['intro', 'pic'], 'string'],
            [['deal_number', 'name'], 'string', 'max' => 255],
            [['type_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'type' => '合同类型 (多态 1 购卡合同; 2 购课合同)',
            'type_id' => '多态ID',
            'member_id' => '会员ID',
            'deal_number' => '合同编号',
            'name' => '合同名称',
            'type_name' => '合同类型名称',
            'intro' => '合同内容',
            'company_id' => '公司ID',
            'venue_id' => '场馆ID',
            'create_at' => '生成时间',
            'pic' => '合同照片',
        ];
    }
}