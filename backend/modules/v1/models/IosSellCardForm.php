<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/9 0009
 * Time: 下午 2:18
 */

namespace backend\modules\v1\models;


use common\models\base\CardCategory;
use common\models\base\ConsumptionHistory;
use common\models\base\LimitCardNumber;
use common\models\base\Member;
use common\models\base\MemberDetails;
use common\models\base\Order;
use common\models\base\VenueLimitTimes;
use common\models\Func;
use yii\base\Model;

class IosSellCardForm extends Model
{
    public $memberId;               //会员id  (必传)
    public $cardCategoryId;         //卡种id  (必传)
    public $amountMoney;            //金额    (必传)
    public $payMethod;              //支付方式(必传)

    public $mobile;                 //会员手机号
    public $memberName;             //会员姓名
    public $venueId;                //会员场馆id
    public $companyId;              //公司id
    public $cardName;               //卡名称

    public function rules()
    {
        return [
            [['memberId','cardCategoryId','amountMoney','payMethod'],'required'],

        ];
    }

    /**
     * 云运动 - 售卡系统 - 获取会员手机号，姓名
     * @author huangpengju<huangpengju@itsports.club>
     * @create 2017/7/9
     */
    public function getMemberCardInfo()
    {
        $member          = Member::findOne(['id'=>$this->memberId]);
        $this->mobile    = $member->mobile;
        $memberDetails   = MemberDetails::findOne(['member_id'=>$this->memberId]);
        $this->memberName= $memberDetails->name;
    }
    /**
     * 云运动 - 售卡系统 - 存储会员卡
     * @author huangpengju<huangpengju@itsports.club>
     * @create 2017/7/9
     * @return array
     */
    public function saveMemberCard()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->getMemberCardInfo();
            $cardCategory                  = CardCategory::findOne(['id' => $this->cardCategoryId]);     //查出所选卡种的信息
            $this->venueId                 = $cardCategory->venue_id;
            $this->companyId               = $cardCategory->company_id;
            $time                          = json_decode($cardCategory->duration, true);                  //卡种有效期
            $leave                         = json_decode($cardCategory->leave_long_limit, true);          //卡种每次请假天数、请假次数
            $this->cardName                = $cardCategory->card_name;                   //卡名称
            $memberCard                    = new MemberCard();
            $memberCard->card_number       = (string)'0' . mt_rand(0, 10) . time();      //卡号
            $memberCard->create_at         = time();                                     //开卡时间
            $memberCard->amount_money      = $this->amountMoney;                         //金额
            $memberCard->status            = 4;                                          //状态：1正常，2异常，3冻结，4未激活
            $memberCard->payment_type      = 1;                                          //付款类型：1全款，2分期
            $memberCard->is_complete_pay   = 1;                                          //是否完成付款
            $memberCard->total_times       = $cardCategory->times;                       //总次数(次卡)
            $memberCard->consumption_times = $cardCategory->times;                       //消费次数
            $memberCard->invalid_time      = time() + ($time['day'] * 24 * 60 * 60);     //失效时间
            $memberCard->balance           = 0;                                          //余额
            $memberCard->level             = 1;                                          //等级
            $memberCard->card_category_id  = $this->cardCategoryId;                      //卡种id
            $memberCard->member_id         = $this->memberId;                            //会员ID
            $memberCard->present_money     = $cardCategory->recharge_give_price;         //买赠金额
            $memberCard->card_name         = $cardCategory->card_name;                   //卡名
            $memberCard->another_name      = $cardCategory->another_name;                //另一个卡名
            $memberCard->card_type         = $cardCategory->category_type_id;            //卡类别
            $memberCard->type              = $cardCategory->card_type;                   //卡分类
            $memberCard->count_method      = $cardCategory->count_method;                //计次方式
            $memberCard->attributes        = $cardCategory->attributes;                  //属性
            $memberCard->active_limit_time = $cardCategory->active_time;                 //激活期限
            $memberCard->transfer_num      = $cardCategory->transfer_number;             //转让次数
            $memberCard->surplus           = $cardCategory->transfer_number;             //剩余转让次数
            $memberCard->transfer_price    = $cardCategory->transfer_price;              //转让金额
            $memberCard->recharge_price    = $cardCategory->recharge_price;              //充值卡充值金额
            $memberCard->renew_price       = $cardCategory->renew_price;                 //续费价
            $memberCard->renew_best_price  = $cardCategory->offer_price;                 //续费优惠价
            $memberCard->renew_unit        = $cardCategory->renew_unit;                  //续费多长时间/天
            $memberCard->leave_total_days  = $cardCategory->leave_total_days;            //请假总天数
            $memberCard->leave_least_days  = $cardCategory->leave_least_Days;            //每次请假最少天数
            $memberCard->leave_days_times  = json_encode($leave);                        //每次请假天数、请假次数
            $memberCard->deal_id           = $cardCategory->deal_id;                     //合同id
            $memberCard->duration          = $time['day'];                               //有效期
            $memberCard->venue_id          = $this->venueId;                             //场馆id
            $memberCard->company_id        = $this->companyId;                           //公司id
            $memberCard = $memberCard->save() ? $memberCard : $memberCard->errors;       //生成会员卡记录
            if (!$memberCard) {
                return $memberCard->errors;
            }
            $consumption = $this->saveConsumption($memberCard);                           //生成消费历史记录
            if($consumption != true)
            {
                return $consumption;
            }
            $order = $this->saveOrder($memberCard);                                       //生成订单
            if($order != true)
            {
                return $order;
            }
            $limit = $this->saveLimit();                                                  //存储卡种剩余张数
            if($limit != true)
            {
                return $limit;
            }
            $venueLimit = $this->saveVenueLimit($memberCard);                             //存储进场次数
            if($venueLimit != true)
            {
                return $venueLimit;
            }
            if ($transaction->commit() === null) {
                return true;
            } else {
                return false;
            }
        }catch (\Exception $e)
        {
            $transaction->rollBack();
            return  $e->getMessage();
        }
    }
    /**
     * 云运动 - 售卡系统 - 存储消费记录表
     * @author huangpengju<huangpengju@itsports.club>
     * @create 2017/7/9
     * @param $memberCard       //购卡记录
     * @return array
     */
    public function saveConsumption($memberCard)
    {
        $consumption                      = new ConsumptionHistory();
        $consumption->member_id           = $this->memberId;           //会员id
        $consumption->consumption_type    = 'card';                    //消费类型
        $consumption->consumption_type_id = $memberCard->id;           //消费项目id
        $consumption->type                = 4;                         //消费方式
        $consumption->consumption_date    = time();                    //消费日期
        $consumption->consumption_amount  = $this->amountMoney;        //消费金额
        $consumption->consumption_time    = time();                    //消费时间
        $consumption->consumption_times   = 1;                         //消费次数
        $consumption->network_payment     = $this->amountMoney;        //网络付款
        $consumption->venue_id            = $this->venueId;            //场馆id
        $consumption->describe            = json_encode('办会员卡');   //消费描述
        $consumption->category            = '办卡';
        $consumption->company_id          = $this->companyId;          //公司id
        $consumption = $consumption->save() ? $consumption : $consumption->errors;
        if ($consumption) {
            return true;
        }else{
            return $consumption->errors;
        }
    }
    /**
     * 云运动 - 售卡系统 - 存储订单表
     * @author huangpengju<huangpengju@itsports.club>
     * @create 2017/6/14
     * @param $memberCard  //会员卡记录
     * @return array
     */
    public function saveOrder($memberCard)
    {

        $order                      = new Order();
        $order->venue_id           = $this->venueId;                                       //场馆id
        $order->company_id         = $this->companyId;                                     //公司id
        $order->member_id          = $this->memberId;                                      //会员id
        $order->card_category_id   = $memberCard->id;                                      //会员卡id
        $order->total_price        = $this->amountMoney;                                   //总价
        $order->order_time         = time();                                               //订单创建时间
        $order->pay_money_time     = time();                                               //付款时间
        $order->pay_money_mode     = $this->payMethod;                                     //付款方式
        $order->status             = 2;                                                    //订单状态：2已付款
        $order->note               = '售卡';                                               //备注
        $number                    = Func::setOrderNumber();
        $order->order_number       = "{$number}";                                          //订单编号
        $order->card_name          = $this->cardName;                                      //卡名称
        $order->member_name        =  $this->memberName;                                   //购买人姓名
        $order->pay_people_name    =  $this->memberName;                                   //付款人姓名
        $order = $order->save() ? $order : $order->errors;
        if ($order) {
            return true;
        }else{
            return $order->errors;
        }
    }
    /**
     * @云运动 - 售卡系统 - 判断卡种售卖张数
     * @author 黄鹏举 <huangpengju@itsports.club>
     * @create 2017/7/9
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
     * 云运动 - 售卡系统 - 存储卡种剩余张数
     * @author huangpengju<huangpengju@itsports.club>
     * @create 2017/5/23
     * @return array
     */
    public function saveLimit()
    {
        $limitCardNum = LimitCardNumber::findOne(['card_category_id' => $this->cardCategoryId]);
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
            return true;
        }else{
            return $limitCardNum->errors;
        }
    }
    /**
     * 云运动 - 售卡系统 - 存储进场次数核算表
     * @author huangpengju<huangpengju@itsports.club>
     * @create 2017/6/23
     * @param $memberCard  //会员卡记录
     * @return array
     */
    public function saveVenueLimit($memberCard)
    {
        $limit = LimitCardNumber::find()->where(['card_category_id' => $this->cardCategoryId])->asArray()->all();
        if(isset($limit)){
            foreach($limit as $k=>$v){
                $limitVenue                 = new VenueLimitTimes();
                $limitVenue->member_card_id = $memberCard->id;
                $limitVenue->level           = $memberCard->level;
                $limitVenue->venue_id       = $v['venue_id'];
                $limitVenue->total_times    = $v['times'];
                $limitVenue->overplus_times = $v['times'];
                $limitVenue->company_id     =  $this->companyId;
                if(!$limitVenue->save()){
                    return $limitVenue->errors;
                }
            }
            return true;
        }
        return true;
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
}