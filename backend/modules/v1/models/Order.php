<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/9
 * Time: 17:14
 * Desc:健身餐订单
 */
namespace backend\modules\v1\models;

use common\models\GoodsSetting;
use common\models\MemberMealRecord;
use common\models\MemberTakeMealRecord;
use common\models\GoodsStandard;
use Yii;

class Order extends \common\models\Order
{
    public $venueId;            //场馆ID
    public $accountId;           //账户id
    public $state;              //订单状态
    public $orderId;              //订单id
    public $type;                //退款售后标识
    /**
     * 会员端 - 订单列表(分页) - 返回相应字段
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/20
     */
    public function fields()
    {
        $fields = [
            'id',
            'status',
            'order_time' => function($model) {
                if (!empty($model->order_time)) {
                    return date('Y-m-d',$model->order_time);
                }
                return '';
            },
            'specs' => function($model) {
                $arr = MemberMealRecord::find()
                    ->alias('mmr')
                    ->joinWith(['goods g' => function($q) {
                        $q->joinWith('imageManagement im');
                    }],FALSE)
                    ->where(['order_id' => $model->id])
                    ->select([
                        'im.url pic',
                        'mmr.name',
                        'mmr.spec specName',
                        'mmr.num',
                        'mmr.unit_price',
                        'mmr.total_price'
                    ])
                    ->asArray()
                    ->all();
                if ($arr) {
                    return $arr;
                }
                return [];
            },
            'purchase_num' => function($model) {
                if (!empty($model->purchase_num)) {
                    return $model->purchase_num;
                }
                return 0;
            },
            'total_price' => function($model) {
                if (!empty($model->total_price)) {
                    return $model->total_price;
                }
                return '';
            },
            'receive_time' => function($model) {
                $arr = MemberTakeMealRecord::find()
                    ->where(['id' => $model->id])
                    ->select('receive_time')
                    ->asArray()
                    ->one();
                if ($arr['receive_time']) {
                    return $arr['receive_time'];
                }
                return '';
            },
            'meal_code' => function($model) {
                $arr = MemberTakeMealRecord::find()
                    ->where(['id' => $model->id])
                    ->select('meal_code')
                    ->asArray()
                    ->one();
                if ($arr['meal_code']) {
                    return $arr['meal_code'];
                }
                return '';
            },
            'meal_date' => function($model) {
                $arr = MemberTakeMealRecord::find()
                    ->where(['id' => $model->id])
                    ->select('meal_date')
                    ->asArray()
                    ->one();
                if ($arr['meal_date']) {
                    return $arr['meal_date'];
                }
                return '';
            },
            'meal_time' => function($model) {
                $arr = MemberTakeMealRecord::find()
                    ->where(['id' => $model->id])
                    ->select('meal_time')
                    ->asArray()
                    ->one();
                if ($arr['meal_time']) {
                    return $arr['meal_time'];
                }
                return '';
            }
        ];
        return $fields;
    }
    /**
     * 会员端 - 第一次登录保存个人信息 - 验证规则
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/06/19
     */
    public function rules()
    {
        return [
            [['venueId','accountId','state','orderId','type'],'safe']
        ];
    }
    /**
     * 会员端 - API - 健身餐列表
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/09
     */
    public function getMealOrderList()
    {
        $query = Order::find()
            ->alias('o')
            ->joinWith(['member m'],FALSE)
            ->where(['m.member_account_id' => $this->accountId])
            ->andWhere(['consumption_type' => 'meal']);
        if (isset($this->state)) $query->andWhere(['o.status' => $this->state]);
        if ((isset($this->type)) && ($this->type == 'refund'))  $query->andWhere(['o.status' => [4,5,6]]);
        $query->orderBy(['o.id' => SORT_DESC]);
        return $query;
    }
    /**
     * 会员端 - API - 健身餐订单详情
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/09
     */
    public function getMealOrderDetail()
    {
        $arr = Order::find()
            ->alias('o')
            ->joinWith(['memberMealRecord mmd' => function($query) {
                $query->joinWith('organization or')
                      ->joinWith(['goods g' => function($q) {
                        $q->joinWith('imageManagement im')
                          ->joinWith('goodsSetting gs');
                }],FALSE);
            }],FALSE)
            ->joinWith(['memberTakeMealRecord mtmr'],FALSE)
            ->where(['o.id' => $this->orderId])
            ->select([
                'o.status',
                'o.pay_money_mode',
                'o.order_number',
                'o.order_time',
                'o.new_note',
                'or.name venueName',
                'gs.valid_time',
                'gs.valid_type',
                'im.url pic',
                'mmd.id',
                'mmd.name',
                'mmd.spec',
                'mmd.num',
                'mmd.unit_price',
                'mmd.total_price',
                'mtmr.meal_code',
                'mtmr.meal_date',
                'mtmr.meal_time'
            ])
            ->asArray()
            ->all();
        return $arr;
    }
    /**
     * 会员端 - API - 格式化返回取餐时间
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/09
     */
    public function getTakeMealTime()
    {
        $arr = $this->getGoodsSetting();
        $data = [];
        $mealTime = [];
        $mealDate = 0;
        if ($arr) {
            foreach ($arr as $k => $v) {
                //同一公司下的取餐时间一致
                $mealTime = $arr['0']['meal_time'];
                $mealDate = $arr['0']['preset_day'];
            }
        }
        for ($i = 0;$i <= $mealDate;$i++) {
            if ($i == 0) $i = $i + 1;
            $time[] = date('m-d',strtotime('+'.$i.'days'));
        }
        $mealTime = json_decode($mealTime,true);
        $data['mealTime'] = !empty($mealTime) ? $mealTime : [];
        foreach ($time as $k1 => $v1) {
            if (date('m-d') >= $v1){
                $data1 = $data['mealTime'];
                foreach ($data['mealTime'] as $k2 => $v2) {
                    $date = explode('-',$v2)['1'];
                    if (date('H:i') > $date) {
                        array_splice($data1,$k2,1);
                    }
                }
                $da[$k1]['mealDate'] = $v1;
                $da[$k1]['mealTime'] = $data1;
                if (empty($data1)) unset($da[$k1]);
            }else{
                $da[$k1]['mealDate'] = $v1;
                $da[$k1]['mealTime'] = $data['mealTime'];
            }
        }
        return array_values($da);
    }
    /**
     * 会员端 - API - 获取取餐时间
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/10
     */
    public function getGoodsSetting()
    {
        $arr = GoodsSetting::find()
            ->alias('gs')
            ->joinWith(['organization o'],FALSE)
            ->where(['gs.is_delete' => 0])
            ->select(['gs.meal_time','gs.preset_day'])
            ->asArray()
            ->all();
        return $arr;
    }
    /**
     * 会员端 - API - 退款
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/21
     */
    public function refundOrder()
    {
        $arr = $this->getOrderInfo($this->orderId);
        if ($arr['status'] <> 2) {
            return ['code' => '0','status' => 'error', 'message' => '对不起，只有已付款的才可以申请退款!'];
        }
        $data = Order::updateAll(
            ['status' => 4,'request_refund_money'=> $arr['total_price'],'apply_time'=>time()],
            ['id' => $this->orderId]
            );
        if ($data) {
            return ['code' => '1','status' => 'success', 'message' => '申请退款成功!'];
        }
        return ['code' => '0','status' => 'error', 'message' => '网络错误，请稍后重试!'];
    }
    /**
     * 会员端 - API - 取消订单
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/21
     */
    public function cancelOrder()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            Order::updateAll(['status' => 3],['id' => $this->orderId]);
            $time = date("Y-m-d H:i:s",time());
            MemberTakeMealRecord::updateAll(['cancle_time' => $time],['id' => $this->orderId]);
            $arr = $this->getInventoryLeft($this->orderId);
            if ($arr) {
                foreach ($arr as $k => $v) {
                    GoodsStandard::updateAll(
                        ['inventory_left' => $v['inventory_left'] + $v['num']],
                        ['standard' => $v['standard']]
                    );
                }
            }
            if ($transaction->commit() === NULL) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return  $e->getMessage();
        }
    }
    /**
     * 会员端 - API - 获取有限库存的商品
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/21
     */
    public function getInventoryLeft($orderId)
    {
        $arr = MemberMealRecord::find()
            ->alias('mmr')
            ->joinWith(['goodsStandard gs'])
            ->where(['mmr.order_id' => $orderId])
            ->andWhere(['gs.inventory_type' => 2])
            ->select(['mmr.num','gs.standard','gs.inventory_left'])
            ->groupBy('gs.standard')
            ->asArray()
            ->all();
        return $arr;
    }
    /**
     * 会员端 - API - 获取订单状态
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/21
     */
    public function getOrderInfo($orderId)
    {
        $arr = Order::find()
            ->where(['id' => $orderId])
            ->select(['status','total_price'])
            ->asArray()
            ->one();
        return $arr;
    }
}