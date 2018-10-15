<?php
namespace backend\models;

use common\models\ArrayConfig;
use common\models\base\AboutClass;
use common\models\base\CardCategory;
use common\models\base\ClassRecord;
use common\models\base\Classroom;
use common\models\base\Course;
use common\models\base\EntryRecord;
use common\models\base\Auth;
use common\models\base\LeaveRecord;
use common\models\base\LimitCardNumber;
use common\models\base\Order;
use common\models\base\Organization;
use common\models\base\VenueLimitTimes;
use common\models\BindPack;
use common\models\Func;
use common\models\relations\MemberCardRelations;
use common\models\base\Employee;

class MemberCard extends \common\models\MemberCard
{
    use MemberCardRelations;
    public $theDateStart;
    public $theDateEnd;
    public $dateStart;
    public $dateEnd;
    public $venueId;
    public $searchDateStart;//搜索开始时间
    public $searchDateEnd;//搜索结束时间
    public $nowBelongId;
    public $nowBelongType;
    public $startTime;
    public $endTime;
    public $anotherVenue;

    
    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    const START    = 'startTime';
    const END      = 'endTime';
    const ANOTHER_VENUE = 'anotherVenue';
    const VENUE_ID = 'venueId';

    /**
     * 后台会员管理 - 会员列表详情 - 获取会员卡详情
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/1/19
     * @param $MemberId
     * @param $type
     * @param $venueId
     * @return string
     */
    public function memberCardData($MemberId,$type,$venueId)
    {
        if($type == 1){
            $model = MemberCard::find()
                ->alias('mc')
                ->joinWith(['member mm'])
//                ->joinWith(['venueLimitTimesArr venueLimitTimesArr'])
                ->joinWith(['cardCategory cardCategory' =>function($query){
                    $query->joinWith(['cardCategoryType cardCategoryType']);
                }])
                ->where(['mc.member_id'=> $MemberId])
//                ->andFilterWhere(['mc.venue_id'=> $venueId])
//                ->andFilterWhere(['or',['like','venueLimitTimesArr.venue_ids','"'.$venueId.'"'],['venueLimitTimesArr.venue_id'=>$venueId]])
                ->orderBy("mc.create_at DESC")
                ->asArray();
        }else{
            $data = time();
            $model = MemberCard::find()
                ->alias('mc')
                ->joinWith(['member mm'])
                ->joinWith(['cardCategory cardCategory' =>function($query){
                    $query->joinWith(['cardCategoryType cardCategoryType']);
                }])
                ->where(['mc.member_id'=> $MemberId])
                ->andWhere(['and',['<>','mc.status','2'],['<>','mc.status','3']])
                ->andWhere(['>','mc.invalid_time',$data])
                ->orderBy("mc.create_at DESC")
                ->asArray();
        }
        $dataProvider              =  Func::getDataProvider($model,100);
        $dataProvider->models     =  $this->getEmployeeData($dataProvider->models);
     //   $dataProvider->models     =  $this->calculateInvalidTime($dataProvider->models);
        return $dataProvider;
    }

//    public function calculateInvalidTime($data){
//        //计算截止的激活时间
//        $endActiveTime ="";
//        if(!empty($data[0]["create_at"])&&!empty($data[0]["active_limit_time"])){
//            $endActiveTime = $data[0]["create_at"]+ 60*60*24*$data[0]["active_limit_time"];
//        }
//        if(!empty($data[0]["active_time"])&&$data[0]["active_time"]<=$endActiveTime){
//            $data[0]["invalid_time"] = $data[0]["active_time"]+ 60*60*24*$data[0]["duration"];
//        }else{
//            $data[0]["invalid_time"] = (int)$endActiveTime+60*60*24*$data[0]["duration"];
//        }
//
//
//    }
    /**
     * 后台会员管理 - 会员信息查询 - 获取员工表数据
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/18
     * @return string
     */
    public function getEmployeeData($data)
    {

        foreach ($data as &$value){
            $value['employee'] =  \backend\models\Employee::find()
                ->where(['id'=>$value['employee_id']])->asArray()->one();
        }

        return $data;
    }
    /**
     * 后台 - 会员管理 - 会员详细信息删除
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/18
     * @return bool
     */
    public  function  getMemberCardDel($id)
    {
        $member             =   MemberCard::findOne($id);
        $resultDelMem       =   $member->delete();
        if($resultDelMem)
        {
            return true;
        }else{
            return false;
        }
    }
    /**
     * 后台 - 会员管理 - 会员详细信息
     * @author lihuien <lihuien@itsports.club>
     * @param $id int
     * @create 2017/4/18
     * @return bool
     */
    public function getCheckCard($id,$venueId = '')
    {
        $this->venueId = $venueId;
        $query = \backend\models\Member::find()
                    ->alias('mm')
                    ->select('mm.*,mc.id as memberCard_id,mc.pid as memberCard_pid,mc.card_category_id,mc.total_times,mc.consumption_times,mc.create_at,mc.active_limit_time,mc.card_number,mc.status as mc_status,mc.recent_freeze_reason,mc.invalid_time,mc.active_time,mc.card_name as cardName,md.name,md.id_card,md.birth_date,md.sex,employee.name as employeeName,md.pic,md.ic_number,mc.absentTimes as absent_times,mc.leave_type,org.name as venueName')
                    ->joinWith(['memberDetails md'],false)
                    ->joinWith(['memberCard mc'=>function($query)use($id){
                       $query->joinWith(['employee employee'],false);
                       $query->where(['mc.id'=>$id]);
                    }])
                    ->joinWith(['organization org'],false)
                    ->asArray()->one();
        if($query['mc_status'] == 2){
            $con = \common\models\base\ConsumptionHistory::find()
                ->where(['and',
                    ['member_id'=>$query['id']],
                    ['consumption_type'=>'card'],
                    ['category'=>'升级'], ['like','describe','由'.$query['cardName'].'升级为']
                ])->asArray()->one();
            if(isset($con)){
                $newCard = \common\models\base\MemberCard::findOne(['id'=>$con['consumption_type_id']]);
                $query['newCardNum'] = $newCard['card_number'];
            }else{
                $query['newCardNum'] = null;
            }
        }
        $this->autoLoad('d');
        if(isset($query['memberCard_pid']) && !empty($query['memberCard_pid'])){                                    //判断是否是副卡
            $mainEntry = \backend\models\EntryRecord::find()                    //判断主卡当天有没有进场
                        ->where(['member_card_id'=>$query['memberCard_pid']])
                        ->andWhere(['between','entry_time',strtotime(date('Y-m-d').' 00:00:00'),strtotime(date('Y-m-d').' 23:59:59')])
                        ->asArray()
                        ->one();
            $mainCardNum  =  substr($query['card_number'],0,strrpos($query['card_number'],'-')); //获取主卡卡号
            if($mainEntry){         //主卡进场
                $data  = $this->getCabinetData($query);
                $data  = $this->getCoachData($data);
                $data  = $this->getClassRecordData($data);
                $query = $this->getEntryTimesData($data,$id);
                $query = $this->getCardCategoryOneData($query);
                $query = $this->getMemCardTowelData($query);
                $query = $this->getNowLeave($query);
                $query = $this->getIdentify($query,$venueId);
                $query['main_card_status'] = 1;
                $query['vice_card']        = 1;
                $query['main_card_number'] = $mainCardNum;
            }else{                 //主卡没进场
                $data  = $this->getCabinetData($query);
                $data  = $this->getCoachData($data);
                $data  = $this->getClassRecordData($data);
                $query = $this->getEntryTimesData($data,$id);
                $query = $this->getCardCategoryOneData($query);
                $query = $this->getMemCardTowelData($query);
                $query = $this->getNowLeave($query);
                $query = $this->getIdentify($query,$venueId);
                $query['main_card_status'] = 0;
                $query['vice_card']        = 1;
                $query['main_card_number'] = $mainCardNum;
            }
        }else{
            //会员卡激活期限到期仍未激活，自动激活
            $query = $this->initMemberCardData($query);
            $data   = $this->getCabinetData($query);
            $data   = $this->getCoachData($data);
            $data   = $this->getClassRecordData($data);
            $query = $this->getEntryTimesData($data,$id);
            $query = $this->getCardCategoryOneData($query);
            $query = $this->getMemCardTowelData($query);
            $query = $this->getNowLeave($query);
            $query = $this->getIdentify($query,$venueId);
            $query['vice_card']        = 0;
            $query['main_card_status'] = 1;
        }

         if(!empty($query) && $query['venue_id'] != $venueId){
             $query['is_apply'] = true;
         }else{
             $query['is_apply'] = false;
         }
        return $query;
    }
    public function getClassRecordData($data)
    {
        if($data && !empty($data)){
            $data['classRecord'] = ClassRecord::find()
                ->alias('ac')
                ->where(['ac.member_id'=>$data['id']])
                ->andWhere(['status'=>3])
                ->asArray()->one();
        }
        return $data;
    }
    public function getCoachData($query)
    {
        if($query && !empty($query)){
            $query['coach'] = \backend\models\MemberCourseOrder::find()
                ->alias('ac')
                ->joinWith(['employeeS employeeS'],false)
                ->select('ac.private_id,employeeS.name')
                ->where(['ac.member_id'=>$query['id']])->asArray()->one();
        }
        return $query;
    }

    /**
     * 后台 - 会员管理 - 会员详细信息
     * @author huanghua <huanghua@itsports.club>
     * @param $id int
     * @param $venueId
     * @create 2017/8/30
     * @return bool
     */
    public function getCheckCardData($id,$venueId = '')
    {
        $query = \backend\models\Member::find()
            ->alias('mm')
            ->select('mm.*,mc.id as memberCard_id,mc.card_category_id,mc.total_times,mc.consumption_times,mc.create_at,mc.active_limit_time,mc.card_number,mc.status as mc_status,mc.invalid_time,mc.active_time,mc.card_name as cardName,md.name,md.sex,employee.name as employeeName,md.pic')
            ->joinWith(['memberCard mc'],false)
            ->joinWith(['employee employee'],false)
            ->joinWith(['memberDetails md'],false)
            ->where(['mc.id'=>$id])
            ->asArray()->one();
        $query = $this->getLeaveStatus($query);
        if(!empty($query) && $query['venue_id'] != $venueId){
            $query['is_apply'] = true;
        }else{
            $query['is_apply'] = false;
        }
        return $query;
    }
    /**
     * 后台 - 验卡 - 根据会员卡编号查询毛巾信息(获取会员当前是否在请假状态) 在请假状态  获取请假数据
     * @author huanghua <huanghua@itsports.club>
     * @param  $query      //初始化数据
     * @create 2017/8/30
     * @return array
     */
    public  function  getLeaveStatus($query){
//        if(!empty($query)&&isset($query["memberCard_id"])){
//            LeaveRecord::updateAll(['status'=>2],["AND",["<","leave_end_time",time()],["member_card_id"=>$query["memberCard_id"]]]);
//        }
        if(!empty($query)&&isset($query["memberCard_id"])){
            $model = LeaveRecord::find()->where(["and",["member_card_id"=>$query["memberCard_id"]],['or','status=1','status=3']])
                ->asArray()->one();
            if(empty($model)){
                $query["leaveStatus"]  = [];
            }else{
                $query["leaveStatus"]  = $model;
            }
        }else{
            $query["leaveStatus"]  = [];
        }
        return $query;
    }
    /**
     * 后台 - 会员管理 - 会员详细柜子信息
     * @author lihuien <lihuien@itsports.club>
     * @param $query array
     * @create 2017/4/18
     * @return bool
     */
    public function getCabinetData($query)
    {
        if(RechargeRuleForm::commonJudgment($query)){
           $query['cabinet'] = \backend\models\Cabinet::find()
                              ->alias('cb')
                              ->select('cb.*,mc.end_rent,ct.type_name')
                              ->joinWith(['memberCabinet mc'])
                              ->joinWith(['cabinetType ct'])
                              ->where(['mc.member_id'=>$query['id']])
                              ->andWhere(['mc.status'=>1])
                              ->asArray()
                              ->one();
        }
        return $query;
    }
    /**
     * 后台 - 会员管理 - 会员详细卡种信息信息
     * @author lihuien <lihuien@itsports.club>
     * @param $query array
     * @create 2017/4/18
     * @return bool
     */
    public function getCardCategoryOneData($query)
    {
        if($query && isset($query['card_category_id'])){
            $query['cardCategory'] = \backend\models\CardCategory::find()
                ->alias('cc')
                ->joinWith(['limitCardNumbers lcn'])
                ->select(
                    'cc.id,
                     cc.category_type_id,
                     cc.card_name,
                     cc.create_at,
                     cc.id as cardCategoryId,
                     cc.duration')
                ->where(['cc.id'=>$query['card_category_id']])
                ->asArray()->one();
        }
        return $query;
    }
    /**
     * 后台 - 会员管理 - 会员详细获取进场馆时间
     * @author lihuien <lihuien@itsports.club>
     * @param $data array
     * @param $id int
     * @create 2017/4/18
     * @return bool
     */
    public function getEntryTimesData($data,$id)
    {
        $query = EntryRecord::find()->where([
            'and',
            ['member_card_id'=>$id],
            ['venue_id'=>$this->venueId],
            ]);
        $queryWeek  = clone $query;
        $queryMonth = clone $query;
        $queryYear  = clone $query;
        $data['entryWeek']  = $this->getEntryWeekMonth($queryWeek,'week');
        $data['entryMonth'] = $this->getEntryWeekMonth($queryMonth,'month');
        $data['entryYear']  = $this->getEntryWeekMonth($queryYear,'year');
        $query = $query->orderBy('create_at DESC')->one();
        $data['fewDays']    = isset($query['create_at']) ? $query['create_at'] : 0;
        return $data;
    }
    /**
     * 后台 - 会员管理 - 会员详细获取进场馆周月时间
     * @author lihuien <lihuien@itsports.club>
     * @param $query array
     * @param $attr string
     * @create 2017/4/18
     * @return bool
     */
    public function getEntryWeekMonth($query,$attr)
    {
        $query = $this->getEntryDateWhere($query,$attr);
        return  $query->count();
    }
    /**
     * 后台 - 会员管理 - 会员详细获取时间条件搜索
     * @author lihuien <lihuien@itsports.club>
     * @param $query array
     * @param $week string
     * @create 2017/4/18
     * @return bool
     */
    public function getEntryDateWhere($query,$week)
    {
        if($week == 'week'){
            $data = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y"))).' 00:00:00';
            $start = strtotime($data);
            $end   = strtotime("+0 week ");
            $query->andFilterWhere(['between','entry_time',$start,$end]);
        }elseif ($week == 'month'){
            $start = Func::getMkTimeDate(date('m'),1,date('Y'));
            $end   = Func::getMkTimeDate(date('m'),date('t'),date('Y'));
            $query->andFilterWhere(['between','entry_time',$start,$end]);
        }elseif ($week == 'year'){
            $start = Func::getMkTimeDate(1,1,date('Y',time()));
            $end   = Func::getMkTimeDate(12,31,date('Y',time()));
            $query->andFilterWhere(['between','entry_time',$start,$end]);
        }
        return $query;
    }
    /**
     * 后台 - 会员管理 - 会员约课信息
     * @author lihuien <lihuien@itsports.club>
     * @param $id int
     * @create 2017/4/18
     * @return bool
     */
    public function getAboutClassRecord($id,$type)
    {
        $mc    = MemberCard::getMemberCardOneById($id);
        if(empty($type)){
            $about = \backend\models\AboutClass::find()->alias('ac')
                ->select('ac.*,gc.course_id as course_id,gc.classroom_id as classroom_id,gc.coach_id as coach_id,ac.status,gc.venue_id as venueId,organization.name as venueName')
                ->joinWith(['groupClass gc'=>function($mc){
                    $mc->joinWith(['organization organization']);
            }])
                ->joinWith(['seat seat'])
                ->where(['or',['member_card_id'=>$id],['member_id'=>$mc['member_id']]])
                ->andWhere(['ac.type'=>(string)2])
                ->andWhere(['ac.status'=>1])
                ->andFilterWhere(['>=','ac.end',time()])
//                ->orderBy('ac.create_at DESC')
                ->asArray()->all();
        }else{
            $date  = date('Y-m-d');
            $about = \backend\models\AboutClass::find()->alias('ac')
                ->select('ac.*,gc.course_id as course_id,gc.classroom_id as classroom_id,gc.coach_id as coach_id,ac.status,gc.venue_id as venueId,organization.name as venueName')
                ->joinWith(['groupClass gc'=>function($mc){
                    $mc->joinWith(['organization organization']);
                }])
                ->joinWith(['seat seat'])
                ->where(['or',['member_card_id'=>$id],['member_id'=>$mc['member_id']]])
                ->andWhere(['ac.type'=>(string)2])
                ->andWhere(['ac.status'=>1])
                ->andWhere(['ac.class_date'=>$date])
                ->andFilterWhere(['>=','ac.end',time()])
                ->orderBy('ac.start DESC')
                ->asArray()->all();
        }
        $about = $this->getAboutCourse($about);
        return $about;
    }
    /**
     * 后台 - 会员管理 - 会员约课信息
     * @author lihuien <lihuien@itsports.club>
     * @param $data array
     * @create 2017/4/18
     * @return bool
     */
    public function getAboutCourse($data)
    {
        if(RechargeRuleForm::commonJudgment($data)){
            foreach ($data as &$v){
                $v['course']    =   Course::find()->where(['id'=>$v['course_id']])->asArray()->one();
                $v['classroom'] =   Classroom::find()->where(['id'=>$v['classroom_id']])->asArray()->one();
                if(isset($v['classroom']['venue_id'])){
                    $v['venue']     =   \backend\models\Organization::getVenueOne($v['classroom']['venue_id']);
                }else{
                    $v['venue']     =   NUll;
                }
                $v['coach']     =   Employee::find()->where(['id'=>$v['coach_id']])->asArray()->one();
            }
        }else{
            $v['course'] = [];
        }
        return $data;
    }
    /**
     * 后台 - 会员管理 - 获取单条会员卡信息
     * @author lihuien <lihuien@itsports.club>
     * @param $num     //单条会员卡卡号
     * @param $venueId  //管理员所属场馆id
     * @create 2017/4/21
     * @return array
     */
    public function checkMemberCardNumber($num,$venueId)
    {
         $check = self::findOne(['card_number'=>(string)$num]);                        //查询会员卡的信息
         if($check && !empty($check)){
                if($check['status'] == 3 && !empty($check['frozen_end_time'])){
                    if(time() > (int)$check['frozen_end_time']){
                        $check->status = 1;                                                 //若冻结时间已过 则恢复正常状态
                        $check->save();
                    }
                }
                if(($check['venue_id'] == $venueId) || empty($venueId)){                       //判断是不是同一场馆的卡(或者是超级管理员验卡)
                    return isset($check['id'])?$check['id']:false;
                }else{
                    $limit = self::getVenueData($venueId,$check['id']);                  //参数：验卡的场馆id,会员卡id（返回结果，为空的是不能通店）
                    if(empty($limit))                                                    //判断会员卡的通店数据是否为空
                    {
                        return 'limit';                                                  //该卡不能通店
                    }else{
                        $venue = Organization::find()->where(['id'=>$check['venue_id']])->select('name')->asArray()->one();  //查询场馆信息
//                     $data = self::setVenueLimitTimes($limit);                        //（通店数据不为空的卡）处理通店次数
//                     if($data == false)
//                     {
//                         return 'error';                                              //扣次失败
//                     }else if($data == 'over'){
//                         return 'over';                                               //通店次数使用完毕
//                     }
                        $memberCard = [];
                        $memberCard['id'] = $check['id'];
                        $memberCard['venueName'] = $venue['name'];                            //获取外场馆的场馆名称
                    }
                    return isset($memberCard['id'])?$memberCard:false;
                }
            }
         return false;
    }
    /**
     * 后台 - 会员管理 - 验卡获取会员卡可以通店的场馆
     * @author huangpengju <huangpengju@itsports.club>
     * @param $memberCardId                  //会员卡id
     * @create 2017/6/23
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getVenueLimitTimes($memberCardId)
    {
        return VenueLimitTimes::find()->where(['member_card_id'=>$memberCardId])->asArray()->all();
    }

    /**
     * 后台 - 会员管理 - 验卡获取会员卡可以通店的场馆
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/23
     * @param $venueId       //场馆id
     * @param $memberCardId  //会员卡id
     * @return mixed|string|\yii\db\ActiveRecord  //当前验卡场馆通店数据或者空
     */
    public static function getVenueData($venueId,$memberCardId)
    {
        $data = self::getVenueLimitTimes($memberCardId);            //查询会员卡的通店数据
        $venueLimitArr ='';
        $num = 0;
        foreach ($data as $k=>$v)
        {
            $num++;
            if($v['venue_id'] == $venueId)
            {
                $venueLimitArr = $v;
                break;
            }else{
                $idArr = json_decode($v['venue_ids']);
                \Yii::trace($idArr,'fff'.$num);
                if(!$idArr){ $idArr = [-1]; }
                if(in_array($venueId,$idArr)){
                    $venueLimitArr = $v;
                    break;
                }
            }
        }
        return $venueLimitArr;
    }

    /**
     * 后台 - 会员管理 - 修改通店剩余次数
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/24
     * @param $limitData   //会员卡通店次数修改
     * @return bool
     */
    public static function setVenueLimitTimes($limitData)
    {
        $limit = VenueLimitTimes::find()->where(['id'=>$limitData['id']])->asArray()->one();
        if($limit['total_times'] != '-1' && $limitData['total_times'] >0)                       //判断是不是不限场馆或者通店次数使用完的
        {
            $limit = VenueLimitTimes::findOne($limitData['id']);
            $limit->overplus_times = ((int)$limit->overplus_times - 1);
            if(!$limit->save())
            {
                return false;                                                                   //失败
            } 
        }else if($limit['total_times'] != -1 && $limitData['total_times'] == 0)
        {
            return 'over';                                                                       //通店次数使用完毕
        }else if($limit['total_times'] == -1)
        {
            return true;                                                                        //不限次数                                                    
        }
    }
    /**
     * 后台 - 会员管理 - 获取会员不过期卡种
     * @author lihuien <lihuien@itsports.club>
     * @param $memberId     //单条会员ID
     * @create 2017/4/21
     * @return array
     */
    public static function getMemberCardNoInvalidTime($memberId)
    {
        return self::find()->where(['member_id'=>$memberId])->andWhere(['>','invalid_time',time()])->asArray()->one();
    }
    /**
     * 后台 - 会员管理 - 获取单条会员卡信息
     * @author lihuien <lihuien@itsports.club>
     * @param $memberId     //单条会员
     * @param $venueId      //场馆id(正在验卡的场馆id)
     * @create 2017/4/21
     * @return array
     */
    public static function checkMemberCardId($memberId,$venueId)
    {
        $check = self::find()->where(['member_id'=>(int)$memberId])->asArray()->one();      //查询会员卡表（看会员是否存在）
        if($check && !empty($check)){
            if(($check['venue_id'] == $venueId) || empty($venueId)){                        //判断是不是同一场馆的卡(或者是超级管理员验卡)
                return isset($check['id'])?$check['id']:false;                             //是同一场馆的卡
            }else{
                $limit = self::getVenueData($venueId,$check['id']);                         //参数：验卡的场馆id,会员卡id（返回结果，为空的是不能通店）
                if(empty($limit)){
                    return 'limit';                                                                 //该卡不能通店
                }else{
                    $venue = Organization::find()->where(['id'=>$check['venue_id']])->select('name')->asArray()->one();  //查询会员场馆信息
//                    $data = self::setVenueLimitTimes($limit);                                       //可以通店，处理剩余通店次数
//                    if($data == false)
//                    {
//                        return 'error';                                                             //扣次失败   
//                    }else if($data == 'over')
//                    {
//                        return 'over';                                                              //通店次数使用完毕
//                    }
                    $memberCard = [];
                    $memberCard['id'] = $check['id'];
                    $memberCard['venueName'] = $venue['name'];                                      //获取外场馆的场馆名称
                    return isset($memberCard['id'])?$memberCard:false;
                }
            }
        }else{
            return false;
        }
    }

    /**
     * 后台 - 会员管理 - 获取单条会员卡信息
     * @author houkaixin <houkaixin@itsports.club>
     * @param $id     //单条会员卡id
     * @create 2017/4/21
     * @return array
     */
    public function getOneMemberCard($id){
        $data =MemberCard::find()->joinWith(['cardCategory'])->joinWith(["member"])
               ->select("cloud_member_card.*,cloud_card_category.card_name,cloud_member.counselor_id")
               ->where(["cloud_member_card.id"=>$id])
               ->asArray()->one();
        $data["invalid_time"] =date("Y-m-d H:m:s",$data["invalid_time"]);
        //同时查询销售顾问名称
        $employeeName          =Employee::find()->where(["id"=>$data["counselor_id"]])->asArray()->one();
        $data["employeeName"] = $employeeName["name"];
        //查询组织架构表，标识为xiaoshou的部门id
        $venueId              = Organization::find()->select("id")->where(["code"=>'xiaoshou'])->asArray()->one();
        //同时查询所有销售信息
        $employee              = Employee::find()->select("id,name")->where(["organization_id"=>$venueId['id']])->asArray()->all();
        $data["employee"]     = $employee;
        return $data;
    }
    /**
     * 后台 - 会员管理 - 获取所有的销售顾问信息
     * @author houkaixin <houkaixin@itsports.club>
     * @param
     * @create 2017/5/12
     * @return array
     */
    public function getAdviser(){
        return  $data =  Employee::find()
            ->joinWith(['organization or'])
            ->where(['or.style'=>'3'])
            ->andWhere(['or.code'=>'tuanjiao'])
            ->asArray()
            ->all();
    }
    /**
     * 后台 - 验卡 - 根据会员卡编号查询毛巾信息
     * @author houkaixin <houkaixin@itsports.club>
     * @param  $query      //初始化数据
     * @create 2017/5/13
     * @return array
     */
    public function getMemCardTowelData($query){
        if(isset($query) && isset($query["cardCategory"])){
            $query['serverData'] = $this->getMemberTowelOne($query["cardCategory"]["cardCategoryId"]);
        }else{
            $query['serverData'] = [];
        }
        return $query;
    }
    /**
     * 后台 - 验卡 - 根据会员卡编号查询毛巾信息 - 公共方法
     * @author houkaixin <houkaixin@itsports.club>
     * @param  $id      //初始化数据
     * @create 2017/5/13
     * @return array
     */
    public function getMemberTowelOne($id)
    {
       return BindPack::find()
            ->where(["and",["polymorphic_type"=>"server"],["card_category_id"=>$id]])
            ->joinWith(["server"])
            ->select('
                         cloud_bind_pack.*,
                         cloud_server.name as serverName,
                ')
            ->asArray()->one();
    }
    /**
     * 后台 - 验卡 - 根据会员卡编号查询毛巾信息(获取会员当前是否在请假状态) 在请假状态  获取请假数据
     * @author houkaixin <houkaixin@itsports.club>
     * @param  $query      //初始化数据
     * @create 2017/5/13
     * @return array
     */
     public  function  getNowLeave($query){
//         if(!empty($query)&&isset($query["memberCard_id"])){
//             LeaveRecord::updateAll(['status'=>2],["AND",["<","leave_end_time",time()],["member_card_id"=>$query["memberCard_id"]]]);
//         }
         if(!empty($query)&&isset($query["memberCard_id"])){
             $model = LeaveRecord::find()->where(["and",["member_card_id"=>$query["memberCard_id"]],["status"=>1]])
                      ->asArray()->one();
             if(empty($model)){
                 $query["nowLeaveStatus"]  = [];
             }else{
                 $query["nowLeaveStatus"]  = $model;
             }
         }else{
             $query["nowLeaveStatus"]  = [];
         }
          return $query;
     }

    /**
     * @后台会员管理 - 查询会员卡ID
     * @author Huang pengju <huangpengju@itsports.club>
     * @create 2017/5/20
     * @param $memberId                         //会员id
     * @return array|null|\yii\db\ActiveRecord  //会员卡ID
     */
    public function getMemberCardId($memberId)
    {
        return \common\models\base\MemberCard::find()->where(['member_id'=>$memberId])->select('id')->asArray()->one();
    }
    /**
     * @后台会员管理 - 返回会员身份
     * @author Huang pengju <huangpengju@itsports.club>
     * @create 2017/6/1
     * @param $query                        //会员卡信息
     * @return array|null|\yii\db\ActiveRecord  //会员卡ID
     */
    public function getIdentify($query,$venueId){
        if(isset($query)&&!empty($query["cardName"])){
              if(is_bool(strpos($query["cardName"],'金爵')) && is_bool(strpos($query["cardName"],'尊爵'))){
                  if(isset($query['cardCategory']['limitCardNumbers'])&&!empty($query['cardCategory']['limitCardNumbers'])){
                      $venueArr = array_column($query['cardCategory']['limitCardNumbers'],'venue_id');
                      if(in_array($venueId,$venueArr) && !empty($query['cardCategory']['create_at'])){
                          $limit = $this->getVenueLimitArrOne($query['cardCategory']['id'],$venueId);
                          $query["identify"] = $limit['level'];
                          if($query["identify"] == 1){
                              $query["identify"] =  $this->setMemberIdentify($query['card_number'],$query["cardName"]);
                          }
                      }else{
                          $limit = $this->getVenueLimitArrOne($query['cardCategory']['id'],$venueId);
                          if(!empty($limit) && !empty($limit['level'])){
                              $query["identify"] = $limit['level'];
                          }else{
                              $query["identify"] = $this->setMemberIdentify($query['card_number'],$query["cardName"]);
                          }
                      }
                  }else{
                      $query["identify"] = 1;      //普通
                  }
              }else{
                  if(isset($query['cardCategory']['limitCardNumbers'])&&!empty($query['cardCategory']['limitCardNumbers'])){
                      $venueArr = array_column($query['cardCategory']['limitCardNumbers'],'venue_id');
                      if(in_array($venueId,$venueArr) && !empty($query['cardCategory']['create_at'])){
                          $limit = $this->getVenueLimitArrOne($query['cardCategory']['id'],$venueId);
                          $query["identify"] = $limit['level'];
                          if($query["identify"] == 1){
                              $query["identify"] =  $this->setMemberIdentify($query['card_number'],$query["cardName"]);
                          }
                      }else{
                          $query["identify"] = 2;      //VIP
                      }
                  }else{
                      $query["identify"] = 2;      //VIP
                  }
              }
          }else{
            $query["identify"] = $this->setMemberIdentify($query['card_number'],$query["cardName"]);
          }
        return $query;
    }
    /**
     * @后台会员管理 - 获取会员身份
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/6/4
     * @param  $id        //ID
     * @param   $venueId         //场馆ID
     * @return array   //会员卡信息
     */
    public function getVenueLimitArrOne($id,$venueId)
    {
         return LimitCardNumber::find()
             ->where(['card_category_id' => $id,'status'=>[1,3]])
             ->andWhere(['or',['venue_id' => $venueId],['like','venue_ids','"'.$venueId.'"']])
             ->select('level')
             ->asArray()
             ->one();
    }
    /**
     * @后台会员管理 - 获取会员身份
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/6/4
     * @param  $number        //会员卡号
     * @param   $name         //会员姓名
     * @return array   //会员卡信息
     */
    public function setMemberIdentify($number,$name)
    {
        $vipArr = ArrayConfig::setVipCardNameArr();
        if(preg_match('/^80[0-9]*$/',$number)||preg_match('/^80[0-9]*[-][A-Z][0-9]*$/',$number)||preg_match('/^2[0-9]*$/',$number) || preg_match('/^100[0-9]*$/',$number) || preg_match('/^01600[0-9]*$/',$number)){                                        //正则匹配存在的会员卡号（1000xxxx 尊黑）都可以预约
            $identify = 2;                                                                  //会员座位权限 （随意挑）
        }elseif (preg_match('/^3710[0-9]*$/',$number)){                                 //正则匹配存在的会员卡号（3710xxxx）
            $identify = 2;                                                             //会员座位权限  （随意挑）
        }else{
            foreach ($vipArr as $v){
                if(!is_bool(strpos($name,$v))){
                    $identify  = 2; //会员座位权限  （随意挑）
                    return $identify;
                }
            }
            $identify      = 1;  //座位只能选择 一般位置
        }
        return $identify;
    }
    /**
     * @后台会员管理 - 获取会员办卡信息
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/4
     * @param $id        //会员id
     * @param $identify  //会员身份
     * @return array   //会员卡信息
     */

    public function getMemberCard($id,$identify){
            $data = MemberCard::find()->select("id,card_name,status,invalid_time,card_category_id,venue_id,company_id,card_number")->where(["member_id"=>$id])->andWhere(['IS','pid',null])->asArray();
            if($identify["nowBelongType"] && $identify["nowBelongType"] == 'company'){
                $data->andFilterWhere(['company_id'=> $identify['nowBelongId']]);
            }
            if($identify["nowBelongType"] && $identify["nowBelongType"] == 'venue'){
                $data->andFilterWhere(['venue_id'=>$identify['nowBelongId']]);
            }
            $data = $data->all();
            $data =  $this->filterMemberCard($data);
            return $data;
    }
    /**
     * @后台会员管理 - 将不合法的会员卡 过滤掉
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/4
     * @param $data     // 会员卡信息
     * @return array   //返回会员卡信息
     */
    public function  filterMemberCard($data){
            if(empty($data)||!isset($data)){
                return [];
            }
            foreach($data as $keys=>$values){
                 if($values["status"]!=1||time()>$values["invalid_time"]){
                     unset($data[$keys]);
                 }
            }
           return $data;
    }
    /**
     * 员工管理 - 员工详情 - 根据ID获取会员卡信息
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/6
     * @param $id
     * @return \yii\db\ActiveQuery
     */
    public static function getMemberCardOneById($id)
    {
        return MemberCard::find()->where(['id'=>$id])->asArray()->one();
    }
    /**
     * 员工管理 - 会员管理 - 获得对应卡种的请假限制信息
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/6/26
     * @param   $memberCardId   //  会员卡id
     * @return array
     */
    public function getTheLimitData($memberCardId){
        $data = MemberCard::find()->alias('mc')
//            ->joinWith(['cardCategory cardCategory'],false)
            ->where(["mc.id"=>$memberCardId])
            ->select("mc.leave_total_days,mc.leave_least_days,mc.leave_days_times,mc.invalid_time,mc.attributes,mc.leave_type,mc.student_leave_limit")
            ->asArray()
            ->one();
        if(isset($data["leave_days_times"])){
            $data["leave_days_times"] = json_decode($data["leave_days_times"]);//会员卡请假天数次
        }
//        if(isset($data["leave_long_limit"])){
//            $data["leave_long_limit"] = json_decode($data["leave_long_limit"]);//卡种请假天数次
//        }
        if(isset($data["student_leave_limit"])){
            $data["student_leave_limit"] = json_decode($data["student_leave_limit"]);//会员卡学生请假天数次
        }
//        if(isset($data["studentLeaveType"])){
//            $data["studentLeaveType"] = json_decode($data["studentLeaveType"]);//卡种学生请假天数次
//        }
        return $data;
    }
    /**
     * 验卡管理- 验卡卡种激活 - 到了激活期限 仍未激活的卡 实现自动激活
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/6/26
     * @param   $query   //  会员卡信息的整理
     * @return array
     */
    public function initMemberCardData($query){
           //卡 未激活 并且已经 超过激活期限 自动激活
            $createCardTime = $query["create_at"];
            $activeNumDay   = $query["active_limit_time"];
            $endActiveTime = $createCardTime + $activeNumDay*60*60*24;
            if(empty($query["active_time"])&&time()>=$endActiveTime){
                $query["memberCardId"] = !empty($query["memberCard_id"])?$query["memberCard_id"]:null;
                $data = [];
                $data[] = $query;
                $dataS = MemberAboutClass::filterData($data);
               // mc_status,mc.invalid_time,mc.active_time
                if($query["mc_status"] == 4){
                    $query["mc_status"] = 1;
                }
                $query["invalid_time"]  = $dataS[0]["invalid_time"];
                $query["active_time"]   = $endActiveTime;
            }
          return $query;
    }
    /**
     * 会员管理 - 会员详情 - 会员卡到期
     * @author Huang hua<huanghua@itsports.club>
     * @create 2017/7/13
     * @param $id
     * @return bool|string
     */
     public function memberCard($id)
     {
         $model = MemberCard::find()
             ->alias('mc')
             ->select("
             mc.invalid_time,
             ")
             ->where(['mc.member_id'=>$id])
             ->orderBy('mc.invalid_time DESC')
             ->asArray()->one();
         return $model;
     }

    /**
     * 后台 - 营运统计 - 会员卡种统计
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/19
     * @return array
     */
    public function memberCardCount($params)
    {
        $this->autoLoad($params);
        $entry = \backend\models\EntryRecord::find()
            ->alias('er')
            ->joinWith(['memberCard mc'],false)
            ->where(["and",[">=","er.entry_time",$this->theDateStart],["<=","er.entry_time",$this->theDateEnd]])
            ->select('er.member_card_id,mc.card_category_id,mc.card_name')
            ->asArray();
        if(empty($this->anotherVenue)){
            $entry->andFilterWhere(['er.venue_id' => $this->venueId]);
        }else{
            $entry->andFilterWhere(['and',
                ['er.venue_id' => $this->nowBelongId],
                ['mc.venue_id' => $this->anotherVenue]
            ]);
        }
        $entry = $entry->all();           //查询出时间段内所有进场记录数据

        $num   = array_column($entry,'card_category_id');       //取出进场的卡种
        $num   = array_filter($num);                            //过滤掉空值
        $times = array_count_values($num);                      //每个卡种的进场次数  卡种=>次数
        arsort($times);                                         //倒序排列
        $data  = array_slice($times, 0, 10, true);              //取前十条数据  保持键值不变
        $count = [];
        foreach($data as $key=>$value){
            $name = CardCategory::findOne(['id' => $key]);
            $count[] = ['number'=>$value,'cardName'=>$name['card_name']];
        }
        return $count;
    }

    /**
     * 后台 - 营运统计 - 处理日期
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/20
     * @param
     */
    public function autoLoad($param)
    {
        if(isset($param["date"])){
            $dateStart  = $param["date"]." "."00:00:00";
            $dateEnd    = $param["date"]." "."23:59:59";
        }elseif(isset($param["startTime"]) && isset($param["endTime"])){
            $dateStart  = $param["startTime"]." "."00:00:00";
            $dateEnd    = $param["endTime"]." "."23:59:59";
        }
        if(isset($dateStart) && isset($dateEnd)){
            $this->theDateStart = strtotime($dateStart);
            $this->theDateEnd   = strtotime($dateEnd);
            $this->dateStart    = $dateStart;
            $this->dateEnd      = $dateEnd;
        }
        if($param == 'w'){
            $this->searchDateStart = Func::getGroupClassDate($param,true);
            $this->searchDateEnd   = Func::getGroupClassDate($param,false);
        }elseif($param == 'd'){
            $this->searchDateStart = Func::getGroupClassDate($param,true);
            $this->searchDateEnd   = Func::getGroupClassDate($param,false);
        }else{
            $this->searchDateStart = Func::getGroupClassDate($param,true);
            $this->searchDateEnd   = Func::getGroupClassDate($param,false);
        }
        $roleId       = \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId      = Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds = array_column($vId, 'id');
        }else{
            //拿到用户有权限查看的场馆
            $venuesId = Auth::findOne(['role_id' => $roleId])->venue_id;
            $authId   = json_decode($venuesId);
            //去掉组织架构里面设置"不显示"的场馆id
            $venues   = Organization::find()->where(['id'=>$authId])->andWhere(['is_allowed_join'=>1])->select(['id','name'])->asArray()->all();
            $venueIds = array_column($venues, 'id');
        }
        $this->venueId      = (isset($param[self::VENUE_ID]) && !empty($param[self::VENUE_ID])) ? $param[self::VENUE_ID] : $venueIds;
        $this->nowBelongId  = (isset($param[self::NOW_BELONG_ID]) && !empty($param[self::NOW_BELONG_ID])) ? $param[self::NOW_BELONG_ID] : null;
        $this->anotherVenue = (isset($param[self::ANOTHER_VENUE]) && !empty($param[self::ANOTHER_VENUE])) ? $param[self::ANOTHER_VENUE] : null;
    }

    /**
     * @营运统计 - 会员上课统计搜索字段
     * @create 2017/8/31
     * @author zhumengke <zhumengke@itsports.club>
     * @param $data
     */
    public function custom($data)
    {
        $roleId             =   \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId            =    Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds       =    array_column($vId, 'id');
        }else{
            //拿到用户有权限查看的场馆
            $venuesId       =    Auth::findOne(['role_id' => $roleId])->venue_id;
            $authId         =    json_decode($venuesId);
            //去掉组织架构里面设置"不显示"的场馆id
            $venues         =    Organization::find()->where(['id'=>$authId])->andWhere(['is_allowed_join'=>1])->select(['id','name'])->asArray()->all();
            $venueIds       =    array_column($venues, 'id');
        }
        $this->venueId       = (isset($data[self::VENUE_ID]) && !empty($data[self::VENUE_ID])) ? $data[self::VENUE_ID] : $venueIds;
        $this->nowBelongId   = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->startTime     = (isset($data[self::START])&& !empty($data[self::START]))? $data[self::START] : null;
        $this->endTime       = (isset($data[self::END]) && !empty($data[self::END])) ? $data[self::END] : null;
        $this->anotherVenue  = (isset($data[self::ANOTHER_VENUE]) && !empty($data[self::ANOTHER_VENUE])) ? $data[self::ANOTHER_VENUE] : null;
    }
    /**
     * @营运统计 - 会员上课统计搜索字段
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/8/31
     * @param  $query
     */
    public function searchWhere($query)
    {
        $roleId       = \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId      = Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds = array_column($vId, 'id');
        }else{
            //拿到用户有权限查看的场馆
            $venuesId = Auth::findOne(['role_id' => $roleId])->venue_id;
            $authId   = json_decode($venuesId);
            //去掉组织架构里面设置"不显示"的场馆id
            $venues   = Organization::find()->where(['id'=>$authId])->andWhere(['is_allowed_join'=>1])->select(['id','name'])->asArray()->all();
            $venueIds = array_column($venues, 'id');
        }
        if(isset($this->venueId) && !empty($this->venueId)){
            $query->andFilterWhere(['course.venue_id' => $this->venueId]);
        }else{
            $query->andFilterWhere(['course.venue_id' => $venueIds]);
        }
        return $query;
    }
    /**
     * 后台 - 营运统计 - 会员上课统计
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/20
     */
    public function classCount($params)
    {
        AboutClass::updateAll(["status"=>3],["and",["<","start",time()],[">","end",time()],["is_print_receipt"=>1],["type"=>2],["not in","status",[2,6]]]);  // 批量修改教练上课 （除了 已取消,旷课）  已上课
//        AboutClass::updateAll(["status"=>4],["and",["<","end",time()],["is_print_receipt"=>1],["type"=>2],["!=","status",2]]);                       //已下课
//        AboutClass::updateAll(["status"=>6],["and",["<","start",time()],["is_print_receipt"=>2],["type"=>2],["!=","status",2]]);                     // 旷课
        $this->custom($params);
        //私教上课统计
        $private = \backend\models\AboutClass::find()
            ->alias('ac')
            ->joinWith(['memberCourseOrderDetails mco'=>function($query){
                $query->joinWith(['memberCourseOrder order'=>function($order){
                    $order->joinWith(['chargeClass cc'],false);
                }],false);
            }],false)
            ->joinWith(['memberCard mc'],false)
            ->where(['ac.type'=>1])
            ->andWhere(['or',['ac.status' => 3],['ac.status' => 4]])
            ->andWhere(["and",[">=","ac.class_date",$this->startTime],["<=","ac.class_date",$this->endTime]])
            ->select('ac.class_id,mco.course_id,ac.member_card_id,mc.venue_id,mc.card_name')
            ->asArray();
        if(empty($this->anotherVenue)){
            $private->andFilterWhere(['cc.venue_id' => $this->venueId]);
        }else{
            $private->andFilterWhere(['and',
                ['cc.venue_id' => $this->nowBelongId],
                ['mc.venue_id' => $this->anotherVenue]
            ]);
        }
        $private = $private->count();
        //团课上课统计
        $topArr   = Course::find()->alias('course')->where(['pid' => 0,'class_type' => 2])->asArray()->all();
        $allCount = [];
        foreach ($topArr as $key=>$value) {
            $count      = $this->loadCourse($value);
            $allCount[] = [$value['name'] => $count];
        }
        $allCount[] = ['私教' => $private];
        return $allCount;
    }
    /**
     * 后台 - 营运统计 - 处理课程数据
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/20
     */
    public function loadCourse($topCourse)
    {
        $courseIdArr = array();
        if(!empty($topCourse)){
            $courseTwo   = Course::find()->where(['pid' => $topCourse['id']])->asArray()->all();
            $courseTwoId = array_column($courseTwo,'id');       //全部二级id
            $courses     = Course::find()->where(['like','path',','.$topCourse['id'].','])->asArray()->all();
            $coursesId   = array_column($courses,'id');         //全部三级及以下id
            $courseIdArr = array_merge([$topCourse['id']],$courseTwoId,$coursesId);     //把各级id数组合并为一个数组
        }

        $about = \backend\models\AboutClass::find()
            ->alias('ac')
            ->joinWith(['groupClass gc'],false)
            ->where(['ac.type'=>2])
            ->andWhere(['or',['ac.status' => 3],['ac.status' => 4]])
            ->andWhere(["and",[">=","ac.class_date",$this->startTime],["<=","ac.class_date",$this->endTime]])
            ->select('ac.class_id,gc.course_id')
            ->asArray();
        if(isset($this->venueId) && !empty($this->venueId)){
            $about->andFilterWhere(['gc.venue_id' => $this->venueId]);
        }
        $about = $about->all();
        $courseId = array_column($about,'course_id');
        $courseId = array_filter($courseId);

        $num = 0;
        foreach($courseId as $key=>$value){
            if(in_array($value,$courseIdArr)){
                $num = $num + 1;
            }
        }
        return $num;
    }

    /**
     * 后台 - 营运统计 - 处理课程数据
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/20
     */
    public function loadClass($topCourse,$date,$venueId)
    {
        $this->autoLoad($date);
        $start = strtotime($this->searchDateStart);
        $end   = strtotime($this->searchDateEnd);
        $courseIdArr = array();
        if(!empty($topCourse)){
            $courseTwo   = Course::find()->where(['pid' => $topCourse['id']])->asArray()->all();
            $courseTwoId = array_column($courseTwo,'id');       //全部二级id
            $courses     = Course::find()->where(['like','path',','.$topCourse['id'].','])->asArray()->all();
            $coursesId   = array_column($courses,'id');         //全部三级及以下id
            $courseIdArr = array_merge([$topCourse['id']],$courseTwoId,$coursesId);     //把各级id数组合并为一个数组
        }

        $about = \backend\models\AboutClass::find()
            ->alias('ac')
            ->joinWith(['groupClass gc'],false)
            ->where(['ac.type'=>2])
            ->andWhere(['gc.venue_id'=>$venueId])
            ->andWhere(["and",[">=","ac.create_at",$start],["<=","ac.create_at",$end]])
            ->select('ac.class_id,gc.course_id,gc.venue_id')
            ->asArray()
            ->all();
        $courseId = array_column($about,'course_id');

        $num = 0;
        foreach($courseId as $key=>$value){
            if(in_array($value,$courseIdArr)){
                $num = $num + 1;
            }
        }
        return $num;
    }

    /**
     * 后台 - 营运统计 - 会员上课统计
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/20
     */
    public function classCounts($date,$venId)
    {
        $card         = new \backend\models\CardCategory();
        $venueId      = (isset($venId) && !empty($venId)) ? $venId : $card->getVenueIdByRole();
//        $venueId = 76;
        //私课预约统计
        $privates  = Course::find()
                    ->where(['class_type' => 1])
                    ->andWhere(['pid' => 0])
                    ->andFilterWhere(['venue_id' => $venueId])
                    ->asArray()
                    ->all();
        $private   = $this->loadPrivateCourse($date,$privates);
        //团课预约统计
        $allCount = [];
        $topArr   = Course::find()
                    ->alias('course')
                    ->andWhere(['pid' => 0,'class_type' => 2])
                    ->asArray()
                    ->all();
        foreach ($topArr as $key=>$value) {
            $count      = $this->loadClass($value,$date,$venueId);
            $allCount[] = [$value['name'] => $count];
        }
        $allCount[] = ['私教' => $private];
        return $allCount;
    }

    /**
     * 后台 - 营运统计 - 处理课程数据 - 统计私课
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/09/09
     */
    public function loadPrivateCourse($date,$topCourse)
    {
        $this->autoLoad($date);
        $start = strtotime($this->searchDateStart);
        $end   = strtotime($this->searchDateEnd);
        $arr   = array_column($topCourse, 'id');
        $about = \backend\models\Course::find()
                    ->alias('cs')
                    ->joinWith(["memberCourseOrderDetails mco"=>function($query){
                        $query->joinWith(["aboutClass ac"],false);
                    }],false)
                    ->where(['ac.type'=>1])
                    ->andWhere(['cs.id'=> $arr])
                    ->andWhere(["and",[">=","ac.create_at",$start],["<=","ac.create_at",$end]])
                    ->select('cs.*,mco.id as mco_id,mco.course_id,ac.*,ac.id as ac_id')
                    ->asArray()
                    ->all();

        $courseId = array_column($about,'id');
        $num      = count($courseId);
        return $num;
    }

    /**
     * 后台 - 会员管理 - 会员卡详情
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/3
     * @return array
     * @param $id //会员卡id
     */
    public function memberCardDetails($id)
    {
        $details = MemberCard::find()->alias('mc')
            ->joinWith(['memberCardTime cardTime'])
            ->joinWith(['organization organization'],false)
//            ->joinWith(['bindMemberCard bindMemberCard'])
            ->joinWith(['cardCategory cc'=>function($query){
                $query->joinWith(['bindPack bindPack']);
            }])
            ->joinWith(['deal deal'],false)
            ->where(['mc.id' => $id])
            ->select('mc.*,deal.name as dealName,deal.intro,organization.name as venueName,')
//            ->andWhere(['or',['<>','bindPack.polymorphic_id',0],['IS NOT','bindPack.polymorphic_ids',null]])
            ->asArray()->one();
        $data["total_summer_vacation"]=[];
        $data = $this->setMemberDetailById($details);
        $limitTimes = \common\models\VenueLimitTimes::find()
            ->alias('vlt')
            ->where(['member_card_id' => $id])
            ->joinWith(["organization org"],false)
            ->select("vlt.*,org.name,org.identity")
            ->asArray()->all();
        $data["goVenue"] = [];
        foreach ($limitTimes as $key => $value){
            if(empty($value['name'])){
                $venueIds = json_decode($value['venue_ids'],true);
                $value['organization'] = Organization::find()->where(['id'=>$venueIds])->select('id,name,identity')->asArray()->all();
                array_push($data["goVenue"],$value);
            }else{
                array_push($data["goVenue"],$value);
            }
        }

        $cardGiftData = \backend\models\BindMemberCard::find()
            ->alias('bp')
            ->joinWith(["goodsAll goods"],false)
            ->where(["and",["bp.member_card_id"=>$id],["bp.polymorphic_type"=>"gift"]])
            ->select("goods.id as goodsId,goods.goods_name,bp.number")
            ->asArray()->all();
        $data['memberBindGift']         = $cardGiftData;

        if (isset($details['student_leave_limit'])){
            $student = json_decode($details['student_leave_limit'],true);
            if(isset($student) && !empty($student)){
                $total_summer_vacation = $student[0][0];
                $total_winter_vacation = $student[1][0];
                $data["total_summer_vacation"] = $total_summer_vacation;
                $data['total_winter_vacation'] = $total_winter_vacation;
            }
        } else {
            $data["total_summer_vacation"] = null;
            $data['total_winter_vacation'] = null;
        }
        return $data;
    }
    /**
     * 后台 - 会员管理 - 会员卡详情
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/3
     * @return array
     * @param $data
     */
    public function setMemberDetailById($data)
    {
//        if(!empty($data) && isset($data['bindMemberCard'])){
//            foreach ($data['bindMemberCard'] as $k=>$v){
//                if($v['polymorphic_type'] == 'class'){
//                    $course = new \backend\models\Course();
//                    if($v['polymorphic_ids'] != null){
//                        $classId = json_decode($v['polymorphic_ids'],true);
//                    }else{
//                        $classId = $v['polymorphic_id'];
//                    }
//                    $data['bindMemberCard']['class'][$k]['arr'] = $course->courseDetailAll($classId);
//                    $data['bindMemberCard']['class'][$k]['number'] = $v['number'];
//                }elseif ($v['polymorphic_type'] == 'server'){
//                    $sever = new Server();
//                    $data['bindMemberCard']['sever'][$k] = $sever->getServerOne($v['polymorphic_id']);
//                }else  if($v['polymorphic_type'] == 'gift'){
//                    $goods = new Goods();
//                    $data['bindMemberCard']['gift'][$k] = $goods->getGoodsDetailData($v['polymorphic_id']);
//                }
//            }
//        }
//        return $data;
        if(!empty($data) && isset($data['cardCategory']) && isset($data['cardCategory']['bindPack'])){
            foreach ($data['cardCategory']['bindPack'] as $k=>$v){
                if($v['polymorphic_type'] == 'class'){
                    $course = new \backend\models\Course();
                    if($v['polymorphic_ids'] != null){
                        $classId = json_decode($v['polymorphic_ids'],true);
                    }else{
                        $classId = $v['polymorphic_id'];
                    }
                    $data['cardCategory']['class'][$k]['arr'] = $course->courseDetailAll($classId);
                    $data['cardCategory']['class'][$k]['number'] = $v['number'];
                }elseif ($v['polymorphic_type'] == 'server'){
                    $sever = new Server();
                    $data['cardCategory']['sever'][$k] = $sever->getServerOne($v['polymorphic_id']);
                }else  if($v['polymorphic_type'] == 'gift'){
                    $goods = new Goods();
                    $data['cardCategory']['gift'][$k] = $goods->getGoodsDetailData($v['polymorphic_id']);
                }
            }
        }
        return $data;
    }

    /**
     * 后台 - 会员卡- 会员卡id
     * @author houkaixin <houkaixin@itsports.club>
     * @param $memberCardId  // 会员卡id
     * @param $venueId       // 场馆id
     * @param $price         //缴纳金额
     * @create 2017/8/3
     * @return boolean
     */
    public function thawMemberCard($memberCardId,$venueId,$price){
         $model    = new AbsentRecord();
         $rule     = $model->gainFreezeWay($venueId);
         $price    = (!isset($rule["punish_money"])||empty($rule["punish_money"]))?0:$rule["punish_money"];
         // 查询会员卡相关信息
         $model       = MemberCard::findOne($memberCardId);
        // 查询会员相关信息
         $memberModel = Member::findOne($model->member_id);
         // 同时生成相对应的订单
         $endResult  = $this->generateOrder($venueId,$model,$price,$memberModel);      // 同时生成相对应的订单
         if($endResult!==true){
             return "订单生成错误";
         }
         $model->status                 = 1;     // 卡状态
         $model->absentTimes           = 0;      // 冻结次数
         $model->recent_freeze_reason = 2;      //其它原因
         $model->last_freeze_time      = null;  // 最后一次冻结时间
         if(!$model->save()){
             return $model->errors;
         }
        return true;
    }
    /*
     * 后台会员管理 - 会员卡信息查询 - 会员卡状态修改
     * @author Huang hua huanghua@itsports.club
     * @create 2017/9/22
     * @param $memberCardId
     * @param $memberCardModel
     * @return bool
     */
    public function generateOrder($venueId,$memberCardModel,$price,$memberModel){
       // 搜索相关数据
       $employee     = Employee::findOne(["admin_user_id"=>\Yii::$app->user->identity->id]);
       $model = new  Order();
       $model->venue_id          = $venueId;
       $model->member_id         = $memberCardModel->member_id;  // 会员id
       $model->card_category_id = $memberCardModel->card_category_id; // 卡种id
       $model->total_price       = $price;
       $model->order_time        = time();
       $model->pay_money_time    = time();
       $model->status             = 2;
       $model->note               = "团课罚金";
       $model->order_number      = Func::setOrderNumber();  // 订单号
       $model->card_name          = $memberCardModel->card_name;  // 卡名称
       $model->member_name        = $memberModel->username;    // 会员姓名
       $model->pay_people_name     = $memberModel->username;    // 会员姓名
       $model->consumption_type_id = $memberCardModel->id;     // 会员卡id
       $model->sell_people_id      = \Yii::$app->user->identity->id;          // 售卖人id
       $model->payee_id             = \Yii::$app->user->identity->id;          // 操作人id
       $model->create_id            = $employee->id;
       $model->sell_people_name    = isset($employee->name)?$employee->name:null;   // 收款人姓名
       $model->payee_name           = isset($employee->name)?$employee->name:null;   //操作人姓名
       $model->consumption_type    = "groupFine";             // 团课罚金
       $model->purchase_num         = 1;       // 购买数量
       if(!$model->save()){
           return $model->errors;
       }
       return true;
    }
    /*
     * 后台会员管理 - 会员卡信息查询 - 会员卡状态修改
     * @author Huang hua huanghua@itsports.club
     * @create 2017/9/22
     * @param $memberCardId
     * @return bool
     */
    public static function getUpdateMemberCard($memberCardId)
    {
        $memberCard = \common\models\base\MemberCard::findOne(['id'=>$memberCardId]);
        if($memberCard->status == 1){
            $memberCard->status = 3;
            static::setViceMemberCard($memberCardId,3);
        }else if($memberCard->status == 4){
            $memberCard->status = 3;
            static::setViceMemberCard($memberCardId,3);
        }else if($memberCard->status == 3){
            //判断冻结之前的卡是否已经被激活
            $status = empty($memberCard->active_time)?4:1;
            $memberCard->status = $status;
            static::setViceMemberCard($memberCardId,$status);
        }
        if($memberCard->save()){
            return true;
        }else{
            return $memberCard->errors;
        }
    }
    /**
     * 后台 - 验卡管理 - 修改冻结卡状态
     * @author lihuien <lihuien@itsports.club>
     * @param $id
     * @param $status
     * @create 2017/9/23
     * @return bool
     */
    public function setViceMemberCard($id,$status)
    {
        $card = MemberCard::findAll(['pid'=>$id]);
        if(!empty($card)){
            \common\models\base\MemberCard::updateAll(['status'=>$status],['pid'=>$id]);
        }
        return true;
    }
    /**
     * 后台 - 验卡管理 - 获取副卡信息
     * @author lihuien <lihuien@itsports.club>
     * @param $id int
     * @create 2017/9/23
     * @return bool
     */
    public function getViceCards($id)
    {
        $viceCards = MemberCard::find()
                    ->alias('mc')
                    ->joinWith(['member member'=>function($query){
                        $query->joinWith(['memberDetails md']);
                    }])
                    ->where(['mc.pid'=>$id])
                    ->andWhere(['<>','mc.member_id',0])
                    ->asArray()
                    ->all();
        return $viceCards;
    }
    /**
     * 后台 - 验卡管理 - 判断是否有副卡
     * @author lihuien <lihuien@itsports.club>
     * @param $id int
     * @create 2017/9/23
     * @return bool
     */
    public function isMemberHaveViceCards($id)
    {
        $viceCards = MemberCard::find()
            ->alias('mc')
            ->joinWith('cardCategory cardCategory')
            ->where(['mc.member_id'=>$id])
            ->andWhere(['IS','mc.pid',NULL])
            ->andWhere(['or',['mc.status'=>4],['mc.status'=>1]])
            ->andWhere(['<>','mc.bring',0])
            ->asArray()
            ->all();
        return $viceCards;
    }

    /**
     * 后台 - 验卡管理 - 判断是否有副卡
     * @author lihuien <lihuien@itsports.club>
     * @param $id int
     * @create 2017/9/23
     * @return bool
     */
    public function delMemberHaveViceCards($id)
    {
        $viceCards            = MemberCard::findOne(['id'=>$id]);
        $member               = Member::findOne(['id'=>$viceCards->member_id]);
        $viceCards->member_id = 0;
        $viceCards->save();
        $member->member_type  = 2;
        $member->save();
        return true;
    }

    /**
     * 后台 - 会员管理 - 会员卡删除
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/11/27
     * @param $memberCardId
     * @return bool
     */
    public  function  memberCardDel($memberCardId)
    {
        $memberCard              =   MemberCard::findOne(['id'=>$memberCardId]);
        $resultDel               =   $memberCard->delete();
        $history                 =   ConsumptionHistory::deleteAll(["consumption_type_id"=>$memberCardId]);
        $order                   =   Order::deleteAll(['and',['consumption_type'=>'card'],['consumption_type_id'=>$memberCardId]]);
        $venueLimitTimes         =   VenueLimitTimes::deleteAll(["member_card_id"=>$memberCardId]);
        if($resultDel)
        {
            return true;
        }else{
            return false;
        }
    }
    /**
     * 后台 - 会员管理 - 跨店升级
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/1/13
     * @param $cardNumber
     * @param $companyId
     * @return bool
     */
    public  function  memberCardInfo($cardNumber,$companyId)
    {
            $data = time();
            $model = MemberCard::find()
                ->alias('mc')
                ->joinWith(['member mm' => function($query){
                 $query->joinWith(['memberDetails memberDetails'],false);
                 $query->joinWith(['venue venue'],false);
                }],false)
                ->joinWith(['cardCategory cardCategory' =>function($query){
                    $query->joinWith(['cardCategoryType cardCategoryType'],false);
                }],false)
                ->where(['mc.card_number'=>$cardNumber])
                ->andWhere(['and',['<>','mc.status','2'],['<>','mc.status','3']])
                ->andWhere(['>','mc.invalid_time',$data])
                ->andFilterWhere(['mc.company_id'=>$companyId])
                ->select("
                mc.id,mc.member_id,mc.card_name,mc.amount_money,mc.duration,mc.invalid_time,mc.card_number,mm.id as memberId,
                memberDetails.name,memberDetails.sex,mm.venue_id,venue.name as venueName,cardCategoryType.id as cardCategoryTypeId,
                mc.create_at,mm.mobile
                ")
                ->asArray()
                ->one();
        return $model;
    }
    
    public function getJudgeMember($memberId)
    {
        $result = MemberCard::find()
            ->where(['member_id'=>$memberId])
            ->andWhere(['>=','create_at',time()-24*60*60])
            ->asArray()->all();
        if ($result) {
            return true;
        }else{
            return false;
        }
    }
    public function checkMemberInfo($cardNumber,$venueId)
    {
        $data = \common\models\MemberCard::find()
            ->alias('mc')
            ->select('mc.member_id,mds.name,mds.sex,mds.id_card,mds.pic,mds.birth_date,mm.mobile')
            ->where(['card_number'=>$cardNumber])
            ->joinWith(['member mm'],false)
            ->joinWith(['memberDetails mds'],false)
            ->asArray()->all();
        return $data;
    }

    /**
     * 会员卡数据 - 0720开头和0920开头通亚星和帝湖，不限次数
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2018/2/9
     * @return bool|string
     */
    public function applyDyData()
    {
        $memberCard = MemberCard::find()
            ->where(['like','card_number','0720%',false])
            ->orWhere(['like','card_number','0920%',false])
            ->select('id,card_number')
            ->asArray()->all();
        $venueD = Organization::find()->where(['like','name','帝湖瑜伽健身馆'])->andWhere(['style' => 2])->asArray()->one();
        $venueY = Organization::find()->where(['like','name','亚星游泳健身馆'])->andWhere(['style' => 2])->asArray()->one();
        $apply  = [$venueD['id'],$venueY['id']];
        foreach ($memberCard as $key => $value) {
            $limitD = VenueLimitTimes::find()->where(['member_card_id' => $value['id'],'venue_id' => $venueD['id']])->one();
            if(isset($limitD) && !empty($limitD)){
                $limitD->delete();
            }
            $limitY = VenueLimitTimes::find()->where(['member_card_id' => $value['id'],'venue_id' => $venueY['id']])->one();
            if(isset($limitY) && !empty($limitY)){
                $limitY->delete();
            }
            $limit = VenueLimitTimes::find()->where(['member_card_id' => $value['id'],'venue_ids' => json_encode($apply)])->one();
            if(!isset($limit) && empty($limit)){
                $limit = new VenueLimitTimes();
                $limit->member_card_id = $value['id'];
                $limit->total_times    = -1;
                $limit->overplus_times = -1;
                $limit->week_times     = -1;
                $limit->venue_ids      = json_encode($apply);
                $limit->level          = 1;
                $limit->about_limit    = 1;
                if(!$limit->save()){
                    return $limit->errors;
                }
            }
        }
        return true;
    }

    /**
     *后台会员管理 - 会员请假 -  请假记录查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2018/3/2
     * @param $cardId
     * @return bool|string
     */
    public function LeaveRecordData($cardId)
    {
        $model = LeaveRecord::find()
            ->alias('lr')
            ->where(['and', ['lr.member_card_id' => $cardId], ['>', 'lr.leave_end_time', time()]])
            ->andWhere(['and', ['lr.status' => 1], ['lr.leave_property' => 2]])
            ->select('
            lr.id,
            lr.leave_end_time')
            ->orderBy('lr.id desc')
            ->asArray()
            ->all();
        return $model;
    }
    /**
     * @desc: 判断此卡是否是请假
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/03/01
     * @param $memberCardId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function judgeLeave($memberCardId)
    {
        $data = \common\models\LeaveRecord::find()
            ->where(['and',
                ['member_card_id'=>$memberCardId],
                ['type'=>2],
            ])
            ->orderBy(['create_at'=>SORT_DESC])
            ->select('status')->asArray()->one();
        return $data;
    }
}