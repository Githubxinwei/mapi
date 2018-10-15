<?php
namespace backend\models;
use backend\modules\v1\models\MemberCard;
use backend\modules\v1\models\Member;
use common\models\base\AboutClass;
use common\models\base\MissAboutSet;
use common\models\Func;

Class MissRecords extends  AboutClass{
    public $venueId;     // 场馆id
    public $cardStatus;  // 卡状态
    public $sorts;       // 排序
    public $keywords;   // 主搜索栏搜索
    public $punishMoney;  // 罚款金额
    public $freezeWay;   // 解冻方式
    public $accountId;   // 解冻方式
    public $freezeDay;  // 冻结天数
    public $isRequestMember; // 请求来源（是否来自app请求）

    /**
     * 后台 - 新团课管理 -  团课爽约设置
     * @author  <houkaixin@itsports.club>
     * @create 2017/9/14
     * @param  $data
     * @return object     //返回会员爽约记录信息
     */
     public function getData($data,$venueId){
         $this->customLoad($data,$venueId);
         $this->automaticThaw();  // 按照指定方式解冻
         $query = \backend\models\MemberCard::find()->alias("memberCard")
                  ->joinWith(["member member"=>function($query){
                       $query->joinWith(["memberDetails MemberDetail"]);
                  }],false)
                  ->joinWith(["missAboutSet missAboutSet"],false)
                  ->where([">","memberCard.invalid_time",time()])
                  ->andFilterWhere(["member.venue_id"=>$this->venueId])
                  ->andWhere(["is not","member.id",null])
                  ->orderBy($this->sorts)
                  ->groupBy("memberCard.id");
         $query =  $this->delSql($query);
         $query          = $this->getSearchWhere($query);
         $data           = Func::getDataProvider($query,8);
         $data->models  = $this->updateData($data->models);
         return $data;
     }
    /**
     * 后台 - 新团课管理 -爽约（自动解冻（两种方式））
     * @author  <houkaixin@itsports.club>
     * @create 2017/9/14
     * @param  $data      //分页数据
     * @return object     //返回会员爽约记录信息
     */
    public function updateData($data){
        if(!empty($data)){
           foreach($data as $keys=>$values){
                if(!(($values["status"]==3)&&($values["recent_freeze_reason"]==1))){
                    $data[$keys]["surplusDay"] = "暂无数据";
                }
           }
        }
        return $data;
    }
    /**
     * 后台 - 新团课管理 -爽约（自动解冻（两种方式））
     * @author  <houkaixin@itsports.club>
     * @create 2017/9/14
     * @return object     //返回会员爽约记录信息
     */
    public function automaticThaw(){
        // 第一种解冻方式
        $nowDate       = date("Y-m-d",time());
        $nowBeginMonth = date("Y-m",time())."-"."01";
        if($this->freezeWay==3){
            return null;
        }
        if(($nowDate==$nowBeginMonth)&&($this->freezeWay==1)){
           $this->MonthBeginFreeze(1);   // 月初解冻
           return true;
        }
        if($this->freezeWay==2){
            $this->MonthBeginFreeze(2);   // 按照设定日期解冻
            return true;
        }
        return true;
    }
    /**
     * 后台 - 新团课管理 -爽约（自动解冻（两种方式）） - 解冻后默认值的设置
     * @author  <houkaixin@itsports.club>
     * @param $type       // 冻结方式
     * @create 2017/9/14
     * @return object     //返回会员爽约记录信息
     */
    public function MonthBeginFreeze($type){
        $freezeMemberCardId = $this->checkAllFrozenCard($type);
        MemberCard::updateAll(["last_freeze_time"=>null,"absentTimes"=>0,"status"=>1,"recent_freeze_reason"=>2],["id"=>$freezeMemberCardId]);
    }
    /**
     * 后台 - 新团课管理 -爽约（自动解冻（两种方式）） - 解冻后默认值的设置
     * @author  <houkaixin@itsports.club>
     * @param $type       // 冻结方式
     * @create 2017/9/14
     * @return object     //返回会员爽约记录信息
     */
    public function checkAllFrozenCard($type){
        $freezeMemberCardId = MemberCard::find()->where(["venue_id"=>$this->venueId])->andWhere(["and",["recent_freeze_reason"=>1],["status"=>3]]);
        if($type==1){
            $freezeCard = $freezeMemberCardId->select("id")->asArray()->column();
        }else{
            // 获取冻结方式
            $data        = MissAboutSet::find()->where(["venue_id"=>$this->venueId])->select("freeze_day")->asArray()->one();
            $freezeTime  = !empty($data["freeze_day"])?$data["freeze_day"]*60*60*24:null;
            if(empty($this->isRequestMember)){   // 针对 所有卡的自动解冻
                $freezeMemberCardId = $freezeMemberCardId->select("id,absentTimes,last_freeze_time,({$freezeTime}+last_freeze_time) as endTime")->asArray()->all();
            }else{                                 // 针对 特定会员所持卡的自动解冻
                $members = Member::find()->where(['member_account_id' => $this->accountId])->asArray()->all();
                foreach ($members as $member) {
                    $ids[] = $member['id'];
                }
                $freezeMemberCardId = $freezeMemberCardId->andWhere(["member_id" => $ids])->select("id,absentTimes,last_freeze_time,({$freezeTime}+last_freeze_time) as endTime")->asArray()->all();
            }
            $freezeCard  =  $this->gainFreezeCard($freezeMemberCardId);
        }
        return $freezeCard;
    }
    /**
     * 后台 - 新团课管理 - 获取对应sql（根据请求来源）
     * @author  <houkaixin@itsports.club>
     * @create 2017/10/5
     * @return object     //返回对应sql
     */
   public function gainTheSql(){

   }


    /**
     * 后台 - 新团课管理 -爽约（自动解冻（两种方式）） - 收集符合解冻的会员卡id
     * @author  <houkaixin@itsports.club>
     * @param $freezeMemberCardId      // 被冻结的卡id
     * @create 2017/9/14
     * @return object     //返回会员爽约记录信息
     */
    public function gainFreezeCard($freezeMemberCardId){
        $freezeCard = [];
        if(empty($freezeMemberCardId)){
            return $freezeCard;
        }
        foreach ($freezeMemberCardId as $keys=>$value){
            if(time()>$value["endTime"]){
                $freezeCard[] = $value["id"];
            }
        }
        return $freezeCard;
    }
    /**
     * 后台 - 新团课管理 -爽约（自动解冻（两种方式）） - 解冻后默认值的设置
     * @author  <houkaixin@itsports.club>
     * @param $query        // 初始化sql
     * @create 2017/9/14
     * @return object     //返回会员爽约记录信息
     */
    public function delSql($query){
        $lastMonthTime = strtotime(date('Y-m-t'));               // 获取当前月最后一天
       if($this->freezeWay==1){                                 // 当月冻结到月末
           $query = $query->select("memberCard.id,memberCard.member_id,memberCard.card_number,memberCard.invalid_time,memberCard.status,
                         memberCard.absentTimes,memberCard.frozen_start_time,memberCard.frozen_end_time,memberCard.recent_freeze_reason,
                         MemberDetail.name as memberName,member.mobile,memberDetail.sex as memberSex,
                         missAboutSet.freeze_way,missAboutSet.punish_money,ceil((({$lastMonthTime}-memberCard.last_freeze_time)/(60*60*24))) as surplusDay,memberCard.create_at");
       }
       if($this->freezeWay==2){                               //自定义冻结天数
           $endTime = ($this->freezeDay)*24*60*60;
           $time    = time();
           $query =  $query->select("memberCard.id,memberCard.member_id,memberCard.card_number,memberCard.invalid_time,memberCard.status,
                         memberCard.absentTimes,memberCard.frozen_start_time,memberCard.frozen_end_time,memberCard.recent_freeze_reason,
                         MemberDetail.name as memberName,member.mobile,memberDetail.sex as memberSex,
                         missAboutSet.freeze_way,missAboutSet.punish_money,ceil((memberCard.last_freeze_time+{$endTime}-{$time})/(24*60*60)) as surplusDay,memberCard.create_at");
       }
       if($this->freezeWay==3){
           $query =  $query->select("memberCard.id,memberCard.member_id,memberCard.card_number,memberCard.invalid_time,memberCard.status,
                         memberCard.absentTimes,memberCard.frozen_start_time,memberCard.frozen_end_time,memberCard.recent_freeze_reason,
                         MemberDetail.name as memberName,member.mobile,memberDetail.sex as memberSex,
                         missAboutSet.freeze_way,missAboutSet.punish_money,('暂无数据') as surplusDay,memberCard.create_at");
       }
       return  $query;
    }
    /**
     * 后台 - 爽约 -  爽约罚款金额
     * @author  <houkaixin@itsports.club>
     * @create 2017/9/14
     * @param  $venueId   //场馆金额
     * @return int        //罚款金额
     */
    public function getVenueRule($venueId){
         $data  = MissAboutSet::find()
                  ->where(["venue_id"=>$venueId])
                  ->asArray()
                  ->all();
         $punishMoney = empty($data["punish_money"])?null:$data["punish_money"];
         return $punishMoney;
    }
    /**
     * 后台 - 新团课管理 - 团课搜索
     * @author  <houkaixin@itsports.club>
     * @create 2017/9/18
     * @param  $query      // 执行sql语句
     * @return object     //拼接sql语句
     */
    public function getSearchWhere($query){
        if(!empty($this->cardStatus)){
           $query   =  $query->andFilterWhere(["memberCard.status"=>$this->cardStatus]);
        }
        if(!empty($this->keywords)){
            $query  =   $query->andFilterWhere(["or",["like","member.mobile",$this->keywords."%",false],["like","MemberDetail.name",$this->keywords],["like","memberCard.card_number",$this->keywords]]);
        }
        return $query->asArray();
    }
    /**
     * 后台 - 新团课管理 - 爽约数据处理
     * @author  <houkaixin@itsports.club>
     * @create 2017/9/18
     * @param  $data
     * @return object     //拼接sql语句
     */
    public function dealData($data){
          if(empty($data)){
              return $data;
          }
          foreach($data as $keys=>$values){
                 $data[$keys]["absentTimes"] = empty($values["absentTimes"])?0:$values["absentTimes"];
                 if(empty($data[$keys]["frozen_end_time"])&&($values["status"]==3)){
                     $data[$keys]["surplusDay"] = "场馆冻结";
                     continue;
                 }elseif (!empty($data[$keys]["frozen_end_time"])&&(time()<$data[$keys]["frozen_end_time"])&&($values["status"]==3)){
                     $data[$keys]["surplusDay"] = (time()-$data[$keys]["frozen_end_time"])/(60*60*24);
                     $data[$keys]["surplusDay"] = ceil($data[$keys]["surplusDay"]);
                     continue;
                 }
                     $data[$keys]["surplusDay"] = "暂无数据";

          }
        return $data;
    }
    /**
     * 后台 - 新团课管理 - 数据初始加载
     * @author  <houkaixin@itsports.club>
     * @create 2017/9/18
     * @param  $data
     * @return boolean
     */
    public function customLoad($data,$venueId){
        $this->venueId      = empty($venueId)?null:$venueId;
        $this->cardStatus   = empty($data["cardStatus"])?null:$data["cardStatus"];
        $this->punishMoney  = empty($data["punish_money"])?null:$data["punish_money"];
        $this->keywords     = empty($data["keywords"])?null:$data["keywords"];
        $this->freezeWay    = empty($data["freeze_way"])?null:$data["freeze_way"];
        $this->freezeDay    = empty($data["freeze_day"])?null:$data["freeze_day"];
        $this->sorts         = self::loadSort($data);
        return true;
    }
    /**
     * 后台 - 新团课管理 - 数据排序
     * @author  <houkaixin@itsports.club>
     * @create 2017/9/18
     * @param  $data
     * @return array
     */
    public function loadSort($data){
        $sorts = ["FIELD(memberCard.status,'3','1','2','4','5')"=>true,"memberCard.absentTimes"=>SORT_DESC];
        if (empty($data['sortType'])) {
            return $sorts;
        }
        switch ($data['sortType']){
            case 'memberName'  :  // 会员名称
                $attr = 'memberDetail.memberName';
                break;
            case 'sex'  :         // 会员性别
                $attr = 'memberDetail.sex';
                break;
            case 'mobile':        // 会员手机号
                $attr = 'member.mobile';
                break;
            case 'missTimes':     // 爽约次数
                $attr = 'memberCard.absentTimes';
                break;
            case 'cardStatus' :   // 卡状态
                $attr = 'memberCard.status';
                break;
            case "cardNumber":   // 会员卡号
                $attr = "memberCard.card_number";
                break;
            case "course_difficulty":
                $attr = "surplusDay";
                break;
            default:
                $attr = 'memberCard.status';
                break;
        }
        if($attr!=="memberDetail.sex"){
            $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
        }
        if(($attr=="memberDetail.sex")&&($data['sortName']=="ASC")){
            $sorts = ["isnull(memberDetail.sex)"=>SORT_ASC,"FIELD(memberDetail.sex,'1','2','0')"=>true,"memberCard.absentTimes"=>SORT_DESC];
        }
        if(($attr=="memberDetail.sex")&&($data['sortName']=="DES")){
            $sorts = ["isnull(memberDetail.sex)"=>SORT_ASC,"FIELD(memberDetail.sex,'2','1','0')"=>true,"memberCard.absentTimes"=>SORT_DESC];
        }
        return $sorts;
    }
    /**
     * 后台 - 新团课管理 - 数据排序
     * @author  <houkaixin@itsports.club>
     * @create 2017/9/18
     * @param  $sort  // 排序字段
     * @return string
     */
    public static function convertSortValue($sort)
    {
        if ($sort == 'ASC') {
            return SORT_ASC;
        } elseif ($sort == 'DES') {
            return SORT_DESC;
        }else{
            return SORT_ASC;
        }
    }
    /**
     * 后台 - 新团课管理 - 数据排序
     * @author  <houkaixin@itsports.club>
     * @create 2017/9/18
     * @param  $venueId  // 场馆id
     * @return string
     */
    public function delMissSet($venueId){
      $data = MissAboutSet::findOne(["venue_id"=>$venueId]);
      // 解冻当前场馆所有因爽约被冻结的卡
      \backend\models\MemberCard::updateAll(["status"=>1,"recent_freeze_reason"=>2,"last_freeze_time"=>null],["and",["venue_id"=>$venueId],["status"=>3],["recent_freeze_reason"=>1],[">","invalid_time",time()]]);
      if(empty($data)){
         return true;
      }
      $data->delete();
      return true;
    }




}