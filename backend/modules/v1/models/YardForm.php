<?php
namespace backend\modules\v1\models;

use backend\modules\v1\models\MemberCard;
use backend\models\MemberAboutClass;
use common\models\VenueYard;
use common\models\base\AboutYard;
use yii\base\Model;

class YardForm extends Model
{
    public $accountId;              //账户ID
    public $venueId;                //场馆ID
    public $yardId;                 //场地ID
    public $memberId;               //会员ID
    public $memberCardId;           //卡ID
    public $aboutIntervalSection;   //预约时间段
    public $aboutDate;              //预约日期
    public $status;                 //状态
    public $createAt;               //创建日期
    public $aboutStart;             //预约开始时间
    public $aboutEnd;               //预约结束时间

    public function rules()
    {
        return [
            [['yardId', 'memberId', 'memberCardId', 'aboutIntervalSection', 'aboutDate', 'accountId', 'venueId'], 'required'],
            [['yardId', 'memberId', 'memberCardId', 'aboutIntervalSection', 'aboutDate', 'accountId', 'venueId'], 'safe'],
        ];
    }
    public function verify()
    {
        if(strtotime($this->aboutDate) >= (strtotime(date('Y-m-d',time())) + 7*24*60*60)){
            return ['status' => 'error', 'code' => 0, 'message' => '对不起,您只能预约最近七天的场地' , 'data' => []];
        }
        if(Fun::isLatentMember($this->accountId)){
            $ids = Fun::receiveAccount($this->accountId);
            if(YardModel::isAbout($ids, $this->venueId)){
                return ['status' => 'error', 'code' => 0, 'message' => '您是潜在会员,只能预约一次' , 'data' => []];
            }
        }
        //格式化预约时间
        $aboutDate = strtotime($this->aboutDate);
        $aboutTimes = date("Y-m-d",$aboutDate);
        $model = new YardModel();
        $sectionPeople = $model->getEveryTimeAboutPeoplesCount($this->yardId, $aboutTimes, $this->aboutIntervalSection);
        $limitPeople = VenueYard::find()->select('people_limit,business_time')->where(['id' => $this->yardId])->asArray()->one();
        if (strtotime($aboutTimes . ' ' . (explode('-', $this->aboutIntervalSection)[0])) < time()) {
            return ['status' => 'error', 'code' => 0, 'message' => '场地时间已开始,不可以预约' , 'data' => []];
        }
        if($sectionPeople>=$limitPeople['people_limit']){
            return ['status' => 'error', 'code' => 0, 'message' => '该时间段预约人数已满' , 'data' => []];
        }
        $pass = YardModel::getMemberAboutOtherStatus($this->accountId,$aboutTimes,$this->aboutIntervalSection);
        if($pass){
            return ['status' => 'error', 'code' => 0, 'message' => '您在此时间段已经有预约了' , 'data' => []];
        }
        $start = strtotime($aboutTimes . ' ' . (explode('-', $this->aboutIntervalSection)[0]));
        $end   = strtotime($aboutTimes . ' ' . (explode('-', $this->aboutIntervalSection)[1]));
        //不可以预约会员卡到期后的场地 状态：status 1正常，2异常，3冻结，4未激活，5挂起，6已过期
        $data = MemberCard::find()->where(['id' => $this->memberCardId])->select('invalid_time,status')->asArray()->one();
        if ($data['invalid_time'] < $start) {
            return ['status' => 'error', 'code' => 0, 'message' => '不可以预约会员卡到期后的场地' , 'data' => []];
        }
        if ($data['status'] == 4){
            return ['status' => 'error', 'code' => 0, 'message' => '会员卡未激活请去前台验卡' , 'data' => []];
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $aboutYard = new AboutYard();
            $aboutYard->yard_id = $this->yardId;
            $aboutYard->member_id = MemberCard::findOne(['id' => $this->memberCardId])->member_id;
            $aboutYard->member_card_id = $this->memberCardId;
            $aboutYard->about_interval_section = $this->aboutIntervalSection;
            $aboutYard->aboutDate = $aboutTimes;
            $aboutYard->status = 1;
            $aboutYard->create_at = time();
            $aboutYard->about_start = $start;
            $aboutYard->about_end = $end;
            $aboutYard->is_print_receipt = 2;
            if(!$aboutYard->save()){
                throw new \Exception('预约失败');
            }
            // 同时激活会员卡（验证规则修改激活会员卡需要管理人员手动激活）
//            if(isset($this->memberCardId)){
//                $memberCard = \common\models\base\MemberCard::findOne($this->memberCardId);
//                if(!empty($memberCard) && ($memberCard->status == 4)){
//                    $time  = $memberCard['duration'];
//                    $theData    = MemberAboutClass::editMemberCardData($memberCard, $time);
//                    if (isset($theData["activeTime"])) {
//                        $memberCard->active_time = $theData["activeTime"];
//                    }
//                    if (isset($theData["invalidTime"])) {
//                        $memberCard->invalid_time = $theData["invalidTime"];
//                    }
//                    $memberCard->status = 1;
//                    $memberCard->save();
//                }
//            }
            if($transaction->commit() === null){
                return ['status' => 'success', 'code' => 1, 'message' => '预约成功' , 'data' => $aboutYard];
            }else{
                throw new \Exception('事务执行失败');
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return ['status' => 'error', 'code' => 0, 'message' =>  $e->getMessage(), 'data' => []];
        }
    }
}