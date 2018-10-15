<?php
namespace backend\models;

use common\models\base\AboutYard;
use common\models\base\VenueYardCardcategory;
use common\models\Func;
use common\models\relations\VenueYardRelations;

class VenueYard extends \common\models\base\VenueYard
{

    public $yardName;    // 场地名称
    public $sorts;       // 排序字段
    public $venueId;     //场馆id
    public $cardId;      // 会员卡id

    public $aboutDate;  // 预约日期

    use VenueYardRelations;

    /**
     * 场地预约 - 获取场地相关信息
     * @create 2018/5/15
     * @author 辛威<xinwei@itsports.club>
     * @param $venueId //场馆id
     * @return array    // 场馆对应卡种信息
     */
    public function getVenueCardCategory($venueId)
    {
        $data = CardCategory::find()->select("id,card_name")->where(["venue_id" => $venueId])->asArray()->all();
        return $data;
    }

    /**
     * 场地预约 - 场地主页面分页信息遍历
     * @create 2018/5/15
     * @param  $search // 信息搜索参数
     * @author 辛威<xinwei@itsports.club>
     * @return array    // 场地预约信息
     */
    public function getVenueYardData($search)
    {
        $this->autoLoad($search);   //自动加载数据
        $data = VenueYard::find()->alias("venueYard")
            ->joinWith(["organization organization"], false)
            //   ->joinWith(["venueYardCardCategory yardCardCategory"],false)
            ->select("venueYard.*,organization.name")
            ->orderBy(["venueYard.id"=>SORT_ASC])
            ->asArray();
        $data = $this->searchWhere($data);       // 场地相关搜索
        $data = Func::getDataProvider($data, 8);
        return $data;
    }

    /**
     * 场地预约 - 场地主页面分页信息遍历 - 分页数据相关搜索
     * @create 2018/5/15
     * @param  $data // 搜索sql语句
     * @author 辛威<xinwei@itsports.club>
     * @return boolean
     */
    public function searchWhere($data)
    {
        if (!empty($this->yardName)) {
            $data = $data->andFilterWhere(['like','venueYard.yard_name',$this->yardName]);
        }
        if (!empty($this->venueId)) {
            $data = $data->andFilterWhere(["venueYard.venue_id" => $this->venueId]);
        }
        return $data;
    }

    /**
     * 场地预约 - 场地主页面分页信息遍历 - 数据自动加载
     * @create 2018/5/15
     * @param  $search // 信息搜索参数
     * @author 辛威<xinwei@itsports.club>
     * @return boolean
     */
    public function autoLoad($search)
    {
        $roleId             =   \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId            =    Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds      =    array_column($vId, 'id');
        }else{
            //拿到用户有权限查看的场馆
            $venuesId       =    Auth::findOne(['role_id' => $roleId])->venue_id;
            $authId         =    json_decode($venuesId);
            //去掉组织架构里面设置"不显示"的场馆id
            $venues         =    Organization::find()->where(['id'=>$authId])->andWhere(['is_allowed_join'=>1])->select(['id','name'])->asArray()->all();
            $venueIds       =    array_column($venues, 'id');
        }
        $this->venueId  = !empty($search["venueId"]) ? $search["venueId"] : $venueIds;
        $this->yardName = !empty($search["yardName"]) ? $search["yardName"] : null;
//        $this->venueId = !empty($search["venueId"]) ? $search["venueId"] : null;
        $this->sorts = self::loadSort($search);
        return true;
    }

    /**
     * 场地预约 - 场地主页面分页信息遍历 - 数据字段排序
     * @create 2018/5/15
     * @param  $data // 排序相关数据
     * @author 辛威<xinwei@itsports.club>
     * @return boolean
     */
    public static function loadSort($data)
    {
        $sorts = ["venueYard.create_at" => SORT_DESC];
        if (!isset($data["sortType"]) || empty($data["sortType"])) {
            return $sorts;
        }
        switch ($data["sortType"]) {
            case 'yard_name':
                $attr = 'venueYard.yard_name';    // 场地名称
                break;
            case 'people_limit':
                $attr = 'venueYard.people_limit';  //人数上限限制
                break;
            case 'business_time':
                $attr = 'venueYard.business_time';  //场馆运营时间
                break;
            case 'active_duration':
                $attr = 'venueYard.business_time';  //每次活动时长
                break;
            case 'venueName':
                $attr = 'organization.name';           //场馆名称
                break;
            default:
                $attr = "venueYard.create_at";        // 场地添加时间
                break;
        };
        if ($attr) {
            $sorts = [$attr => self::convertSortValue($data['sortName'])];
        }
        return $sorts;
    }

    /**
     * 场地预约 - 场地主页面分页信息遍历 - 数据字段排序
     * @create 2018/5/15
     * @param  $sortName // 排序字段名称
     * @author 辛威<xinwei@itsports.club>
     * @return string  // 排序规则
     */
    public function convertSortValue($sortName)
    {
        if ($sortName == 'ASC') {
            return SORT_ASC;
        } elseif ($sortName == 'DES') {
            return SORT_DESC;
        }
    }

    /**
     * 场地预约 - 场地主页面分页信息- 数据删除
     * @create 2018/5/15
     * @param  $id // 场馆适用场地的 删除
     * @author 辛威<xinwei@itsports.club>
     * @return boolean   // 删除结果
     */
    public function deleteDealVenueYard($id)
    {
        // 初始化 会员预约状态
      //  $this->initMemberAboutStatus($id);
        // 删除条件限制
//        $deleteCondition  = $this->deleteCondition($id);
//        if($deleteCondition!==true){
//              return $deleteCondition;
//        }
        $model = VenueYard::findOne($id);
        $model->delete();
        $this->deleteVenueYardCategory($id);
        $this->deleteAboutRecord($id);
        return true;
    }
    public function initMemberAboutStatus($id){
        $data =  \backend\models\AboutYard::find()->alias("aboutYard")
            ->where(["and",["!=","aboutYard.status",5],["aboutYard.yard_id"=>$id]])
            ->joinWith(["entryRecord entryRecord"=>function($query){
                $query->select("entryRecord.id,entryRecord.member_id");
            }])
            ->select("aboutYard.id,aboutYard.member_id,aboutYard.yard_id")
            ->asArray()
            ->all();
    }

    public function deleteAboutRecord($id)
    {
        \backend\models\AboutYard::deleteAll(["yard_id"=>$id]);
    }

    public function deleteCondition($id){
       // 查询所有未开课的会员
        $countNum = AboutYard::find()->where(["and",["in","status",[1,2]],["yard_id"=>$id]])->count();
        if($countNum!=0){
           return "场地有人预约,不能删除";
        }
        return  true;
    }
    /**
     * 场地预约 - 场地主页面分页信息- 数据删除
     * @create 2018/5/15
     * @param  $id // 场馆适用场地的 删除
     * @author 辛威<xinwei@itsports.club>
     * @return boolean   // 删除结果
     */
    public function deleteVenueYardCategory($id)
    {
        $result = VenueYardCardcategory::deleteAll(["yard_id" => $id]);
    }

    /**
     * 场地预约 - 场地主页面左上角- 获取指定场馆信息
     * @create 2018/5/15
     * @param  $companyId // 公司id
     * @author 辛威<xinwei@itsports.club>
     * @return array  // 返回场馆信息
     */
    public function getAllVenue($companyId)
    {
        $data = Organization::find()->where(["and", ["style" => 2], ["pid" => $companyId]])->select("id,name")->asArray()->all();
        return $data;
    }

    /**
     * 场地预约 - 场地预约详情- 获取场地预约详情区间段数据
     * @create 2018/5/15
     * @param  $yardId // 场地id
     * @param  $memberAboutDate  // 会员的预约日期
     * @param  $cardNumber       // 会员卡 卡号
     * @param  $aboutObject      // 预约对象
     * @param  $memberId         // 会员ID
     * @author 辛威<xinwei@itsports.club>
     * @return array  // 返回场地预约详情数据
     */
    public function VenueYardDetailData($yardId,$memberAboutDate,$cardNumber="",$memberId = "",$aboutObject)
    {
        $memberAboutDate = !empty($memberAboutDate)?$memberAboutDate:date("Y-m-d",time());
        define('memberAboutDate',$memberAboutDate);
        $yardDetailData  = VenueYard::find()->alias("yard")
            ->where(["yard.id" => $yardId])
            ->joinWith(["aboutYard aboutYard"])
            ->select("yard.*,count(aboutYard.id) as aboutPeopleNum")->asArray()->one();
        // 查询该卡号所有的预约区间段
        $allIntervalSection =  $this->getIntervalSection($cardNumber,$memberId,$aboutObject);
        $data   = $this->dealData($yardDetailData,$memberAboutDate,$allIntervalSection);   // 处理数据
        $params = empty(array_keys($data)[0])?"":array_keys($data)[0];
        $theEndData = ["orderNumList"=>$data,"yardMessage"=>$yardDetailData,"params"=>$params];
        return $theEndData;
    }
    /**
     * 场地预约 - 场地预约详情 - 返回该卡号的所有预约区间段
     * @create 2018/5/15
     * @param  $cardNumber     // 卡号
     * @param  $aboutObject   // 预约对象
     * @param $memberId      // 会员id
     * @author 辛威<xinwei@itsports.club>
     * @return array      // 返回所有的预约区间段
     */
    public function getIntervalSection($cardNumber,$memberId,$aboutObject){
         if(empty($cardNumber)&&($aboutObject=="memberCard")){
             return [];      // 卡号为空  面向对象是会员卡
         }
         if(empty($memberId)&&($aboutObject=="member")){
            return [];     // 会员id为空  面向对象是会员
         }
         // 根据指定对象获取预约区间段
         $allIntervalSection = $this->accordAppointObject($cardNumber,$memberId,$aboutObject);
         $allIntervalSection = array_unique($allIntervalSection);
         return $allIntervalSection;
    }
    /**
     * 场地预约 - 根据会员卡号
     * @create 2018/5/15
     * @param  $cardNumber  // 会员卡号
     * @param  $memberId    // 会员id
     * @param $aboutObject  // 预约对象
     * @author 辛威<xinwei@itsports.club>
     * @return array  // 返回所有的场地预约区间段
     */
    public function accordAppointObject($cardNumber,$memberId,$aboutObject){
        if($aboutObject=="memberCard"){    // 针对对象 会员卡
            $memberCard = MemberCard::find()
                ->where(["card_number"=>$cardNumber])
                ->select("id")
                ->one();
            // 查询指定日期的  该会员卡的所有预约区间段
            $allIntervalSection = \backend\models\AboutYard::find()
                ->where(["and",["aboutDate"=>constant('memberAboutDate')],["member_card_id"=>$memberCard["id"]],["!=","status",5]])
                ->select("about_interval_section")
                ->column();
        }else{                              // 针对对象 会员
            $allIntervalSection = \backend\models\AboutYard::find()
                ->where(["and",["aboutDate"=>constant('memberAboutDate')],["member_id"=>$memberId],["!=","status",5]])
                ->select("about_interval_section")
                ->column();
        }
       return $allIntervalSection;
    }
    /**
     * 场地预约 - 会员场地预约详情数据 （数据处理）
     * @create 2018/5/15
     * @param  $data // 处理数据
     * @param  $memberAboutDate  // 会员预约日期
     * @param  $allIntervalSection   // 该会员卡的所有预约区间段
     * @author 辛威<xinwei@itsports.club>
     * @return array  // 返回会员场地预约数据
     */
    public function dealData($data,$memberAboutDate,$allIntervalSection)
    {
        $dataKey   = [];
        $dataValue = [];
        // 时间处理
        $exampleTime = $memberAboutDate;             // 样例时间
        $timeLong    = $data["active_duration"];
        $theEndTime  = strtotime($exampleTime . " " . explode("-", $data["business_time"])[1]);
        $startTime   = strtotime($exampleTime . " " . explode("-", $data["business_time"])[0]);
        $endTime     = $startTime + 60 * $timeLong;   // 初始结束时间
        for ($i = 0; $i>-1; $i++){
            $startTime = ($i == 0) ? $startTime : $startTime + $timeLong * 60;
            $endTime   = ($i == 0) ? $endTime   : $endTime + $timeLong * 60;
            if($endTime>$theEndTime){
                break;
            }
            $dataKey[$i] = date("H:i", $startTime) . "-" . date("H:i", $endTime);
            $dataValue[$i]["num"] = 0;
            if (in_array($dataKey[$i],$allIntervalSection)){
                $dataValue[$i]["isAbout"] = true;  // 已预约
            }else{
                $dataValue[$i]["isAbout"] = false; //未预约
            }
            $dataValue[$i]["timeStatus"] = $this->timeStatus($startTime);
        }
        $endData =  array_combine($dataKey,$dataValue);                  // 初始化不同区间段预约人数
        $endData =  $this->aboutNumTimeLong($endData,$data);            // 在不同区间段预约的人数 汇总计算
        return $endData;
    }

    public function  timeStatus($startTime){
        if($startTime<time()){
            $status = 1;             // 时间段已过期
        }else{
            $status = 2;             // 时间段未过期
        }
        return $status;
    }
    /**
     * 场地预约 - 会员场地预约详情数据 （数据处理）
     * @create 2018/5/15
     * @param  $data // 处理数据
     * @param  $endData // 参数数据
     * @author 辛威<xinwei@itsports.club>
     * @return array  // 返回会员场地预约数据 每个时间段预约总人数
     */
    public function  aboutNumTimeLong($endData,$data){
        if(empty($data["aboutYard"])){
            return  $endData;
        }
        foreach($data["aboutYard"] as $keys=>$values){
           $timeLong      = $values["about_interval_section"];
           if(isset($endData[$timeLong]["num"])){
               $endData[$timeLong]["num"] = $endData[$timeLong]["num"]+1;   //每个区间段的预约人数
           }
         }
        return $endData;
    }
    /**
     * 场地预约 - 会员场地预约详情数据 （数据处理）
     * @create 2018/5/15
     * @param  $id     // 场地id
     * @author 辛威<xinwei@itsports.club>
     * @return array  // 返回场地卡种
     */
    public function getYardCategory($id){
       $data =  VenueYardCardcategory::find()->where(["yard_id"=>$id])->asArray()->all();
       return  $data;
    }

    public function judgeIsAboutTheCourse($yardId,$cardNum){
        $data = $this->getYardCategory($yardId);
        if(empty($data)){
            return true;  // 能预约
        }
        // 根据卡号获取卡种id
        $cardCategoryId  =  $this->getCardCategoryId($cardNum);
        $cardCategoryIdS = array_column($data,"card_category_id");
        if(in_array($cardCategoryId,$cardCategoryIdS)){
             return true;
        }else{
            return  false;
        }
    }

     public function getCardCategoryId($cardNumber,$type="cardCategoryId"){
         $memberCard = MemberCard::find()
             ->where(["card_number"=>$cardNumber])
             ->select("id,card_category_id")
             ->asArray()
             ->one();
         if(empty($memberCard)){
             return "";
         }
         if($type!=="cardCategoryId"){
             return $memberCard["id"];
         }
         if(!isset($memberCard["card_category_id"])){
             return "";
         }
         return $memberCard["card_category_id"];
     }

    public function getVenueAllAboutRecord($venueId,$cardNum){
        $cardId    = $this->getCardCategoryId($cardNum,$type="memberCard");
        $this->cardId = $cardId;  // 会员卡id
        $aboutData = \backend\models\AboutYard::find()
                    ->alias("aboutYard")
                    ->joinWith(["venueYard venueYard"=>function($query){
                        $query->joinWith(["organization organization"]);
                    }],false)
                    ->where(["and",["!=","aboutYard.status",5],["aboutYard.member_card_id"=>$cardId],[">","aboutYard.about_end",time()]])
                    ->andWhere(["venueYard.venue_id"=>$venueId])
                    ->select("aboutYard.id,aboutYard.yard_id,aboutYard.aboutDate,aboutYard.about_interval_section,venueYard.yard_name,organization.name as venueName")
                    ->asArray()
                    ->all();
        return $aboutData;
    }
    public function cancelYardAbout($yardId,$cardId,$intervalSection,$memberAboutDate,$memberId){
         $model = \common\models\base\AboutYard::find()->where(["yard_id"=>$yardId,"about_interval_section"=>$intervalSection])->andWhere(['<>','status',5]);
         if(!empty($cardId)){
             $model->andWhere(["member_card_id"=>$cardId]);
         }
         if(!empty($memberId)){
             $model->andWhere(["member_id"=>$memberId]);
         }
         if(!empty($memberAboutDate)){
             $model = $model->andWhere(["aboutDate"=>$memberAboutDate])->one();
         }else{
             $model = $model->one();
         }
         $model->status = 5;
         if(!$model->save()){
             return $model->errors;
         }
        return true;
    }

    public function cancelTheYardAbout($id){
        $model = \backend\models\AboutYard::findOne($id);
        $model->status = 5;
        if(!$model->save()){
          return $model->errors;
        }
        return true;
    }







}