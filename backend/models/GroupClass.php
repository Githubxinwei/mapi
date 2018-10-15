<?php
namespace backend\models;
use backend\modules\v1\models\AboutRecordForm;
use backend\modules\v1\models\MemberCard;
use common\models\base\AboutClass;
use common\models\base\CoachClassRecord;
use common\models\base\Course;
use common\models\base\Member;
use common\models\base\MissAboutSet;
use common\models\Func;
use common\models\relations\GroupClassRelations;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class GroupClass extends \common\models\GroupClass
{
    use GroupClassRelations;
    public $courseName;
    public $classroomId;
    public $startTime;
    public $endTime;
    public $venueId;
    public $courseDate;
    public $venueIdArr = array();
    public $sorts;
    public $sortName;

    public $weekStart;
    public $weekEnd;

    public $memberId;
    public $classId;
    public $company;
    public $venue;

    public $nowBelongId;
    public $nowBelongType;

    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';

    const COUR = 'courseName';
    const CLA = 'classroomId';
    const VEN = 'venueId';
    const STA = 'startTime';
    const END = 'endTime';
    const SEA = 'seats';
    public $counselorId;    //counselor_id是会员表的顾问id,员工约课，生成的会员信息，该字段存的是员工id
    /**
     * 后台 - 课种管理 - 团课数据遍历
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/13
     * @param  $params array //获取前台的排序或则收索数据
     * @return  mixed
     */
    public function getGroupClass($params)
    {
        $this->customLoad($params);
        $query = GroupClass::find()
            ->alias("groupClass")
            ->joinWith(["course"])//课种表
            ->joinWith(["employee"])//员工表
            ->joinWith(["classroom"])//教室表
            ->select(" 
                     groupClass.id,
                     groupClass.coach_id,
                     groupClass.course_id,
                     groupClass.classroom_id,
                     groupClass.start,
                     groupClass.end,
                     groupClass.class_date,
                     cloud_employee.name as coachName,
                     cloud_classroom.name as classRoomName,"
            )
            ->orderBy($this->sorts)
            ->asArray();
        $query     = $this->getSearchWhere($query);
        $data      = Func::getDataProvider($query,10);

        return $data;
    }


    /**
     * 会员卡管理 - 团课 - 搜索数据处理数据
     * @create 2017/4/14
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $data array //前台获取的排序处理数据
     * @return  boolean
     */
    public function customLoad($data)
    {
        $this->courseName  = (isset($data[self::COUR]) && !empty($data[self::COUR])) ? $data[self::COUR] : null;
        $this->classroomId = (isset($data[self::CLA]) && !empty($data[self::CLA])) ? $data[self::CLA] : null;
        $this->venueId     = (isset($data[self::VEN]) && !empty($data[self::VEN])) ? $data[self::VEN] : null;
        $this->startTime   = (isset($data[self::STA]) && !empty($data[self::STA])) ? $data[self::STA] : null;
        $this->endTime     = (isset($data[self::END]) && !empty($data[self::END])) ? $data[self::END] : null;
        $this->nowBelongId  = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType= (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->sorts       = self::loadSort($data);
        return true;
    }

    /**
     * 后台 - 团课管理  - 排序数据的判断过滤
     * @create 2017/4/14
     * @author houkaixin<houkaixin@itsports.club>
     * @param $data  array //前台获取的排序处理数据
     * @return array
     */
    public static function loadSort($data)
    {
        $sorts = ['class_date' => SORT_DESC];
        if (!isset($data['sortType'])) {
            return $sorts;
        }
        switch ($data['sortType']){
            case 'courseName'  :
                $attr = '`cloud_course`.name';
                break;
            case 'coachName'  :
                $attr = '`cloud_employee`.name';
                break;
            case 'classrooms':
                $attr = '`cloud_classroom`.name';
                break;
            case 'courseDate':
                $attr = '`groupClass`.class_date';
                break;
            case 'startTime' :
                $attr = '`groupClass`.start';
                break;
            default:
                $attr = NULL;
        }
        if($attr){
            $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }


    /**
     * 后台 - 团课管理 - 获取排序规则
     * @create 2017/4/14
     * @author houkaixin<houkaixin@itsports.club>
     * @param $sort     // 前台传过来的排序规则
     * @return mixed
     */
    public static function convertSortValue($sort)
    {
        if ($sort == 'ASC') {
            return SORT_ASC;
        } elseif ($sort == 'DES') {
            return SORT_DESC;
        }
    }


    /**
     * 会员卡管理 - 团课管理 - 增加搜索条件
     * @create 2017/4/14
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $query //后台的sql语句
     * @return  mixed
     */
    public function getSearchWhere($query)
    {
        $query->andFilterWhere([
            'or',
            ['like','cloud_course.name', $this->courseName],
            ['like','cloud_employee.name',$this->courseName]
        ]);

        $query->andFilterWhere([
            'and',
            ['>=','groupClass.class_date',$this->startTime],
            ['<=','groupClass.class_date',$this->endTime]
        ]);
        $query->andFilterWhere([
            'and',
            ['cloud_classroom.id' => $this->classroomId],
            ['cloud_classroom.venue_id'=>$this->venueId ]
        ]);

        if($this->nowBelongType && $this->nowBelongType == 'company'){
            $query->andFilterWhere(['groupClass.company_id'=>$this->nowBelongId]);
        }
        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
            $query->andFilterWhere(['groupClass.venue_id'=>$this->nowBelongId]);
        }
        return $query;
    }

    /**
     * 后台 - 课种管理 - groupClass数据删除
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/13
     * @param $id   //前台发送过来的删除数据id
     * @return boolean
     */
    public function getDelete($id)
    {
        $deleteGroupClass = GroupClass::findOne($id)->delete();
        if( $deleteGroupClass){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 后台 - 团课管理 - 详情数据处理
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/4/15
     * @param $id
     * @return string
     */
    public function getGroupClassDetail($id)
    {
        $data = GroupClass::find()->alias('gc')
            ->joinWith(['aboutClass aboutClass'],false)
            ->joinWith(['employee employee'],false)
            ->joinWith(['classroom classroom'],false)
            ->joinWith(['course course'],false)
            ->select(" 
                     gc.id,
                     gc.coach_id,
                     gc.course_id,
                     gc.classroom_id,
                     gc.start,
                     gc.end,
                     gc.class_date,
                     gc.difficulty,
                     aboutClass.id as aboutId,
                     aboutClass.class_id,
                     employee.id as coachId,
                     employee.name as coachName,
                     employee.age,
                     employee.entry_date,
                     classroom.id as classroomId,
                     classroom.total_seat,
                     classroom.venue_id,
                     course.id as courseId,
                     course.name,
                     course.course_desrc,
                     "
            )
            ->where(['gc.id'=>$id])
            ->asArray()->one();
        $data = $this->getClassRoomData($data);             //获取教室数据
        return $data;
    }
    /**
     * 后台 - 团课管理 - 详情数据处理 获取公司名称和地址
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/5/2
     * @param $data
     * @return string
     */
    public function getOrganizationData($data)
    {
        foreach ($data as $keys=>$value){
            if($value["venue_id"] != null){
                $name = Organization::find()->where(["id"=>$value["venue_id"]])->select("name,address")->asArray()->one();
                $data[$keys]["venueName"]     = $name["name"];
                $data[$keys]["venueAddress"]  = $name["address"];
            }
        }

        return $data;
    }
    /**
     * 后台 - 团课管理 - 详情获取教室数据
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/4/15
     * @param $data
     * @return string
     */
    public function getClassRoomData($data)
    {
        $num = [1,2,3];
        $data[self::SEA]    = Seat::find()->where(['classroom_id'=>$data['classroom_id']])
            ->joinWith(['aboutClass aboutClass'])
            ->asArray()->all();                                         //查询一个教室的所有座位
        $data[self::SEA]    = $this->getSeatAbout($data[self::SEA]);
        $data = $this->getSeatTypeData($num,$data);
        $data['venue_id'] = ClassRoom::find()->where(['venue_id'=>$data['venue_id']])
            ->joinWith(['organization organization'])
            ->asArray()->one();
        return $data;
    }
    /**
     * 后台 - 团课管理 - 详情作为身份数据处理
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/4/15
     * @param $num
     * @param $data
     * @return string
     */
    public function getSeatTypeData($num,$data)
    {
        foreach ($num as $k=>$v){
            $data['seat_type'][$k] = Seat::find()->where(['classroom_id'=>$data['classroom_id'],'seat_type'=>$v])->asArray()->count();
        }
        return $data;
    }
    /**
     * 后台 - 团课管理 - 详情约课记录数据
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/4/15
     * @param $data
     * @return string
     */
    public function getSeatAbout($data)
    {
        if($data){
            foreach($data as &$v){
                $v['member'] = Member::find()
                    ->alias('member')->select('
                           member.id,
                           memberDetails.name
                     ')
                    ->joinWith(['memberDetails memberDetails'])
                    ->joinWith(['memberCard memberCard'])
                    ->where(['memberCard.id'=>$v['aboutClass']['member_card_id']])
                    ->asArray()->one();
            }
        }
        return $data;
    }
    /**
     * 后台 - 会员管理 - 团课数据遍历
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/2
     * @param $id int //会员Id
     * @param $type
     * @return  mixed
     */
    public function getGroupClassData($id,$type)
    {
//        $query = GroupClass::find()
//            ->alias('groupClass')
//            ->joinWith(['aboutClass aboutClass'])   //约课记录表
//            ->joinWith(['employee employee'])       //员工表
//            ->joinWith(['course course'])           //课程表
//            ->joinWith(['classroom classroom'])    //教室表
//            ->select(
//                "groupClass.id,
//                groupClass.coach_id,
//                groupClass.course_id,
//                groupClass.classroom_id,
//                groupClass.start,
//                groupClass.class_date,
//                aboutClass.status,
//                course.name,
//                classroom.name as className,
//                employee.id as eid,
//                employee.name as employeeName,")
//            ->where(['aboutClass.member_id' => $id])
//            ->asArray();
        $this->updateMemberAboutStatus($id);       // 修改会员约客状态
        $query = \backend\models\AboutClass::find()
            ->joinWith(["groupClass"=>function($query){
                $query->joinWith(["course","classroom","organization"]);
            }],false)
            ->joinWith(["memberCard"],false)
            ->joinWith(["seat"],false)
            ->joinWith(["employee"],false)
            ->select("cloud_about_class.id  as aboutId,
                    cloud_about_class.class_id,  
                    cloud_about_class.member_id,
                    cloud_about_class.coach_id,
                    cloud_about_class.employee_id,
                    cloud_about_class.member_card_id,
                    cloud_group_class.id, 
                    cloud_seat.seat_number,
                    cloud_group_class.course_id,
                    cloud_group_class.start,
                    cloud_group_class.end,
                    cloud_group_class.classroom_id,
                    cloud_about_class.class_date,
                    cloud_about_class.status,
                    cloud_course.name,
                    cloud_classroom.name as className,
                    cloud_employee.id as eid,
                    cloud_employee.name as employeeName,
                    cloud_member_card.card_name,
                    cloud_organization.name as oName
                    ")
            ->where(["cloud_about_class.member_id"=>$id])
            ->andWhere(['cloud_about_class.type'=>2])
            ->orderBy(['cloud_about_class.id' =>SORT_DESC])
            ->asArray();
        if($type == 'pot'){
            $data = Func::getDataProvider($query,80);
        }else{
            $data = Func::getDataProvider($query,8);
        }
        return $data;
    }
    /**
     * 后台 - 验卡 - 会员团课预约 批量修改会员预约状态
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/9/5
     * @param $id      // 会员id
     * @return array
     */
    public function updateMemberAboutStatus($id){
        AboutClass::updateAll(["status"=>3],["and",["and",["<","start",time()],[">","end",time()]],["is_print_receipt"=>1],["member_id"=>$id],["type"=>2],["not in","status",[2,6]]]);  //已上课(除了 已取消和旷课)
//        AboutClass::updateAll(["status"=>6],["and",["<","start",time()],["is_print_receipt"=>2],["member_id"=>$id],["type"=>2],["!=","status",2]]);                              // 旷课
//        AboutClass::updateAll(["status"=>4],["and",["<","end",time()],["is_print_receipt"=>1],["member_id"=>$id],["type"=>2],["!=","status",2]]);                                // 下课
    }
     
    /**
     * 后台 - 课种管理 - groupClass获取单条数据
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/19
     * @param
     * @return array
     */
  public function oneCourseData(){
       $id = \Yii::$app->session->get('courseId');
       $data =GroupClass::find()->where(["cloud_group_class.id"=>$id])->joinWith(["course"])
             ->joinWith(["classroom"])->select("cloud_group_class.*,
                    cloud_course.name as courseName,
                    cloud_classroom.venue_id,
                     cloud_classroom.total_seat
                    ")->asArray()->one();
       $data["start"]=date("H:i",$data["start"]);
       $data["end"]  =date("H:i",$data["end"]);
      //同时根据场馆id 同一个场馆的所有教室
       $classroom = ClassRoom::find()->where(["venue_id"=>$data["venue_id"]])->asArray()->all();
       $data["classroom"] = $classroom;
       return  $data;
  }
    /**
     * 后台 - 课种管理 - id（session保存）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/19
     * @param $id   //前台发送过来的获取单条信息id
     * @return boolean
     */
    public function saveSession($id){
        $session = \Yii::$app->session;
        $session->set('courseId',$id);
    }

    /**
     * 场地预约 - 场地主页面分页信息遍历 - 数据自动加载
     * @create 2017/9/7
     * @param  $search // 信息搜索参数
     * @author houkaixin<houkaixin@itsports.club>
     * @return boolean
     */
    public function autoLoad($search,$belongId)
    {
        $this->venueId  = (isset($search['venueId']) && !empty($search["venueId"])) ? $search["venueId"] : $belongId;
        $this->sorts = self::loadSort($search);
        return true;
    }

    /**
     * 后台 - 团课数据遍历 - 获得团课数据
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/15
     * @param  $param;        //
     * @param  $belongType  //所属级别
     * @param  $belongId    //所属身份
     * @return boolean
     */
    public  function getClassData($param,$belongType,$belongId){
        $this->autoLoad($param,$belongId);
        $this->memberId = $param["memberId"];
        $this->getTimeWeek($param);
        $query = GroupClass::find()
            ->alias("groupClass")
            ->joinWith(["course"=>function($q){$q->select('id');}])//课种表
            ->joinWith(["employee"=>function($q){$q->select('id');}])//员工表
            ->joinWith(["classroom"=>function($q){$q->select('id');}])//教室表
            ->select(" 
                     groupClass.id,
                     groupClass.coach_id,
                     groupClass.course_id,
                     groupClass.venue_id,
                     groupClass.company_id,
                     groupClass.classroom_id,
                     groupClass.start,
                     groupClass.end,
                     groupClass.class_date,
                     cloud_employee.name as coachName,
                     cloud_classroom.name as classRoomName,
                     cloud_course.name as courseName,"
            )->orderBy(["groupClass.class_date"=>SORT_ASC,"groupClass.start" => SORT_ASC])
            ->where(["between","groupClass.class_date",$this->weekStart,$this->weekEnd])
            ->asArray();
//        if(isset($belongType) && $belongType == 'company'){
//            $query = $query->andFilterWhere(['groupClass.company_id'=>$belongId]);
//        }
//        if(isset($belongType) && $belongType == 'venue'){
//            $query = $query->andFilterWhere(['groupClass.venue_id'=>$belongId]);
//        }
        if (!empty($this->venueId)) {
            $query = $query->andFilterWhere(["groupClass.venue_id" => $this->venueId]);
        }
        $query = $query->all();
        $data         = $this->getCourseStatus($query);
        //按照日期数据整理
        $arrangeData  = $this->getArrange($data,$this->weekStart);
        return  $arrangeData;
    }
    /**
     * 后台 - 团课数据遍历 - 获得团课详情权限
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/5/15
     * @param  $memberId;    //会员id
     * @param  $classId;    //课程id
     * @param  $memberCardId;    //会员卡id
     * @param $isEmployee  //是不是员工 1是
     * @update huang pengju
     * @return boolean
     */
    public  function getClassMemberRuleOneData($memberId,$classId,$memberCardId,$isEmployee)
    {
        if($isEmployee == 1){
            $this->counselorId = $memberId;
            $this->classId     = $classId;
        }else{
            $this->memberId   = $memberId;
            $this->classId    = $classId;
        }
        return $this->getMemberRule($memberCardId);
    }
    /**
     * 后台 - 团课数据遍历 - 获得团课数据权限
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/5/15
     * @param  $memberCardId;    //会员卡id
     * @return boolean
     */
    public function getMemberRule($memberCardId)
    {
        $model                        = new \backend\modules\v1\models\GroupClass();
        $memberCard                   = new MemberCard();
        if(!empty($this->counselorId) && $this->counselorId != 0)
        {
            $member                  = \backend\models\Member::find()->where(['counselor_id'=>$this->counselorId])->andWhere(['is_employee'=>1])->asArray()->one();//获取员工的会员信息
        }else{
            $member                       = \backend\models\Member::getMemberOneById($this->memberId);                              //获取会员信息
        }
        $card                         =  \backend\models\MemberCard::getMemberCardOneById($memberCardId);       //获取会员卡信息
        $query                        =  GroupClass::find()->where(['id'=>$this->classId])->asArray()->one();    //查询课程是否存在
        //其它状态显示
        if(isset($query["id"])&& (isset($this->memberId) || ($this->counselorId != 0))){
            //是否可以约课  //包括潜在会员
            if(!empty($this->counselorId) && $this->counselorId != 0)
            {
                $aboutOne                     = $model->isSetMemberAbout($member['id'],$query['start'],$this->counselorId);          //查询会员，在这个课程时间有没有预约其他课程

            }else {
                $aboutOne                     = $model->isSetMemberAbout($this->memberId,$query['start'],$this->counselorId);          //查询会员，在这个课程时间有没有预约其他课程
            }
            // 判断卡种是不是次卡
            $judgeResult  =  AboutRecordForm::judgeIsNotTimesCard($memberCardId);
            if($judgeResult!==true){                          // 不是次卡 走里边
                if($aboutOne && !empty($aboutOne)){           // 时间段内是否可以重复预约
                    $query['isAboutClass'] = true;          //有预约过课程
                }else{
                    $query['isAboutClass'] = false;         //没有预约过
                }
            }
            if($member['member_type'] ==2)              //如果是潜在会员
            {
                $num = \backend\models\AboutClass::find()->alias('ac')->joinWith(['groupClass gc'])->where(['ac.member_id'=>$member['id'],'gc.venue_id'=>$query['venue_id']])->andWhere(['ac.status'=>1])->count();
                if($num == 1)
                {
                    $query['potential'] = true;          //表示是潜在会员,并且不是第一次约课
                }else{
                    $query['potential'] = false;         //表示是潜在会员，并且是第一次约课
                }
            }
            $classInfo                    = $model->getFieldsByOne($query,'course');                                    //$query是课程信息， 返回课种信息
            $groupOne['name']             = $classInfo['name'];
            if(!empty($member) && ($member['member_type'] != 2 || $member['is_employee'] != 1)){                       //是会员，并且不是潜在会员或者员工
                $cardCategory = CardCategory::findOne(['id'=>$card['card_category_id']]);
                if((isset($card['create_at']) && $card['create_at'] > strtotime('2017-6-18')) || (isset($cardCategory->create_at) && $cardCategory->create_at > strtotime('2017-6-18'))){
                    // 是否买过课
                    $about       = new AboutRecordForm([],'group');
                    $classId     = $memberCard->getMemberClass($this->memberId,$memberCardId);                       //会员是否买过课
                    $cid         = $this->getCourseTop($query['course_id']);
                    $about->memberCardId = $memberCardId;
                    $about->courseId     = $query['course_id'];
                    $num         = $about->courseLimitArr();
                    if(($classId && in_array($cid,$classId)) || $num){
                        $model                 = new \backend\models\MemberCard();
                        $card['cardName']      = $card['card_name'];
                        $data                  = $model->getIdentify($card,$this->venueId);
                        $query['identify']     = $data['identify'] ;                                                    //判断会员身份
                        $query['isCanClass']   = true;                                                                  //买过
                    }else{
                        $organId = Organization::getVenueIdByName('大卫城');
                        $organ   = Organization::getVenueIdByName('艾搏');
                        if($card['venue_id'] == $organId['id']){
                            $query['identify']       = 2;                                                                           //座位只能选择 一般位置
                            $query['isCanClass']     = true;
                        }else{
                            if($card['venue_id'] == $organ['id']){
                                $query['identify']       = 2;                                                                           //座位只能选择 一般位置
                                $query['isCanClass']     = true;
                            }else{
                                $memberAbout = new MemberAboutClass();
                                $memberAbout->statusMemberAboutClass($query,$classInfo['category'],$member,$card,$classInfo['categorySelf']);//课程信息,课种类型，会员信息（数组），会员卡信息（数组）。返回可不可约课信息                         //没有买过
                                $query['isCanClass']   = $memberAbout->statusMemberCardName($query,$classInfo['category'],$member,$card,$classInfo['categorySelf']);
                            }
                        }
                    }
                }else{
                    $organId = Organization::getVenueIdByName('大卫城');
                    $organ   = Organization::getVenueIdByName('艾搏');
                    if($card['venue_id'] == $organId['id']){
                        $query['identify']       = 2;                                                                           //座位只能选择 一般位置
                        $query['isCanClass']     = true;
                    }else{
                        if($card['venue_id'] == $organ['id']){
                            if(preg_match('/^80[0-9]*$/',$card['card_number'])){                                        //正则匹配存在的会员卡号（1000xxxx 尊黑）都可以预约
                                $query['identify'] = 2;                                                                  //会员座位权限 （随意挑）
                            }else{
                                $query['identify']       = 2;  //座位只能选择 一般位置
                            }
                            $query['isCanClass']     = true;
                        }else{
                            $memberAbout = new MemberAboutClass();
                            $memberAbout->statusMemberAboutClass($query,$classInfo['category'],$member,$card,$classInfo['categorySelf']);//课程信息,课种类型，会员信息（数组），会员卡信息（数组）。返回可不可约课信息
                            $query['isCanClass'] = $memberAbout->statusMemberCardName($query,$classInfo['category'],$member,$card,$classInfo['categorySelf']);
                        }
                    }
             }
            }else{
                $query['identity']       = 1;                                                                           //座位只能选择 一般位置
                $query['isCanClass']     = true;                                                                       //潜在会员和员工什么课都可以约
            }
            $groupOne['start']            = date('H:i',$query['start']);                                                //几点开始
            $groupOne['end']              = date('H:i',$query['end']);                                                  //几点结束
            $query['isDance']             = true;                                                                       //舞蹈
        }
        return $query;
    }
    /**
     * 后台 - 验卡 - 获取顶级课种
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/5/15
     * @param
     * @return boolean
     */
    public function getCourseTop($id)
    {
        $course = new \backend\models\Course();
        $data   = $course->getCourseOneById($id);
        if(!empty($data) && $data['pid'] != 0){
           return  $this->getCourseTop($data['pid']);
        }
        return $data['id'];
    }
    /**
     * 后台 - 验卡 - 获取当前周的  开始日期和结束日期 （周一 周日）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/15
     * @param $param    // 模板请求参数
     * @return boolean
     */
    public  function getTimeWeek($param) {
        $nowDate = date("Y-m-d");
        $first=1;
        $w=date('w',strtotime($nowDate));
        $week_start = date('Y-m-d',strtotime("$nowDate -".($w ? $w - $first : 6).' days'));
        $week_end   = date('Y-m-d',strtotime("$week_start +6 days"));
        // 根据weekStart 计算周结束时间
        $weekStart  = isset($param["weekStart"])&&!empty($param["weekStart"])?$param["weekStart"]:null; //参数周开始时间
        $weekEnd    = !empty($weekStart)?date('Y-m-d',strtotime("$weekStart +6 days")):null;  // 参数周结束时间
      //  $weekEnd    = isset($param["weekEnd"])&&!empty($param["weekEnd"])?$param["weekEnd"]:null;
        // 周开始时间 周结束时间 赋值
        $this->weekStart = (empty($weekStart)||empty($weekEnd))?$week_start:$weekStart;
        $this->weekEnd   = (empty($weekStart)||empty($weekEnd))?$week_end:$weekEnd;
    }
    /**
     * 后台 - 验卡 - 按照日期整理数据
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/15
     * @param $query
     * @param $dateStatus
     * @return boolean
     */
    public function getArrange($query,$dateStatus,$type = 'check'){
        $arrangeData = ["week1","week2","week3","week4","week5","week6","week7"];
        $arrangeData = $this->intArrangeData($arrangeData,$dateStatus,$type);
        if(!empty($query)){
            foreach($query as $keys=>$values){
                //判断是否有数据
                if(count($values)==1){
                    $query[$keys]["data"] = 1;    //1有数据
                }else{
                    $query[$keys]["data"] = 2;    //2 没数据
                }
                $w=date('w',strtotime($values["class_date"]));
                $key = "week".$w;
                if($key=="week0"){
                    $key = "week7";
                }
                if(isset($arrangeData[$key][0])&&count($arrangeData[$key][0])==1){
                      unset($arrangeData[$key]);
                      $arrangeData[$key] = [];
                }
                    $arrangeData[$key][] = $values;
            }
        }
        ksort($arrangeData);
        return $arrangeData;
    }
    /**
     * 后台 - 验卡 -  数据整理
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/15
     * @param $arrangeData，
     * @param $dateStatus
     * @param $type
     * @return boolean
     */
    public function intArrangeData($arrangeData,$dateStatus,$type){
        $myDate = [];
        if($dateStatus){
            $w=date('w',strtotime($dateStatus));
        }else{
            $w=date('w',time());
            $w =($w==0)?7:$w;
        }
        $w = (int)$w;
            for($i=$w-1;$i>=0;$i--){
                $myDate[][] = ["class_date"=>date('Y-m-d',strtotime($dateStatus."-$i day"))];
            }
            $q = 1;
            for($i=$w;$i<7;$i++){
                $myDate[][] = ["class_date"=>date('Y-m-d',strtotime($dateStatus."+$q day"))];
                $q++;
            }
        $c=array_combine($arrangeData,$myDate);
        return $c;
    }
    /**
     * 后台 - 验卡 - 获取预约数据
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/15
     * @param $post   //客户端传过来课程id
     * @return boolean
     */
    public  function getClassDetail($post){
          $model   = new \backend\modules\v1\models\GroupClass();
          $result  = $model->getGroupClassDetail($post["id"]);
          return   $result;
    }
    /**
     * 后台 - 验卡 - 课程状态赋值
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/15
     * @param  $query   //获取课程数据
     * @return boolean
     */
    public function   getCourseStatus($query)
    {
          if(!empty($query)){
              foreach($query as $key=>$values){
                  // 课程过期状态显示
                  if (!empty($query) && count($query) > 0) {
                      if (isset($values["start"]) && time() > $values["start"]) {
                          $query[$key]["status"] = false;    // 1是课程已过期
                      } else {
                          $query[$key]["status"] = true;    // 1是课程未过期
                      }
                  }
              }
          };
           return $query;
    }

    /**
     * @后台 - 团课添加 - 查询是否同一教练同一时间是否存在课程
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/22
     * @param $coachId      //教练id
     * @param $courseId     //课种id
     * @param $start        //开始时间
     * @param $end          //结束时间
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getGroupClassExits($coachId,$courseId,$start,$end)
    {
        return \common\models\base\GroupClass::find()
            ->where(['coach_id'=>$coachId])
            ->andWhere(['course_id'=>$courseId])
            ->andWhere(['start'=>$start])
            ->andWhere(['end'=>$end])
            ->asArray()
            ->one();
    }
    /**
     * 后台 - 新团课 - 修改教练（指定课程）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/22
     * @param  $data   //array ["coachId"=>,"courseId"=>]
     * @return object
     */
    public function changeCoach($data){
        $num = AboutClass::find()->where(["AND",["status"=>1],["coach_id"=>$data["originalCoachId"]],["class_id"=>$data["courseId"]],["type"=>"团课"]])->count();
        if($data["originalCoachId"]==$data["coachId"]){
            return  "未执行教练修改";
        }
        $transaction                  =  \Yii::$app->db->beginTransaction();                //开启事务
        try {
            $model = GroupClass::findOne(["id"=>$data["courseId"]]);
            $model->coach_id = $data["coachId"];
            $groupClassUpdate = $model->save();
            if(!$groupClassUpdate){
                \Yii::trace($model->errors);
                throw new \Exception($model->errors);
            }
            //同时修改
            $aboutClassUpdate = AboutClass::updateAll(['coach_id'=>$data["coachId"]],["AND",["status"=>1],["class_id"=>$data["courseId"]],["type"=>"团课"]]);
            if($aboutClassUpdate != $num){
                \Yii::trace("会员预约记录修改不完整");
                throw new \Exception("会员预约记录修改不完整");
            }
            if($transaction->commit()==null){
                    return true;
            };
        }catch(\Exception $e){
            //如果抛出错误则进入 catch ,先callback,然后捕捉错误，返回错误
            $transaction->rollBack();
            return  $error = $e->getMessage();  //获取抛出的错误
        }
    }

    /**
     * 后台 - 潜在会员约课 - 获取指定时间的课程
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/23
     * @param $params
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getGroupClassInfo($params)
    {
        if(empty($params['class_date']) && !isset($params['class_date'])){
            $params['class_date'] = date("Y-m-d");
        }
        $data = GroupClass::find()
            ->alias("groupClass")
            ->joinWith(["course"])//课种表
            ->joinWith(["employee"])//员工表
            ->joinWith(["classroom"])//教室表
            ->select(" 
                     groupClass.id,
                     groupClass.coach_id,
                     groupClass.course_id,
                     groupClass.classroom_id,
                     groupClass.start,
                     groupClass.end,
                     groupClass.class_date,
                     cloud_employee.name as coachName,
                     cloud_classroom.name as classRoomName,
                     cloud_course.name as courseName,"
            )->orderBy(["groupClass.class_date"=>SORT_ASC,"groupClass.start" => SORT_ASC])
            ->where(["groupClass.class_date"=>$params['class_date']])
            ->andWhere(['>=','groupClass.start',time()])
            ->asArray()->all();
        return $data;
    }

    /**
     * 后台 - 潜在会员约课 - 获取选中的课程预约情况
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/23
     * @param $post
     * @return array|\yii\db\ActiveRecord[]
     */
    public  function getAboutClassDetails($post)
    {
        if (!empty($post) && isset($post)) {
            $data = AboutClass::find()->where(['member_id' => $post['memberId']])->asArray()->one();
            if (empty($data) && !isset($data)) {
                $model = new \backend\modules\v1\models\GroupClass();
                $result = $model->getGroupClassDetail($post["courseId"], $post['memberId']);
                return $result;
            } else {
                return false;
            }
        }else{
            return true;
        }
    }
    /**
     * 员工管理 - 员工详情 - 根据ID获取课程
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/6
     * @param $id
     * @return \yii\db\ActiveQuery
     */
    public static function getClassOneById($id)
    {
        return GroupClass::find()->where(['id'=>$id])->asArray()->one();
    }

    /**
     * 新团课管理 - 模板数据清楚 - 删除指定个周期的模板
     * @author Houkixin<Houkixin<@itsports.club>
     * @create 2017/6/22
     * @param  $data   // 发送过来 清楚模板的时间范围
     * @return string  // 返回删除范围
     */
    public function deleteCourses($data){
       $data = \common\models\base\GroupClass::deleteAll(["and",["venue_id"=>$data["venueId"]],["between","class_date",$data["startDate"],$data["endDate"]]]);
       return "删除成功";
    }
    /**
     * 新团课管理 - 修改教练上课记录表上课状态
     * @author Houkixin<Houkixin<@itsports.club>
     * @create 2017/7/11
     * @param  $data   // 教练上课记录表状态，教练记录上课表id
     * @return string  // 返回删除范围
     */
    public function editCoachClassStatus($data,$companyId,$venueId){
        $this->company = $companyId;
        $this->venue   = $venueId;
        $model = CoachClassRecord::findOne(["class_id"=>$data["id"]]);
        $situation  =  $this->OperationSituation($data,$venueId);
        if(!empty($model)){
            $model->status = $data["status"];
            $model->class_over_time = time();
            $result = $model->save()?true:$model->errors;
            if($result!==true){
                  return ["status"=>"error","data"=>$result];
            }
            return ["status"=>"success","data"=>$situation];
        }
        $newModel = new CoachClassRecord();
        $theTime  = time();
        $newModel->sign_time        = $theTime;
        $newModel->class_id         = $data["id"];
        $newModel->coach_id         = $data["coachId"];
        $newModel->status           = $data["status"];
        if(!$newModel->save()){
            return ["status"=>"error","data"=>$newModel->errors];
        }
        return ["status"=>"success","data"=>["message"=>$situation,"signTime"=>$theTime]];
    }
    /**
     * 新团课管理 - 后台操作情况判断
     * @author Houkixin<Houkixin<@itsports.club>
     * @create 2017/7/11
     * @param  $data   // 操作状态情况判断
     * @param  $venueId   // 场馆id
     * @return string  // 返回操作状态
     */
    public function  OperationSituation($data,$venueId)
    {
        switch ($data["status"]) {
            case 1:
                $result = "未打卡";
                break;
            case 2:
                $result = "已打卡";
                break;
            case 3:
                $result = "已下课";
                $this->censusClassSituation($data["id"],$venueId);    //统计未上课会员信息
                break;
            case 4:
                $result = "未上课";
                break;
            default:
                $result = "未执行任何操作";
                break;
        }
        return  $result;
    }
    /**
     * 新团课管理 - 批量修改约课记录表 旷课会员状态
     * @author Houkixin<Houkixin<@itsports.club>
     * @create 2017/7/11
     * @param  $id       // 团课id
     * @param  $venueId  // 场馆id
     * @return string   // 返回最后结果
     */
    public function censusClassSituation($id,$venueId){
        // 首选查询该课程是否有未下课的会员
          $checkTheEndResult = $this->checkMemberIsClassOver($id);
          if($checkTheEndResult===false){
               return false;        // 会员还没下课,下课以后 会员状态没有批量更改
          }
        // 批量修改会员卡状态 （4种状态）
         $this->updateMemberCardStatus($id,$venueId);
         $model    = new AbsentRecord();
         $result = $model->gainFreezeWay($venueId);
         if($result["freeze_way"]==3){
             return "该场官没有爽约惩罚措施";
         }
        // 查询所有缺课会员，会员卡记录缺课情况
         $data   = \backend\models\AboutClass::find()
                   ->alias("aboutClass")
                   ->joinWith(["memberCard memberCard"],false)
                   ->where(["and",["aboutClass.class_id"=>$id],["aboutClass.status"=>6],["aboutClass.type"=>2],["!=","aboutClass.status",2],["memberCard.status"=>[1,4]]])
                   ->select("aboutClass.member_card_id,aboutClass.class_id,aboutClass.status,aboutClass.type,aboutClass.status,memberCard.status as memberCardStatus")
                   ->asArray()->all();
         // 获取所有的会员卡id
         $memberCard = array_column($data,"member_card_id");
         // 根据会员卡 在会员卡记录旷课次数（超出旷课次数归零冻结卡）
         if(empty($memberCard)){   // 没有旷课的会员（上课次数不用加1）
            return true;
         }
         $this->updateMemberRecord($memberCard,$venueId);
    }
    /**
     * 新团课管理 - 爽约 - 检查是否执行批量修改 爽约记录 旷课次数
     * @author Houkixin<Houkixin<@itsports.club>
     * @create 2017/7/11
     * @param  $id       //
     * @return string   // 返回最后结果
     */
    public function checkMemberIsClassOver($id){
          // 查询未下课会员数量
          $aboutClassMemberNum = \backend\models\AboutClass::find()
                                 ->where(["and",["class_id"=>$id],["in","status",[1,3]]])
                                 ->count();
          //查询是否到了下课时间团课id
          $isTimeOut             = GroupClass::findOne($id);
          $classOverTime         = $isTimeOut->end;
          if($classOverTime>time()){
              return false;      // 还未到下课时间
          }
          if(($classOverTime<=time())&&($aboutClassMemberNum==0)){
              return false;      // 已经到了下课时间 会员状态也已经改过了
          }
          return true;          // 到了下课时间  会员状态 还没有改
    }








    /**
     * 团课管理 - 批量修改会员卡状态
     * @author Houkixin<Houkixin@itsports.club>
     * @create 2017/10/16
     * @param  $id            //  约课id
     * @param  $venueId      // 场馆id
     * @return string        // 返回最后结果
     */
    public function updateMemberCardStatus($id,$venueId){
        /*//  旷课状态分两种（1:卡没冻结,没上课 2:卡被冻结未上课）
        // 1:卡没冻结,没上课
        $cardNotFreeze    = $this->gainTheSql($id,[1,4],[1,3]);
        $id1              = $cardNotFreeze->asArray()->all();
        $id1S             = array_column($id1,"id");
        // 2:卡被冻结未上课
        $cardFreeze       = $this->gainTheSql($id,3,[1,3]);
        $id2              = $cardFreeze->asArray()->all();
        $id2S             = array_column($id2,"id");
        //  正常的上下课
        $normalClassing   = $this->gainTheSql($id,[1,4],[1,3],1);
        $id3              = $normalClassing->asArray()->all();
        $id3S             = array_column($id3,"id");*/
// 判断爽约条件: 1.课程当天进场 2.打印小票 3.到场在课程结束之前,三个条件必须满足
//  旷课状态分两种（1:卡没冻结,没上课 2:卡被冻结未上课）
       //1.卡被冻结
        //$id2S = $this->gainTheSql($id,'frozen');
        //2.卡未被冻结
            //2.1 未下课
        $id1S = $this->gainTheSql($id,'a');
            //2.2 已下课
        $id3S = $this->gainTheSql($id, 'b');
        // 批量修改 会员上课状态  卡没冻结旷课
        if(!empty($id1S)){
            $absent1   = AboutClass::updateAll(["status"=>6],["id"=>$id1S]);           // 修正为旷课状态   旷课
        }
        // 批量修改 会员上课状态  卡冻结旷课
        /*if(!empty($id2S)){
            $absent2   = AboutClass::updateAll(["status"=>7],["id"=>$id2S]);           // 修正为旷课状态   旷课（卡被冻结）
        }*/
        // 批量修改为 已下课
        if(!empty($id3S)){
            $hadClass  = AboutClass::updateAll(["status"=>4],["id"=>$id3S]);          // 批量修改会员下课状态
        }
    }
    /**
     * 团课管理 - 批量修改会员卡状态
     * @author Houkixin<Houkixin@itsports.club>
     * @create 2017/10/16
     * @param  $id                 //  约课id
     * @param  $memberCardStatus  // 卡状态
     * @param  $printReceipt      // 是否打印小票
     * @param  $aboutStatus        // 约课状态
     * @return string              // 返回最后结果
     */
    /*public function  gainTheSql($id,$memberCardStatus,$aboutStatus,$printReceipt = 2){
        $id1     = \backend\models\AboutClass::find()
            ->alias("aboutClass")
            ->joinWith(["memberCard memberCard"],false)
            ->select("aboutClass.id,aboutClass.member_card_id,aboutClass.class_id,aboutClass.is_print_receipt,aboutClass.type,aboutClass.status,memberCard.status as memberCardStatus")
            ->where(["and",["aboutClass.class_id"=>$id],["aboutClass.is_print_receipt"=>$printReceipt],["aboutClass.type"=>2],["in","aboutClass.status",$aboutStatus],["memberCard.status"=>$memberCardStatus]]);
        return $id1;
    }*/

    /**
     * @desc: 业务后台 - 判断会员是否爽约
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/03/16
     * @param $id
     * @param $type
     * @return array|\yii\db\ActiveRecord[]
     */
    public function gainTheSql($id,$type){
        /*if ($type == 'frozen') {
            //卡被冻结
            $arr = \backend\models\AboutClass::find()
                ->alias('aboutClass')
                ->joinWith(["groupClass groupClass"],false)
                ->joinWith(['memberCard memberCard'],false)
                ->select("groupClass.end,groupClass.company_id,groupClass.class_date,groupClass.venue_id,aboutClass.id,aboutClass.member_id,aboutClass.member_card_id,aboutClass.class_id,aboutClass.is_print_receipt,aboutClass.type,aboutClass.status,memberCard.status as memberCardStatus")
                ->where(['and',["aboutClass.class_id"=>$id],["aboutClass.type"=>2],["in","aboutClass.status",[1,3]],["memberCard.status"=>3]])
                ->asArray()->all();
            $ids = array_column($arr,'id');
            return $ids;

        }else {*/
            $query = \backend\models\AboutClass::find()
                ->alias('aboutClass')
                ->joinWith(["groupClass groupClass"],false)
                ->joinWith(['memberCard memberCard'],false)
                ->select("groupClass.end,groupClass.class_date,groupClass.company_id,groupClass.venue_id,aboutClass.id,aboutClass.member_id,aboutClass.member_card_id,aboutClass.class_id,aboutClass.is_print_receipt,aboutClass.type,aboutClass.status,memberCard.status as memberCardStatus")
                ->where(['and',["aboutClass.class_id"=>$id],["aboutClass.type"=>2],["in","aboutClass.status",[1,3]]])
                ->asArray();
            if ($type == 'a') {
                //卡未被冻结,未下课
                $ids1 = $this->judgeClass($query,'a');
                $arr = \backend\models\AboutClass::find()
                    ->alias('aboutClass')
                    ->joinWith(["groupClass groupClass"],false)
                    ->joinWith(['memberCard memberCard'],false)
                    ->select("groupClass.end,groupClass.class_date,groupClass.company_id,groupClass.venue_id,aboutClass.id,aboutClass.member_id,aboutClass.member_card_id,aboutClass.class_id,aboutClass.is_print_receipt,aboutClass.type,aboutClass.status,memberCard.status as memberCardStatus")
                    ->where(['and',["aboutClass.class_id"=>$id],["aboutClass.type"=>2],["in","aboutClass.status",[1,3]],["aboutClass.is_print_receipt"=>2]])
                    ->asArray()->all();
                $ids2 = array_column($arr,'id');
                if (empty($ids1)) {
                    $ids1 = [];
                }
                if (empty($ids2)) {
                    $ids2 = [];
                }
                $ids = array_merge($ids1,$ids2);
                return $ids;
            }elseif ($type == 'b') {
                //卡未被冻结,已下课
                $ids = $this->judgeClass($query,'b');
                return $ids;
            }


    }

    /**
     * @desc: 业务后台 - 判断会员卡未冻结的会员是否爽约
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/03/16
     * @param $query
     * @param $type
     * @return array
     */
    public function judgeClass($query,$type){
        //卡未被冻结,已下课
        $arr = $query->andFilterWhere(["aboutClass.is_print_receipt"=>1])->all();
        if (isset($arr) && !empty($arr)) {
            $arr2 = [];
            foreach ($arr as  $k=>$value) {
                //获取进馆记录
                $re = EntryRecord::find()->where([
                    'and',
                    ['venue_id'=>$value['venue_id']],
                    ['company_id'=>$value['company_id']],
                    ['member_card_id'=>$value['member_card_id']],
                    ['member_id'=>$value['member_id']],
                ])->orderBy('id desc')->one();
                //判断进馆时间
                if (isset($re) && !empty($re)) {
                    $entry_time = date('Y-m-d',$re['entry_time']);
                    if ($entry_time != $value['class_date'] || (int)$re['entry_time'] >= (int)$value['end']) {
                        $arr2[] = $value;
                        unset($arr[$k]);
                    }
                }else{
                    $arr2[] = $value;
                    unset($value);
                }
            }
            if ($type == 'b') {
                //卡未被冻结,已下课
                $ids = array_column($arr,"id");
                return $ids;
            }elseif ($type == 'a') {
                //卡未被冻结,未下课
                $ids = array_column($arr2,"id");
                return $ids;
            }
        }
    }
    /**
     * 团课管理 - 批量修改会员卡旷课次数记录
     * @author Houkixin<Houkixin<@itsports.club>
     * @create 2017/7/11
     * @param  $memberCardId  //  会员卡ID
     * @param  $venueId      // 场馆id
     * @return boolean   // 程序执行的结果
     */
    public function updateMemberRecord($memberCardId,$venueId){
           $missAboutSet = $this->getMissRules();   // 冻结规则
          // 将所有爽约次数为空的会员爽约次数设置为0
          MemberCard::updateAll(["absentTimes"=>0],["and",["and",["id"=>$memberCardId],["absentTimes"=>null],["venue_id"=>$venueId]]]);
          // 将所有爽约会员的爽约次数+1
          MemberCard::updateAllCounters(["absentTimes"=>1],["id"=>$memberCardId]);
            if(empty($missAboutSet)){
                return true;
            }
          $time          = $missAboutSet["miss_about_times"];
          // 搜索需要冻结的会员卡
          $needFrozenMemberCard = MemberCard::find()
                                  ->where(["and",["id"=>$memberCardId],[">=","absentTimes",$time]])
                                  ->select("id")
                                  ->asArray()
                                  ->column();
          //批量修改卡状态(冻结)
          MemberCard::updateAll(["status"=>3,"last_freeze_time"=>time(),"recent_freeze_reason"=>1],["id"=>$needFrozenMemberCard]);
          return true;        // 批量修改会员卡状态
    }
    /**
     * 团课管理 - 获取爽约规则表的数据
     * @author Houkixin<Houkixin<@itsports.club>
     * @create 2017/9/19
     * @return array    // 规则数据
     */
    public  function getMissRules(){
        $missAboutSet = MissAboutSet::find()->where(["venue_id"=>$this->venue])->asArray()->one();
        return $missAboutSet;
    }
    /**
     * 团课管理 - 返回教练上课记录信息
     * @author Houkixin<Houkixin<@itsports.club>
     * @create 2017/7/11
     * @param  $id       //  团课排课id
     * @return string   // 返回最后结果
     */
    public function getCoachClassRecord($id){
         $data = CoachClassRecord::find()->where(["class_id"=>$id])->asArray()->one();
         return $data;
    }
    /**
     * 后台 - 场馆管理 - 判断是否能删除修改座次
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/8/11
     * @param $seatId
     * @return object
     */
    public function seatType($seatId)
    {
        $seat = GroupClass::find()->where(['and',['seat_type_id' => $seatId],['>','end',time()]])->asArray()->all();
        if(!empty($seat)){
            foreach ($seat as $key => $value) {
                $about = AboutClass::find()->where(['and',['class_id' => $value['id']],['type' => 2],['>','end',time()]])->one();
                if(isset($about)){
                    return true;
                }
            }
            return false;
        }else{
            return false;
        }
    }

    public function addCoach($courseCategoryId,$addCoachId){
        $data = Course::find()->where(["id"=>$courseCategoryId])
                ->select("coach_id")
                ->asArray()
                ->one();
        $nowCoachId = json_decode($data["coach_id"]);
        $endCoachId = $this->dealCoachData($nowCoachId,$addCoachId);   // 数据过滤处理
        if(!is_array($endCoachId)){
            return $endCoachId;
        }
        $result     = $this->updateCourseCoachId($endCoachId,$courseCategoryId);
        return $result;
    }

    public function dealCoachData($nowCoachId,$addCoachId){
        $i = 0;
        if(!empty($addCoachId)&&!empty($nowCoachId)){
            foreach($addCoachId as $keys=>$coachId){
                if(!in_array($coachId,$nowCoachId)){
                     array_push($nowCoachId,$coachId);     // 新的教练压入
                     continue;
                }
                $i++;
            }
            if(count($addCoachId)===$i){
               return "你选择的教练已存在";
            }
        }elseif(empty($nowCoachId)&&!empty($addCoachId)){
                 $nowCoachId = $addCoachId;               // 整体压入
        }else{
                 $nowCoachId = [];                       // 都为空
        }
        return  $nowCoachId;
    }

    public function updateCourseCoachId($endCoachId,$courseCategoryId){
          $model= \backend\models\Course::findOne($courseCategoryId);
          $model->coach_id = json_encode($endCoachId);
          if(!$model->save()){
              return $model->errors;
          }
         return true;
    }






}



