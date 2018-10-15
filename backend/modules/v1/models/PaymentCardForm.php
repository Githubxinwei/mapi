<?php
namespace backend\modules\v1\models;

use backend\models\ChargeClass;
use backend\models\Employee;
use backend\models\Goods;
use common\models\base\BindMemberCard;
use common\models\base\BindPack;
use common\models\base\EntryRecord;
use common\models\base\MessageCode;
use common\models\base\Order;
use backend\models\Deal;
use common\models\base\VenueLimitTimes;
use common\models\Func;
use common\models\base\Organization;
use common\models\base\ConsumptionHistory;
use common\models\base\CardCategory;
use common\models\base\LimitCardNumber;
use common\models\base\MemberDetails;
use common\models\base\MemberCard;
use common\models\base\Member;
use common\models\base\MemberDealRecord;
use yii\base\Model;
use Yii;

class PaymentCardForm extends Model
{
    public $name;          //姓名
    public $idCard;        //身份证号
    public $idAddress;     //身份证住址
    public $nowAddress;    //现居地
    public $mobile;        //手机号
    public $code;          //填写的验证码
    public $newCode;       //生成的验证码
    public $cardId;        //卡种id
    public $seller;        //销售ID
    public $payMoneyMode;
    public $giftStatus;
    public $sourceId;
    public $venueId;
    public $companyId;
    public $cardName;
    public $memberCardId;
    public $url;
    public $birthDay;
    public $price;
    public $amountMoney;
    public $data = [];
    public $merchantOrderNumber;
    public $chargeName;                //私教课程名称
    public $type;                      //类型
    public $chargeClassId;             //私教ID
    public $memberDealRecordId;         //合同记录id
    const CODE   = 'code';
    const NOTICE = '操作失败';

    /**
     * 云运动 - 售卡系统 - 售卡表单规则验证
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/6/6
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required', 'message' => '请填写姓名'],

            ['idCard', 'trim'],
            ['idCard', 'required', 'message' => '请填写身份证号'],

            ['idAddress', 'trim'],
            ['idAddress', 'required', 'message' => '请填写身份证住址'],

            ['nowAddress', 'trim'],
            ['nowAddress', 'required', 'message' => '请填写现居地'],

            ['mobile', 'trim'],
            ['mobile', 'required', 'message' => '请填写手机号'],
//            ['mobile', 'setMobile'],

//            [self::CODE, 'trim'],
//            [self::CODE, 'required', 'message' => '请填写验证码'],
//            [self::CODE, 'compare', 'compareAttribute' => 'newCode', 'message' => '验证码错误'],
//            [self::CODE, 'newCodeTime'],

//            ['cardId', 'required', 'message' => '请选择会员卡'],
            [['venueId','companyId','num','cardId','birthDay','seller','payMoneyMode','giftStatus','sourceId'],'safe']
        ];
    }

    public function loadCode($mobile,$code)
    {
        $mesCode = MessageCode::findOne(['mobile' => $mobile]);
        if($mesCode['code'] != $code){
            return '验证码错误';
        }else{
            $this->delCode();
            return true;
        }
    }

    public function delCode()
    {
        $code = MessageCode::findOne(['mobile'=>$this->mobile]);
        $del  = $code->delete();
        return $del;
    }


    /**
     * @云运动 - 售卡系统 - 验证码
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/6
     * @inheritdoc
     */
//    public function loadCode()
//    {
//        if (Yii::$app->session->has('sms')) {
//            $temp = Yii::$app->session->get('sms');
//            $this->newCode = $temp['code'];
//        }
//    }

    /**
     * @云运动 - 售卡系统 - 验证码时间
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/6
     * @inheritdoc
     */
    public function newCodeTime($attribute)
    {
        $temp = Yii::$app->session->get('sms');
        $time = $temp['time'];
        $num = time() - $time;
        if ($num > 300) {
            $this->addError($attribute, '验证码已失效');
        }
    }

    /**
     * @云运动 - 售卡系统 - 验证码手机号
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/6
     * @inheritdoc
     */
    public function setMobile($attribute)
    {
        $temp = Yii::$app->session->get('sms');
        $mobile = $temp['mobile'];
        if ($this->mobile != $mobile) {
            $this->addError($attribute, '手机号错误，请填写接收验证码的手机号');
        }
    }

    /**
     * @云运动 - 售卡系统 - 存储数据
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/6
     * @inheritdoc
     */
    public function setSellCard()
    {
        $companyId     = $this->companyId;
        $venueId       = $this->venueId;
        $cardCategory  = CardCategory::findOne(['id' => $this->cardId]);
        if(!empty($cardCategory)){
            $time          = json_decode($cardCategory->duration,true);
            $leave         = json_decode($cardCategory->leave_long_limit,true);
        }
        $member        = Member::findOne(['mobile' => $this->mobile]);
        $memberDetails = MemberDetails::findOne(['member_id' => isset($member['id'])?$member['id']:null]);

        if(isset($member) && !empty($member)){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if(isset($member->member_type) && $member->member_type == 2){
                    $member->member_type                  = 2;
                }
                $member->counselor_id                     = $this->seller;
                $member->company_id                       = $this->companyId;
                $member->venue_id                         = $this->venueId;
                $member = $member->save() ? $member : $member->errors;
                if(!isset($member->id)){
                    throw new \Exception(self::NOTICE);
                }
                if(isset($memberDetails) && !empty($memberDetails)){

                    $memberDetails->member_id             = $member->id;
                    $memberDetails->name                  = $this->name;
                    $memberDetails->id_card               = $this->idCard;
                    $memberDetails->family_address       = $this->idAddress;
                    $memberDetails->way_to_shop          = $this->sourceId;
                    $memberDetails->recommend_member_id = 0;
                    $memberDetails->updated_at           = time();
                    $memberDetails->birth_date           = $this->birthDay;
                    $memberDetails = $memberDetails->save() ? $memberDetails : $memberDetails->errors;
                    if(!isset($memberDetails->id)){
                        throw new \Exception(self::NOTICE);
                    }
                }else{
                    $memberDetails = $this->saveMemberDetails($member);
                    if(!isset($memberDetails->id)){
                        throw new \Exception(self::NOTICE);
                    }
                }
//                $memberCard = $this->saveMemberCard($member,$time,$cardCategory,$leave,$companyId,$venueId);
//                if(!isset($memberCard->id)){
//                    throw new \Exception(self::NOTICE);
//                }
//
//                $consumption = $this->saveConsumption($member,$memberCard,$companyId,$venueId);
//                if(!isset($consumption->id)){
//                    throw new \Exception(self::NOTICE);
//                }
//
//                $order = $this->saveOrder($member,$memberCard,$cardCategory,$companyId,$venueId);
//                if(!isset($order->id)){
//                    throw new \Exception(self::NOTICE);
//                }
                $order = $this->savePreOrder($member,$cardCategory,$companyId,$venueId);
                if(!isset($order->id)){
                    throw new \Exception(self::NOTICE);
                }
                $this->data['name']      = $this->name;
                $this->data['cardName']  = $cardCategory->card_name;
                $this->data['type']      = $cardCategory->category_type_id;
                $this->data['price']     = $cardCategory->sell_price;
                $this->data['idCard']    = $this->idCard;
                $this->data['orderId']   = $order->id;
                $this->data['number']    = $order->order_number;
                $this->data['times']     = $cardCategory->times;
                $this->data['activeTime'] = $cardCategory->active_time;
                $this->data['duration']   = $cardCategory->duration;
                $this->data['payMoneyMode'] = $this->payMoneyMode == 2 ? '微信支付' : '支付宝支付';
//                $limit = $this->saveLimit();
//                if(!isset($limit->id)){
//                    throw new \Exception(self::NOTICE);
//                }

//                $data = $this->setEntryRecord($member);
//                if(!isset($data->id)){
//                    throw new \Exception(self::NOTICE);
//                }

                if ($transaction->commit() === null) {
                    return $this->data;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                //如果抛出错误则进入catch，先callback，然后捕获错误，返回错误
                $transaction->rollBack();
                return  $e->getMessage();
            }
        }else{
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $member                  = new Member();
                $member->username       = $this->mobile;
                $member->mobile         = $this->mobile;
                $password                = '123456';
                $password                = Yii::$app->security->generatePasswordHash($password);
                $member->password       = $password;
                $member->register_time = time();
                $member->status         = 0;
                $member->member_type    = 2;
                $member->counselor_id   = $this->seller;
                $member->company_id     = $this->companyId;
                $member->venue_id       = $this->venueId;
                $member = $member->save() ? $member : $member->errors;
                if(!isset($member->id)){
                    throw new \Exception(self::NOTICE);
                }
                $memberDetails = $this->saveMemberDetails($member);
                if(!isset($memberDetails->id)){
                    throw new \Exception(self::NOTICE);
                }
                $order = $this->savePreOrder($member,$cardCategory,$companyId,$venueId);
                if(!isset($order->id)){
                    throw new \Exception(self::NOTICE);
                }
                $this->data['name']      = $this->name;
                $this->data['cardName']  = $cardCategory->card_name;
                $this->data['type']      = $cardCategory->category_type_id;
                $this->data['price']     = $cardCategory->sell_price;
                $this->data['idCard']    = $this->idCard;
                $this->data['orderId']   = $order->id;
                $this->data['number']    = $order->order_number;
                $this->data['times']     = $cardCategory->times;
                $this->data['activeTime'] = $cardCategory->active_time;
                $this->data['duration']   = $cardCategory->duration;
                $this->data['payMoneyMode'] = $this->payMoneyMode == 2 ? '微信支付' : '支付宝支付';
//                $memberCard = $this->saveMemberCard($member,$time,$cardCategory,$leave,$companyId,$venueId);
//                if(!isset($memberCard->id)){
//                    throw new \Exception(self::NOTICE);
//                }
//
//                $consumption = $this->saveConsumption($member,$memberCard,$companyId,$venueId);
//                if(!isset($consumption->id)){
//                    throw new \Exception(self::NOTICE);
//                }
//
//                $order = $this->saveOrder($member,$memberCard,$cardCategory,$companyId,$venueId);
//                if(!isset($order->id)){
//                    throw new \Exception(self::NOTICE);
//                }
//
//                $limit = $this->saveLimit();
//                if(!isset($limit->id)){
//                    throw new \Exception(self::NOTICE);
//                }

//                $data = $this->setEntryRecord($member);
//                if(!isset($data->id)){
//                    throw new \Exception(self::NOTICE);
//                }

                if ($transaction->commit() === null) {
                    return $this->data;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                //如果抛出错误则进入catch，先callback，然后捕获错误，返回错误
                $transaction->rollBack();
                return  $e->getMessage();
            }
        }
    }

    /**
     * 云运动 - 售卡系统 - 存储会员详情表
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/6/6
     * @return array
     */
    public function saveMemberDetails($member)
    {
        $memberDetails                        = new MemberDetails();
        $memberDetails->member_id            = $member->id;
        $memberDetails->name                  = $this->name;
        $memberDetails->id_card              = $this->idCard;
        $memberDetails->family_address      = $this->idAddress;
        $memberDetails->sex                 = null;
        $memberDetails->now_address          = $this->nowAddress;
        $memberDetails->way_to_shop          = $this->sourceId;
        $memberDetails->recommend_member_id = 0;
        $memberDetails->created_at           = time();
        $memberDetails->birth_date           = $this->birthDay;
        $memberDetails = $memberDetails->save() ? $memberDetails : $memberDetails->errors;
        if ($memberDetails) {
            return $memberDetails;
        }else{
            return $memberDetails->errors;
        }
    }
    /**
     * 会员端 - API - 获取会员卡合同信息
     * @author 辛威<xinwei@itsports.club>
     * @create 2018/07/27
     * @return array
     */
    public function getMemberCardDealInfo($cardCategoryId)
    {
        $arr = \common\models\CardCategory::find()
            ->alias('cc')
            ->joinWith(['deal d' => function($q) {
                $q->joinWith('dealType dt');
            }],FALSE)
            ->where(['cc.id' => $cardCategoryId])
            ->select([
                'd.name',
                'd.deal_number',
                'd.intro',
                'dt.type_name',
            ])
            ->asArray()
            ->one();
        return $arr;
    }
    /**
     * 会员端 - API - 获取私教课合同信息
     * @author 辛威<xinwei@itsports.club>
     * @create 2018/07/27
     * @return array
     */
    public function getChargeDealInfo($chargeClassId)
    {
        $arr = \common\models\ChargeClass::find()
            ->alias('cc')
            ->joinWith(['deal d' => function($q) {
                $q->joinWith('dealType dt');
            }],FALSE)
            ->where(['cc.id' => $chargeClassId])
            ->select([
                'd.name',
                'd.deal_number',
                'd.intro',
                'dt.type_name',
            ])
            ->asArray()
            ->one();
        return $arr;
    }
    /**
     * 会员端 - API - 会员合同记录
     * @author 辛威<xinwei@itsports.club>
     * @create 2018/07/17
     * @return array
     */
    public function saveMemberDealRecord($order,$member,$companyId,$venueId)
    {
        $memberDealRecord = new MemberDealRecord();
        if ($order['consumption_type'] == 'card') {
            $memberDealRecord->type = 1;
            $memberDealRecord->type_id = $order['consumption_type_id'];
            $arr = $this->getMemberCardDealInfo($order['card_category_id']);
        } elseif ($order['consumption_type'] == 'charge') {
            $memberDealRecord->type = 2;
            $arr = $this->getChargeDealInfo($order['consumption_type_id']);
        }
        $memberDealRecord->member_id     = isset($member->id)?$member->id:0;
        $memberDealRecord->deal_number   = isset($arr['deal_number'])?$arr['deal_number']:'';
        $memberDealRecord->name           = isset($arr['name'])?$arr['name']:'';
        $memberDealRecord->type_name      = isset($arr['type_name'])?$arr['type_name']:'';
        $memberDealRecord->intro          = isset($arr['intro'])?$arr['intro']:'';
        $memberDealRecord->venue_id      = isset($venueId)?$venueId:0;
        $memberDealRecord->company_id    = isset($companyId)?$companyId:0;
        $memberDealRecord->create_at     = time();
        $memberDealRecord = $memberDealRecord->save() ? $memberDealRecord : $memberDealRecord->errors;
        if (isset($memberDealRecord->id)) {
            $this->memberDealRecordId  = $memberDealRecord->id;
            return $memberDealRecord;
        }else{
            return $memberDealRecord->errors;
        }
    }
    /**
     * 云运动 - 售卡系统 - 存储会员卡
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/6/6
     * @return array
     */
    public function saveMemberCard($member,$time,$cardCategory,$leave,$companyId,$venueId)
    {
        $memberCard                       = new MemberCard();
        $memberCard->member_id           = $member->id;                            //会员ID
        $memberCard->card_category_id   = $this->cardId;                          //卡种
        $memberCard->payment_type       = 1;                                       //付款方式
        $memberCard->create_at         = time();                                   //售卡时间
        $memberCard->level              = 1;                                       //等级
        $memberCard->card_number       = (string)'0'.mt_rand(0,10).time();         //卡号
        $memberCard->is_complete_pay  = 1;                                         //完成付款
        $memberCard->invalid_time       = time()+($time['day']*24*60*60);          //失效时间
        $memberCard->amount_money       = $this->price;
        if($cardCategory->category_type_id == 3 || $cardCategory->category_type_id == 4){
            $memberCard->balance         = $cardCategory->recharge_price + $cardCategory->recharge_give_price;   //余额
        }else{
            $memberCard->balance         = 0;
        }
        $memberCard->status             = 4;
        $memberCard->usage_mode         = 1;
        $memberCard->total_times        = $cardCategory->times;                   //总次数(次卡)
        $memberCard->consumption_times  =  0;                   //消费次数
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
        $memberCard->type               = $cardCategory->card_type;                   //卡分类
        $memberCard->deal_id             = isset($this->memberDealRecordId)?$this->memberDealRecordId:0;               //合同id
        $memberCard->duration            = $time['day'];                         //有效期
        $memberCard->venue_id            = $venueId;                              //场馆id
        $memberCard->company_id          = $companyId;                            //公司id
        $memberCard = $memberCard->save() ? $memberCard : $memberCard->errors;
        if (isset($memberCard->id)) {
            $this->cardName      = $cardCategory->card_name;
            $this->memberCardId  = $memberCard->id;
            return $memberCard;
        }else{
            return $memberCard->errors;
        }
    }

    /**
     * 云运动 - 售卡系统 - 存储消费记录表
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/6/6
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
        $consumption->consumption_time    = time();                    //消费时间
        $consumption->venue_id             = $venueId;                 //场馆id
        $consumption->describe             = json_encode('办会员卡'); //消费描述
        $consumption->category             = '售卡';
        $consumption->due_date             = $memberCard->invalid_time;  // 新增消费时间
        $consumption->company_id           = $companyId;              //公司id
        $consumption->consumption_amount   = isset($memberCard->amount_money) ? $memberCard->amount_money : 0 ;
        $consumption->seller_id            = isset($member->counselor_id) ? $member->counselor_id : 0 ;
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
     * @create 2017/6/15
     * @return array
     */
    public function saveOrder($member,$memberCard,$cardCategory,$companyId,$venueId)
    {
        $order                      = new Order();
        $order->venue_id           = $venueId;                                              //场馆id
        $order->company_id         = $companyId;                                           //公司id
        $order->member_id          = $member->id;                                          //会员id
        $order->card_category_id   = $memberCard->id;                                     //会员卡id
        $order->order_time         = time();                                               //订单创建时间
        $order->pay_money_time     = time();                                               //付款时间
        $order->pay_money_mode     = 1;                                                    //付款方式：1现金
        $order->status             = 2;                                                     //订单状态：2已付款
        $order->note               = '购卡';                                                //备注
        $number                    = time();
        $order->order_number      = "{$number}";                                           //订单编号
        $order->card_name         = $cardCategory->card_name;                              //卡名称
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
     * 云运动 - 售卡系统 - 存储订单表
     * @author 李慧恩<lihuien@itsports.club>
     * @param $member
     * @param $cardCategory
     * @param $companyId
     * @param $venueId
     * @create 2017/6/15
     * @return array
     */
    public function savePreOrder($member,$cardCategory,$companyId,$venueId)
    {
        $employee = Employee::find()->where(['id'=>$this->seller])->asArray()->one();
        if(!empty($cardCategory->sell_price)){
            $this->amountMoney = $cardCategory->sell_price;
        }else{
            $this->amountMoney = $cardCategory->min_price;
        }
        $order                      = new Order();
        $order->venue_id           = $venueId;                                              //场馆id
        $order->company_id         = $companyId;                                           //公司id
        $order->member_id          = $member->id;                                          //会员id
        $order->card_category_id   = $cardCategory->id;                                    //会员卡id
        $order->order_time         = time();                                               //订单创建时间
        $order->total_price        = $this->amountMoney;
        $order->pay_money_time     = time();                                               //付款时间
        if(!empty($this->payMoneyMode) && $this->payMoneyMode == 'wx'){
            $this->payMoneyMode = 2;
        }elseif (!empty($this->payMoneyMode) && $this->payMoneyMode == 'pay'){
            $this->payMoneyMode = 3;
        }
        $order->pay_money_mode       = !empty($this->payMoneyMode) ? $this->payMoneyMode : 1;                                                    //付款方式：1现金
        $order->status               = 1;                                                     //订单状态：2已付款
        $order->note                 = '售卡';                                                //备注
        $number                      = Func::setOrderNumber();
        $order->order_number        = "{$number}";                                           //订单编号
        $order->card_name           = $cardCategory->card_name;                              //卡名称
        $order->member_name         = $this->name;                                           //购买人姓名
        $order->pay_people_name     = $this->name;                                           //付款人姓名
        $order->payee_name          = !empty($employee['name']) ? $employee['name'] : '';
        $order->sell_people_name    = !empty($employee['name']) ? $employee['name'] : '';
        $order->refund_note         = $this->giftStatus;
        $order->sell_people_id      = $this->seller;
        $order->payee_id            = $this->seller;
        $order->consumption_type    = 'card';
        $order->consumption_type_id = $cardCategory->id;
        $order = $order->save() ? $order : $order->errors;
        if ($order) {
            return $order;
        }else{
            return $order->errors;
        }
    }

    /**
     * * 云运动 - 潜在会员 -  进场信息添加
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/27
     * @param $memberId     //会员id
     * @return bool
     */
    public function setEntryRecord($memberId)
    {
        $entryRecord = new EntryRecord();
        $entryRecord->entry_time = time();          //会员进场时间
        $entryRecord->create_at  = time();          //创建时间
        $entryRecord->member_id  = $memberId['id'];       //会员id
        $entryRecord = $entryRecord->save() ? $entryRecord : $entryRecord->errors;
        if ($entryRecord) {
            return $entryRecord;
        }else{
            return $entryRecord->errors;
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
        $limitCardNum = LimitCardNumber::findOne(['card_category_id' => $this->cardId]);
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
     * 云运动 - 售卡系统 - 获取大上海售卖的卡种
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/6/6
     * @param  $venueId
     * @return array
     */
    public static function getCardCategory($venueId)
    {
        $company = Organization::find()->where(['pid' => 0])->andWhere(["like","name","我爱运动"])->asArray()->one();
        $venue   = Organization::find()->where(['pid' => $company['id']])->andWhere(["like","name","大上海"])->asArray()->one();
        $limit   = LimitCardNumber::find()->where(['venue_id' => !empty($venueId)?$venueId:$venue['id']])->andWhere(['>','sell_end_time',time()])->asArray()->all();
        $array = [];
        foreach($limit as $arr){
            $cardCategory = CardCategory::find()->where(['id' => $arr['card_category_id']])->asArray()->one();
            $array[] = $cardCategory;
        }
        return $array;
    }

    /**
     * 云运动 - 售卡系统 - 获取卡种的合同详情
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/6/6
     * @param  $id
     * @return array
     */
    public static function getDeal($id)
    {
        $card = CardCategory::find()->where(['id' => $id])->select('id,deal_id')->asArray()->one();
        $deal = Deal::find()->where(['id' => $card['deal_id']])->select('id,name,intro')->asArray()->one();
        return $deal;
    }

    /**
     * 云运动 - 售卡管理 - 售卡成功发送短信
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/6
     * @return array
     */
    public function sendMessage()
    {
//        $deal     = self::getDeal($this->cardId);
        if($this->type == 'card'){
            $card     = CardCategory::findOne(['id' => $this->cardId]);
            $name     = !empty($card)?$card['card_name']:'';
        }else{
            $charge   = ChargeClass::findOne(['id' => $this->chargeClassId]);
            $name     = !empty($charge)?$charge['name']:'';
        }
        $data     = $this->mobile;
        $info     = '您已成功提交办理我店会员卡的资料';
        $dealName = 'http://product.aixingfu.net/purchase-card/contact-send?cardId='.$this->cardId;
        Func::sellAliCardSendCode($data,$name,$dealName);
        return ['status'=>'success','data'=>$info];
    }
    /**
     * 云运动 - 售卡管理 - 售卡成功发送短信
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/6
     * @return array
     */
    public function setMemberCardInfo($id,$orderNumber = '')
    {
        $this->merchantOrderNumber = $orderNumber;
        $order         = Order::find()->where(['id'=>intval($id)])->asArray()->one();
        if(!empty($order) && ($order['consumption_type'] == 'card'||$order['consumption_type']=="machineCard")){
            $this->type = 'card';
            return  $this->saveMemberCardDetail($id,$order);
        }elseif(!empty($order) && ($order['consumption_type'] == 'chargeGroup')){//小团体课回调
            $chargeClassNumber = ChargeClassNumber::findOne($order['consumption_type_id']);
            $model = new MemberPrivateGroupOrderForm();
            $model->memberId = $order['member_id'];
            $model->peopleId = $chargeClassNumber->class_people_id;
            $model->coachId = 0;
            $model->saleType = 'APP';
            $model->payType = $order['pay_money_mode'];
            $model->giftType = 1;
            $model->primePrice = $order['total_price'];
            $model->buyNote = 'APP购买小团体课';
            $model->create_at = time();
            $model->numberId = $chargeClassNumber->id;
            $model->productName = $chargeClassNumber->chargeClass->name;
            $model->venueId = $chargeClassNumber->venue_id;
            $model->companyId = $chargeClassNumber->company_id;
            $model->orderId = $order['id'];
            $model->merchantOrderNumber = $this->merchantOrderNumber;
            Yii::info($id, 'notify');
            Yii::info($orderNumber, 'notify');
            $result = $model->addPrivateGroupLesson();
            Yii::info($result, 'notify');
            if($result){
                return 'SUCCESS';
            }else{
                Yii::info($model->errors, 'notify');
                return FALSE;
            }
        } elseif(!empty($order) && ($order['consumption_type'] == 'meal')){//健身餐回调
            return $this->updateMealOrder($id);
        }else{
            $iosCharge = new IosSellClassForm();
            $iosCharge->merchantOrderNumber = $this->merchantOrderNumber;
            $this->chargeClassId            =  $order['consumption_type_id'];
            return $iosCharge->saveCharge($id,$order);
        }
    }
    public function updateMealOrder($id)
    {
        $data = Order::updateAll(['status' => 2],['id' => $id]);
        if ($data) {
            return 'SUCCESS';
        } else {
            return FALSE;
        }
    }
    public function saveMemberCardDetail($id,$order)
    {
        $cardCategory  = CardCategory::findOne(['id' => $order['card_category_id']]);
        $this->cardId  = $cardCategory->id;
        if(!empty($cardCategory)){
            $time          = json_decode($cardCategory->duration,true);
            $leave         = json_decode($cardCategory->leave_long_limit,true);
        }
        $member        = Member::findOne(['id' => $order['member_id']]);
        $companyId     = $member->company_id;
        $venueId       = $member->venue_id;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $member->member_type = 1;
            if(!$member->save()){
               // return $member->errors;
                throw new \Exception(self::NOTICE);
            }
            $this->mobile = $member->mobile;
            if(isset($order['total_price'])){
                $this->price = $order['total_price'];
            }
            //会员购买会员卡合同记录
            $memberDealRecord = $this->saveMemberDealRecord($order,$member,$companyId,$venueId);
            if(!isset($memberDealRecord->id)){
                throw new \Exception(self::NOTICE);
            }
            $memberCard = $this->saveMemberCard($member,$time,$cardCategory,$leave,$companyId,$venueId);
            if(!isset($memberCard->id)){
//                return $memberCard;
                throw new \Exception(self::NOTICE);
            }
            $consumption = $this->saveConsumption($member,$memberCard,$companyId,$venueId);
            if(!isset($consumption->id)){
        //        return  $consumption;
                throw new \Exception(self::NOTICE);
            }
            $dealRecord = $this->updateMemberDealRecord();
            if($dealRecord !== true){
                throw new \Exception(self::NOTICE);
            }
            $order = $this->updateOrder($id,$memberCard);
            if($order !== true){
         //       return $order;
                throw new \Exception(self::NOTICE);
            }

            $limit = $this->saveLimit();
            if(!isset($limit->id)){
             //   return $limit;
                throw new \Exception(self::NOTICE);
            }
            $data = $this->saveVenueLimit($memberCard,$cardCategory);
            if($data !== true){
//                return $data;
                throw new \Exception(self::NOTICE);
            }
            $gift = $this->saveRecord($memberCard);
            if($gift !== true){
//                return $gift;
                throw new \Exception(self::NOTICE);
            }
            $bindMemberCard = $this->saveBindCard($memberCard);
            if($bindMemberCard !== true){
                throw new \Exception(self::NOTICE);
            }
            if ($transaction->commit() === null) {
                return 'SUCCESS';
            } else {
                return false;
            }
        } catch (\Exception $e) {
            //如果抛出错误则进入catch，先callback，然后捕获错误，返回错误
            $transaction->rollBack();
            return  $e->getMessage();
        }
    }
    /**
     * 云运动 - 售卡系统 - 存储进场次数核算表
     * @author 黄华<huanghua@itsports.club>
     * @create 2017/8/11
     * @param $memberCard
     * @param $cardCategory
     * @return array
     */
    public function saveVenueLimit($memberCard,$cardCategory)
    {
        $limit = LimitCardNumber::find()->where(['card_category_id' => $this->cardId,'status'=>[1,3]])->asArray()->all();
        if(isset($limit)){
            foreach($limit as $k=>$v){
                $limitVenue = new VenueLimitTimes();
                $limitVenue->member_card_id = $memberCard->id;
                $limitVenue->venue_id       = $v['venue_id'];
                $limitVenue->total_times    = $v['times'];
                if(!empty($v['times'])){
                    $limitVenue->overplus_times = $v['times'];
                }else{
                    $limitVenue->overplus_times = $v['week_times'];
                }
                $limitVenue->week_times     = $v['week_times'];
                $limitVenue->level      = $v['level'];
                $limitVenue->venue_ids      = $v['venue_ids'];
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
     * 云运动 - 售卡系统 - 存储进场次数核算表
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/6/23
     * @return array
     */
    public function saveRecord($memberCard)
    {
        $limit = \common\models\base\BindPack::find()->where(['card_category_id' => $this->cardId,'polymorphic_type'=>'gift'])->asArray()->all();
        if(isset($limit)){
            foreach($limit as $k=>$v){
                $goods      = Goods::find()->where(['id'=>$v['polymorphic_id']])->asArray()->one();
                $gift    = new \common\models\base\GiftRecord();
                $gift->member_card_id = $memberCard->id;
                $gift->member_id      = $memberCard->member_id;
                $gift->num            = $v['number'];
                $gift->name           = $goods['goods_name'];
                $gift->create_at      = time();
                $gift->service_pay_id = $goods['id'];
                $gift->status         = $this->giftStatus;
                if($this->giftStatus == 1){
                    $gift->get_day = null;
                }else{
                    $gift->get_day = time();
                }
                if(!$gift->save()){
                    return $gift->errors;
                }
            }
            return true;
        }
        return true;
    }
    public function updateMemberDealRecord()
    {
        $dealRecord = MemberDealRecord::findOne(['id' => $this->memberDealRecordId]);
        $dealRecord->type_id = $this->memberCardId;
        if ($dealRecord->save()) {
            return true;
        }
        return $dealRecord->errors;
    }
    public function updateOrder($id,$memberCard)
    {
        $order          = Order::findOne(['id'=>$id]);
        $order->consumption_type_id   = $this->memberCardId;
        $order->merchant_order_number = $this->merchantOrderNumber;
        $order->status = 2 ;
        if($order->save()){
            $this->giftStatus = intval($order->refund_note);
            return true;
        }
        return $order->errors;
    }
    public function thawMemberCard($id){
        $order = Order::findOne($id);
        //  1:解冻会员卡
        $memberCard = $this->thawTheMemberCard($order->consumption_type_id);
        if($memberCard!==true){
           return $memberCard;
        }
        // 2:修改订单状态
        $order->status = 2;
        if(!$order->save()){
            return $order->errors;
        }
        return "SUCCESS";
    }

    public function thawTheMemberCard($cardId){
        $card  = MemberCard::findOne($cardId);  // 消费类型id 就是会员卡id
        $card->absentTimes = 0;
        $card->status       = 1;
        if(!$card->save()){
            return $card->errors;
        }
        return true;
    }
    /**
     * 云运动 - 售卡系统 - 存储会员卡绑定套餐表
     * @author huanghua<huanghua@itsports.club>
     * @param $memberCard
     * @create 2017/2/10
     * @return array
     */
    public function saveBindCard($memberCard)
    {
        $bindData = BindPack::find()->where(['card_category_id' => $this->cardId])->asArray()->all();
        if(isset($bindData)){
            foreach($bindData as $k=>$v){
                $memberBindCard = new BindMemberCard();
                $memberBindCard->member_card_id    = $memberCard->id;
                $memberBindCard->venue_id          = $v['venue_id'];
                $memberBindCard->company_id        = $v['company_id'];
                $memberBindCard->polymorphic_id    = $v['polymorphic_id'];
                $memberBindCard->polymorphic_type  = $v['polymorphic_type'];
                $memberBindCard->number            = $v['number'];
                $memberBindCard->status            = $v['status'];
                $memberBindCard->polymorphic_ids   = $v['polymorphic_ids'];
                if(!$memberBindCard->save()){
                    return $memberBindCard->errors;
                }
            }
            return true;
        }
        return true;
    }

}