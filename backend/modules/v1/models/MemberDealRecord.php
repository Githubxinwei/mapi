<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/16
 * Time: 9:30
 * Desc:会员合同记录
 */
namespace backend\modules\v1\models;

use Yii;
use common\models\MemberCard;
use common\models\Order;

class MemberDealRecord extends \common\models\MemberDealRecord
{
    public function fields()
    {
        $fields = [
            'id',
            'type',
            'deal_number',
            'create_at',
            'product_name' => function($model) {
                if ($model->type == 1) {
                    $arr = $this->getMemberCardName($model->type_id);
                    if (!empty($arr['card_name'])){
                        return $arr['card_name'];
                    }
                    return '';

                } else {
                    $arr = $this->getProductName($model->type_id);
                    if (!empty($arr['product_name'])){
                        return $arr['product_name'];
                    }
                    return '';
                }
            },
        ];
        return $fields;
    }
    /**
     * 会员端 - API - 获取课程名称
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/16
     */
    public function getProductName($typeId)
    {
        $arr = MemberCourseOrderDetails::find()
            ->select('product_name')
            ->where(['id' => $typeId])
            ->asArray()->one();
        return $arr;
    }
    /**
     * 会员端 - API - 获取会员卡名称
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/16
     */
    public function getMemberCardName($typeId)
    {
        $arr = MemberCard::find()
            ->select('card_name')
            ->where(['id' => $typeId])
            ->asArray()
            ->one();
        return $arr;
    }
    /**
     * 会员端 - API - 会员协议记录
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/16
     */
    public function getMemberDealRecord($accountId)
    {
        $arr = MemberDealRecord::find()
            ->alias('mdr')
            ->joinWith(['member m'], FALSE)
            ->where(['m.member_account_id' => $accountId])
            ->orderBy('mdr.create_at desc');
        return $arr;
    }
    /**
     * 会员端 - API - 会员协议详情
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/16
     */
    public function getMemberDealRecordDetails($memberDealRecordId)
    {
        $arr = MemberDealRecord::find()
            ->alias('mdr')
            ->joinWith(['member m'], FALSE)
            ->joinWith(['memberDetails md'],FALSE)
            ->joinWith(['memberCard mc' => function($q){
                $q->andOnCondition(['mdr.type' => 1])
                  ->joinWith('orders o')
                  ->joinWith('cardCategory cct');
            }], FALSE)
            ->joinWith(['memberCourseOrderDetails mcod'=>function($q){
                $q->andOnCondition(['mdr.type'=> 2])
                  ->joinWith(['memberCourseOrder mco' => function($query) {
                      $query->joinWith('order order');
                    }],FALSE);
            }], FALSE)
            ->joinWith(['organization or'],FALSE)
            ->where(['mdr.id' => $memberDealRecordId])
            ->select([
                'md.name username',
                'or.name venueName',
                'md.id_card',
                'm.mobile',
                'o.order_number orderNumber',
                'order.order_number',
                'mc.card_name',
                'mcod.product_name',
                'mco.deadline_time',
                'cct.card_type',
                'o.total_price totalPrice',
                'order.total_price',
                'mc.duration',
                'o.consumption_type consumptionType',
                'order.consumption_type',
                'o.consumption_type_id typeId',
                'order.consumption_type_id',
                'o.sign orderSign',
                'order.sign',
                'mdr.type',
                'mdr.name dealName',
                'mdr.deal_number',
                'mdr.intro',
                'mdr.create_at',
                'mco.course_amount'
            ])
            ->asArray()
            ->one();
        return $arr;
    }
}