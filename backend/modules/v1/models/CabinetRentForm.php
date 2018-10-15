<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/7/8
 * Time: 14:46
 */
namespace backend\modules\v1\models;
use backend\models\Cabinet;
use backend\models\CabinetRuleForm;
use backend\models\ConsumptionHistory;
use backend\models\MemberDetails;
use common\models\base\Member;
use common\models\base\MemberCabinet;
use common\models\base\Order;
use common\models\Func;
use yii\base\Model;
class CabinetRentForm extends Model
{
    public $venueId;    // 场馆id
    public $companyId;  //公司id
    public $memberId;  // 会员id
    public $cabinetId; // 柜子id
    public $price;     // 订单价格
    public $startRent; // 租用开始时间
    public $endRent;   // 租用结束时间

    /**
     * 云运动 - 后台- 下预支付订单的时候参数验证
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/8
     * @return  string  //返回报错信息
     */
    public function rules()
    {
        return [
            ["companyId",'required','message' => '公司id不能为空'],
            ["venueId",'required','message' => '场馆id不能为空'],
            ["memberId",'required','message' => '会员id不能为空'],
            ["cabinetId",'required','message' => '柜子id不能为空'],
            ["price",'required','message' => '订单价格不能为空'],
            ["startRent",'required','message' => '租用开始时间不能为空'],
            ["endRent","required",'message' => '租用结束时间不能为空']
        ];
    }
    /**
     * 云运动 - 后台- 手机端会员购买柜子下订单
     * @author houkaixin <houkaixin@itsports.club>
     * @param $payMode  // 付款方式
     * @create 2017/6/8
     * @return  string  //返回报错信息
     */
    public function rentCabinet($payMode=""){
        $member     = MemberDetails::findOne(['member_id'=>$this->memberId]);
        $cabinet    = Cabinet::findOne(['id'=>$this->cabinetId]);
        $order                      = new Order();
        $order->venue_id           = $this->venueId  ;          //场馆id
        $order->company_id         = $this->companyId;         //公司id
        $order->member_id          = $this->memberId;           //会员id
        $order->card_category_id   = 0;                         //卡种id
        $order->total_price        = $this->price;             //总价
        $order->order_time         = time();                   //订单创建时间
        $order->pay_money_time     = time();                   //付款时间
        $order->create_id          = 0;                        //操作人id
        $order->sell_people_id     =0;                        //售卖人id
        $order->payee_id           = 0;                       //收款人id
        $order->status             = 1;                                                     //订单状态：1:代表未付款 2已付款
        $order->note               = 'app租柜';                                                //备注
        $number                     = Func::setOrderNumber();
        $order->order_number       = "{$number}";                                           //订单编号
        $order->member_name        = $member['name'];                                       //购买人姓名
        $order->pay_people_name    = $member['name'];                                       //付款人姓名
        //  $order->sell_people_name   = $adminModel['name'];                                   //售卖人姓名
        //  $order->payee_name         = $adminModel['name'];                                   //收款人姓名
        $order->card_name          = $cabinet['cabinet_number'];
        $order->consumption_type   = 'cabinet';
        $order->consumption_type_id= $this->cabinetId;                                     // 消费类型id 是 柜子id
        //  $order->new_note            = $this->startRent.",".$this->endRent;                //租用开始时间 租用结束时间
        $order->other_note           =  $this->startRent.",".$this->endRent;             // 租柜开始时间 和 结束时间
        if(!empty($payMode)){
            $order->pay_money_mode =$payMode;
        }
        if(!$order->save()){
            return  ["status"=>"error","data"=>$order->errors];
        }
        return ["status"=>"success","data"=>$order];
    }

    /**
     * 云运动 - 后台-  会员租柜后套业务逻辑
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/8
     * @param $orderId  //订单id
     * @return  string  //返回报错信息
     */
    public function rentCabinetLogic($orderId){
        $order = Order::findOne($orderId);
        if($order->status==2){
            return 'SUCCESS';
        }
//        file_put_contents('log.txt',"...\n"."111111111"."....\n",FILE_APPEND);
        //1： 给会员购买柜子
        $rentDate = explode(",",$order->other_note);
        $startRentTime = isset($rentDate[0])?strtotime($rentDate[0]):null;
        $endRentTime   = isset($rentDate[1])?strtotime($rentDate[1]):null;
        $transaction = \Yii::$app->db->beginTransaction();                //开启事务
        try {
            // ---------------------------------------------回滚业务逻辑--------------------------------
            $model = new MemberCabinet();
            $model->creater_id  = 0;   // 创建人id
            $model->member_id   = isset($order["member_id"])?$order["member_id"]:null;   // 会员id
            $model->price        = isset($order["total_price"])?$order["total_price"]:null;      // 订单价格
            $model->start_rent  = $startRentTime;                                     // 租用开始时间
            $model->end_rent    = $endRentTime;   // 租用结束时间
            $model->status      = 1;
            $model->create_at   = time();
            $model->cabinet_id  = isset($order["consumption_type_id"])?(int)$order["consumption_type_id"]:null;
            //   $model->member_card_id = !empty($this->memberCardId)?$this->memberCardId:null;
            $model->rent_type   = "新租";
            $theResult  = $model->save();
            if(!$theResult){
                \Yii::trace($model->errors);
                throw new \Exception($model->errors);
            }
//            file_put_contents('log.txt',"...\n"."22222222222"."....\n",FILE_APPEND);
            // 2：会员租用历史记录表数据录入
            $cabinetRuleModel = new CabinetRuleForm();
            $cabinetRuleModel->memberId   =$order->member_id;                   // 会员id初始化赋值
            $cabinetRuleModel->cabinetId  = (int)$order->consumption_type_id;  // 柜子类型 初始化 赋值
//            file_put_contents('log.txt',"...\n"."22223333333333333"."....\n",FILE_APPEND);

//        $cabinetRuleModel->quiteDate                                       // 退款日期
//        $cabinetRuleModel->memberCardId                                    // 会员卡id暂时先不用
            $memCabinetHistoryTableInsert = $cabinetRuleModel->cabinetHistoryTableInsert([$startRentTime,$endRentTime],"新租",$order["total_price"]);
//            file_put_contents('log.txt',"...\n"."222266666"."....\n",FILE_APPEND);
            if($memCabinetHistoryTableInsert["status"]!=="success"){
                // 程序出错回 数据处理
                $memCabinetHistoryTableInsert["data"] = isset($memCabinetHistoryTableInsert["data"])?null:$memCabinetHistoryTableInsert["data"];
                \Yii::trace($memCabinetHistoryTableInsert["data"]);
                throw new \Exception($memCabinetHistoryTableInsert["data"]);
            }
//            file_put_contents('log.txt',"...\n"."33333333333"."....\n",FILE_APPEND);
            // 3：会员租用历史记录表数据录入
            //消费历史记录表 数据录入
            $consumeHistory   = new ConsumptionHistory();
            $model->price     = $order->total_price;      // 订单价格
            $combineData    = $cabinetRuleModel->combineData($order->company_id,$order->venue_id,$memCabinetHistoryTableInsert,
                "cabinet","调柜",0,$order["total_price"],$model->end_rent);
            $insertResult = $consumeHistory->cabinetDataInsertHistory($combineData);
            if($insertResult!=="success"){
                \Yii::trace("历史记录表出错");
                throw new \Exception("历史记录表出错");
            }
//            file_put_contents('log.txt',"...\n"."444444444"."....\n",FILE_APPEND);
            //4：修改订单状态
            $order->status = 2;
            if(!$order->save()){
                \Yii::trace($order->errors);
                throw new \Exception($order->errors);
                //  return $order->errors;
            }
//            file_put_contents('log.txt',"...\n"."555555555555555"."....\n",FILE_APPEND);
            // 5:更改柜子的使用状态
            $cabinetModel = Cabinet::findOne($order->consumption_type_id);
            $cabinetModel->status = 2;
            if(!$cabinetModel->save()){     // 柜子状态更改失败 抛出异常
                \Yii::trace($cabinetModel->errors);
                throw new \Exception($cabinetModel->errors);
            }
//            file_put_contents('log.txt',"...\n"."12121212"."....\n",FILE_APPEND);
            // 6:发送短信业务逻辑
            $message = $this->gainSendContent($order);
            if(isset($message["mobile"])){
                Func::sendFitnessDiet($message["mobile"],$message["content"]);
            }
// ---------------------------------------------回滚业务逻辑--------------------------------
            //执行数据递交
            $transaction->commit();
            return 'SUCCESS';
        }catch(\Exception $e){
            //如果抛出错误则进入 catch ,先callback,然后捕捉错误，返回错误
            $transaction->rollBack();
            return  $error = $e->getMessage();  //获取抛出的错误
        }
    }
    /**
     * 云运动 - 后台-  获取发送内容消息信息
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/8
     * @param   $param  //发送参数
     * @return  string  //返回报错信息
     */
    public function gainSendContent($param){
        $mobile     = isset(Member::findOne($param["member_id"])->mobile)?Member::findOne($param["member_id"])->mobile:null;
        $cabinetNum = isset(Cabinet::findOne($param["consumption_type_id"])->cabinet_number)?Cabinet::findOne($param["consumption_type_id"])->cabinet_number:null;
        $content = "恭喜您,您已成功购买我店的柜子,柜子编号为{$cabinetNum}";
        return ["mobile"=>$mobile,"content"=>$content];
    }












}