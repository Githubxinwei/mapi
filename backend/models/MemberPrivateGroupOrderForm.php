<?php
/**
 * 私教小团体-购买私课-购买数据保存
 * User: 付钟超
 * create: 2018/01/17
 */
namespace backend\models;
use common\models\base\ChargeClassService;
use common\models\base\MemberCourseSaleVenue;
use common\models\base\MemberCourseService;
use common\models\Func;
use common\models\base\Employee;
use common\models\base\MemberDetails;
use common\models\base\Order;
use common\models\base\ClassSaleVenue;
class MemberPrivateGroupOrderForm extends \common\models\MemberCourseOrder
{
    public $memberId;                                   //会员id
    public $peopleId;                                   //人数区间id
    public $coachId;                                    //销售私教id
    public $saleType;                                   //销售渠道
    public $payType;                                    //收款方式
    public $giftType;                                   //赠品:1.未领取2.已领取
    public $primePrice;                                 //最终应付金额
    public $buyNote;                                    //私课备注
    public $create_at;                                  //付款时间
    public $payWays;
    public $numberId;                                   //私课编号id
    public $charge_class_id;                            //产品id
    public $productName;                                //产品名称
    public $courseAmount;                               //课程节数
    public $memberCardId;                               //会员卡id
    public $memberCourseOrderId;                        //会员课程订单表id
    public $pic;                                        //产品图片
    public $desc;                                       //产品描述
    public $deal_id;                                    //合同id
    public $transfer_num;                               //转让次数
    public $transfer_price;                             //转让金额
    public $companyId;
    public $venueId;
    public $cashier_order;                  //收银单号
    /**
     * @desc: 私教小团体-购买私课-表单验证规则
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/17
     * @return array
     */
    public function rules()
    {
        return [
            [[
                'memberId','peopleId','coachId','saleType','payType','giftType','primePrice',
                'buyNote','create_at','numberId','venueId','companyId',
            ],'safe'],
        ];
    }

    /**
     * @desc: 私教小团体-私课购买-购买
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/18
     * @return array|bool|string
     * @throws \yii\db\Exception
     */
    public function addPrivateGroupLesson()
    {
        $this->getChargeClassId();
        $this->getChargeClassInfo();
        $this->getMemberCardId();
        $this->getChargeClassNumberInfo();
        $typeObj = Config::findOne($this->saleType);
        $this->saleType = isset($typeObj->value) ? $typeObj->value : null;
        $transaction      = \Yii::$app->db->beginTransaction();
        $result = MemberCourseOrder::findOne(['member_id'=>$this->memberId,'class_number_id'=>$this->numberId]);
        if (!empty($result)) {
            return 'repeatBuy';
        }
        try{
            $saveMemberCourseOrder = $this->saveMemberCourseOrder();
            if ($saveMemberCourseOrder !== true) {
                return $saveMemberCourseOrder;
            }
            $saveMemberCourseOrderDetails = $this->saveMemberCourseOrderDetails();
            if ($saveMemberCourseOrderDetails !== true) {
                return $saveMemberCourseOrderDetails;
            }
            $saveMemberCourseSaleVenue = $this->saveMemberCourseSaleVenue();
            if ($saveMemberCourseSaleVenue !== true) {
                return $saveMemberCourseSaleVenue;
            }
            $saveChargeClassService = $this->saveChargeClassService();
            if ($saveChargeClassService !== true) {
                return false;
            }
            $saveOrder = $this->saveOrder();
            if ($saveOrder !== true) {
                return $saveOrder;
            }
            $saveConsumptionHistory = $this->saveConsumptionHistory();
            if ($saveConsumptionHistory !== true) {
                return false;
            }
            $saveGiftRecord = $this->saveGiftRecord();
            if ($saveGiftRecord !== true) {
                return false;
            }
            $saveChargeClassNumber = $this->saveChargeClassNumber();
            if ($saveChargeClassNumber !== true) {
                return $saveChargeClassNumber;
            }
            if($transaction->commit()){
                return false;
            }else{
                return true;                                                                         //提交
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return  $e->getMessage();
        }
    }
    /**
     * @desc: 私教小团体-私课购买-通过查询人数区间表获取charge_class_id
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/17
     * @param $peopleId
     * @return string|void
     */
    public function getChargeClassId()
    {
        $data = ChargeClassPeople::findOne($this->peopleId);
        if (empty($data)) {
            return false;
        }
        $this->charge_class_id = isset($data->charge_class_id) ? $data->charge_class_id : null;
    }

    /**
     * @desc: 私教小团体-私课购买-通过产品id获取私课产品信息
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/17
     */
   public function getChargeClassInfo()
   {
       $validTime               = new \backend\models\ChargeClass();
       $data                    = $validTime->getClassValidTime($this->charge_class_id);
       if (empty($data)) {
           return false;
       }
       $this->productName       = isset($data['name']) ? $data['name'] : null;
       $this->pic               = isset($data['pic']) ? $data['pic'] : null;
       $this->desc              = isset($data['describe']) ? $data['describe'] : null;
       $this->deal_id           = isset($data['deal_id']) ? $data['deal_id'] : null;
       $this->transfer_num      = isset($data['transfer_num']) ? $data['transfer_num'] : null;
       $this->transfer_price    = isset($data['transfer_price']) ? $data['transfer_price'] : null;
   }

    /**
     * @desc: 私教小团体-私课购买-获取会员卡id
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/17
     */
   public function getMemberCardId()
   {
       $model               = new MemberCard();
       $data                = $model->getMemberCardId($this->memberId);    //获取会员卡id
       if (isset($data['id'])) {
           $this->memberCardId  = $data['id'];
       }
   }

    /**
     * @desc: 私教小团体-私教购买-获取课种名称
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/17
     * @param $classId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getClassName($classId)
    {
        return Course::find()->where(['id'=>$classId])->select('name')->one();
    }

    public function getChargeClassNumberInfo()
    {
        $number = ChargeClassNumber::findOne(['id' => $this->numberId]);
        if(empty($number)) {
            return false;
        }
        $this->courseAmount = isset($number->total_class_num) ? $number->total_class_num : null;
    }

    /**
     * @desc: 私教小团体-私课购买-保存会员课程订单表(cloud_member_course_order)
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/17
     * @return array|bool
     */
    public function saveMemberCourseOrder()
    {
        $model                          = new MemberCourseOrder();
        $model->course_amount           = $this->courseAmount;                      //课程总节数
        $model->overage_section         = $this->courseAmount;                      //剩余节数
        $model->create_at               = time();                                   //买课时间
        $model->money_amount            = $this->primePrice;                        //总金额
        $model->product_id              = $this->charge_class_id;                   //产品id
        $model->product_type            = 1;                                        //产品类型1私课,2团课
        $model->private_type          = '健身私教';
        $model->private_id              = $this->coachId;                           //销售私教id
        $model->present_course_number   = 0;                                        //赠课总次数
        $model->surplus_course_number   = $this->courseAmount;                      //剩余总课数
        $model->member_card_id          = $this->memberCardId;                      //会员卡ID
        $model->member_id               = $this->memberId;                          //会员id
        $model->business_remarks        = $this->saleType;
        $model->product_name            = $this->productName;                       //产品名称
        $model->activeTime              = time();                                   //激活时间
        $model->course_type             = 1;                                        //课程类型:1收费课,2免费课,3生日课
        $model->set_number              = $this->courseAmount;
        $model->type                    = 1;
        $model->seller_id               = \Yii::$app->user->identity->id;
        $model->note                    = $this->buyNote;                           //备注
        $model->pay_status              = 1;                                        //1:已付款，2:已退款
        $model->class_number_id         = $this->numberId;                          //私课编号id
        $save                           = $model->save() ? $model : $model->errors;
        if (!isset($save->id)) {
            return $save->errors;
        }
        $this->memberCourseOrderId = $save->id;                                 //获取课程订单id
        return true;
    }

    /**
     * @desc: 私教小团体-私教购买-保存会员购买私课订单详情表(charge_member_course_order_details)
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/17
     * @return array|bool
     */
    public function saveMemberCourseOrderDetails()
    {
        $model = new \backend\models\CoursePackageDetail();
        $data = $model->getClassPrice($this->charge_class_id);
        foreach ($data as $k=>$v) {
            $details                        = new MemberCourseOrderDetails();
            $details->course_order_id       = $this->memberCourseOrderId;
            $details->course_id             = $v['course_id'];
            $details->course_num            = isset($v['course_num']) ? $v['course_num'] : $this->courseAmount;
            $details->class_length          = $v['course_length'];
            $details->original_price        = $v['original_price'];
            $details->sale_price            = $v['sale_price'];
            $details->pos_price             = $v['pos_price'];
            $details->type                  = 2;
            $details->category              = $v['category'];
            $details->product_name          = $this->productName;
            $name                           = $this->getClassName($v['course_id']);
            $details->course_name           = $name['name'];
            $details->pic                   = $this->pic;
            $details->desc                  = $this->desc;
            $details->transfer_num          = $this->transfer_num;
            $details->transfer_price        = $this->transfer_price;
            $details->deal_id               = $this->deal_id;
            $save                           = $details->save() ? $details : $details->errors;
            if (!isset($save->id)) {
                return $save->errors;
            }
        }
            return true;
    }

    /**
     * @desc: 私教小团体-私课购买-生成会员课种售卖场馆表(cloud_member_course_sale_venue)
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/17
     * @return array|bool
     */
    public function saveMemberCourseSaleVenue()
    {
        $venue = ClassSaleVenue::find()->where(['charge_class_id' => $this->charge_class_id])->asArray()->all();
        if(!empty($venue)){
            foreach ($venue as $key => $value) {
                $saleVenue = new MemberCourseSaleVenue();
                $saleVenue->course_order_id = $this->memberCourseOrderId;
                $saleVenue->venue_id        = $value['venue_id'];
                $saleVenue->sale_num        = $value['sale_num'];
                $saleVenue->sale_start_time = $value['sale_start_time'];
                $saleVenue->sale_end_time   = $value['sale_end_time'];
                $saleVenue->status          = $value['status'];
                if(!$saleVenue->save()){
                    return $saleVenue->errors;
                }
            }
            return true;
        }
        return true;
    }

    /**
     * @desc: 私教小团体-私课购买-存储消费记录表(cloud_consumption_history)
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/17
     * @return array|bool
     */
    public function saveConsumptionHistory()
    {
        $history                        = new ConsumptionHistory();
        $history->member_id             = $this->memberId;
        $history->consumption_type      = 'chargeGroup';
        $history->consumption_type_id   = $this->memberCourseOrderId;
        $history->type                  = (int)1;
        $history->consumption_date      = time();
        $history->consumption_time      = time();
        $history->consumption_times     = 1;
        $history->cashier_order         = $this->cashier_order;           //收银单号
        $history->cash_payment          = $this->primePrice;
        $history->seller_id             = $this->coachId;                 //销售私教id
        $history->company_id            = $this->companyId;
        $history->venue_id              = $this->venueId;
        $history->consumption_amount    = $this->primePrice;
        $history->category              = '购买私教小团体课程';
        $save                           = $history->save() ? $history : $history->errors;
        if (!isset($save->id)) {
            return $save->errors;
        }
        return true;
    }

    /**
     * @desc: 私教小团体-私课购买-存储会员私课服务赠品表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/18
     * @return bool
     */
    public function saveChargeClassService()
    {
        $server = ChargeClassService::find()->where(['charge_class_id' => $this->charge_class_id])->asArray()->all();
        if(!empty($server)){
            foreach ($server as $key => $value) {
                $giftServer = new MemberCourseService();
                $giftServer->course_order_id = $this->memberCourseOrderId;
                $giftServer->service_id      = $value['service_id'];
                $giftServer->gift_id         = $value['gift_id'];
                $giftServer->type            = $value['type'];
                $giftServer->category        = $value['category'];
                $giftServer->create_time     = time();
                $giftServer->service_num     = $value['service_num'];
                $giftServer->gift_num        = $value['gift_num'];
                if(!$giftServer->save()){
                    return $giftServer->errors;
                }
            }
            return true;
        }
        return true;
    }

    /**
     * @desc: 私教小团体-私课购买-存储订单表(cloud_order)
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/18
     * @return array|bool
     */
    public function saveOrder()
    {
        $saleName = Employee::findOne(['id' => $this->coachId]);
        $adminModel = Employee::findOne(['admin_user_id'=>\Yii::$app->user->identity->id]);
        if (empty($adminModel) && \Yii::$app->user->identity->username == 'admin'){
            return '系统管理员不能私自购买私课';
        }
        $order  = new Order();
        $order->venue_id = $this->venueId;
        $order->member_id = $this->memberId;
        $order->card_category_id = $this->memberCardId;
        $order->order_time = time();
        $order->pay_money_time = time();
        $order->pay_money_mode = 1;
        $order->sell_people_id = $this->coachId;
        $order->payee_id =  \Yii::$app->user->identity->id;
        $order->create_id = \Yii::$app->user->identity->id;
        $order->status = 2;
        $order->note = '私教小团体';
        $number = Func::setOrderNumber();
        $this->cashier_order = "{$number}";
        $order->order_number = $this->cashier_order;
        $memberName = MemberDetails::findOne(['member_id' => $this->memberId]);
        $order->card_name = $this->productName;
        $order->sell_people_name = $saleName->name;
        $order->payee_name = $adminModel->name;
        $order->member_name = $memberName->name;
        $order->pay_people_name = $memberName->name;
        $order->company_id = $this->companyId;
        $order->consumption_type_id = $this->memberCourseOrderId;
        $order->consumption_type = 'chargeGroup';
        $order->deposit = 0;
        $order->card_name = $this->productName;
        $order->cash_coupon = 0;
        $order->net_price = $this->primePrice;
        $order->all_price = $this->primePrice;
        $order->new_note = $this->buyNote;
        $order->total_price = $this->primePrice;
        if(!$order->save()) {
            return $order->errors;
        }
            return true;

    }

    /**
     * @desc: 私教小团体-私课购买-赠品记录表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/18
     * @return array|bool
     */
    public function saveGiftRecord()
    {
        $limit = \common\models\base\ChargeClassService::find()->where(['charge_class_id' => $this->charge_class_id])->andWhere(['type'=>2])->asArray()->all();
        if (isset($limit) && !empty($limit)) {
            foreach ($limit as $k=>$v) {
                $goods = Goods::find()->where(['id'=>$v['gift_id']])->asArray()->one();
                $gift = new \common\models\base\GiftRecord();
                $gift->member_card_id = $this->memberCardId;
                $gift->member_id      = $this->memberId;
                $gift->num            = $v['gift_num'];
                $gift->name           = $goods['goods_name'];
                $gift->create_at      = time();
                $gift->service_pay_id = $goods['id'];
                $gift->status         = $this->giftType;
                $gift->note           = $this->buyNote;
                if($this->giftType == 1){
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

    /**
     * @desc: 私教小团体-私课购买-存储编号表(cloud_charge_class_number)
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/18
     * @return array|bool
     */
    public function saveChargeClassNumber()
    {
        $num = substr(date("ymdHis"), 2, 8) . mt_rand(100000, 999999);
        $number = ChargeClassNumber::findOne(['id' => $this->numberId]);
        if ($number->surplus - 1 == 0) {
            if ($number->surplus_sale_num != 0) {
                $ccn = new ChargeClassNumber();
                $ccn->charge_class_id       = $number->charge_class_id;
                $ccn->class_people_id       = $number->class_people_id;
                $ccn->class_number          = $num;
                $ccn->sell_number           = $number->sell_number;
                $ccn->surplus               = $number->sell_number;
                $ccn->total_class_num       = $number->total_class_num;
                $ccn->attend_class_num      = $number->total_class_num;
                $ccn->venue_id              = $number->venue_id;
                $ccn->company_id            = $number->company_id;
                $ccn->valid_start_time      = $number->valid_start_time;
                $ccn->valid_time            = $number->valid_time;
                $ccn->sale_num              = $number->sale_num;
                $ccn->surplus_sale_num      = $number->surplus_sale_num - 1;
                $ccn->save();
            }
        }
        $number->surplus = $number->surplus-1;
        if ($number->save()) {
            return true;
        } else {
            return $number->errors;
        }
    }
}