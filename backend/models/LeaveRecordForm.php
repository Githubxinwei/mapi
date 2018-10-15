<?php
namespace backend\models;
use common\models\base\LeaveRecord;
use common\models\base\MemberCard;
use common\models\base\CardCategory;
use yii\base\Model;
/**
 * @云运动 - 后台 - 验卡管理 - 请假表单验证
 * @author Houkaixin <Houkaixin@itsports.club>
 * @create 2017/5/13
 */
class LeaveRecordForm extends Model{
    public  $leavePersonId;   //请假人id
    public  $leaveReason;     // 请假原因
    public  $leaveStartTime;  //请假开始时间
    public  $leaveEndTime;    //请假结束时间

    public  $leaveTotalDays;  // 请假离开总天数
    public  $leaveLimitStatus;  //请假限制 识别
    public  $leaveArrayIndex;   // 请假数组下标

    public $leaveType; //请假类型

    public  $memberCardId;   //会员卡id
    /**
     * @云运动 - 后台 - 会员卡表单修改验证(规则验证)
     * @create 2017/4/8
     * @return array
     */
    public function rules()
    {
        return [
            [['leavePersonId',"leaveStartTime","leaveEndTime","memberCardId","leaveTotalDays","leaveType"], 'required'],
            [["leaveReason","leaveArrayIndex","leaveLimitStatus"],"safe"],
        ];
    }
    /**
     * 云运动 - 验卡管理 - 会员请假记录数据保存
     * @author houkaixin <houkaixin@itsports.club>
     * $requestSource   // 请求来源 （用来区分app 或则 pc端的请求标识）
     * @create 2017/5/13
     * @return string
     */
    public function leaveRecord($requestSource = ""){
        $days  = round(($this->leaveEndTime - $this->leaveStartTime)/24/60/60);    //请假天数
        $card  = MemberCard::findOne(['id' => $this->memberCardId]);
        if ($card['status'] == 6) {
            return '对不起，您的会员卡已过期，请您及时续费或更换新卡！';
        }
        if ($card['invalid_time'] < strtotime($this->leaveEndTime)) {
            return '对不起,请假最多能请到会员卡到期那一天！';
        }
        $leave = json_decode($card['leave_days_times'],true);
        if($this->leaveType == 1){
            if(!empty($card['leave_least_days'])){
                if($days < $card['leave_least_days']){
                    return '该卡每次请假天数不得少于'.$card['leave_least_days'].'天';
                }
            }elseif(!empty($card['leave_days_times'])){
                if($days > $leave[$this->leaveArrayIndex][0]){
                    return '该卡每次请假天数不得多于'.$leave[$this->leaveArrayIndex][0].'天';
                }
            }
            $leaveRecordData = LeaveRecord::find()
                ->where(['and',['leave_employee_id'=>$this->leavePersonId],['member_card_id'=>$this->memberCardId]])
                ->andWhere(['and',['leave_type'=>2],['>','leave_end_time',time()]])
                ->andWhere(['status'=>1])
                ->asArray()->one();
            if(!empty($leaveRecordData)){
                return '该卡正在申请或在特殊请假中!';
            }
        }else if($this->leaveType == 3){
            $cardCategory = CardCategory::findOne(['id'=>$card['card_category_id']]);
            $leave1 = json_decode($cardCategory['student_leave_limit'],true);
            if(!empty($cardCategory['student_leave_limit'])) {
                if ($days > $leave1[$this->leaveArrayIndex][0]) {
                    return '该卡每次请假天数不得多于' . $leave1[$this->leaveArrayIndex][0] . '天';
                }
            }
        }

        $model = new LeaveRecord();
        $model->leave_employee_id   = $this->leavePersonId;
        $model->leave_start_time    = (int)$this->leaveStartTime;
        $model->leave_end_time      = (int)$this->leaveEndTime;

        $model->leave_length        = $days;
        $model->note                = $this->leaveReason;
        if($this->leaveType == 2){
            $model->status          = 3;//特殊请假
        }else{
            $model->status          = 1;//请假中 2.已销假
        }
        $model->create_at           = time();
        $model->member_card_id      = $this->memberCardId;
        if($this->leaveType == 3 &&$this->leaveArrayIndex == 0){
            $model->leave_type          = 4; //暑假
        }else  if($this->leaveType == 3 &&$this->leaveArrayIndex == 1){
            $model->leave_type          = 5;//寒假
        }else{
            $model->leave_type          = $this->leaveType;//1正常请假 2特殊请假
        }

        if($this->leaveType == 2){
            $model->leave_property  = 1;//2特殊请假
        }else if($this->leaveType == 1){
            $model->leave_property  = 2;//1正常请假
        }else{
            $model->leave_property  = 3;//3学生请假
        }
        if($this->leaveType == 2){
            $model->type  = 1;//2特殊请假  1待处理
        }else{
            $model->type  = 2;//1正常请假  3学生请假   2已同意
        }

        if(empty($requestSource)){
            $sell = Admin::getAdminAndEmployeeOne();  // pc端请求  （批准人自动后台获取）
        }else{
            $sell = [];                             // 来自app请求  （手机端 请假 暂无批准人）
        }
        $model->is_approval_id     = !empty($sell) ? $sell['id'] : null;
        // 请假 结果 限制（不符合规格加 限制）
        $endResult = $this->updateOne();
        if($endResult!=="success"){
           return $endResult;
        }
        $result = $model->save();
        if($result){
            return true;
        }else{
            return "请假表保存失败";
        }
    }

    /**
     * 云运动 - 请假管理- 根据所选请假 修改会员卡的请假限制
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/27
     * @return string  // 最终的请假结果
     */
    public function updateOne()
    {
        if($this->leaveType == 1) {
        $memberCard = MemberCard::findOne(["id" => $this->memberCardId]);
        switch ($this->leaveLimitStatus) {
            case 1:
                $memberCard->leave_total_days = $memberCard->leave_total_days - $this->leaveTotalDays;
                if ($memberCard->leave_total_days < 0 || $this->leaveTotalDays < $memberCard->leave_least_days) {
                    $endResult = "预留请假天数或次数不够";
                    break;
                }
                if (!$memberCard->save()) {
                    $endResult = $memberCard->errors;
                    break;
                }
                $endResult = "success";
                break;
            case 2:
                $data = json_decode($memberCard->leave_days_times);
                $data[$this->leaveArrayIndex][1] -= 1;
                if ($data[$this->leaveArrayIndex][1] < 0) {
                    $endResult = "请假次数已经用完";
                    break;
                }
                $memberCard->leave_days_times = json_encode($data);
                if (!$memberCard->save()) {
                    $endResult = $memberCard->errors;
                    break;
                }
                $endResult = "success";
                break;
            default:
                $endResult = "success";
                break;
        }
    }else if($this->leaveType == 3){
            $memberCard = MemberCard::findOne(["id" => $this->memberCardId]);
            $now        = date(time());
            $end_date   = date('Y-m-d', mktime(23, 59, 59, date('m', strtotime($now))+12, 00));
            $date       = strtotime($end_date);
            $leaveRecord1 = LeaveRecord::find()->where(['member_card_id'=>$memberCard['id']])
                ->andWhere(['and',['leave_property'=>3],['leave_type'=>4]])
                ->andWhere(['<','leave_start_time',$date])
                ->asArray()
                ->all();
            $leaveRecord2 = LeaveRecord::find()->where(['member_card_id'=>$memberCard['id']])
                ->andWhere(['and',['leave_property'=>3],['leave_type'=>5]])
                ->andWhere(['<','leave_start_time',$date])
                ->asArray()
                ->all();
            if(!empty($leaveRecord1) && $this->leaveArrayIndex==0){
                return "此会员卡已经请过暑假假期";
            }else if(!empty($leaveRecord2) && $this->leaveArrayIndex==1){
                return "此会员卡已经请过寒假假期";
            }else{
                $endResult = "success";
            }
    }else{
            $endResult = "success";
        }
        return  $endResult;
    }



}