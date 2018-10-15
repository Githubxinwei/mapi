<?php
namespace backend\models;
use common\models\base\MissAboutSet;
use common\models\Func;

class AbsentRecord extends \common\models\base\AboutClass
{
    public $venue_id;       // 场馆id
    public $memberCard;   // 会员卡
    public $dateStart;    // 开始日期时间
    public $dateEnd;      // 结束日期时间
    public $sorts;        // 排序规则

    public $isFrozen;    // 是被冻结
    /**
     * 后台 - 会员爽约记录 - 会员爽约记录分页显示
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/09/19
     * @param $params   // 分页搜索请求参数
     * @return array    // 分页返回数据
     */
      public function getData($params){
          $this->customLoad($params);
          $query =  AboutClass::find()
                    ->alias("aboutClass")
                    ->joinWith(["groupClass groupClass"=>function($query){
                        $query->joinWith(["course course"]);
                    }],false)
                    ->joinWith(["employee coach"],false)
                    ->select("
                          aboutClass.create_at,
                          course.name,
                          aboutClass.id,
                          aboutClass.class_id,
                          aboutClass.member_card_id,
                          aboutClass.start,
                          aboutClass.end,
                          aboutClass.coach_id,
                          coach.name as coachName,
                          aboutClass.class_date,
                          groupClass.venue_id,
                          aboutClass.status as classStatus,                
                          ")
                    ->where(["and",["aboutClass.type"=>2],["aboutClass.member_card_id"=>$this->memberCard],["aboutClass.status"=>[6,7]]])
                 //   ->andWhere(["groupClass.venue_id"=>$this->venueId])
                    ->orderBy(["aboutClass.class_date"=>SORT_DESC])
                    ->asArray();
          $query = $this->searchWhere($query);
          // 如果会员卡被冻结,计算剩余天数
          if($this->isFrozen===true){
              $this->countSurplusDay();
          }
          $data  = Func::getDataProvider($query,8);
          return  $data;
      }
    /**
     * 后台 - 会员爽约记录 - 计算剩余天数
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/09/19
     * @param $params    // 分页搜索请求参数
     * @return array     // 分页返回数据
     */
     public function countSurplusDay(){
        $surplusSet = MissAboutSet::find()
                      ->where(["venueId"=>$this->venueId])
                      ->select("*")
                      ->asArray()
                      ->one();
        if(empty($surplusSet)){
            return "解冻期限未知";
        }
        $this->countTheSurplusDay($surplusSet);

     }

    public function countTheSurplusDay($surplusSet){
           $totalDay = date("t",strtotime(time()));
           if($surplusSet["freeze_way"]==1){
               $surplusDay = $totalDay-date("d",time());
               return $surplusDay;
           }
           $surplusDay = MemberCard::find()
                          ->where(["id"=>$this->memberCard])
                          ->asArray()
                          ->select("last_freeze_time")
                          ->one();
           $endThawTime = $surplusDay["last_freeze_time"] +  $surplusDay["freeze_day"]*60*60*24;
           if(!empty($surplusDay["last_freeze_time"])){

           }

    }





    /**
     * 后台 - 会员爽约记录 - 数据搜索
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/09/19
     * @param $query     //sql语句
     * @return string
     */
    public function searchWhere($query){
        if(!empty($this->dateStart)&&!empty($this->dateEnd)){
           $query =  $query->andFilterWhere(["between","aboutClass.class_date",$this->dateStart,$this->dateEnd]);
             //$query =  $query->andFilterWhere(["and",[">=","aboutClass.class_date",$this->dateStart],["<=","aboutClass.class_date",$this->dateEnd]]);
        }
        return $query;
    }
    /**
     * 后台 - 会员爽约记录 - 初始化数据
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/09/19
     * @param $params   // 分页搜索请求参数
     * @return array    // 分页返回数据
     */
    public function customLoad($params){
        $this->memberCard = empty($params["memberCard"])?null:$params["memberCard"];
        $this->dateStart  = empty($params["dateStart"])?null:$params["dateStart"];
        $this->dateEnd    = empty($params["dateEnd"])?null:$params["dateEnd"];
        $this->venueId    = empty($params["venueId"])?null:$params["venueId"];
        $this->sorts      = self::loadSort($params);
    }
    /**
     * 后台 - 会员爽约记录 - 数据排序
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/09/19
     * @param $data   // 需要加载的字段名称
     * @return array  // 分页返回数据
     */
    public static function loadSort($data)
    {
        $sorts = ["aboutClass.create_at"=>SORT_DESC];
        if(!isset($data["sortType"])){ return $sorts;}
        switch ($data["sortType"]){
            case 'courseName'  :
                $attr = 'course.name';
                break;
            case 'coachName'  :
                $attr = 'coach.name';
                break;
            case 'classDate'  :
                $attr = 'aboutClass.class_date';
                break;
            default:
                $attr = 'aboutClass.create_at';
                break;
        };
        if($attr){
            $sorts = [$attr  => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }
    /**
     * 后台 - 会员爽约记录 -  排序规则
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/09/19
     * @param  $sort   // 排序规则（升序,降序）
     * @return string
     */
    public  static  function convertSortValue($sort){
            if ($sort == 'ASC') {
                return SORT_ASC;
            } elseif ($sort == 'DES') {
                return SORT_DESC;
            }else{
                return SORT_DESC;
            }
    }
    /**
     * 后台 - 爽约设置 - 获取冻结方式
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/09/25
     * @param  $venueId  // 场馆id
     * @return string
     */
    public function gainFreezeWay($venue_id){
       $frozenWay = MissAboutSet::find()
                     ->where(["venue_id"=>$venue_id])
                     ->andWhere(["course_type"=>1])
                     ->select("freeze_day,miss_about_times,freeze_way,punish_money")  // 冻结天数 爽约次数 冻结方式
                     ->asArray()
                     ->one();
       if(empty($frozenWay)){
          return ["freeze_way"=>3,"freeze_day"=>0,"punish_money"=>0];
       }
       return $frozenWay;
    }
    /**
     * 后台 -  爽约 - 会员卡的自动解冻
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/10/12
     * @param  $venueId          // 场馆id
     * @param  $memberId         // 会员id
     * @param  $isRequestMember  // 请求对象是否是会员
     * @return string
     */
    public function cardAutomaticThaw($venueId = null,$memberId,$isRequestMember){
         // 1 获取冻结方式
         $frozenWay =  $this->gainFreezeWay($venueId);
         if(is_array($frozenWay)&&isset($frozenWay["freeze_way"])){
         // 2：调用自动解冻
             $model = new MissRecords();
             $model->freezeWay = !empty($frozenWay["freeze_way"])?$frozenWay["freeze_way"]:null; // 冻结方式
             $model->venueId   =  !empty($venueId)?$venueId:null;     // 所属场馆
             $model->memberId  =  !empty($memberId)?$memberId:null;   // 会员id
             $model->isRequestMember = !empty($isRequestMember)?$isRequestMember:null; // 是否是针对特定会员的自动解冻
             $automaticResult   = $model->automaticThaw();
             $automaticResult   = (empty($automaticResult)||($automaticResult===true))?true:$automaticResult;
             return $automaticResult;
         }
        return  true;
    }



}