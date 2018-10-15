<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/7/8
 * Time: 14:46
 */

namespace backend\modules\v1\models;

use backend\models\MemberCard;
use common\models\base\MissAboutSet;
use common\models\base\Order;
use common\models\Func;
use yii\base\Model;
use Yii;
class ThawPaymentForm extends Model
{
    public  $cardId;        // 会员卡id
    public  $courseType;   // 课程类型
    public  $amountMoney; // 解冻金额
    public  $belVenueId;  // 会员所属场馆id
    public  $memberName;  // 会员姓名
    public  $memberId;     // 会员id
    public  $cardName;    // 卡名称


    /**
     * @云运动 - 罚金解冻 - 场景多表单定义
     * @create 2017/4/24
     * @return array
     */
    public function  scenarios(){
        return [
            "groupThaw"  =>["cardId","courseType","amountMoney"],
        ];
    }
    /**
     * @云运动 - 罚金解冻 - 验证规则
     * @create 2017/4/24
     * @return array
     */
    public function rules()
    {
        return [
            [["cardId"],'required','message' => '会员卡不能为空！',
             'on'=>["groupThaw"]],
            [["courseType"],'required','message' => '课程类别不能为空！',
            'on'=>["groupThaw"]],
            [["amountMoney"],'required','message' => '罚款金额不能为空',
            'on'=>["groupThaw"]],
//            ["amountMoney","validateAmountMoney",
//            'on'=>["groupThaw"]],
        ];
    }
    /**
     * @云运动 - 罚金解冻 - 验证规则逻辑
     * @create 2017/10/11
     * @return boolean
     */
    public function validateAmountMoney($attribute)
    {
        if (!$this->hasErrors()) {
            $result = $this->gainThawMoney(); // 获取罚款金额
            if ($result===2) {
                $this->addError($attribute, '您缴纳的罚款金额有误');
            }
            if($result===3){
                $this->addError($attribute, '不能通过缴纳金额解冻');
            }
            return true;
        }
    }
    /**
     *后台 - app缴纳金额验证（逻辑）
     * @author  houkaixin<houkaixin@itsport.club>
     * @return int
     * @create 2017/10/06
     */
    public function gainThawMoney(){
         $data = MissAboutSet::find()
                  ->where(["venue_id"=>$this->belVenueId])
                  ->asArray()
                  ->one();
         if(!isset($data["punish_money"])){
             return 3;
         }
         if($data["punish_money"]!=$this->amountMoney){
             return 2;
         }
        return true;
    }
    /**
     *后台 - 订单管理 - 订单数据的保存
     * @author  houkaixin<houkaixin@itsport.club>
     * @param $payType  //付款类型
     * @return int
     * @create 2017/10/11
     */
    public function thawPayment($payType = null){
          // 根据会员卡 查询相关信息
          $result = $this->gainMemberMessage();
          if($result===false){
              return "会员卡保存数据异常";
          }
          $model = new Order();
          $model->consumption_type_id = $this->cardId;
          $model->venue_id          = $this->belVenueId;
          $model->member_id         = $this->memberId;
          $model->card_category_id = $this->belVenueId;
          $model->total_price       = $this->amountMoney;
          $model->order_time        = time();
          $model->pay_money_time    = time();
          if(empty($payType)){
              $model->pay_money_mode    = 3;  //微信支付
          }else{
              $model->pay_money_mode    = 2;  //支付宝支付
          }
          $model->status              = 1;  // 未付款
          $model->note                = "app团课罚金";
          $num = Func::setOrderNumber();
          $model->order_number       = "{$num}";  // 订单编号
          $model->purchase_num       = 1;
          $model->is_receipt         = 1;          // 未开票
          $model->member_name        = $this->memberName;                                           //购买人姓名
          $model->pay_people_name    = $this->memberName;
          $model->venue_id           =  $this->belVenueId;
          $model->card_name          =  $this->cardName;  // 卡名称
          if(!$model->save()){
              return ["result"=>false,"data"=>$model->errors];
          }
          return ["result"=>true,"data"=>$model];
    }
    /**
     *后台 - 订单管理 - 初始化数据
     * @author  houkaixin<houkaixin@itsport.club>
     * @return int
     * @create 2017/10/11
     */
    public function gainMemberMessage(){
        $data = MemberCard::find()
                ->alias("memberCard")
                ->joinWith(["member member"=>function($query){
                   $query->joinWith("memberDetails memberDetails");
                }],false)
                ->where(["memberCard.id"=>$this->cardId])
                ->select("memberCard.id,memberCard.card_name,memberCard.member_id,memberCard.card_category_id,memberDetails.name,member.venue_id")
                ->asArray()
                ->one();
        if(empty($data)){
            return false;
        }
        if(!empty($data)&&isset($data["name"])&&isset($data["member_id"])&&isset($data["venue_id"])){
            $this->memberName  = $data["name"];
            $this->memberId    = $data["member_id"];
            $this->belVenueId  = $data["venue_id"];
            $this->cardName    = empty($data["card_name"])?"暂无数据":$data["card_name"];
        }else{
            return false;
        }
        return true;
    }




}


