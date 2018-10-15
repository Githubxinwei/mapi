<?php
namespace backend\modules\v1\models;
use backend\models\AboutClass;
use backend\models\ClassRoom;
use backend\models\Course;
use backend\models\Employee;
use backend\models\EntryRecord;
use backend\models\Member;
use backend\models\MemberAboutClass;
use backend\models\MemberCard;
use backend\models\Organization;
use backend\models\Seat;
use backend\models\SeatType;
use common\models\Func;
use common\models\VenueLimitTimes;

class GroupClass extends \common\models\base\GroupClass
{
    public $date;                       //日期
    public $courseId;                  //课种id
    public $course = [];               //课种id
    public $pid;                        //课种pid
    public $venueId;                    //场馆id
    public static $vid;                 //场馆id
    public static $cid;
    public $memberCardId;              //会员卡id
    public $courseVenueLevel;          // 课程 场馆级别
    public $courseBelVenueId;            // 课程所属场馆
    public $groupOne;                    // 团课课程数据
    public $memberId;                    // 会员id
    public $courseBelongVenueId;        // 课程所属场馆id
    public $memCardLevel;              // 会员卡级别

    public $isOrder;                    // 是否可以跨店预约
    public $isOrderVipSeat;            // 是否可以预约vip座位

    public $isOldMember;               // 是否是老会员（针对老会员）
    public $isAboutTheClass;           // 是否能预约这节课（针对老会员）
    public $venueName;                  // 场馆名称
    public $version;                   // app版本号

    public $seatNum;                   // 实际能用的座位数量
    public $memberIdentify;
    const  LEVEL = [1=>'初级',2=>'中级',3=>'高级'];
    const  COACH_PIC    = 'http://oo0oj2qmr.bkt.clouddn.com/2x.png?e=1498875171&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:7OhJKnzKcruFrJgo5Zl2IajQ3Ek=';
    const  GROUP_PIC    = 'http://oo0oj2qmr.bkt.clouddn.com/20160803094909_6316.jpg?e=1498813628&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:Upr08u-YYiwjVZWPQQlW9sN-CbQ=';

    /**
     * 云运动 - Api - 处理搜索数据
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @param  $data array
     * @return array|\yii\db\ActiveRecord[]
     */
    public function loadParams($data)
    {
        $this->date      = isset($data['date'])?$data['date']:'';
        $this->courseId  = isset($data['course'])&&!empty($data['course'])?$data['course']:'';
//        $this->venueName = !empty($this->venueName)?$data['venueName']:null;
        if(!empty($this->courseId))
        {
            $path = "0,".$this->courseId;
            $courseIdArr = Course::find()->where(["and",["like","path",$path],["class_type"=>2]])->select('id')->asArray()->all();
            foreach ($courseIdArr as $k=>$v)
            {
               $this->course[] = $v['id'];
            }
        }
        $this->venueId = $data['venueId'];
        self::$vid    = $this->venueId;
        self::$cid    = $this->course;
//        if(empty($this->venueId)&&(!empty($this->venueName))&&($this->venueName=="maibu")){
//            $venueId  = self::getVenueId();
//            $this->venueId = $venueId["id"];
//        }
        $version = isset($data["version"])&&(!empty($data["version"]))?$data["version"]:null;
        // 定义version
        define("version",$version);
        return true;
    }
    /**
     * 云运动 - api接口 - 获取数据
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param  $params
     * @return array|\yii\db\ActiveRecord[]
     */
    public function search($params)
    {
        $this->loadParams($params);
        $query = GroupClass::find()
            ->groupBy('class_date');
        $query = $this->getWhere($query);
        $query->createCommand()->getRawSql();
        $query = Func::getDataProvider($query,7);
        return $query;
    }

    public static  function getVenueId(){
        $venueId =Organization::find()
            ->where(["name"=>"天伦锦城店"])
            ->select("id")
            ->asArray()
            ->one();
        return $venueId;
    }




    /**
     * 云运动 - api接口 - 获取搜索条件
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $query
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getWhere($query)
    {
        if($this->date){
            $query->where(['venue_id'=>$this->venueId])->andFilterWhere(['class_date'=>$this->date]);
        }else{
            // 获取本周日的日期
            $nowWeekNum     = date("w",time());   // 获取当前周
//            $nowWeekNum     = ($nowWeekNum != 0)?$nowWeekNum:7;
//            $diffNum        = 7 - $nowWeekNum;
//            // 获取本周日的 时间戳
//            $nowSundayDate  = strtotime("+$diffNum days");
            $nowSundayDate  = strtotime(date('Y-m-d',time()+(60*60*24*7)));
            $query->where(['>=','class_date',date('Y-m-d',time())])
                  ->andWhere(['<=','class_date',date('Y-m-d',$nowSundayDate)])
                  ->andWhere(['venue_id'=>$this->venueId]);
        }
        //  根据发送过来的参数（顶级课种id），$this->course（顶级课程的子类）
            if(!empty($this->courseId)){
                $query->andWhere(['in','course_id',$this->course]);
            }
            return $query;
    }
    public function fields()
    {
//        $fields = parent::fields();
        $fields['class_date'] = 'class_date';
        $fields['info']       = function (){
            if($this->class_date == date('Y-m-d',time())){
                return '今天';
            }else if ($this->class_date == date('Y-m-d',time()+24*60*60)){
                return '明天';
            }else if ($this->class_date == date('Y-m-d',time()+((24*60*60)*2))){
                return '后天';
            }else{
                return '';
            }
        };
        $fields['list'] = function (){
            $query = $this->getQueryData();
            $data = $this->getGroupClassApiDate($query);
            return $data;
        };
        return $fields;
    }


    public function getQueryData(){
        $query = GroupClass::find()
            ->where(['class_date'=>$this->class_date])
            ->andWhere(['venue_id'=>self::$vid])
            ->andFilterWhere(['course_id'=>self::$cid])
            ->select('
                        id,start,end,class_date,difficulty,course_id,coach_id
                    ')
            ->orderBy('start ASC');
        if(constant("version")==2){
           $data = $query->andWhere([">","start",time()])->asArray()->all();
        }else{
           $data = $query->asArray()->all();
        }
        return $data;
    }

    /**
     * 云运动 - api接口 - 获取数据
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $data array
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getGroupClassApiDate($data)
    {
        if(isset($data) && !empty($data) && is_array($data)){
            $endNum = 0;
            foreach ($data as &$v){
                if($v['start'] < time()){                                                                    //判断开始时间是否小于当前时间
                    if(++$endNum > 2) array_shift($data);
                    $v['isTimeEndClass'] = false;                                                            //已经上课的课程（不能预约）
                }else{
                    $v['isTimeEndClass'] = true;                                                             //还未上课的课程（可以预约）
                }
                $v['start_time']     = $v['start'];
                $v['end_time']       = $v['end'];
                $v['start']          = date('H:i',$v['start']);                                               //格式化开始时间
                $v['end']            = date('H:i', $v['end']);                                                //格式化结束时间
                $courseArr           = $this->getFieldsByOne($v,'course');                                    //课种信息数组
                $v['courseName']     = $courseArr['name'];                                                    //课种名称
                $coach               = $this->getFieldsByOne($v,'coach');                                     //教练信息
                $v['coach']          = $coach['name'];                                                        //教练姓名
                $v['coachPic']       = empty($coach['pic'])?self::COACH_PIC:$coach['pic'];                   //教练头像
                $score               = $this->getFieldsByOne($v,'score');                                     //课程级别
                $v['scoreImg']       = $score['scoreImg'];                                                    //课程星星
                $v['level']          = isset($v['difficulty'])?self::LEVEL[$v['difficulty']]:NULL;           //课程难度处理
                unset($v['course_id'],$v['created_at'],$v['coach_id'],$v['classroom_id'],$v['classroom_id']); //处理没用信息
            };
        }
        return $data;
    }
    /**
     * 云运动 - api接口 - 获取处理数据
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $v  array         //课程数组
     * @param $type string
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getFieldsByOne($v,$type)
    {
        switch ($type){
            case 'course':
                $course  = new Course();
                $classInfo            = $course->courseDetail($v['course_id']);              //查询课种信息（用团课表里面的课种id）
                $class['pic']         = $classInfo['pic'];                                   //返回课种图片
                $class['name']        = $classInfo['name'];                                  //返回课种名称
                $classPidInfo         = $course->courseDetail($classInfo['pid']);              //查询课种信息（用团课表里面的课种id）
                $class['courseDesrc'] = $classInfo['course_desrc'];
                $class['category']    = isset($classPidInfo['category'])?$classPidInfo['category']:$classInfo['category'];                       //返回课种类型
                $class['categorySelf'] = $classInfo['category'];                       //返回课种类型
                return $class;                                                           //返回课种类型和课种名称
            case 'classroom':
                if(!empty($v["seat_type_id"])&&!empty($v["classroom_id"])){
                      $classroom["total_seat"] =ClassRoom::getSeatNum($v["seat_type_id"],$v["classroom_id"]);
                }else{
                     $classroom   = ClassRoom::getClassroomOneById($v['classroom_id']);        //用教室id,获取总座位数
                }
                return $classroom['total_seat']; //返回座位总数
            case 'classroomName':
                $classroom   = ClassRoom::getClassroomOneById($v['classroom_id']);        //用教室id,获取总座位数
                return $classroom['name'];
            case 'coach':
                $coach = Employee::getCoachOneById($v['coach_id']);                       //获取教练信息（用团课数组中的教练id）
                return $coach;
            case 'venue':
                if(empty($this->venueId)){
                    $classroom   = ClassRoom::getClassroomOneById($v['classroom_id']);        //获取教室详细信息
                    $venue       = Organization::getOrganizationById($classroom['venue_id']); //通过教室信息去查询组织架构，
                }else{
                    $venue       = Organization::getOrganizationById($this->venueId); //通过教室信息去查询组织架构，
                }
                $venueArr['name']    = $venue['name'];                                    //获取场馆的名称
                $venueArr['id']      = $venue['id'];                                      //获取场馆的id
                $venueArr['address'] = $venue['address'];                                      //获取场馆的id
                return $venueArr;
            case 'about':
                $about       = AboutClass::getAboutClassOneById($v['id']);                //用团课的id，获取所有预约信息
                return count($about);                                                    //统计预约数量
            case 'score':
                $score = isset($v['score'])?$v['score']:4;
                $scoreArr = [];
                $scoreArr['score'] = $score;
                $key = ['one','two','three','four','five'];
                for($i = 1; $i<=5;$i++){
                    if($i <= $score){
                        $scoreArr['scoreImg'][$key[$i-1]] = 'http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=';
                    }else{
                        $scoreArr['scoreImg'][$key[$i-1]] = 'http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4=';
                    }
                }
                return $scoreArr;
            default:;
        }
    }
    /**
     * 云运动 - Api - 获取团课详情
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @param  $id int
     * @param $memberId
     * @param $memberCardId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getGroupClassDetail($id = 245,$memberId = '',$memberCardId = "")
    {
        $groupOne       = GroupClass::find()->where(['id'=>$id])->asArray()->one();                           //查询指定的团课课程数组
        //判断会员身份
        $this->CrossShopOrderClass($groupOne,$memberCardId);


        $this->venueId = $groupOne["venue_id"];
        $config   = new \backend\modules\v1\models\AboutClass();
        $con      = $config->getLeagueAbout($groupOne['start']);                                     //判断是否快上课
        if($con == false)
        {
            $groupOne['limitTime']   = false;                                                          //快上课了，不能预约
        }else{
            $groupOne['limitTime']   = true;                                                           //可以预约
        }
        $member                       = new Member();
        $memberCardId                 = $memberId?$member->getMemberId($memberId,$this->venueId):NULL;                 //获取会员卡Id
        $aboutOne                     = $this->isSetMemberAbout($memberId,$groupOne['start']);          //查询会员约课记录
        if($aboutOne && !empty($aboutOne)){
            $groupOne['isAboutClass'] = true;                                                           //会员有约课记录
        }else{
            $groupOne['isAboutClass'] = false;                                                          //会员没有约课记录
        }
        $memberCard                   = new \backend\modules\v1\models\MemberCard();
        $classId                      = $memberCard->getMemberClass($memberId);                         //获取绑定的多态id(课程id等等)
        if($classId && in_array($groupOne['course_id'],$classId)){                                                         //判断课程id和会员拥有的课程id 是否相等
            $groupOne['isCanClass']   = true;                                                           //相等可以预约
        }else{
            $groupOne['isCanClass']   = false;                                                          //不可预约
        }
        $groupOne['start_time']       = $groupOne['start'];
        $groupOne['end_time']         = $groupOne['end'];
        $groupOne['start']            = date('H:i',$groupOne['start']);                                  //格式化取出团课课程的开始时间
        $groupOne['end']              = date('H:i', $groupOne['end']);                                   //格式化取出团课课程的结束时间
        $classInfo                    = $this->getFieldsByOne($groupOne,'course');                       //获取课种名称和类型信息
        $groupOne['pic']              = $classInfo['pic'];                                               //课种图片
        $groupOne['carousel_pic'] = !empty($classInfo['pic']) ? $classInfo['pic'] : '';
        $groupOne['name']             = $classInfo['name'];                                              //获取课种名称
        $groupOne['courseDesrc']      = $classInfo['courseDesrc'];
        if( $classInfo['category'] == '舞蹈')                                                            //判断课种类型是不是舞蹈类的
        {
            $groupOne['isDance']      = false;                                                           //是舞蹈类的，不让查看座位预约情况
        }else{
            $groupOne['isDance']      = true;                                                            //不是舞蹈类的，让查看座位预约情况
        }

        $score                        = $this->getFieldsByOne($groupOne,'score');                         //评论图信息（课程）
        $groupOne['classScore']       = $score['score'];                                                  //评论图数量
        $groupOne['classScoreImg']    = $score['scoreImg'];                                               //评论图
        $coach                         = $this->getFieldsByOne($groupOne,'coach');                         //获取教练信息数组
        $groupOne['intro'] = !empty($coach['intro']) ? $coach['intro'] :'暂无教练简介';
        $groupOne['coach']            = empty($coach['name'])?"暂无教练":$coach['name'];                                                   //获取教练姓名
        $groupOne['coachAge']         = empty($coach['age'])?25:$coach['age'];                                                    //获取教练年龄
        $groupOne['coachPic']         = empty($coach['pic'])?self::COACH_PIC:$coach['pic'];              //获取教练头像
        $groupOne['workTime']         = empty($coach['work_time'])?3:$coach['work_time'];                                              //获取教练从业时间
        $venue                         = $this->getFieldsByOne($groupOne,'venue');                         //获取场馆信息数组
        $groupOne['venue']            = $venue['name'];                                                   //获取场馆名称
        $groupOne['venue_id']         = $venue['id'];                                                     //获取场馆id
        $groupOne['venueAddress']    = !empty($venue['address'])?$venue['address']:"场馆地址:暂无数据"; //场馆地址
        $groupOne['about']            = $this->getFieldsByOne($groupOne,'about');                         //该课程的预约总数量
        $groupOne['classroom']        = $this->getFieldsByOne($groupOne,'classroom');                     //获取教室座位数量
        $groupOne['classroomName']    = $this->getFieldsByOne($groupOne,'classroomName');                 //获取教室座位数量

        $score                        = $this->getFieldsByOne($groupOne,'score');                          //评论图信息（教练）
        $groupOne['score']            = $score['score'];                                                   //评论图数量
        $groupOne['scoreImg']         = $score['scoreImg'];                                                //评论图
        $course                       = \common\models\base\Course::findOne(['id'=>$groupOne['course_id']]);
        if($course){
            $groupOne['course_duration']  = $course->course_duration;
        }
        $groupOne['level']          = $course['course_difficulty'];                                           //团课等级
        $classModel = new \backend\modules\v1\models\AboutClass();
        
        $groupOne['already_info']  = $classModel->getClassUserInfo($groupOne);//获取已经预约该课程的用户信息
        return $groupOne;                                                                                //返回该课程的所有信息
    }

    public function CrossShopOrderClass($groupOne,$memberCardId){

    }
    /**
     * @云运动 - Api - 获取座位详情
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @param $id
     * @param $seatType
     * @param $memberId          // 会员id
     * @param  $memberCardId    // 会员卡id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getSeatDetail($id,$seatType,$memberId = '',$memberCardId="")
    {
        $this->isOldMember  = false;               // 初始  不是老会员（后面 遇到老会员逻辑 会修正）
        $this->memberCardId = $memberCardId;      // 会员卡id
        $this->memberId      = $memberId;
        $groupOne = \backend\models\GroupClass::find()
            ->alias('group')
            ->joinWith(['course course'])
            ->joinWith(['classroom classroom'],false)
            ->joinWith(["organization organization"],false)
            ->where(['group.id'=>$id])
            ->select('group.*,classroom.name as classroomName,classroom.seat_columns,classroom.seat_rows,classroom.venue_id,organization.identity as courseVenueLevel')
            ->asArray()
            ->one();
        //通过团课id 获团课数组
        $this->courseBelongVenueId = !empty($groupOne["venue_id"])?$groupOne["venue_id"]:"";
        $this->groupOne  = $groupOne;     // 属性赋值
        // 课程场馆级别
        $groupOne["courseVenueLevel"] = !empty($groupOne["courseVenueLevel"])?$groupOne["courseVenueLevel"]:1;   //  普通课程 所属场馆级别
        $this->courseVenueLevel       = $groupOne["courseVenueLevel"];
        $this->courseBelVenueId       = $groupOne["venue_id"];                                         // 课程所属场馆
        $memberCard                   = new \backend\modules\v1\models\MemberCard();
        $venue                        = $this->getFieldsByOne($groupOne,'venue');                         //获取场馆信息数组
        $groupOne['venue']           = $venue['name'];                                                   //获取场馆名称
        $groupOne['venue_id']        = $venue['id'];                                                     //获取场馆id
        $groupOne['venueAddress']   = $venue['address'];                                                //场馆地址
        $aboutOne                     = $this->isSetMemberAbout($memberId,$groupOne['start']);          //查询会员约课记录
        if($aboutOne && !empty($aboutOne)){
            $groupOne['isAboutClass'] = true;                                                           //会员有约课记录
        }else{
            $groupOne['isAboutClass'] = false;                                                          //会员没有约课记录
        }
        $classId       = $memberCard->getMemberClass($memberId,$memberCardId);                         //获取绑定的多态id(课程id等等)
        $model         = new \backend\models\GroupClass([],"");
        $cid          = $model->getCourseTop($groupOne['course_id']);
        // 查询顶级课种id
        if($classId && in_array($cid,$classId)){                                                         //判断课程id和会员拥有的课程id 是否相等
            $groupOne['isCanClass']   = true;                                                           //相等可以预约
        }else{
            $groupOne['isCanClass']   = false;                                                          //不可预约
        }
        $groupOne['start']            = date('H:i',$groupOne['start']);                                  //课程开始时间
        $groupOne['end']              = date('H:i',isset($groupOne['end'])?$groupOne['end']:time());    //课程结束时间
        $data                         = $this->getFieldsByOne($groupOne,'course');
        $groupOne['name']             = $data['name'];                                                   //课种名称
        $coach                        = $this->getFieldsByOne($groupOne,'coach');                        //教练信息
        $groupOne['coach']            = $coach['name'];                                                  //教练姓名
        $groupOne['coachPic']         = isset($coach['pic'])?$coach['pic']:self::COACH_PIC;           //教练头像

        $groupOne['classroom']        = $this->getFieldsByOne($groupOne,'classroom');                    //座位总数
        $groupOne['seatDetail']       = $this->getSeatByClassId($groupOne['seat_type_id'],$id,$seatType,"ios",$groupOne["classroom_id"]);//座位预约信息
        $score                        = $this->getFieldsByOne($groupOne,'score');                        //评论图信息
        $groupOne['score']            = $score['score'];                                                 //评论图数量
        $groupOne['scoreImg']         = $score['scoreImg'];                                              //评论图
        $groupOne["isCanOrder"]       = $this->isOrder;                                               // 跨店能否预约
        $groupOne["isOldMember"]       = $this->isOldMember;                    // 是当是老会员
        if( !$groupOne['isCanClass'] ){
            $groupOne["isCanAboutCourse"] = $this->isAboutTheClass;                    // 是否可以预约这节课
        }else{
            $groupOne["isCanAboutCourse"] = $groupOne['isCanClass'];
        }
       // $groupOne["memberCardMissAbout"] = $this->memberCardMissAbout();
        return $groupOne;
    }
    /**
     * @云运动 - Api获取座位详情 - 会员卡爽约设置
     * @author houkaixn <houkaixin@itsports.club>
     * @create 2017/4/24
     * @return array|null|\yii\db\ActiveRecord
     */
        public function memberCardMissAbout(){
            $data = null;
            if(!empty($this->memberCardId)){
                $data = MemberCard::find()->alias("memberCard")
                                          ->where(["memberCard.id"=>$this->memberCardId])
                                          ->joinWith(["missAboutSet missAboutSet"],false)
                                          ->select("memberCard.absentTimes,memberCard.last_freeze_time,memberCard.recent_freeze_reason,
                                             	memberCard.venue_id")
                                          ->asArray()
                                          ->one();
                $data = !empty($data)?$data:null;
            }
            return $data;
        }
    /**
     * 云运动 - Api - 查看作为详情是否有人上课
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @param  $id int      //座位排次id
     * @param  $classId     //课程id
     * @param  $seatType;   //点击预约
     * @param  $requestType //请求来源  ios 手机端
     * @param  $classroomId //教室id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSeatByClassId($id,$classId,$seatType = 'true',$requestType="",$classroomId="")
    {
        if(empty($requestType)){                           // 请求来源pc端
            $data = SeatType::find()
                ->alias('seatType')
                ->joinWith(['seat seat'])->where(['seatType.id' => $id])
                ->asArray()
                ->one();
        }elseif(!empty($requestType)&&!empty($id)){     // 请求来源 手机端 (来源标志ios 座位排次id存在) 同时返回 会员是否能够预约
            // 已经被占用的座位
            $data = SeatType::find()
                ->alias('seatType')
                ->where(['seatType.id'=>$id])
                ->joinWith(['seat seat'])
                ->select("seatType.*")
                ->asArray()
                ->one();
            // 查询已经预约的课程
            $aboutClass = AboutClass::find()->where(["and",["status"=>1],["class_id"=>$classId]])->asArray()->all();
            //查询已经预约的座位
            $aboutClassSeatId = array_column($aboutClass,"seat_id");
            $seat = $this->getAboutClassSeat($data["seat"],$aboutClassSeatId);
            $data["seat"] = $seat;
            // 对未被暂用的vip座位 判断能否预约（根据:会员卡和 对应的场馆级别判断）
            $data["seat"] = $this->isOrderVipSeat($data["seat"],$classId);
        }
        // 座位 排次id不存在  教室id存在（老数据格式）  来自手机端
        if(empty($id)&&!empty($classroomId)&&!empty($requestType)){
            $data["seat"] = Seat::find()->alias("seat")->where(["and",['seat.classroom_id'=>$classroomId],["seat.rows"=>null],["seat.columns"=>null]])              //通过教室id获取座位表所有信息
                             ->select("seat.*")
                             ->groupBy("seat.id")
                             ->asArray()->all();
            // 查询已经预约的课程
            $aboutClass = AboutClass::find()->where(["and",["status"=>1],["class_id"=>$classId]])->asArray()->all();
            $aboutClassSeatId = array_column($aboutClass,"seat_id");
            // 对未被暂用的vip座位 判断能否预约（根据:会员卡和 对应的场馆级别判断）
            $seat = $this->getAboutClassSeat($data["seat"],$aboutClassSeatId);
            $data["seat"] = $seat;
            $data["seat"] = $this->isOrderVipSeat($data["seat"],$classId);
        }
        // 请求来自pc端（侯凯新标注）
        if($data &&!empty($data) && $seatType == 'true'&&empty($requestType)){
            $data = $this->setIsAboutRecord($data,$classId);                                                          //看座位是否预约（参数满足条件）
        }
        // 返回 之前没加桌次与加过坐次的  区分标志
        $data["isNewSeatStyle"]   =   !empty($id)?true:false;
        $data["total_columns"]    =   !empty($data["total_columns"])?$data["total_columns"]:null;
        $data["total_rows"]        =   !empty($data["total_rows"])?$data["total_rows"]:null;
        $data["seatNum"]           = $this->seatNum;
        return $data;
    }
    /**
     * 云运动 - Api -  判断座位是否被预约
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/14
     * @param  $seat      array   //排次座位
     * @param  $aboutClassSeat   array // 被预约得座位id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAboutClassSeat($seat,$aboutClassSeat){
           if(empty($seat)){
               return $seat;
           }
           $i = 0;
           foreach($seat as $keys=>$values){
                 if(in_array($values["id"],$aboutClassSeat)){
                     $seat[$keys]["is_anyone"] = 1;
                 }else{
                     $seat[$keys]["is_anyone"] = 0;
                 }
                 if($values["seat_type"]!=0){
                   $i++;
                 }
           }
          $this->seatNum = $i;
          return $seat;
    }
    /**
     * 云运动 - Api - 判断是否 能够预约 vip座位 1
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/14
     * @param  $data   array   //教室座位信息
     * @param  $classId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function isOrderVipSeat($data,$classId){
         if(empty($data)){
             return $data;
         }
        // 获取会员卡信息
        $memberCard =  $this->getMemberCardData();
        // 获取会员卡的级别(老会员级别判断)
        // 根据场馆级别（卡所在场馆） 会员卡级别 判断vip座位预约(卡种是老卡种  会员卡是老卡)
        /*
        if((isset($memberCard['create_at']) &&($memberCard['create_at'] < strtotime('2017-6-18')))&&(((isset($memberCard['cardCategory_create_time']))&&($memberCard["cardCategory_create_time"]<strtotime('2017-6-18')))||empty($memberCard['cardCategory_create_time']))){
            $this->memberIdentify = "oldMember";    // 老会员
            $this->memCardLevel   =  $this->memCardLevel($memberCard);
            // 根据会员卡级别   跨店预约情况
            $this->isOrder         = $this->isCrossOrder($memberCard);
        }
        */
        // 新会员级别判断(根据cloud_limit_card_number 判断在不同店面卡级别)  // 直接判断在不同场馆级别
        // 会员卡 开卡时间 新卡 或则  卡种是新卡
        if(true && !empty($memberCard['create_at'])){
            $this->memberIdentify = "newMember";  // 新会员
            $this->memCardLevel   =  $this->newMemCardLevel($memberCard["card_category_id"]);             // 该卡在 对应课程所在场馆的权限
            //  $this->isOrder         = ($this->courseBelongVenueId!=$memberCard["venue_id"])&&($this->memCardLevel==1)?false:true;  // 跨店预约标识
            $this->isOrder         = $this->isCrossOrder();
        }
        // 潜在会员 不能跨店预约
        if(empty($memberCard['create_at'])){
            $this->memberIdentify = "potentialMember";  // 潜在会员
            $this->isOrder       = $this->isCrossOrder();
        }
        // 判断会员 是否预约 vip座位
        $data = $this->judgeOrderVipSeat($data,$memberCard,$classId);
        return  $data;
    }
    /**
     * 云运动 - Api -  判断是否可以 预约vip座位（新会员 新规则）
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/21
     * @return array|\yii\db\ActiveRecord[]
     */
    public function isCanCrossOrder(){
           // 在该场官是vip
             if($this->memCardLevel==3){     //会员卡级别（尊爵卡）    该卡在对应店面的级别
                    $this->isOrderVipSeat = true;
                    return true;
             }
             if($this->memCardLevel==2){
                 $this->isOrderVipSeat  = false;  // 不能预约vip
                    return true;                   // 在别的场馆是 普通卡
             }
           // 在该场官不是vip   级别为1就是不能跨店预约
            $this->isOrderVipSeat = false;
            return false;
    }
    /**
     * 云运动 - Api -  判断是否能够跨店预约（新会员 老会员 潜在会员等）
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/18\
     * @param  $memberCard
     * @return array|\yii\db\ActiveRecord[]
     */
    public function isCrossOrder($memberCard=null){
        switch ($this->memberIdentify){
            case "oldMember":
                // 老会员的跨店预约
              $isOrder   = $this->CrossStoreOrderRule($memberCard);
            break;
            case "newMember":
                // 新会员跨店预约规则
              $isOrder   = $this->isCanCrossOrder();
            break;
            case "potentialMember":
              $isOrder  = ($this->courseBelongVenueId!=$this->venue_id)?false:true;     // 1：课程id和会员注册场馆不在一个场馆（不能跨店预约） 2：同一个场馆可以预约
              $this->isOrderVipSeat = false;        // 不可以预约vip座位
            break;
            default :
              $isOrder = false;
              $this->isOrderVipSeat = false;   // 不可以预约vip座位
            break;
        }
        return $isOrder;
    }
    /**
     * 云运动 - Api - 判断对本场官的权限（新会员）
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/18
     * @param  $memberCard   // 会员卡
     * @return array|\yii\db\ActiveRecord[]
     */
    public function CrossStoreOrderRule($memberCard)
    {
        // 没有跨店预约
        if(($this->courseBelongVenueId==$memberCard["venue_id"])){
            if($this->memCardLevel==2){  // 尊爵卡 可以预约 vip座位
                $this->isOrderVipSeat = true;
            }else{
                $this->isOrderVipSeat = false;
            }
            return true;
        }
        //跨店预约
        // 尊爵卡 （普通店,尊爵会店）
        if($this->memCardLevel==2){
            if($memberCard["venueLevel"]==1){   // 普通店的尊爵卡
                $isOrder = $this->orderCourse($memberCard);
                return $isOrder;
            }
            // 尊爵卡（尊爵会店）
            if($memberCard["venueLevel"]==2){   // 尊爵会 店的金爵卡  可以跨场官
                $this->isOrderVipSeat = true;
                return true;
            }
        }
            // 普通卡    尊爵会，普通店的普通卡不能  跨店和预约vip座位
        if($this->memCardLevel==1){
                $this->isOrderVipSeat = false;  //不可以跨店预约 不可以预约vip
                return false;
        }
    }
    /**
     * 云运动 - Api - 判断对本场官的权限（新会员）
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/18
     * @param  $memberCard  // 会员卡
     * @return array|\yii\db\ActiveRecord[]
     */
    public function orderCourse($memberCard){
        //  *普通店的尊爵卡*
        // 去尊爵会馆  最多进馆6次 不可以预约vip
        if($this->courseVenueLevel==2){
            $this->isOrderVipSeat = false;     // 不可以预约vip座位
            $entryNumber = EntryRecord::find()->where(["and",["venue_id"=>$this->courseBelongVenueId],["member_card_id"=>$memberCard["id"]]])->count();
            if($entryNumber>=6){
                return false;                  // 次数用完 不能跨店预约
            }
                return true;
        }

        //普通店场馆  不限制次数 可以预约vip
        if($this->courseVenueLevel==1){
              $this->isOrderVipSeat = true;
              return true;
        }
              return true;
    }
    /**
     * 云运动 - Api - 判断对本场官的权限（新会员）
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/18
     * @param  $cardCategoryId    //教室座位信息
     * @return array|\yii\db\ActiveRecord[]
     */
    public function newMemCardLevel($cardCategoryId){
        $data = VenueLimitTimes::find()->where(['member_card_id'=>$this->memberCardId])
            ->andWhere(['or',["venue_id"=>$this->courseBelongVenueId], ['>', "json_contains(venue_ids, '[\"{$this->courseBelongVenueId}\"]')", 0]])
            ->asArray()->one();
        if(!empty($data)){
            $data["level"] = ($data["level"]==0||$data["level"]==1||empty($data["level"]))?2:3;  //  2普通 3是vip
        }else{
            $data["level"] = 1;                                    // 不能跨店预约  （没有查询到 对应跨店记录）
        }
        return $data["level"];
    }
    /**
     * 云运动 - Api - 判断是否 能够预约 vip座位 2
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/14
     * @param  $data array   //教室座位信息
     * @param  $memberCard   // 会员卡信息
     * @return array|\yii\db\ActiveRecord[]
     */
    public function judgeOrderVipSeat($data,$memberCard,$classId)
    {
        foreach ($data as $key => $values) {
            // 处理垃圾数据    （防止座位被其它课程占用）
            if(!empty($data[$key]["class_id"])&&($data[$key]["class_id"]!=$classId)){
                $data[$key]["is_anyone"]=0;
            }
            // 赋值 默认权限字段 (有权限)
            $data[$key]["authority"] = true;
            // 针对 老会员 潜在会员的业务逻辑
            if (($values["seat_type"] == 2) && ($values["is_anyone"] == 0)){
                    $data[$key]["authority"] = $this->isOrderVipSeat;
                }
        }
        return $data;
    }

    /**
     * 云运动 - Api - 获取会员卡的 指定信息
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/14
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMemberCardData(){
        if(empty($this->memberCardId)){    // 暂时先这样
             $memberData = Member::find()->where(["id"=>$this->memberId])->asArray()->one();
             $this->venue_id = $memberData["venue_id"];
             return [];
        }
        $memberCard = MemberCard::find()->alias("memberCard")
            ->where(["memberCard.id"=>$this->memberCardId])
            ->joinWith(["organization organization"])
            ->joinWith(["member member"],false)
            ->joinWith(["cardCategory cardCategory"],false)
            ->select(" memberCard.*,
                    organization.identity as venueLevel,
                    member.venue_id as memberVenueId,
                    cardCategory.create_at as cardCategory_create_time,
                    ")
            ->asArray()->one();
        // 当会员卡 所属场馆为空的情况 (默认所属场馆 会员所属场馆)
        $memberCard["venue_id"] = !empty($memberCard["venue_id"])?$memberCard["venue_id"]:$memberCard["memberVenueId"];
        $this->venue_id = !empty($memberCard["memberVenueId"])?$memberCard["memberVenueId"]:"";
        //会员所在场馆id赋值
        $memberCard["venueLevel"] = !empty($memberCard["venueLevel"])?$memberCard["venueLevel"]:1;    // 没有分配场馆权限默认 普通场馆权限
        return $memberCard;
    }
    /**
     * 云运动 - Api - 判断卡的级别
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/14
     * @param  $memberCard       // 会员卡信息
     * @return array|\yii\db\ActiveRecord[]
     */
    public function memCardLevel($memberCard){
        $organId = \backend\models\Organization::getVenueIdByName('大卫城');
        $organ   =\backend\models\Organization::getVenueIdByName('艾搏');
        if(($memberCard['venue_id'] == $organId['id'])||($memberCard['venue_id'] == $organ['id'])){
            return 2;         //座位只能选择 一般位置
        }
        $model   = new AboutRecordForm([],"");
        $model->memberId = $this->memberId;   //初始化数据
        // 查询会员信息
        $memberType = $model->getMemberInfo();
        // 查询顶级课种id
        $topCourseId   = $model->getTopCourseId($this->groupOne);
        // 查询顶级课程名称
        $topCourseName =$model->getCourseName($topCourseId);
        $model                        = new \backend\modules\v1\models\GroupClass();
        $classInfo                    = $model->getFieldsByOne($this->groupOne,'course');
        // 获取卡的级别
        $memberAbout   = new MemberAboutClass();
        $isAboutClass  = $memberAbout->statusMemberAboutClass($this->groupOne,$topCourseName,$memberType,$memberCard,$classInfo["categorySelf"]);//课程信息,课种类型，会员信息（数组），会员卡信息（数组）。返回可不可约课信息
        // 获取卡级别
        $this->isOldMember      = true;            // 是老会员
        $this->isAboutTheClass = $isAboutClass;    //是否能预约这节课
        return  $memberAbout->identity;    // 1代表普通卡 2代表尊爵卡或金爵卡
    }
    /**
     * 云运动 - Api - 查看座位是否有人预约
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @param  $data array   //指定教室的 座位信息
     * @param  $classId     //课程id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function setIsAboutRecord($data,$classId)
    {
        foreach ($data['seat'] as &$v){                         //遍历座位表信息（用座位表id,课程id,课程预约状态，查询约课记录表）
            $about = AboutClass::find()
                ->where(['class_id'=>$classId])
                ->andWhere(['status'=>1])
                ->andWhere(['seat_id'=>$v['id']])
                ->asArray()->one();
            if($about){                                   //查看每个座位是否预约信息存在
                $v['is_anyone'] = 1;                     //有预约
            }else{
                $v['is_anyone'] = 0;                     //没有预约
            }
        }
        return $data;
    }
    /**
     * 云运动 - Api - 获取所有课种
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @param  $type    //课中类型
     * @param  $venueId//场馆id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCourseData($type,$venueId = '')
    {
        $this->getCoursePid($type);                     //获取所有pid
        $data = Course::find()
            ->alias('course')
            ->where(["class_type"=>$type])
            ->andWhere(["not in","id",$this->pid])
            ->andFilterWhere(['venue_id'=>$venueId])
            ->select('id,name,pic')
            ->asArray()->all();
        foreach($data as $k=>&$v)
        {
            if(empty($v['pic']))
            {
                $v['pic'] = self::GROUP_PIC;            //处理课种图片，没有给默认的
            }
        }
        return $data;
    }
    /**
     * 云运动 - Api - 获取所有课种
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @param  $type    //课中类型
     * @param  $venueId//场馆id
     * @param  $companyId  // 公司id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCourseTopData($type,$venueId = '',$companyId="")
    {
        $data = Course::find()
            ->where(["class_type"=>$type])
            ->andWhere(["pid"=>0])
            ->andWhere(["in",'company_id',[75,1]])
            ->select('id,name')
            ->asArray()->all();
        foreach($data as $k=>&$v)
        {
            if(empty($v['pic']))
            {
                $v['pic'] = "image课种";            //处理课种图片，没有给默认的（经商议：图片暂时不需要）
            }
        }
        return $data;
    }
    /**
     * 云运动 - Api - 获取所有课种pid
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/7/1
     * @param $type  //课种类型
     */
    public function getCoursePid($type)
    {
        $pid = Course::find()
            ->where(["class_type"=>$type])
            ->select("pid")
            ->asArray()->all();
        $this->pid = $this->combineData($pid);
    }

    /**
     * 云运动 - Api - 获取所有课种pid
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/7/1
     * @param $pid      //所有pid
     * @return array    //去重后的pid
     */
    public function combineData($pid){
        $thePidS = [];
        foreach($pid as $keys=>$values){
            $thePidS[] = $values["pid"];
        }
        $data = array_unique($thePidS);
        return $data;
    }
    /**
     * @查询约课记录表（看看会员是否有过约课）
     * @param $memberId     //会员id
     * @param $date         //上课开始时间(判断上课时间有没有在约课的上下课时间区间中)
     * @param $counselorId  //是不是员工
     * @return array|null|\yii\db\ActiveRecord      //查询接口
     */
    public function isSetMemberAbout($memberId,$date,$counselorId = '')
    {
        $about = AboutClass::find()->where(['member_id'=>$memberId])
            ->andWhere(['status'=>1])
//           ->andWhere(['start'=>(int)$date])
            ->andWhere(['<=','start',(int)$date])
            ->andWhere(['>=','end',(int)$date])
            ->andFilterWhere(['employee_id'=>$counselorId])
            ->asArray()->one();
        return $about;
    }
}