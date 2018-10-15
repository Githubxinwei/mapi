<?php
namespace backend\models;

use common\models\base\AboutClass;
use common\models\base\VenueYard;
use common\models\base\VenueYardCardcategory;
use common\models\Func;
use common\models\relations\AboutYardRelations;

class AboutYard extends \common\models\base\AboutYard{
    use AboutYardRelations;
    public $memberAboutDate;       // 会员预约日期
    public $aboutIntervalSection; //会员预约时间段
    public $sorts;                  // 字段排序
    public $yardId;                 // 场地id

    public $classStart;             // 开始上课
    public $classEnd;              // 课程结束时间

    public $classStatus;           // 上课状态

    public $venueId;                // 场地id
    
    /**
     * 后台 - 场地预约 - 会员预约分页信息
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/09/08
     * @param $params   // 分页搜索请求参数
     * @return array    // 分页返回数据
     */
    public function getYardAboutDetailData($params)
    {
        $this->customLoad($params);
        define("classStart",strtotime($this->classStart));
        define("classDate",strtotime($this->memberAboutDate." "."00:01"));
      //  $this->initYardAboutStatus();    // 初始化场馆数据
        $query = AboutYard::find()->alias("aboutYard")
                 ->joinWith(["member member"=>function($query){
                      $query->joinWith(["memberDetails memberDetail"]);
                 }],false)
                 ->joinWith(["venueYard venueYard"],false)
                 ->select("aboutYard.*,member.mobile,memberDetail.name as username,aboutYard.status,aboutYard.member_id,venueYard.yard_name")
                 ->where(["and",["!=","aboutYard.status",5],["about_interval_section"=>$this->aboutIntervalSection]])
                 ->asArray();
        $query = $this->setWhereSearch($query);
        $data  = Func::getDataProvider($query,5);
        return  $data;
    }
    public function initYardAboutStatus(){
        if(time()>=strtotime($this->classStart)){
          $data =  AboutYard::find()->alias("aboutYard")
                             ->where(["and",["!=","aboutYard.status",5],["aboutYard.about_interval_section"=>$this->aboutIntervalSection],["aboutYard.yard_id"=>$this->yardId]])
                             ->joinWith(["entryRecord entryRecord"=>function($query){
                                     $query->select("entryRecord.id,entryRecord.member_id");
                             }])
                             ->select("aboutYard.id,aboutYard.member_id,aboutYard.yard_id")
                             ->asArray()
                             ->all();
          if(!empty($data)){
              $this->updateYardAboutStatus($data);
          }
        }
    }
    public function updateYardAboutStatus($data){
          // 爽约会员
        $missAboutYard      = [];
          // 上课中
        $classingAboutYard  = [];
          // 已下课会员
        $classOverAboutYard = [];
          foreach($data as $keys=>$value){
             if(count($value["entryRecord"])==0){
                 $missAboutYard[] = $value["id"];
                 continue;
             }
             if(time()>=strtotime($this->classStart)&&(time()<=strtotime($this->classEnd))){
                 $classingAboutYard[]  = $value["id"];
                 continue;
             }
             if(time()>strtotime($this->classEnd)){
                 $classOverAboutYard[] = $value["id"];
                 continue;
             }
          }
        $this->updateMemberStatus($missAboutYard,$classingAboutYard,$classOverAboutYard);
    }

    public function updateMemberStatus($missClassMember,$classingMember,$classOverMember){
          // 旷课会员
        AboutYard::updateAll(["status"=>4],["id"=>$missClassMember]);
          // 上课中会员
        AboutYard::updateAll(["status"=>2],["id"=>$classingMember]);
        // 下课会员
        AboutYard::updateAll(["status"=>3],["id"=>$classOverMember]);
    }
    public function customLoad($params){
        $this->memberAboutDate           = empty($params["memberAboutDate"])?date("Y-m-d",time()):$params["memberAboutDate"];
        $this->aboutIntervalSection     = empty($params["aboutIntervalSection"])?null:$params["aboutIntervalSection"];
        $this->yardId                     = empty($params["yardId"])?null:$params["yardId"];
        if($this->aboutIntervalSection=="undefined"||empty($this->aboutIntervalSection)){
            $this->initAboutIntervalSection();
        }
        $this->classStatus               = empty($params["classStatus"])?null:$params["classStatus"];
        $classStart = $this->memberAboutDate." ".explode("-",$this->aboutIntervalSection)[0];
        $classEnd   = $this->memberAboutDate." ".explode("-",$this->aboutIntervalSection)[1];
        $this->classStart = $classStart;
        $this->classEnd   =  $classEnd;
        $this->sorts                      = self::loadSorts($params);
    }
    public function initAboutIntervalSection(){
         $data           = VenueYard::find()->where(["id"=>$this->yardId])->select("business_time,active_duration")->asArray()->one();
         $businessTime   = explode("-",$data["business_time"])[0];
         $startClassTime = strtotime(date("Y-m-d",time())." ".$businessTime);
         $endClassTime   = date("H:i",$startClassTime + $data["active_duration"]*60);
         $this->aboutIntervalSection = $startClassTime."-".$endClassTime;
    }
    public function setWhereSearch($query){
        if(!empty($this->memberAboutDate)){
            $query = $query->andFilterWhere(["aboutYard.aboutDate"=>$this->memberAboutDate]);
        }
        if(!empty($this->aboutIntervalSection)){
            $query = $query->andFilterWhere(["aboutYard.about_interval_section"=>$this->aboutIntervalSection]);
        }
        if(!empty($this->yardId)){
            $query = $query->andFilterWhere(["aboutYard.yard_id"=>$this->yardId]);
        }
        if(!empty($this->classStatus)){
            $query = $query->andFilterWhere(["aboutYard.status"=>$this->classStatus]);
        }
        return $query;
    }

    /**
     * 后台 - 组织架构管理 - 对各个字段的排序
     * @create 2017/4/28
     * @author houkaixin<houkaixin@itsports.club>
     * @param $data  array //前台获取的排序处理数据
     * @return array
     */
    public static function loadSorts($data)
    {
        $sorts = ["aboutYard.create_at" =>SORT_DESC];
        if(!isset($data["sortType"])){ return $sorts;}
        switch ($data["sortType"]){
            case 'memberName'  :
                $attr = 'member.username';
                break;
            case 'mobile'  :
                $attr = 'member.mobile';
                break;
            case 'aboutTime'  :
                $attr = 'member.aboutTime';
                break;
            default:
                $attr = 'member.aboutTime';
                break;
        };
        if($attr){
            $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }

    /**
     * 后台 - 场地预约 - 排序规则
     * @create 2017/9/8
     * @author houkaixin<houkaixin@itsports.club>
     * @param $sort     // 前台传过来的排序规则（ASC，DES两种情况）
     * @return string
     */
    public static function convertSortValue($sort)
    {
        if ($sort == 'ASC') {
            return SORT_ASC;
        } elseif ($sort == 'DES') {
            return SORT_DESC;
        }
    }

    public function cancelMemberAbout($id){
        $model = AboutYard::findOne($id);
        $model->status    = 5;
        $model->create_at = time();
        if(!$model->save()){
            return $model->errors;
        }
        return true;
    }

    public function searchMember($mobile,$yardId){
         $cardCategoryId      = $this->searchAccordMemberCard($yardId);
         $memberAboutMessage  = $this->getTrueMemberCard($cardCategoryId,$mobile,$yardId);
         return  $memberAboutMessage;
    }

    public function searchAccordMemberCard($yardId){
       $memberCardCategory = VenueYardCardcategory::find()->where(["yard_id"=>$yardId])->select("card_category_id")->asArray()->all();
        // 根据场地id 定位所在场馆
        $venueYard = VenueYard::findOne($yardId);
        $this->venueId = empty($venueYard)?null:$venueYard->venue_id;
       if(empty($memberCardCategory)){
          return [];
       }
       $cardCategoryId     = array_column($memberCardCategory,"card_category_id");
       return $cardCategoryId;
    }

    public function getTrueMemberCard($cardCategoryId,$mobile,$yardId){
       $memberMessage    =   Member::find()->alias("member")
                                   ->where(["and",["member.mobile"=>$mobile],["member.venue_id"=>$this->venueId]])
                                   ->andWhere(["status"=>1])
                                   ->joinWith(["memberDetails memberDetail"],false)
                                   ->select("member.id,memberDetail.name as username,member.mobile,memberDetail.pic")
                                   ->asArray()->all();
        $memberMessageUnique    = $this->filterData($memberMessage,$mobile);
        if(empty($memberMessageUnique)){
           return [];
        }
        // 会员符合卡种帅选
        $memberCard          = MemberCard::find()->alias("memberCard")
                             ->where(["and",["member_id"=>array_column($memberMessage,"id")],["status"=>[1,4]]])
                             ->select("memberCard.id as memberCardId,memberCard.card_number,memberCard.status,memberCard.card_name");
        // 判断该场地是否有绑定卡种操作
        $judgeBindMemberCard = $this->judgeBindMemberCard($yardId);
        if($judgeBindMemberCard===false){
          $memberCard = $memberCard->asArray()->all();
        }else{
          $memberCard = $memberCard->andWhere(["card_category_id"=>$cardCategoryId])->asArray()->all();
        }
        $endData = ["memberMessage"=>$memberMessageUnique,"memberCard"=>$memberCard];
        return $endData;
    }

    public function judgeBindMemberCard($yardId){
        $num = VenueYardCardcategory::find()->where(["yard_id"=>$yardId])->count();
        if($num==0){
           return false;
        }
        return true;
    }
    public function filterData($memberMessage,$mobile){
        $data = [];
        if(empty($memberMessage)){
          return $data;
        }
        foreach ($memberMessage as $keys=>$value){
             $data["username"][]  = $value["username"];
             $data["id"]           = $value["id"];
             if(!empty($data["pic"])){
                 $data["pic"]          = $value["pic"];
             }
        }
        $data["pic"]          = !empty($value["pic"])?$value["pic"]:null;
        $dataS    = array_unique($data["username"]);
        $username = $this->combineUsername($dataS);
        $data["username"] = $username;
        $data["mobile"]   = $mobile;
        return $data;
    }

    public function combineUsername($data){
        $username = "";
       foreach ($data  as $keys=>$values){
            $username  = $username.$values."/";

       }
        $username = rtrim($username,"/");
        return $username;
    }
    /**
     * @后台会员管理 - 会员预约场地记录
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/12/8
     * @param $memberId
     * @return bool|string
     */
    public function getMemberAboutYardRecord($memberId)
    {
        return self::find()
            ->alias('ay')
            ->joinWith(['venueYard vy'=>function($q){
                   $q->joinWith(['organization or']);
            }],false)
            ->select('ay.id,ay.member_id
            ,ay.about_interval_section
            ,ay.about_start
            ,ay.status
            ,ay.about_end
            ,or.name
            ,or.id as oId
            ,vy.id as vId
            ,vy.yard_name as vName
            ')
            ->where(['ay.member_id'=>$memberId])
            ->asArray()
            ->all();
    }


}