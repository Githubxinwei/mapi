<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/9 0009
 * Time: 下午 5:50
 */

namespace backend\modules\v1\models;


use backend\models\ChargeClass;
use backend\models\ChargeClassPrice;
use backend\models\CoursePackageDetail;
use backend\models\MemberCard;
use backend\modules\v1\models\PaymentCardForm;
use backend\models\MemberCourseOrder;
use common\models\base\ConsumptionHistory;
use common\models\base\Course;
use common\models\base\MemberDetails;
use common\models\base\Order;
use common\models\Func;
use yii\base\Model;
use common\models\base\MemberDealRecord;

class IosSellClassForm extends Model
{
    public $memberId;           //会员ID  (必传)
    public $chargeId;           //产品ID  (必传)
    public $amountMoney;        //金额    (必传)
    public $nodeNumber;         //课程节数（或者套餐数量） (必传)
    public $payMethod;          //支付方式  (必传)
    public $classNum;
    public $memberCardId;       //会员卡id
    public $endTime;            //自定义变量，课程有效期
    public $productName;        //产品名称
    public $desc;               //产品介绍
    public $pic;                //产品图片
    public $type;               //类型
    public $unitPrice = 0;      //自定义变量，购买课程单价金额
    public $totalNum = 0;       //课程总量
    public $price;              //课程总价
    public $memberOrderId;      //订单id
    public $venueId;
    public $companyId;
    public $category;           //购买方式
    public $merchantOrderNumber;
    public $detailsId;                  //课程订单详情ID
    public function rules()
    {
        return [
            [['memberCardId','memberId','chargeId','nodeNumber','amountMoney','payMethod'],'required'],
        ];
    }
    /**
     * 云运动 - 购买私课 -  获取会员卡id
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/7/9
     * @param $memberId     //会员id
     */
    public function getMemberCardId($memberId)
    {
        $model               = new MemberCard();
        $data                = $model->getMemberCardId($memberId);    //获取会员卡id
        $this->memberCardId  = $data['id'];
    }
    /**
     * 云运动 - 购买私课 -  获取课程数据  //第一步通过私教课程ID（计算价格）（alone是买的单节课程）（many买的是多节课程）,计算价格和查询有效期
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/7/9
     * @param $chargeId     //购买课程id
     * @param $type         //购买课程类型
     */
    public function getClassInfo($chargeId,$type = '')
    {
        $validTime          = new ChargeClass();
        $times              = $validTime->getClassValidTime($chargeId);
        if(empty($type))
        {
            if($times['type'] == 1)
            {
                $type = 'many';
            }else{
                $type = 'alone';
            }
        }
        $this->venueId      = $times['venue_id'];
        $this->companyId    = $times['company_id'];
        $this->endTime      = $times['valid_time'];                                                                      //获取课程有效期
        $this->productName  = $times['name'];                                                                            //产品名称
        $this->desc         = $times['describe'];                                                                        //产品描述
        $this->pic          = $times['pic'];                                                                             //产品图片
        $this->type         = $times['private_class_type'] ? $times['private_class_type'] : 1;//收银类型：PT
        if($type == "alone" && $type != "many"){                                                                        //购买的是单节课程
            $model          = new ChargeClassPrice();
            $data           = $model->getAlonePrice($chargeId,$this->nodeNumber);                                       //查询私课价格（是不是在区间内）
            if(empty($data))
            {
                $data = $model->getAloneEndPrice($chargeId,$this->nodeNumber);                                       //最高区间的数据
            }
            if(!empty($data))
            {
                $model = new ChargeClass();
                $memberCard = $model->getMemberCard($this->memberId);       //获取会员卡信息
                if(($memberCard['create_at'] + 86400) > time())             //24小时内办卡的
                {
                    if(!empty($data['posPrice']))                   //pos价不为空
                    {
                        $this->unitPrice = $data['posPrice'];            //用pos价
                    }else{
                        $this->unitPrice= $data['unitPrice'];            //用优惠单价
                    }
                }else{
                    $this->unitPrice= $data['unitPrice'];                                                                   //课程单价
                }
                $this->totalNum = $this->nodeNumber;                 //总节数
            }else{
                $data            = CoursePackageDetail::find()->where(['charge_class_id'=>$chargeId])->asArray()->one();           //课程信息
                $this->unitPrice = $data['original_price'];                                                             //课程单节原价
                $this->totalNum  = $this->nodeNumber;                                                                   //总节数
            }
            $this->price     = $this->totalNum * $this->unitPrice;       //总价
        }else{
            $this->setChargePriceNum($chargeId,$times);                           //处理套餐价格
        }
    }
    /**
     * 云运动 - 购买私课 -  获取课程数据  //第一步通过私教课程ID（计算价格）（alone是买的单节课程）（many买的是多节课程）,计算价格和查询有效期
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/7
     * @param $chargeId     //购买课程id
     * @param $times
     */
    public function setChargePriceNum($chargeId,$times)
    {
        //1.处理价格
        if(empty($times['total_amount']))                         //售价为空，用原价
        {
            $this->price = $times['original_price'] * $this->nodeNumber;                //用原价
        }else{
            $this->price = $times['total_amount'] * $this->nodeNumber;                  //用售价
        }
        $model = new \backend\modules\v1\models\ChargeClass();
        $memberCard = $model->getMemberCard($this->memberId);       //获取会员卡信息
        if(($memberCard['create_at'] + 86400) > time())             //24小时内办卡的
        {
            if(!empty($times['total_pos_price']))                   //pos价不为空
            {
                $this->price = $times['total_pos_price'];            //用pos价
            }
        }
        //2.处理数量
        $model = new CoursePackageDetail();
        $data  = $model->getClassPrice($chargeId);                                                                  //查询私课价格表//购买的是套餐课程
        foreach($data as $v)
        {
            $this->totalNum  += $v['course_num'];                                                                   //计算总数量（一套课程总数量）
        }
        $this->totalNum  = $this->nodeNumber * $this->totalNum;                                                     //购买的总节数（多套）

    }
    public function setAttr($order)
    {
       $this->memberId      =  $order['member_id'];
       $this->chargeId      =  $order['consumption_type_id'];
       $this->amountMoney   =  $order['total_price'];
       $member              =  Member::findOne(['id' => $order['member_id']]);
       $this->companyId     =  isset($member->company_id)?$member->company_id:0;
       $this->venueId       =  isset($member->venue_id)?$member->venue_id:0;
       $this->nodeNumber    =  intval($order['purchase_num']);
    }
    /**
     * 云运动 - 会员买课 - 保存购买私教记录
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/5/19
     * @return boolean
     */
    public function saveCharge($id,$order)
    {
        $this->setAttr($order);
        $this->getMemberCardId($this->memberId);                                                    //获取会员卡id
        /*if(empty($this->memberCardId)){
            return 'No MemberCardId';
        }*/
        $this->getClassInfo($this->chargeId,'');                                     //计算课程价格(获取有效期)
//        if( $this->price != $this->amountMoney)
//        {
//            return '价格不匹配';                                                                          //前台后台计算总价不一样
//        }
        //事务
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //会员第一次购买私课
            $memberOrder =  $this->setBuyClass();                                                 //保存购买私课订单
            if($memberOrder == false)
            {
                return false;                                                                    //购买失败
            }
            //会员购买私课合同记录
            $member = Member::findOne(['id' => $order['member_id']]);
            $companyId = isset($member->company_id)?$member->company_id:0;
            $venueId = isset($member->venue_id)?$member->venue_id:0;
            $payment = new PaymentCardForm();
            $memberDealRecord = $payment->saveMemberDealRecord($order,$member,$companyId,$venueId);
            if(!isset($memberDealRecord->id)){
                throw new \Exception(self::NOTICE);
            }
            $details = $this->addCourseOrderDetails($this->memberOrderId,$this->chargeId,$memberDealRecord->id);        //生成订单详情
            if($details !== true){
                return false;                                                                   //订单详情生成失败
            }
            $history  = $this->addConsumptionHistory();
            if($history !== true){
                return $history;                                                                        //生成消费记录失败
            }
            $dealRecord = $this->updateMemberDealRecord($memberDealRecord->id);
            if($dealRecord !== true){
                return false;
            }
            $order = $this->updateOrder($id);
            if($order !== true){
                return false;
            }
            if($transaction->commit() === null){
                return 'SUCCESS';
            }else{
                return false;                                                                       //提交
            }
        } catch (\Exception $e) {
            //如果抛出错误则进入catch，先callback，然后捕获错误，返回错误
            $transaction->rollBack();
            return  $e->getMessage();
        }
    }
    /**
     * 云运动 - 会员买课 - 保存购买私课记录
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/3
     * @return array|bool|MemberCourseOrder
     */
    public function setBuyClass()
    {
        $member                        = new \common\models\base\MemberCourseOrder();
        $member->member_id             = $this->memberId;                                   //会员id（必填）
        $member->member_card_id        = $this->memberCardId;                               //会员卡id(必填)
        $member->service_pay_id        = $this->chargeId;                                   //收费项目id(必填)买的课程id
        $member->course_amount         = $this->totalNum;                                  //课程总节数
        $member->create_at             = time();                                            //买课时间
        $member->money_amount          = $this->amountMoney;    //总金额
        $member->overage_section       = $this->totalNum;                                  //课程剩余节数
        $member->private_type          = '健身私教';                                        //私教类别
        $member->product_id            = $this->chargeId;                                   //产品id
        $member->product_type          = 1;                                                 //私课
        $member->deadline_time         = time() + (int)$this->endTime * 86400;              //课程截止时间

        $member->present_course_number = 0;                                                  //赠送总次数
        $member->surplus_course_number = $this->totalNum;                                    //剩余总课数
        $member->product_name          = $this->productName;                                 //产品名称
        $member->type                  = $this->type;                                        //类型：1 PT
        $member->set_number            = $this->nodeNumber;                                  //套数或者总数量
        $memberSave = $member->save() ? $member : $member->errors;
        if (!isset($memberSave->id)) {
            return $memberSave;
        }else{
            $this->category      = '购买私课';
            $this->memberOrderId = $memberSave->id;                                                //获取订单id
            return true;
        }
    }
    /**
     * 云运动 - 会员买课 - 生成订单详情
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/23
     * @param $orderId     //产品订单id
     * @param $chargeId     //产品id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function addCourseOrderDetails($orderId,$chargeId,$memberDealRecordId)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $model = new CoursePackageDetail();
            $data  = $model->getClassPrice($chargeId);
            foreach($data as $k=>$v)
            {
                $details = new \common\models\base\MemberCourseOrderDetails();
                $details->course_order_id = $orderId;             //订单id
                $details->course_id       = $v['course_id'];      //课种id
                $details->course_num      = $v['course_num']*$this->nodeNumber;     //课量
                $details->course_length   = $this->endTime;     //有效期
                $details->original_price  = $v['original_price']; //单节原价
                $details->sale_price      = $v['sale_price'];     //单节售价
                $details->pos_price       = $v['pos_price'];      //pos售价
                $details->type            = 1;                    //订单类型
                $details->category        = $v['category'];       //类型:1多课程，2单课程
                $details->product_name    = $this->productName;   //产品名称
                $name                     = $this->getClassName($v['course_id']);
                $details->course_name     = $name['name'];          //课种名称
                $details->class_length    = $v['course_length'];   //单节课时长
                $details->pic             = '';             //产品图片
                $details->desc            = '';            //产品描述
                $details->deal_id         = $memberDealRecordId;         //合同id
                if(!$details->save()) {
                    return $details->errors;
                } else {
                    $this->detailsId = $details->id;
                }
                $extend = new \common\models\base\MemberCourseOrderExtend();
                $extend->pic = $this->pic;
                $extend->desc = $this->desc;
                $extend->details_id = $details->id;
                if (!$extend->save()) return $extend->errors;
            }
            if($transaction->commit() === null){
                return true;
            }else{
                return false;                                                                       //提交
            }
        } catch (\Exception $e) {
            //如果抛出错误则进入catch，先callback，然后捕获错误，返回错误
            $transaction->rollBack();
            return  $e->getMessage();
        }
    }
    /**
     * 云运动 - 会员买课 - 消费记录
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/5/20
     * @return boolean
     */
    public function addConsumptionHistory()
    {
        $history = new ConsumptionHistory();
        $history->member_id           = isset($this->memberId)?$this->memberId:0;                //会员id
        $history->consumption_type    = 'charge';                       //消费类型
        $history->consumption_type_id = $this->memberOrderId;           //消费项目id
        $history->type                = (int)1;                         //消费方式
        $history->consumption_date    = time();    //消费日期
        $history->consumption_amount  = $this->price;

        $history->consumption_time    = time();                         //消费时间
        $history->consumption_times   = 1;                              //消费次数
        $history->category            = $this->category;                //消费类型状态
        if($this->payMethod == "现金"){
            $history->cash_payment        = $this->price;              //现金付款

        }else{
            $history->network_payment     = $this->price;              //网络付款
        }
        $history->venue_id            = $this->venueId;                 //场馆id
        $history->company_id          = $this->companyId;
        if($history->save()){
            return true;
        }else{
            return $history->errors;
        }
    }
    /**
     * 云运动 - 会员课程 - 课种名称获取
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/28
     * @param $classId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getClassName($classId)
    {
        return Course::find()->where(['id'=>$classId])->andWhere(['class_type'=>1])->select('name')->one();
    }
    /**
     * 云运动 - 会员课程 - 生成订单
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/7/14
     * @return array|bool
     */
    public function setOrder()
    {
//        $saleName                  = Employee::findOne(['id' => $this->coachId]);
        $adminModel                = Employee::findOne(['admin_user_id'=>\Yii::$app->user->identity->id]);
        $order                   = new Order();
        $order->venue_id         = $this->venueId;              //场馆id
        $order->member_id        = $this->memberId;             //会员id
        $order->card_category_id = $this->memberCardId;         //会员卡id
        $order->total_price      = $this->price;                //总价
        $order->order_time       = time();                      //订单创建时间
        $order->pay_money_time   = time();                      //付款时间
        if($this->payMethod == "现金")
        {
            $order->pay_money_mode = 1;
        }else if($this->payMethod == "支付宝")
        {
            $order->pay_money_mode = 2;
        }else if($this->payMethod == "微信")
        {
            $order->pay_money_mode = 3;
        }
//        $order->sell_people_id     = $this->coachId;                 //售卖人id
        $order->payee_id           = \Yii::$app->user->identity->id; //收款人id
        $order->create_id          = \Yii::$app->user->identity->id; //操作人id
        $order->status             = 2;                              //订单状态：1未付款；2已付款；3其他状态；
        $order->note               = '购买了私教产品“'.$this->productName.'”';
        $number                    = Func::setOrderNumber();
        $order->order_number       = "{$number}";                    //订单编号
//        $order->sell_people_name   = $saleName->name;               //售卖人姓名
        $order->payee_name         = $adminModel->name;             //收款人姓名
        $memberName                = MemberDetails::findOne(['member_id' => $this->memberId]);
        $order->member_name        = $memberName->name;             //购买人姓名
        $order->pay_people_name    = $memberName->name;             //付款人姓名
        $order->company_id         = $this->companyId;              //公司id
        $order->consumption_type_id=$this->memberOrderId;           //订单id
        $order->consumption_type   = '购买私课';                    //消费类型
        if($order->save())
        {
            return true;
        }else{
            return $order->errors;
        }
    }
    public function updateMemberDealRecord($memberDealRecordId)
    {
        $dealRecord = MemberDealRecord::findOne(['id' => $memberDealRecordId]);
        $dealRecord->type_id = $this->detailsId;
        if ($dealRecord->save()) {
            return true;
        }
        return $dealRecord->errors;
    }
    public function updateOrder($id)
    {
        $order          = Order::findOne(['id'=>$id]);
        $order->card_category_id      = $this->memberCardId;
        $order->merchant_order_number = strval($this->merchantOrderNumber);
        $order->consumption_type_id   = $this->memberOrderId;
        $order->status = 2 ;
        $order->card_name = $this->productName;
        if($order->save()){
            return true;
        }
        return $order->errors;
    }
}