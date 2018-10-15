<?php
namespace backend\modules\v1\models;


use backend\models\AboutClass;
use backend\models\AbsentRecord;
use backend\models\MissRecords;
use backend\models\MemberCabinet;
use common\models\GroupClass;
use common\models\base\MissAboutSet;
use common\models\Member;
use common\models\base\BindPack;
use common\models\base\MemberCourseOrder;

class MemberCard extends \common\models\base\MemberCard
{
    public $cardStatus;     // 会员卡状态
    public $cardMessage;   //对应会员卡 message信息
    public $theFreezeWay;   // 冻结方式


   /**
    * @云运动 - API - 获取卡号（指定会员的卡号）
    * @author lihuien <lihuien@itsports.club>
    * @param status  // 卡种状态 （1,2,3,4）  默认[1]
    * @param
    * @create 2017/4/1
    * @inheritdoc
    */
   public function getMemberCardNum($memberId,$status=[1])
   {
       $member = MemberCard::find()
           ->where(['member_id'=>$memberId])
           ->andWhere(["in",'status',$status])
           ->andWhere(['>','invalid_time',time()])
           ->asArray()->one();
       return $member;
   }
    /**
     * @云运动 - 云运动 - 获取会员所有卡号
     * @author 侯凯新<houkaixin@itsports.club>
     * @param status   // 卡种状态 （1,2,3,4）  默认[1]
     * @param accountId  // 会员id
     * @create 2017/7/25
     * @inheritdoc
     */
   public function getAllMemberCard($accountId,$status=[1]){
       $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
       foreach ($members as $member) {
           $ids[] = $member['id'];
       }
       $member = MemberCard::find()
           ->where(['member_id'=>$ids])
           ->andWhere(["in",'status',$status])
           ->andWhere(['>','invalid_time',time()])
           ->asArray()->all();
       return $member;
   }
    /**
     * @云运动 - 云运动 - 获取单个会员卡信息
     * @author 侯凯新<houkaixin@itsports.club>
     * @param $memberCardId   // 会员卡id
     * @create 2017/7/25
     * @inheritdoc
     */
    public function getTheMemberCard($memberCardId){
          $memberCard = \common\models\base\MemberCard::find()->where(["id"=>$memberCardId])->asArray()->one();
          if(!empty($memberCard)&&!empty($memberCard["status"])){
               $status = $memberCard["status"];
          }else{
               $status  = "noCard";
          }
          return $status;
    }


    /**
     * @云运动 - API - 获取会员课程
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function getMemberClass($memberId,$memberCardId = '')
    {
        if(empty($memberCardId)){
            $member = $this->getMemberCardNum($memberId);                        //获取指定会员的会员卡信息
            if($member && !empty($member)){
                return $this->getBinkPackClass($member['card_category_id']);     //用会员卡中的卡种id 获取绑定的多态id(课程id等等)
            }else{
                return [];
            }
        }else{
            $member = \backend\models\MemberCard::getMemberCardOneById($memberCardId);                        //获取指定会员的会员卡信息
            if($member && !empty($member)){
                return $this->getBinkPackClass($member['card_category_id']);     //用会员卡中的卡种id 获取绑定的多态id(课程id等等)
            }else{
                return [];
            }
        }
    }
    /**
     * @云运动 - API - 获取会员绑定团课（指定卡种的绑定数据）
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc  //返回值 获取多态id(课程id等等)
     */
    public function getBinkPackClass($card_id)
    {
      $card =  BindPack::find()
            ->select('polymorphic_id as pid,polymorphic_ids as pidArr')
            ->where(['polymorphic_type'=>'class'])
            ->andWhere(['card_category_id'=>$card_id])
            ->asArray()
            ->all();
      $pid    = array_column($card,'pid');
      $pidArr = array_column($card,'pidArr');
      $pidArr = $this->mergeArrayClassId($pidArr);
      $data  = array_merge($pid,$pidArr);
//      $data = $this->serArrayClassId($card);        //获取多态id(课程id等等)
      return $data;
    }
    /**
     * @云运动 - API - 获取课程ID
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function serArrayClassId($data)
    {
         $class = [];
         if(isset($data) && !empty($data) && is_array($data)){
             foreach ($data as $k=>$v){
                 $class[] = $v['pid'];           //获取多态id(课程id等等)
             }
         }
        return $class;
    }
    /**
     * @云运动 - API - 获取课程ID
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function mergeArrayClassId($data)
    {
        $class = [];
        if(isset($data) && !empty($data) && is_array($data)){
            foreach ($data as $k=>$v){
                $arr = json_decode($v,true);           //获取多态id(课程id等等)
                if(is_array($arr)){
                    foreach ($arr as $value){
                        $class[] = $value;
                    }
                }else{
                    $class[] = $v;
                }
            }
        }
        return $class;
    }
    /**
     * @云运动 - API - 获取私教课程ID
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function getMemberIsCharge($memberId)
    {
        $charge =  MemberCourseOrder::find()->select('product_id as pid')->where(['member_id'=>$memberId])->asArray()->all();
        return $this->serArrayClassId($charge);
    }

    /**
     * @云运动 - API - 获取会员卡信息
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/27
     * @param $memberCardId //会员卡id
     * @param $memberId     //会员id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberCardInfo($memberCardId)
    {
        $memberCard = \backend\models\MemberCard::find()
                       ->alias("memberCard")
                       ->where(['memberCard.id'=>$memberCardId])
                       /*->andWhere(['memberCard.member_id'=>$memberId])*/
                       ->joinWith(["leaveRecord leaveRecord"],false)
                       ->joinWith(["organization organization"],false)
                       ->select('
                                memberCard.card_number as cardNumber,memberCard.create_at as createAt,memberCard.amount_money as amountMoney,
                                memberCard.status,memberCard.active_time as activeTime,
                                memberCard.invalid_time as invalidTime,memberCard.balance,memberCard.card_name as cardName,memberCard.duration,memberCard.surplus,
                                memberCard.member_id,sum((leaveRecord.leave_end_time-leaveRecord.leave_start_time)) as days,
                                sum(leaveRecord.leave_length) as leaveRecordDay,
                                count(leaveRecord.id) as leaveTimes,
                                memberCard.card_category_id as cardCategoryId,
                                memberCard.venue_id,
                                organization.name as venueName,
                                memberCard.pic,
                                ')
                       ->asArray()
                       ->one();
        if ($memberCard['invalidTime'] < time()) {
            \backend\models\MemberCard::updateAll(['status' => 6], ['id' => $memberCardId]);
        }
           $memberCard['balance']      = !empty($memberCard['balance'])?$memberCard['balance']:0 ;          //剩余金额
           $memberCard["amountMoney"] = !empty($memberCard['amountMoney'])?$memberCard['balance']:0;       // 总金额
           $memberCard["venueName"]   = !empty($memberCard['venueName'])?$memberCard['venueName']:"暂无数据";  // 场馆名称
           $memberCard["pic"]          = !empty($memberCard["pic"])?$memberCard["pic"]:CardCategory::CARD_PIC;
           if($memberCard['status'] !=4)
           {
               $memberCard['createAt']    = date('Y-m-d',$memberCard['createAt']);                     //开卡时间
               $memberCard['lastTime']    = (int)floor(($memberCard['invalidTime'] - time())/86400);        //剩余天数
               $memberCard['invalidTime'] =  !empty($memberCard["invalidTime"])?date("Y-m-d",$memberCard["invalidTime"]):"暂无数据";
               $memberCard['activeTime']  =  !empty($memberCard["activeTime"]) ?date("Y-m-d",$memberCard["activeTime"]):"暂无数据";
               $memberCard["totalDay"]     =  ($memberCard['invalidTime']!="暂无数据")&&(!empty($memberCard['activeTime']!="暂无数据"))?ceil((strtotime($memberCard['invalidTime']) - strtotime($memberCard['activeTime']))/(60*60*24)):"暂无数据";
               $memberCard["timeDescribe"] =  $memberCard['lastTime']."/".$memberCard["totalDay"];    //  剩余时间/总时间
               $memberCard["moneyDescribe"] = $memberCard['balance']."/".$memberCard["amountMoney"];  // 剩余金额/总金额
               $timeLimit =  ($memberCard['activeTime']!="暂无数据")&&($memberCard['invalidTime']!="暂无数据")?$memberCard['activeTime']." 至 ".$memberCard['invalidTime']:"暂无数据";
               //$memberCard["leaveRecordDay"] = !empty($memberCard["days"])?floor($memberCard["days"]/86400):0;         // 已经请假天数
               $memberCard["leaveRecordDay"] = intval($memberCard["leaveRecordDay"]);
               $memberCard["timeLimit"]   =  ["date"=>$timeLimit];        // 时间期限
               $memberCard["surplus"]     = empty($memberCard["surplus"])?0:$memberCard["surplus"];                   // 转让次数
           }else if($memberCard['status'] == 4){
               $memberCard['invalidTime']   =  !empty($memberCard["invalidTime"])?date("Y-m-d",$memberCard["invalidTime"]):"暂无数据";
               $memberCard['createAt']    = date('Y-m-d',$memberCard['createAt']);                     //开卡时间
               $memberCard['activeTime']  = "暂无数据";                                                         //激活时间
               $memberCard['balance']     = $memberCard['balance']?$memberCard['balance']:0;           //剩余金额
               $memberCard['lastTime']    = (int)($memberCard['duration']);                            //剩余多少天                //失效日期
               $memberCard["moneyDescribe"] = $memberCard['balance']."/".$memberCard["amountMoney"];  // 剩余金额/总金额
               $memberCard["timeDescribe"] = "该卡未激活";
               $memberCard["timeLimit"]   =  ["date"=>"该卡未激活"];                                // 时间期限
               $memberCard["leaveRecordDay"] = !empty($memberCard["days"])?floor($memberCard["days"]/86400):0;
               $memberCard["surplus"]      = empty($memberCard["surplus"])?0:$memberCard["surplus"];  // 转让次数
           }
        return $memberCard;
    }

    /**
     * @云运动 - API - 获取会员所有会员卡信息
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/27
     * @param $accountId      //账户id
     * @param $venueId      //场馆id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMemberCardAll($accountId,$venueId,$source)
    {
        //类实例化  初始化赋值   获取场馆冻结方式
        $leagueModel = new AbsentRecord();
        $missRecords = new MissRecords();
        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        if ($members) {
            $ids = array();$venue_id = array();
            foreach ($members as $k => $v) {
                if (empty($venueId)) {
                    $ids[] = $members[$k]['id'];
                    $venue_id[] = $members[$k]['venue_id'];
                } else {
                    if ($members[$k]['venue_id']<>$venueId) {
                        unset($members[$k]['id']);
                        unset($members[$k]['venue_id']);
                    } else {
                        $ids[] = $members[$k]['id'];
                        $venue_id[] = $members[$k]['venue_id'];
                    }
                }
            }
            !empty($venue_id) ? $venue_id : null;
            $freeze      = $leagueModel->gainFreezeWay($venue_id);   // 场馆冻结方式
            $missRecords->venueId = $venue_id;
            $type      = isset($freeze["freeze_way"])&&(!empty($freeze["freeze_way"]))?$freeze["freeze_way"]:3;           // 获取场馆冻结方式
            $customDay = isset($freeze["freeze_day"])&&(!empty($freeze["freeze_day"]))?$freeze["freeze_day"]:null;       // 获取冻结天数
            $missRecords->freezeWay     = $type;      // 冻结方式
            $this->theFreezeWay        = $type;
            $missRecords->accountId      = $accountId;  // 账户id
            $missRecords->isRequestMember  = "isMember";  // 是否是app的请求
            //判断会员卡是否可以解冻
            $missRecords->automaticThaw();           // 根据场馆 预设置的冻结方式 自动解冻
            //获取场馆冻结方式
            $memberCard = $this->gainSql($ids,$source);   // 获取会员卡的sql （公共sql）
            // 计算被冻结后剩余天数
            $memberCard   = $this->delSql($type,$memberCard,$customDay);
            $memberCard = $this->arrangeData($memberCard);
            // 判断所持会员卡状态
            $this->cardStatus = $this->judgeEndStatus($memberCard);
            // 判断各个会员卡 是否处于请假状态
            return $memberCard;
        }
    }
    /**
     * @云运动 - API - 按指定规则的 会员卡公共sql
     * @author houkaixn <houkaixn@itsports.club>
     * @param  $accountId  // 账户id
     * @create 2017/6/27
     * @return string
     */
    public function gainSql($ids,$source){
        $sql =  \backend\models\MemberCard::find()
            ->alias("memberCard")
            ->where(["and",['>','memberCard.invalid_time',time()],['memberCard.member_id'=>$ids]]);
        if ($source == 'leave'){
            $sql->andWhere(['NOT',['memberCard.status'=>[2,3]]]);
        }
        $sql->andWhere(['or',['memberCard.usage_mode' => 1],['memberCard.usage_mode' => NULL]])
            ->joinWith(["leaveRecordIos leaveRecord"=>function($query){
                $query->select("leaveRecord.id,leaveRecord.member_card_id");
            }]);
            if($source == 'leave'){
                $sql->andWhere(['leaveRecord.id'=>null]);
            }
            $sql->joinWith(["member mm"=>function($query){
                $query->joinWith(["venue o"]);
                $query->select("o.name as venue_name");
            }],false)
            ->joinWith(["missAboutSet missAboutSet"],false);
        return $sql;
    }
    /**
     * @云运动 - API - 根据不同的冻结规则拼接sql
     * @author houkaixn <houkaixn@itsports.club>
     * @param  $type  // 冻结方式
     * @param  $sql   //公共sql
     * @param  $customDay // 预先设置冻结天数
     * @create 2017/6/27
     * @return string
     */
    public function  delSql($type,$sql,$customDay){
        if($type==1){
             $lastMonthTime = strtotime(date('Y-m-t'));               // 获取当前月最后一天
             $data = $sql->select("memberCard.id,memberCard.member_id as memberId,o.name as venue_name,memberCard.id as memberCardId,memberCard.card_number as cardNumber,,memberCard.pic,
            memberCard.card_name as cardName,memberCard.active_time,memberCard.invalid_time,memberCard.status as cardStatus,memberCard.venue_id,
            memberCard.absentTimes,memberCard.recent_freeze_reason,memberCard.recent_freeze_reason,missAboutSet.freeze_way,missAboutSet.miss_about_times,missAboutSet.punish_money,(cast(missAboutSet.miss_about_times as signed)-cast(memberCard.absentTimes as signed)) as surplusTimes,
            memberCard.last_freeze_time,ceil((({$lastMonthTime}-memberCard.last_freeze_time)/(60*60*24))) as surplusThawDays")
              ->asArray()
              ->all();
        }
        if($type==2){
            $time    = time();
            $endTime = $customDay*60*60*24;
            $lastMonthTime = strtotime(date('Y-m-t'));               // 获取当前月最后一天
            $data = $sql->select("memberCard.id,memberCard.member_id as memberId,o.name as venue_name,memberCard.id as memberCardId,memberCard.card_number as cardNumber,
            memberCard.card_name as cardName,memberCard.active_time,memberCard.invalid_time,memberCard.status as cardStatus,memberCard.venue_id,,memberCard.pic,
            memberCard.absentTimes,memberCard.recent_freeze_reason,memberCard.recent_freeze_reason,missAboutSet.freeze_way,missAboutSet.miss_about_times,missAboutSet.punish_money,(cast(missAboutSet.miss_about_times as signed)-cast(memberCard.absentTimes as signed)) as surplusTimes,
            memberCard.last_freeze_time,ceil((memberCard.last_freeze_time+{$endTime}-{$time})/(24*60*60)) as surplusDay")
              ->asArray()
              ->all();
        }
        if($type==3){
            $data = $sql->select("memberCard.id,memberCard.member_id as memberId,memberCard.id as memberCardId,o.name as venue_name,memberCard.card_number as cardNumber,memberCard.pic,
            memberCard.card_name as cardName,memberCard.active_time,memberCard.invalid_time,memberCard.status as cardStatus,memberCard.venue_id,
            memberCard.absentTimes,memberCard.recent_freeze_reason,memberCard.recent_freeze_reason,(3) as freeze_way,missAboutSet.miss_about_times,missAboutSet.punish_money,('暂无数据') as surplusTimes,memberCard.last_freeze_time,('暂无数据') as surplusDay")
              ->asArray()
              ->all();
        }
        return $data;
    }
    /**
     * @云运动 - API - 会员卡数据整理
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/8/01
     * @param $data       //会员卡
     * @return boolean        // true 是有正常的卡 false 是没有正常的卡
     */
    public function arrangeData($data){
         if(empty($data)){
             return $data;
         }
         foreach($data as $keys=>$value) {
            $data[$keys]["absentTimes"] = ($data[$keys]["absentTimes"]===null)?0:$data[$keys]["absentTimes"];
            $data[$keys]["pic"]          = !empty($data[$keys]["pic"])?$data[$keys]["pic"]:CardCategory::CARD_PIC;
            if(count($value["leaveRecordIos"])==0){
                $data[$keys]["leaveRecordStatus"] = 0;
            }else{
                $data[$keys]["leaveRecordStatus"] = 1;
            }
         }
        return $data;
    }
    /**
     * @云运动 - API - 判断会员所持卡有没有正常状态的卡
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/8/01
     * @param $memberCard      //会员卡
     * @return boolean        // true 是有正常的卡 false 是没有正常的卡
     */
    public function judgeEndStatus($memberCard){
        // 没有卡返回false
        if(empty($memberCard)){
            $this->cardMessage = "请先办理会员卡";
            return 2;
        }
        // 有卡
        foreach ($memberCard as $keys=>$values){
            $status = $values["cardStatus"];
            if($status==1){
                 $this->cardMessage = "验卡成功";
                 return 1;    // 检查到有正常的卡 停止 循环
            }
        }
        $this->cardMessage = "请检查您的会员卡是否正常或激活";
        return 3;           // 所有的卡都不正常
    }




    /**
     * @云运动 - API - 获取会员所有柜子信息
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/27
     * @param $accountId      //账户id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMemberCabinet($accountId)
    {   
        $cabinet  = new \backend\models\MemberCabinet();
        $data     = $cabinet->getMemberCabinetAll($accountId);
        return $data;
    }
    /**
     * @云运动 - API - 获取会员柜子详细信息
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/27
     * @param $memberCabinetId      //会员柜子id
     * @param $memberId             //会员id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMemberCabinetInfo($memberCabinetId,$memberId)
    {
        $cabinet  = new \backend\models\MemberCabinet();
        $data     = $cabinet->getMemberCabinetInfo($memberCabinetId,$memberId);
        return $data;
    }
    /**
     * @云运动 - API - 获取会员/卡的旷课记录
     * @author houkaixin <houkaixin@itsports.club>
     * @param  $identify   // 获取旷课记录的对象（卡或会员）
     * @param  $identifyId  // 旷课身份的id
     * @param  $courseType  // 课程类型
     * @create 2017/10/06
     * @return array
     */
    public function gainAbsentRecord($identify,$identifyId,$courseType){
        if(empty($identify)||empty($identifyId)||empty($courseType)){
            return "缺少传入的参数";
        }
        $members = Member::find()->where(['member_account_id' => $identifyId])->asArray()->all();
        foreach ($members as $member) {
            $ids[] = $member['id'];
        }
        $data = $this->gainPublicSql();
        if($identify=="card"&&($courseType!=3)){           // 卡 courseType 1代表私课 2代表团课 旷课记录
            $data = $data->andWhere(["and",["aboutClass.member_card_id"=>$identifyId],["aboutClass.type"=>$courseType]]);
        }elseif($identify=="card"&&($courseType==3)){      //卡 私课和团课的旷课记录
            $data = $data->andWhere(["and",["aboutClass.member_card_id"=>$identifyId]]);
        }elseif($identify=="member"&&($courseType!=3)){    // 会员  courseType 1代表私课 2代表团课 旷课记录
            $data = $data->andWhere(["and",["aboutClass.member_id"=>$ids],["aboutClass.type"=>$courseType]]);
        }else{
            $data = $data->andWhere(["aboutClass.member_id"=>$ids]);
        }
        $data = $data->asArray()->all();
        $data = $this->dealPrivateCourseName($data);
        return $data;
    }
    /**
     * @云运动 - API - 如果是 私教课 对 私教课程名称重新赋值
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/01/02
     * @return array
     */
    public function dealPrivateCourseName($data){
      // 1：确定是否有爽约的私课
      if(empty($data)){
          return [];
      }
      foreach ($data as $keys=>$value){
          if(empty($data[$keys]["courseName"])){
              $data[$keys]["courseName"] = $data[$keys]["product_name"];
          }
      }
      return $data;
    }
    /**
     * @云运动 - API - 获取会员/卡的旷课记录(公共sql)
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/10/06
     * @return array
     */
    public function gainPublicSql(){
        $data = AboutClass::find()
            ->alias("aboutClass")
            ->where(["and",["aboutClass.status"=>[6,7]],["aboutClass.is_print_receipt"=>2],["<","aboutClass.start",time()]])  // 状态不是取消状态，课程类型，没有打印小票，课程已经开始、
            ->joinWith(["member member" => function($query){
                $query->joinWith(["venue as v"],false);
            }],false)
            ->joinWith(["groupClass groupClass"=>function($query){
                $query->joinWith(["course course"])
                      ->joinWith(["classroom classroom"]);
                $query->joinWith(["organization as o"],false);
            }],false)
            ->joinWith(['memberCard memberCard'=>function($query){
            }],false)
            ->joinWith(["memberCourseOrderDetails memberCourseOrderDetails"],false)
            ->joinWith(["employee coach"],false)
            ->select("
                      aboutClass.id,   
                      aboutClass.member_card_id,
                      aboutClass.class_id,
                      aboutClass.coach_id,
                      aboutClass.type,
                      coach.name as coachName,         
                      classroom.name as classroomName,
                      course.name as courseName,
                      memberCourseOrderDetails.product_name,
                      memberCard.card_name as memberCardName,
                      aboutClass.start as courseStartTime,
                      aboutClass.end as courseEndTime,
                      aboutClass.class_date as courseDate,
                      aboutClass.type as courseType,
                      aboutClass.status as classStatus,
                      o.name as venue_name,
                      v.name as venueName,
                      ")
               ->orderBy(["aboutClass.start"=>SORT_DESC]);
        return $data;
    }

    public function getMember()
    {
        return $this->hasOne(\backend\models\Member::className(), ['id' => 'member_id']);
    }

    /**
     * @会员端 - API - 获取账户id
     * @author xinwei <xinwei@itsports.club>
     * @create 2018/04/24
     * @return array
     */
    public function getAccountId($memberCardId)
    {
        $accountId = AboutClass::find()
            ->alias('ac')
            ->joinWith(["member m"],false)
            ->where(['ac.member_card_id' => $memberCardId])
            ->select('member_account_id')
            ->asArray()
            ->one();
        return $accountId;
    }
    //https请求(支持GET和POST)
    function getHttpRequest($url,$data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;

    }
}