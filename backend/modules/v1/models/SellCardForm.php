<?php
namespace backend\modules\v1\models;

use common\models\base\EntryRecord;
use common\models\base\MessageCode;
use common\models\base\Order;
use backend\models\Deal;
use common\models\Func;
use common\models\base\Organization;
use common\models\base\ConsumptionHistory;
use common\models\base\CardCategory;
use common\models\base\LimitCardNumber;
use common\models\base\MemberDetails;
use common\models\base\MemberCard;
use common\models\base\Member;
use common\models\MemberAccount;
use yii\base\Model;
use Yii;
class SellCardForm extends Model
{
    public $name;          //姓名
    public $idCard;        //身份证号
    public $idAddress;     //身份证住址
    public $nowAddress;    //现居地
    public $mobile;        //手机号
    public $code;          //生成的验证码
    public $newCode;       //填写的验证码
    public $cardId;        //卡种id
    public $venueId;
    public $companyId;
    public $cardName;
    public $memberCardId;
    public $url;
    public $birthDay;
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

            [['venueId','companyId','cardId','birthDay','newCode','code'],'safe']
        ];
    }

    /**
     * @云运动 - 售卡系统 - 存储数据
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/6
     * @inheritdoc
     */
    public function setSellCard()
    {
//        if(isset($member)){
//            return '您已登记，不需重复登记';
//        }
        $mesCode = MessageCode::find()->where(['mobile' => $this->mobile])->orderBy('create_at DESC')->one();
        if($mesCode['code'] != $this->newCode){
            return '验证码错误';
        }
        $mesCode->delete();
        $companyId     = $this->companyId;
        $venueId       = $this->venueId;
        $cardCategory  = CardCategory::findOne(['id' => $this->cardId]);
        if(!empty($cardCategory)){
            $time          = json_decode($cardCategory->duration,true);
            $leave         = json_decode($cardCategory->leave_long_limit,true);
        }
        $member = Member::find()->where(['and',['mobile' => $this->mobile],['venue_id' => $this->venueId]])->one();
        $member        = Member::findOne(['id' => $member['id']]);
        $memberDetails = MemberDetails::findOne(['member_id' => isset($member['id'])?$member['id']:null]);

        if(isset($member) && !empty($member)){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $member->member_type                      = 2;
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
                if(isset($cardCategory) && !empty($cardCategory)) {
                    $order = $this->saveOrder($member, $cardCategory, $companyId, $venueId);
                    if (!isset($order->id)) {
                        throw new \Exception(self::NOTICE);
                    }
                }
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
                    return true;
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
                if(isset($cardCategory) && !empty($cardCategory)){
                    $order = $this->saveOrder($member,$cardCategory,$companyId,$venueId);
                    if(!isset($order->id)){
                        throw new \Exception(self::NOTICE);
                    }
                }
//                $limit = $this->saveLimit();
//                if(!isset($limit->id)){
//                    throw new \Exception(self::NOTICE);
//                }

//                $data = $this->setEntryRecord($member);
//                if(!isset($data->id)){
//                    throw new \Exception(self::NOTICE);
//                }

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
        if($cardCategory->category_type_id == 3){
            $memberCard->balance         = $cardCategory->recharge_price + $cardCategory->recharge_give_price;   //余额
        }else{
            $memberCard->balance         = 0;
        }
        $memberCard->total_times        = $cardCategory->times;                   //总次数(次卡)
        $memberCard->consumption_times  = 0;                   //消费次数
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
     * @create 2017/6/15
     * @return array
     */
    public function saveOrder($member,$cardCategory,$companyId,$venueId)
    {
        $order                      = new Order();
        $order->venue_id           = $venueId;                                              //场馆id
        $order->company_id         = $companyId;                                           //公司id
        $order->member_id          = $member->id;                                          //会员id
        $order->card_category_id   = $this->cardId;                                      //卡种id
        $order->order_time         = time();                                               //订单创建时间
//        $order->pay_money_time     = time();                                               //付款时间
        $order->pay_money_mode     = 0;                                                    //付款方式：1现金
        $order->status             = 1;                                                     //订单状态：2已付款
        $order->note               = '会员登记';                                            //备注
        $number                    = Func::setOrderNumber();
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
        $deal     = self::getDeal($this->cardId);
        $card     = CardCategory::findOne(['id' => $this->cardId]);
        $data     = $this->mobile;
        $info     = '您已成功提交办理我店会员卡的资料';
        $dealName = !empty($deal)?$deal['name']:'';
        $cardName = !empty($card)?$card['card_name']:'';
        Func::sellCardSendCode($data,$cardName,$dealName);
        return ['status'=>'success','data'=>$info];
    }

    /**
     * @desc: 业务后台 - 扫描二维码 - 生成潜在会员
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/03/15
     * @return bool|string
     * @throws \Exception
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function registerPotential()
    {
        $mesCode = MessageCode::find()->where(['mobile' => $this->mobile])->orderBy('create_at DESC')->one();
        if($mesCode['code'] != $this->newCode){
            return '验证码错误';
        }
        $mesCode->delete();
        //查找账户表,是否存在手机号
        $memberAccount = MemberAccount::findOne(['mobile' => $this->mobile,'company_id'=>$this->companyId]);
        $transaction = \Yii::$app->db->beginTransaction();
        $cardCategory  = CardCategory::findOne(['id' => $this->cardId]);
        if(!empty($cardCategory)){
            $time          = json_decode($cardCategory->duration,true);
            $leave         = json_decode($cardCategory->leave_long_limit,true);
        }
        try{
            if (empty($memberAccount)){
                //生成账户表
                $memberAccount = $this->saveMemberAccount();
            }
            $member = new Member();
            $member->username = $memberAccount->username;
            $member->password = $memberAccount->password;
            $member->mobile = $memberAccount->mobile;
            $member->register_time = time();
            $member->status = 1;
            $member->member_type = 2;           //潜在会员
            $member->venue_id = $this->venueId;
            $member->company_id = $this->companyId;
            $member->member_account_id = $memberAccount->id;
            $member = $member->save() ? $member : $member->errors;
            if (!isset($member->id)) {
                throw new \Exception(self::NOTICE);
            }
            $memberDetails = $this->saveMemberDetails($member);
            if (!isset($memberDetails->id)) {
                throw new \Exception(self::NOTICE);
            }
            //执行
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

    /**
     * @desc: 业务后台 - 生成会员账户表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/03/15
     * @return array|MemberAccount
     * @throws \yii\base\Exception
     */
    public function saveMemberAccount()
    {
        $memberAccount = new MemberAccount();
        $memberAccount->username    = $this->name;
        $password = '123456';
        $password = \Yii::$app->security->generatePasswordHash($password);
        $memberAccount->password    = $password;
        $memberAccount->mobile      = $this->mobile;
        $memberAccount->company_id  = $this->companyId;
        $memberAccount->create_at   = time();
        $memberAccount = $memberAccount->save() ? $memberAccount : $memberAccount->errors;
        if ($memberAccount) {
            return $memberAccount;
        }else {
            return $memberAccount->errors;
        }
    }
}