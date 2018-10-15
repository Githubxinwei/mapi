<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/7/8
 * Time: 14:46
 */

namespace backend\modules\v1\models;

use backend\models\ChargeClass;
use yii\base\Model;
use common\models\base\Order;
use common\models\base\Employee;
use common\models\base\VenueLimitTimes;
use common\models\Func;
use common\models\Goods;
use common\models\base\ConsumptionHistory;
use Yii;
use common\models\base\Member;
use common\models\base\MemberDetails;
use common\models\base\MemberCard;
use common\models\base\CardCategory;
use common\models\base\LimitCardNumber;

class PaymentForm extends Model
{
    public $memberId;               //姓名
    public $cardCateGoryId;        //卡种
    public $paymentType;           //付款方式
    public $payTimes;              //分期月数
    public $firstPayMonths;       //首付月数
    public $firstPayPrice;        //首付金额
    public $monthPrice;           //每月还款金额
    public $amountMoney;          //总金额
    public $payMethod;            //付款方式
    public $typeId;
    public $type;
    public $cardName;
    public $name;
    public $saleMan;
    public $mobile;
    public $num;
    public $coachId;               // 教练id
    public $sign;                  //签名
    const CODE   = 'code';
    const NOTICE = '操作失败';
    public $isNotMachinePay;
    public $companyId;                   //公司ID
    public $venueId;                   //场馆ID
    public $spec;                       //商品规格sepc = [{'goods_id' => '','sepcName ' => '','num' => ''},...]
    public $total_price;                //商品总价
    public $order_note;                //订单备注
    public $mealDate;                  //取餐日期
    public $mealTime;                  //取餐时间段
    public $orderId;                   //订单id
    public $secretKey = 'xingfufit';    //加密密码
    /**
     * 云运动 - 售卡系统 - 售卡表单规则验证
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/5/13
     * @return array
     */
    public function rules()
    {
        return [
            /*['typeId', 'required', 'message' => '请选择商品'],
            ['memberId', 'required', 'message' => '请选择会员'],
            ['paymentType', 'required', 'message' => '请选择付款方式'],*/
            [['typeId','memberId','paymentType','orderId','mealDate','mealTime','companyId','venueId','spec','total_price','order_note','payTimes', 'firstPayMonths','num', 'typeId','type','firstPayPrice', 'monthPrice', 'amountMoney','idCard','payMethod',"coachId",'sign'], 'safe'],
        ];
    }

    /**
     * @云运动 - 售卡系统 - 判断卡种售卖张数
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/5/22
     * @inheritdoc
     */
    public function setSellNum($cardCateGoryId){
        if($cardCateGoryId){
            $limitCardNum = LimitCardNumber::findOne(['card_category_id' => $cardCateGoryId]);
            if($limitCardNum['limit'] == -1){
                return true;
            }else{
                $num = $limitCardNum['surplus'];
                if($num <= 0){
                    return '此卡种已售卖完，请选择其他卡种';
                }else{
                    return true;
                }
            }
        }
    }

    /**
     * @云运动 - 售卡系统 - 存储数据
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/5/15
     * @inheritdoc
     */
    public function setSellCard($companyId,$venueId)
    {
        $cardCategory  = CardCategory::findOne(['id' => $this->cardCateGoryId]);    //查出所选卡种的信息
        $time          = json_decode($cardCategory->duration,true);                  //卡种有效期
        $leave         = json_decode($cardCategory->leave_long_limit,true);         //卡种每次请假天数、请假次数
        $member        = Member::findOne(['id' => $this->memberId]);               //查询所填手机号的会员信息
        $memberDetail  = MemberDetails::findOne(['member_id'=>$this->memberId]);
        if(isset($member)){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $member->member_type                      = 1;
                $member = $member->save() ? $member : $member->errors;
                if(!isset($member->id)){
                    throw new \Exception(self::NOTICE);
                }
                if($memberDetail && isset($memberDetail->name)){
                    $this->name = $memberDetail->name;
                }else{
                    $this->name = $member->username;
                }
                $memberCard = $this->saveMemberCard($member,$time,$cardCategory,$leave,$companyId,$venueId);
                if(!isset($memberCard->id)){
                    throw new \Exception(self::NOTICE);
                }

                $consumption = $this->saveConsumption($member,$memberCard,$companyId,$venueId);
                if(!isset($consumption->id)){
                    throw new \Exception(self::NOTICE);
                }

                $order = $this->saveOrder($member,$memberCard,$cardCategory,$companyId,$venueId);
                if(!isset($order->id)){
                    throw new \Exception(self::NOTICE);
                }

                $limit = $this->saveLimit();
                if(!isset($limit->id)){
                    throw new \Exception(self::NOTICE);
                }

                $limit = $this->saveVenueLimit($memberCard,$cardCategory);
                if($limit !== true){
                    throw new \Exception(self::NOTICE);
                }

                if ($transaction->commit() === null) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                //如果抛出错误则进入catch，先callback，然后捕获错误，返回错误
                $transaction->rollBack();
                return  $e->getMessage();
            }
        }
        return false;
    }

    /**
     * 云运动 - 售卡系统 - 存储会员详情表
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/5/19
     * @return array
     */
    public function saveMemberDetails($member)
    {
        $memberDetails                        = new MemberDetails();
        $memberDetails->member_id            = $member->id;
        $memberDetails->name                  = $this->name;
        $memberDetails->id_card              = $this->idCard;
        $memberDetails->sex                   = $this->sex;
        $memberDetails->recommend_member_id = 0;
        $memberDetails->created_at           = time();
        $memberDetails = $memberDetails->save() ? $memberDetails : $memberDetails->errors;
        if ($memberDetails) {
            return $memberDetails;
        }else{
            return $memberDetails->errors;
        }
    }

    /**
     * 云运动 - 售卡系统 - 存储会员卡
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/5/19
     * @return array
     */
    public function saveMemberCard($member,$time,$cardCategory,$leave,$companyId,$venueId)
    {
        $memberCard                       = new MemberCard();
        $memberCard->member_id           = $member->id;                       //会员ID
        $memberCard->card_category_id   = $this->cardCateGoryId;            //卡种
        $memberCard->payment_type       = $this->paymentType;                //付款方式
        $memberCard->pay_times          = $this->payTimes;                   //分期月数
        $memberCard->paid_months        = $this->firstPayMonths;             //已付月数
        $memberCard->first_pay_price   = $this->firstPayPrice;              //首付金额
        $memberCard->month_price       = $this->monthPrice;                  //每月还款金额
        $memberCard->amount_money      = $this->amountMoney;                 //总金额
        $memberCard->employee_id       = $this->saleMan;                      //销售
        $memberCard->create_at         = time();                               //售卡时间
        $memberCard->level              = 1;                                    //等级
        $memberCard->card_number       = (string)'0'.mt_rand(0,10).time();    //卡号
        if($this->paymentType == 1 || $this->paymentType == 2){
            $memberCard->is_complete_pay  = 1;                                     //完成付款
        }else{
            $memberCard->is_complete_pay  = 0;                                     //未完成付款
        }
        $memberCard->invalid_time       = time()+($time['day']*24*60*60);          //失效时间
        if($cardCategory->category_type_id == 3){
            $memberCard->balance         = $cardCategory->recharge_price + $cardCategory->recharge_give_price;
        }else{
            $memberCard->balance         = 0;                                      //余额
        }
        $memberCard->total_times        = $cardCategory->times;                   //总次数(次卡)
        $memberCard->consumption_times = $cardCategory->times;                   //消费次数
        $memberCard->card_name          = $cardCategory->card_name;              //卡名
        $memberCard->another_name       = $cardCategory->another_name;          //另一个卡名
        $memberCard->card_type          = $cardCategory->category_type_id;      //卡类别
        $memberCard->count_method       = $cardCategory->count_method;          //计次方式
        $memberCard->attributes         = $cardCategory->attributes;             //属性
        $memberCard->active_limit_time = $cardCategory->active_time;            //激活期限
        $memberCard->transfer_num       = $cardCategory->transfer_number;       //转让次数
        $memberCard->surplus            = $cardCategory->transfer_number;       //剩余转让次数
        $memberCard->transfer_price     = $cardCategory->transfer_price;        //转让金额
        $memberCard->recharge_price     = $cardCategory->recharge_price;        //充值卡充值金额
        $memberCard->present_money      = $cardCategory->recharge_give_price;  //买赠金额
        $memberCard->renew_price        = $cardCategory->renew_price;           //续费价
        $memberCard->renew_best_price   = $cardCategory->offer_price;          //续费优惠价
        $memberCard->renew_unit         = $cardCategory->renew_unit;            //续费多长时间/天
        $memberCard->leave_total_days   = $cardCategory->leave_total_days;     //请假总天数
        $memberCard->leave_least_days   = $cardCategory->leave_least_Days;     //每次请假最少天数
        $memberCard->leave_days_times   = json_encode($leave);                   //每次请假天数、请假次数
        $memberCard->deal_id             = $cardCategory->deal_id;               //合同id
        $memberCard->duration            = $time['day'];                         //有效期
        $memberCard->venue_id            = $venueId;                              //场馆id
        $memberCard->company_id          = $companyId;                            //公司id
        $memberCard = $memberCard->save() ? $memberCard : $memberCard->errors;
        if ($memberCard) {
            $this->cardName              = $cardCategory->card_name;
            return $memberCard;
        }else{
            return $memberCard->errors;
        }
    }

    /**
     * 云运动 - 售卡系统 - 存储消费记录表
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/5/23
     * @return array
     */
    public function saveConsumption($member,$memberCard,$companyId,$venueId)
    {
        $consumption                        = new ConsumptionHistory();
        $consumption->member_id            = $member->id;               //会员id
        $consumption->consumption_type    = 'card';                    //消费类型
        $consumption->type                 = 1;                          //消费方式
        $consumption->consumption_type_id = $memberCard->id;           //消费项目id
        $consumption->consumption_date    = time();                    //消费日期
        $consumption->consumption_amount  = $this->amountMoney;       //消费金额
        $consumption->consumption_time    = time();                    //消费时间
        $consumption->consumption_times   = 1;                         //消费次数
        $consumption->cash_payment        = $this->amountMoney;       //现金付款
        $consumption->venue_id             = $venueId;                 //场馆id
        $consumption->seller_id            = $this->saleMan;          //销售员id
        $consumption->describe             = json_encode('办会员卡'); //消费描述
        $consumption->category             = '办卡';
        $consumption->company_id           = $companyId;              //公司id
        $consumption = $consumption->save() ? $consumption : $consumption->errors;
        if ($consumption) {
            return $consumption;
        }else{
            return $consumption->errors;
        }
    }

    /**
     * 云运动 - 售卡系统 - 存储订单表
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/6/14
     * @return array
     */
    public function saveOrder($member,$memberCard,$cardCategory,$companyId,$venueId)
    {
        $saleName   = Employee::findOne(['id' => $this->saleMan]);
        if(isset(\Yii::$app->user->identity->id)){
            $adminModel = Employee::findOne(['admin_user_id'=>\Yii::$app->user->identity->id]);
        }else{
            $adminModel = [];
        }
        $order                      = new Order();
        $order->venue_id           = $venueId;                                              //场馆id
        $order->company_id         = $companyId;                                           //公司id
        $order->member_id          = $member->id;                                          //会员id
        $order->card_category_id   = $memberCard->id;                                     //会员卡id
        $order->total_price        = $this->amountMoney;                                  //总价
        $order->order_time         = time();                                               //订单创建时间
        $order->pay_money_time     = time();                                               //付款时间
        $order->pay_money_mode     = $this->payMethod;                                    //付款方式
        $order->sell_people_id     = intval($this->saleMan);                              //售卖人id
        $order->create_id          = isset($adminModel->id)?intval($adminModel->id):0;    //操作人id
        $order->status             = 2;                                                     //订单状态：2已付款
        $order->note               = '售卡';                                                //备注
        $number                    = Func::setOrderNumber();
        $order->order_number      = "{$number}";                                           //订单编号
        $order->card_name         = $cardCategory->card_name;                              //卡名称
        $order->sell_people_name  = isset($saleName['name'])?$saleName['name']:null;                                     //售卖人姓名
        $order->member_name       = $this->name;                                           //购买人姓名
        $order->pay_people_name   = $this->name;                                           //付款人姓名
        $order = $order->save() ? $order : $order->errors;
        if ($order) {
            return $order;
        }else{
            return $order->errors;
        }
    }

    /**
     * 云运动 - 售卡系统 - 存储卡种剩余张数
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/5/23
     * @return array
     */
    public function saveLimit()
    {
        $limitCardNum = LimitCardNumber::findOne(['card_category_id' => $this->cardCateGoryId]);
        if($limitCardNum['limit'] == -1){
            $limitCardNum->surplus = -1;
        }else{
            if($limitCardNum['surplus'] <= 0){
                $limitCardNum->surplus = 0;
            }else{
                $limitCardNum->surplus = $limitCardNum['surplus'] - 1;
            }
        }
        $limitCardNum = $limitCardNum->save() ? $limitCardNum : $limitCardNum->errors;
        if ($limitCardNum) {
            return $limitCardNum;
        }else{
            return $limitCardNum->errors;
        }
    }

    /**
     * 云运动 - 售卡系统 - 存储进场次数核算表
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/6/23
     * @return array
     */
    public function saveVenueLimit($memberCard,$cardCategory)
    {
        $limit = LimitCardNumber::find()->where(['card_category_id' => $this->cardCateGoryId])->asArray()->all();
        if(isset($limit)){
            foreach($limit as $k=>$v){
                $limitVenue = new VenueLimitTimes();
                $limitVenue->member_card_id = $memberCard->id;
                $limitVenue->venue_id       = $v['venue_id'];
                $limitVenue->total_times    = $v['times'];
                $limitVenue->overplus_times = $v['times'];
                $limitVenue->company_id     = $cardCategory->company_id;
                if(!$limitVenue->save()){
                    return $limitVenue->errors;
                }
            }
            return true;
        }
        return true;
    }

    /**
     * 云运动 - 售卡系统 - 获取大上海售卖的卡种
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/5/15
     * @param  $venueId
     * @return array
     */
    public static function getCardCategory($venueId)
    {
        $limit = LimitCardNumber::find()->filterWhere(['venue_id' => !empty($venueId) ? $venueId : null])->andWhere(['>', 'sell_end_time', time()])->asArray()->all();
        $array = [];
        foreach ($limit as $arr) {
            $cardCategory = CardCategory::find()->where(['id' => $arr['card_category_id'], 'status' => 1])->asArray()->one();
            if($cardCategory){
                $array[] = $cardCategory;
            }
        }
        return $array;
    }

    /**
     * 云运动 - 售卡管理 - 售卡成功发送短信
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/5
     * @return array
     */
    public function sendMessage()
    {
        $data = $this->mobile;
        $info = '尊敬的会员，您已成功办理我店会员卡';

        Func::sendSellCardInfo($data,$info);
        return ['status'=>'success','data'=>$info];
    }

    /**
     * 云运动 - 售卡管理 - 根据名称查询卡种
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/6
     * @return array
     */
    public function getAllCard($id,$type)
    {
        $data         = CardCategory::find()->alias("cc")->select('id,name')->orderBy($this->sorts)->asArray();
        $query        = $this->setWhereSearch($data,$id,$type);
        return $query;
    }

    /**
     * @云运动 - 售卡管理 - 卡种 处理搜索条件
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/6
     * @param $query
     * @return mixed
     */
    public function setWhereSearch($query,$id,$type)
    {
        $query->andFilterWhere([
            'like','cc.name',$this->keywords,
        ]);
        if($type && $type == 'company'){
            $query->andFilterWhere(['cc.company_id'=>$id]);
        }
        if($type && ($type == 'venue' || $type == 'department')){
            $query->andFilterWhere(['cc.venue_id'=>$id]);
        }
        return $query;
    }
    /**
     * 会员端 - API - 健康餐取餐记录
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/11
     */
    public function saveMemberTakeMealRecord($orderId)
    {
        $model = new \common\models\base\MemberTakeMealRecord();
        $model->oper_id = $this->memberId;
        $model->meal_code = strval(Func::getTakeMealCode());
        $model->code_status = 1;
        $model->meal_date = date("Y")."-".$this->mealDate;
        $model->meal_time = $this->mealTime;
        $model->is_evalaute = 0;
        $model->id = $orderId;
        if (!$model->save()) {
            return ['code' => '0','status' => 'error', 'message' => '网络错误请稍后重试!'];
        }
        return true;
    }
    /**
     * 会员端 - API - 健康餐商品信息
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/11
     */
    public function getMealInfo($goods_id,$spec = null)
    {
        $arr = Goods::find()
            ->alias('g')
            ->joinWith(['goodsStandard gs' => function ($q) use ($goods_id,$spec) {
                if (isset($spec)) {
                    $q->where(['gs.goods_id' => $goods_id,'gs.standard' => $spec]);
                } else {
                    $q->where(['gs.goods_id' => $goods_id]);
                }
            }],FALSE)
            ->joinWith(['goodsSetting gst'],FALSE)
            ->andWhere(['g.shelf_type' => 1])
            ->andWhere(['gs.status' => 1])
            ->select([
                'g.goods_name',
                'g.standardSet',
                'gs.price',
                'gs.id',
                'gs.inventory_type',
                'gs.inventory_left',
                'gst.preset_time_begin',
                'gst.preset_time_end'
            ])
            ->asArray()
            ->one();
        return $arr;
    }
    /**
     * 会员端 - API - 健康餐预约记录
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/11
     */
    public function saveMemberMealRecord($orderId)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $spec_array = json_decode($this->spec,true);
            $arr = [];
            foreach ($spec_array as $k => $v) {
                if (empty($v['goods_id'])) {
                    return ['code' => '0','status' => 'error', 'message' => '请选择商品!'];
                }
                if (!isset($v['specName'])) $v['specName'] = null;
                $mealInfo = $this->getMealInfo($v['goods_id'],$v['specName']);
                if (empty($mealInfo)) {
                    return ['code' => '0','status' => 'error', 'message' => '商品不存在!'];
                }
                if ($mealInfo['inventory_type'] == 2) {
                    if ($v['num'] > $mealInfo['inventory_left']) {
                        return ['code' => '0','status' => 'error', 'message' => '商品库存不足，剩余'.$mealInfo['inventory_left'].'件!'];
                    }
                    $mealInfo['inventory_left'] -= $v['num'];
                    \common\models\GoodsStandard::updateAll(['inventory_left' => $mealInfo['inventory_left']], ['id' => $mealInfo['id']]);
                }
                $standard = json_decode($mealInfo['standardSet'],true);
                $spec = '';
                if (isset($v['specName'])) {
                    $arr_specName = explode(" ",$v['specName']);
                    if ($standard) {
                        foreach ($standard as $key => $value) {
                            $spec .= $value['key'].'：'.$arr_specName[$key].' ';
                        }
                    }
                }
                $arr[$k][] = $this->companyId;
                $arr[$k][] = $this->venueId;
                $arr[$k][] = $this->memberId;
                $arr[$k][] = $v['goods_id'];
                $arr[$k][] = $orderId;
                $arr[$k][] = $mealInfo['goods_name'];
                $arr[$k][] = $spec;
                $arr[$k][] = $mealInfo['price'];
                $arr[$k][] = $mealInfo['price']*$v['num'];
                $arr[$k][] = $v['num'];
            }
            $query = Yii::$app->db->createCommand();
            $sql = $query->batchInsert('{{cloud_member_meal_record}}',
                ['company_id','venue_id','member_id','goods_id','order_id','name','spec','unit_price','total_price','num'],
                $arr);
            $sql->execute();
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
     * 会员端 - API - 支付宝待支付再支付更新支付方式
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/26
     */
    public function updateAliPayMoneyMode($orderId,$payMoneyMode = 2)
    {
        $data = \common\models\Order::updateAll(
            ['pay_money_mode' => $payMoneyMode],
            ['id' => $orderId]
        );
        return $data;
    }
    /**
     * 会员端 - API - 微信待支付再支付更新订单号和支付方式
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/26
     */
    public function updateOrderNumber($orderNumber,$orderId,$payMoneyMode = 3)
    {
        $data = \common\models\Order::updateAll(
            ['order_number'=>$orderNumber,'pay_money_mode' => $payMoneyMode],
            ['id' => $orderId]
            );
        return $data;
    }
    /**
     * 会员端 - API - 获取订单字段
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/20
     */
    public function getOrderInfo($orderId)
    {
        $arr = Order::find()
            ->where(['id' => $orderId])
            ->select(['id','order_number','total_price','consumption_type'])
            ->asArray()
            ->one();
        return $arr;
    }
    /**
     * 会员端 - API - 获取支付参数
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/07/20
     */
    public function getPayParams()
    {
        $arr = Order::find()
            ->where(['id' => $this->orderId])
            ->select(['total_price price','pay_params'])
            ->asArray()
            ->one();
        if ($arr) {
            $arr['pay_params'] = \Yii::$app->security->decryptByPassword($arr['pay_params'],$this->secretKey);
            return $arr;
        }
        return NULL;
    }
    public function paymentCard()
    {
        if($this->type == 'charge'){
            $cardCategory  = ChargeClass::findOne(['id' => $this->typeId]);    //查出所选卡种的信息
        }elseif($this->type == 'chargeGroup'){//私教小团体课
            $cardCategory = ChargeClassNumber::findOne($this->typeId);
        }elseif($this->type == 'meal'){//健康餐
            $cardCategory = '';
        }else{
            if(!empty($this->cardCateGoryId)){
                $this->typeId = $this->cardCateGoryId;
            }
            $cardCategory  = CardCategory::findOne(['id' => $this->typeId]);    //查出所选卡种的信息
        }
        $member        = Member::findOne(['id' => $this->memberId]);               //查询所填手机号的会员信息
        if(empty($member)){
            return ['data'=>'会员不存在'];
        }
        if ($this->type == 'meal') {
            $companyId     = $this->companyId;
            $venueId       = $this->venueId;
            $spec_array = json_decode($this->spec,true);
            $time = strtotime(date('H:i',time()));
            foreach ($spec_array as $k => $v) {
                if (!isset($v['specName'])) $v['specName'] = null;
                $mealInfo = $this->getMealInfo($v['goods_id'], $v['specName']);
                $presetTimeBegin = strtotime($mealInfo['preset_time_begin']);
                $presetTimeEnd = strtotime($mealInfo['preset_time_end']);
                if (($time < $presetTimeBegin) || ($time > $presetTimeEnd))
                return ['code' => '0', 'status' => 'error', 'message' => '对不起,商品' . $mealInfo['goods_name'] . '只能在' . $mealInfo['preset_time_begin'] . '-' . $mealInfo['preset_time_end'] . '时间段内预约'];
            }
        } else {
            $companyId     = $member->company_id;
            $venueId       = $member->venue_id;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $order = $this->savePreOrder($member,$cardCategory,$companyId,$venueId);
            if(isset($order->id)){
                $this->mobile = $member->mobile;
            }
            if ($this->type == 'meal') {
                $orderId = $order->id;
                $memberMealRecord = $this->saveMemberMealRecord($orderId);
                if (is_array($memberMealRecord)) {
                    return $memberMealRecord;
                }
                $memberTakeMealRecord = $this->saveMemberTakeMealRecord($orderId);
                if (is_array($memberTakeMealRecord)) {
                    return $memberTakeMealRecord;
                }
            }
            if ($transaction->commit() === NULL) {
                return $order;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return  $e->getMessage();
        }
    }
    /**
     * 云运动 - 售卡系统 - 存储订单表
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/6/14
     * @return array
     */
    public function savePreOrder($member,$cardCategory,$companyId,$venueId)
    {
        if ($this->type == 'meal') {
            $saleName['name']   = NULL;
        } else {
            $saleName   = Employee::findOne(['id' => $this->saleMan]);
        }
        if(isset(\Yii::$app->user->identity->id)&&empty($this->isNotMachinePay)){
            $adminModel = Employee::findOne(['admin_user_id'=>\Yii::$app->user->identity->id]);
        }else{
            $adminModel = [];
        }
        if($this->type != 'charge'){        //邢老师说的 现在订单金额全部定位最高价格（2017-11-03）
//            if(!empty($cardCategory->sell_price)){
//                $endResultPrice = ($cardCategory->sell_price>$cardCategory->original_price)?$cardCategory->sell_price:$cardCategory->original_price;  // 卡最终价格
//                $this->amountMoney =$endResultPrice;
//            }else{
//                $endResultPrice = ($cardCategory->max_price>$cardCategory->min_price)?$cardCategory->max_price:$cardCategory->min_price;    // 区间价 （最高价格）
//                $this->amountMoney = $endResultPrice;
//            }
            $this->amountMoney = isset($cardCategory->app_sell_price)?$cardCategory->app_sell_price:0;
        }
        $order                      = new Order();
        $order->venue_id           = !empty($venueId)?$venueId:0;                                              //场馆id
        $order->company_id         = !empty($companyId)?$companyId:0;                                           //公司id
        $order->member_id          = $member->id;                                          //会员id
        $order->consumption_type   = ($this->type=="machineCard")?"card":$this->type;  // 消费类型修正;
        $order->consumption_type_id = $this->typeId;
        $order->source               = 4;
        if($this->type == 'charge'){
            $order->card_category_id   = 0;    //会员卡id
            $order->note                 = "app私课购买";
            $order->purchase_num        = $this->num;
            if(!empty($this->coachId)){
               $order->new_note = (string)$this->coachId;
            }
        }elseif($this->type == 'machineCard'){
            $order->card_category_id   = $cardCategory->id;
            $order->note                 = "迈步一体机办卡";
            $order->purchase_num        = 1;
        }elseif($this->type == 'chargeGroup'){
            $order->card_category_id   = 0;
            $order->note                 = "app私教小团体课购买";
            $order->purchase_num        = 1;
            if(!empty($this->coachId)){
                $order->new_note = (string)$this->coachId;
            }
            $this->amountMoney = $cardCategory->price;
            //$this->amountMoney = '0.01';
        }elseif($this->type == 'meal'){
            $order->card_category_id   = 0;
            $order->note                 = "app健身餐购买";
            $order->purchase_num         = $this->num;
            $order->new_note             = $this->order_note;
            $this->amountMoney = isset($this->total_price) ? $this->total_price : 0;
        }else{
            $order->card_category_id   = $cardCategory->id;
            $order->note                 = "app办卡";
            $order->purchase_num        = 1;
        }
        $order->total_price        = $this->amountMoney;                                  //总价
        $order->order_time         = time();                                               //订单创建时间
        $order->pay_money_time     = time();                                               //付款时间
        if($this->paymentType == 'wx'){
            $order->pay_money_mode     = 3; //付款方式  微信付款
        }elseif($this->paymentType=="zfbScanCode" || $this->paymentType == 'alipay'){
            $order->pay_money_mode     = 2; //支付宝付款
        }else{
            $order->pay_money_mode     = 4; //付款方式
        }
        if ($this->type == 'meal') {
            $order->sell_people_id     = 0;                                                      //售卖人id
        } else {
            $order->sell_people_id     = intval($this->saleMan);                              //售卖人id
        }
        $order->create_id          = isset($adminModel->id)?intval($adminModel->id):0;    //操作人id
        $order->status             = 1;                                                     //订单状态：2已付款
        $number                    = Func::setOrderNumber();
        $order->order_number      = "{$number}";                                           //订单编号
        $order->card_name         = isset($cardCategory->card_name)?$cardCategory->card_name:'';                              //卡名称
        $order->sell_people_name  = isset($saleName['name'])?$saleName['name']:null;                                     //售卖人姓名
        $order->member_name       = $member->username;                                           //购买人姓名
        $order->pay_people_name   = $member->username;                                           //付款人姓名
        if($this->sign) $order->sign = $this->sign;//签名
        $order = $order->save() ? $order : $order->errors;
        if ($order) {
            return $order;
        }else{
            return $order->errors;
        }
    }
    /**
     * 云运动 - 会员入会 - 获取迈步配置参数(网页支付)
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/10/28
     * @return array
     */
    public function getMbScanConfig(){
        $config = array (
            //应用ID,您的APPID。
            'app_id' => "2017091108675479",

            //商户私钥
            'merchant_private_key' =>"MIICXgIBAAKBgQC4yBrNmztd7TJTeRkzs6XW32pv4Sq8Uu5SouqZ0Zvtn1v8hedODcdeJp/xdLD+bB0KcwQH7WYs1b7OIp3wMW3w0MDmBh/hpbib2wZoWsK198EY+rAf55VaGduwIB2u1/RemLIgH583z+5P2BQr+SRPoV0LcI+51HD8TFlbBQ9FLQIDAQABAoGAQX/1OFLBXY8aGsq/aztQGvXBJf18B5uiDTrgzDun+ThXBBF3J4zs0ewBIDcMEnPCa5TPpfu6D0SJSUquD5mF7Y+ccAR6wI6igpaCqWCWgx/X7FbvKhBGjOm2pfPqck6S6zQOof906BcA3hBJOkODZPLOoo+rXpqwTi3RcV8GweECQQDoJXrt75vNI3utkkk7P3MhHOFvD81g3vYw6wPsx47cMyOnYYgya0gJ2o3U0DtUIFieewdACxoYVBfkQcsMewaFAkEAy8S2pZi9X4H4jN71mQ/X40UD6IziL5sD3qyvNrcQM/ErLHLacNtjB8cGy+B7C2ozfEf3lF5tLO8jvy9zfaQoiQJBANsN6ua0b67t6ZmKbUHUCH5ZczvKjID5QxQ735NBZ0PPmbgq50q0QuDRc346E5G5iAXbj6bWEwSb7YN8te4L9MUCQQCVJulWr0W2uikX3D/DiQBKgAMLXsxVck9T1+zszPTUQGyMvYk9YKjNUZac9zS5t0P2batAdBnP8T+mOvJ7fgSZAkEA5qqxzlw6CKKJzYG5i1HiOKQ+X38SuN8UCsvyzCYbszYftnElQ1M8c9p5NeNiiUd0oMVrgcXpBEzbhOhMa3BRZQ==",

            //异步通知地址
            'notify_url' => "http://product.aixingfu.net/member-ship-pay/ali-notify",

            //同步跳转
            'return_url' => "http://product.aixingfu.net/member-ship/index",

            //编码格式
            'charset' => "UTF-8",

            //签名方式
            'sign_type'=>"RSA",

            //支付宝网关
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB",
        );
        return $config;
    }
    /**
     * 云运动 - 会员入会 - 获取迈步配置参数(扫码支付)
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/10/28
     * @return array
     */
    public function getMb1ScanConfig(){
        $config = array (
            //应用ID,您的APPID。
            'app_id' => "2017091108675479",

            //商户私钥
            'merchant_private_key' =>"MIICXgIBAAKBgQC4yBrNmztd7TJTeRkzs6XW32pv4Sq8Uu5SouqZ0Zvtn1v8hedODcdeJp/xdLD+bB0KcwQH7WYs1b7OIp3wMW3w0MDmBh/hpbib2wZoWsK198EY+rAf55VaGduwIB2u1/RemLIgH583z+5P2BQr+SRPoV0LcI+51HD8TFlbBQ9FLQIDAQABAoGAQX/1OFLBXY8aGsq/aztQGvXBJf18B5uiDTrgzDun+ThXBBF3J4zs0ewBIDcMEnPCa5TPpfu6D0SJSUquD5mF7Y+ccAR6wI6igpaCqWCWgx/X7FbvKhBGjOm2pfPqck6S6zQOof906BcA3hBJOkODZPLOoo+rXpqwTi3RcV8GweECQQDoJXrt75vNI3utkkk7P3MhHOFvD81g3vYw6wPsx47cMyOnYYgya0gJ2o3U0DtUIFieewdACxoYVBfkQcsMewaFAkEAy8S2pZi9X4H4jN71mQ/X40UD6IziL5sD3qyvNrcQM/ErLHLacNtjB8cGy+B7C2ozfEf3lF5tLO8jvy9zfaQoiQJBANsN6ua0b67t6ZmKbUHUCH5ZczvKjID5QxQ735NBZ0PPmbgq50q0QuDRc346E5G5iAXbj6bWEwSb7YN8te4L9MUCQQCVJulWr0W2uikX3D/DiQBKgAMLXsxVck9T1+zszPTUQGyMvYk9YKjNUZac9zS5t0P2batAdBnP8T+mOvJ7fgSZAkEA5qqxzlw6CKKJzYG5i1HiOKQ+X38SuN8UCsvyzCYbszYftnElQ1M8c9p5NeNiiUd0oMVrgcXpBEzbhOhMa3BRZQ==",

            //异步通知地址
            'notify_url' => "http://product.aixingfu.net/member-ship-pay/ali-notify",

            //最大查询重试次数
            'MaxQueryRetry' => "10",

            //查询间隔
            'QueryDuration' => "3",

            //编码格式
            'charset' => "UTF-8",

            //签名方式
            'sign_type'=>"RSA",

            //支付宝网关
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB",
        );
        return $config;
    }




    /**
     * 云运动 - 会员入会 - 获取迈步公钥
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/10/28
     * @return string
     */
    public function getPublicKey(){
         $data       = $this->getMbScanConfig();
         $publicKey  = $data["alipay_public_key"];
         return $publicKey;
    }
}