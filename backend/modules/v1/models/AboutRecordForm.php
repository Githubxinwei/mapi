<?php

namespace backend\modules\v1\models;

use backend\models\GroupCourse;
use backend\models\MemberAboutClass;
use backend\models\MemberCard;
use backend\models\MemberCourseOrder;
use backend\models\MemberCourseOrderDetails;
use common\models\base\AboutClass;
use common\models\base\BindPack;
use common\models\base\ClassRecord;
use common\models\base\Course;
use common\models\base\Employee;
use common\models\base\GroupClass;
use common\models\base\Member;
use common\models\base\MemberDetails;
use common\models\base\Organization;
use common\models\base\VenueLimitTimes;
use common\models\Config;
use common\models\Func;
use common\models\MemberCardTime;
use common\models\EntryRecord;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\models\relations\GroupClassRelations;

class AboutRecordForm extends Model
{
    use GroupClassRelations;
    public $memberId;            //会员id
    public $coachId;
    public $classDate;
    public $memberCardId;
    public $start;
    public $end;
    public $seatId;              //座位id
    public $aboutTime;
    public $classType;           //预约课程类型
    public $classId;             //订单详情课程id
    public $aboutType;           //预约类型
    public $orderDetailsId;      //订单详情id
    public $is_employee;         //是不是员工
    public $employeeName;        //员工姓名
    public $employeeMobile;      //员工手机号
    public $employeeMemberId;    //员工生成的会员id
    public $type;                //区分PC端和手机端
    public $groupId;            //团课课程id
    public $courseId;           //课程id
    public $limitType;     //限制设置
    public $aboutT;
    public $potVenueId;
    public $isClass;             // 课程状态
    public $venueIds;           //通用场馆id
    public $cardCategoryId;    // 卡种id
    public $venueId;           // 场馆id
    /**
     * 云运动 - Api - 构造函数
     * @author lihuien <lihuien@itsports.club>
     * @param array
     * @param $scenario
     * @create 2017/4/24
     */
    public function __construct(array $config,$scenario)
    {
        $this->scenario = $scenario;
        parent::__construct($config);
    }
    /**
     * 云运动 - Api - 场景
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @return array|\yii\db\ActiveRecord[]
     */
    public function scenarios()
    {
        return [
            'charge'=>['classDate','coachId','aboutType','start','end','memberId','classType','classId','memberCardId','type',"venueId"],
            'group' =>['classDate','aboutT','coachId','aboutType','memberId','seatId','classType','classId','memberCardId','is_employee',"venueId"],
        ];
    }
    /**
     * 云运动 - Api - 预约规则
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @return array|\yii\db\ActiveRecord[]
     */
    public function rules()
    {
        return [
            ['classDate','required','message'=>'预约时间不能为空','on'=>'charge'] ,
            ['coachId','required' ,'message'=>'教练不能为空','on'=>'charge'] ,
            [['memberId','memberCardId',
                'aboutType','aboutT','coachId','aboutTime','seatId','classType','classId','classDate','start','end','is_employee','type',"venueId"],'safe'],
        ];
    }
    /**
     * 云运动 - Api - 私课预约保存
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @return array|\yii\db\ActiveRecord[]
     */
    public function saveAbout()
    {

        $details = \common\models\base\MemberCourseOrderDetails::findOne(['id' => $this->classId]);
        $order   = \common\models\base\MemberCourseOrder::findOne(['id' => $details['course_order_id']]);
        if($order['course_type'] == 3){
            if((strtotime($this->classDate) < $order['activeTime']) || (strtotime($this->classDate) > $order['deadline_time'])){
                return '此课程的预约时间为'.date('Y-m-d',$order['activeTime']).'到'.date('Y-m-d',$order['deadline_time']);
            }
        }
        if(isset($this->type) && ($this->type == 'PC')){
            $this->getClassDate($this->classDate);                                                                        //处理私课预约时间
            $this->getMemberId();                                                                                         //获取会员单条数据
            $aboutInfo = $this->memberType();                                                                            //潜在会员和普通会员约课信息处理
            if($aboutInfo === false)
            {
                return 'not repeat';   // 重复预约（同样的课程重复预约）
            }
            $reAbout = AboutClass::find()
                ->where(['member_id' => $this->memberId])
                ->andWhere(['class_date' => $this->classDate])
                ->andWhere(['<>','status',2])
                ->asArray()->all();
            foreach($reAbout as $key=>$value){
                if((int)$this->start >= $value['start'] && (int)$this->start <= $value['end']){
                    return '此会员在该时间段已预约其他课程';
                }elseif((int)$this->end >= $value['start'] && (int)$this->end <= $value['end']){
                    return '此会员在该时间段已预约其他课程';
                }
            }
            $about                   = new AboutClass();
            $about->type             = (isset($this->classType) && $this->classType=='charge') ? (string)1 : (string)2;
            $about->class_id         = $this->classId;                    //订单详情表id                                                //课程id
            $about->coach_id         = $this->coachId;                                                                    //教练id
            $about->start            = (int)$this->start;                                                                 //开始时间
            $about->end              = (int)$this->end;                                                                   //结束时间
            $about->class_date       = $this->classDate;
            $about->create_at        = time();
            $about->status           = 1;
            $about->member_card_id   = $this->memberCardId;
            $about->member_id        = $this->memberId;       
            $about->is_print_receipt = 2;                                                                                 //是否打印小票（1有2没有）
            $about->about_type       = 2;                                          //预约方式(1电话预约2自助预约）
        }else{
            $orderData              = $this->getAboutOrder($this->classId);                                                  //查询订单和订单详情
            $this->orderDetailsId = $this->getAboutNum($orderData,$this->memberId);                                        //（遍历订单详情表）获取订单详情表id
            $this->getClassDate($this->classDate);                                                                         //处理私课预约时间
            $this->getMemberId();                                                                                          //获取会员单条数据
            $aboutInfo              = $this->memberType();                                                                   //潜在会员和普通会员约课信息处理
            if($aboutInfo === false)
            {
                return 'not repeat';                                                  // 重复预约（同样的课程重复预约）
            }
            // 交叉时间不能重复预约
            $isOrderClass = $this->isOrderClass();
            if($isOrderClass!=0){
                return "hadClass";
            }
            //是否与教练其他课冲突
            $crossNum = AboutClass::find()->where(['coach_id'=>$this->coachId])->andWhere(['!=', 'status', 2])
                ->andWhere(['or',
                    ['and', ['>','start',$this->start], ['<', 'start', $this->end]],
                    ['and', ['>','end',$this->start], ['<', 'end', $this->end]]])->count();
            if($crossNum>0) return 'hadCrossClass';

            if($this->orderDetailsId == false)
            {
                  return 'not class';                                                 //没有课程了
            }
            $about                   = new AboutClass();
            $about->type             = (isset($this->classType) && $this->classType=='charge') ? (string)1 : (string)2;
            $about->class_id         = $this->orderDetailsId;              //订单详情表id                                                //课程id
            $about->coach_id         = $this->coachId;                                                                    //教练id
            $about->start            = (int)$this->start;                                                                 //开始时间
            $about->end              = (int)$this->end;                                                                   //结束时间
            $about->class_date       = $this->classDate;
            $about->create_at        = time();
            $about->status           = 1;
            $about->member_card_id   = $this->memberCardId;
            $about->member_id        = $this->memberId;
            $about->is_print_receipt = 2;                                                                                 //是否打印小票（1有2没有）
            $about->about_type       = 2;
        }
        if($about->save()){
            $this->checkClassStatus();
            return $about;
        }else{
            return $about->errors;
        }
    }
    /**
     * 云运动 - Api - ios 私课预约（交叉时间判重：判断本次课程预约时间是否预约过其它课程）
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/7/21
     * @return mixed   // 返回交叉时间 是否预约其它课程
     */
    public function isOrderClass(){
        $date = date("Y-m-d",strtotime($this->classDate));
        $num = AboutClass::find()
            ->where(["and",["member_id"=>$this->memberId],["class_date"=>$date],["!=","status",2]])
            ->andWhere(["or",["between","start",$this->start,$this->end],
                ["between","end",$this->start,$this->end,
                ],["and",["<","start",$this->start],[">","end",$this->end]]])
            ->asArray()->count();
        return $num;
    }

    /**
     * 云运动 - Api - 获取私课订单详情id
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/15
     * @param $orderData   //订单信息
     * @param $memberId    //会员信息
     * @return mixed
     */
    public function getAboutNum($orderData,$memberId)
    {
        if(!empty($orderData)){
            foreach ($orderData['memberCourseOrderDetails'] as $k=>$v){
                //遍历订单详情表（多节课程）
                $orderNum = \backend\models\AboutClass::find()->where(['and',['class_id'=>$v['id']],['member_id'=>$memberId]])->andWhere(['<>','status',2])->count();
                if($orderNum < $v['course_num'])  //预约数量小于 基础量
                {
                    return $v['id'];            //课程没有用完，继续预约
                }else if($orderNum == $v['course_num'])  //预约的数量 等于 基础课量
                {
                    continue;                            //跳出本次循环（也就是本课程已上完，继续判断下个课程是否上完）
                }else{
                    return false;                       //所有课程都上完了
                }
            }
        }
        return false;
    }
    /**
     * 云运动 - Api - 私课预约订单详情查询
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/15
     * @param $orderId  //订单id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAboutOrder($orderId)
    {
        return MemberCourseOrder::find()
            ->alias('order')
            ->joinWith(['memberCourseOrderDetails details'])
            ->andWhere(['order.id'=>$orderId])
            ->select('order.id')
            ->asArray()
            ->one();
    }
    /**
     * 后台 - 约课管理 - 查询会员是否重复预约课程
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/22
     * @param $memberId //会员id
     * @param $classId      //课程id
     * @param $start        //开始时间
     * @param $end          //接收时间
     * @return bool
     */
    public function getExistClass($memberId,$classId,$start,$end){
        $model = new \backend\models\AboutClass();
        return $model->getClassExist($memberId,$classId,$start,$end);
    }
    /**
     * 后台 - 约课管理 - 查询会员是否重复预约课程
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/5/22
     * @param $memberId //会员id
     * @param $start        //开始时间
     * @param $end          //接收时间
     * @return bool
     */
    public function getClassDataExist($memberId,$start,$end){
        $model = new \backend\models\AboutClass();
        return $model->getClassData($memberId,$start,$end);

    }

    /**
     * 云运动 - 员工约课 - 数据处理
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/2
     * @return bool
     */
    public function employeeInfo()
    {
        $this->employeeMemberId = $this->memberId;                                       //获取员工id
        $employee = $this->getEmployeeMemberInfo($this->employeeMemberId);               //查询员工有没有生成过会员信息
        if(empty($employee))
        {
            $data = $this->setEmployeeMemberInfo();                                      //给员工绑定会员身份
            if($data != true)                                                           //员工没有生成过会员信息，则给员工生成会员信息（成功，并且获取会员id）
            {
                return false;                                                           //事务回滚了阻止，代码往下走
            }
        }else{
            $this->memberId = $employee['id'];                                             //获取会员id
        }
    }

    /**
     * 云运动 - 约课 - 会员和潜在会员信息处理
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/2
     * @return string
     */
    public function memberType()
    {
        $member = $this->getMemberType($this->memberId);                                                            //判断会员是潜在会员还是会员

        if(!empty($member))
        {                                                                                                            //防止会员重复时间预约或者预约重复课程
            if($this->orderDetailsId)
            {
                $data = $this->getExistClass($this->memberId,$this->orderDetailsId,(int)$this->start,(int)$this->end);         //防止重复提交同一课程
            }else{
                $data = $this->getExistClass($this->memberId,$this->classId,(int)$this->start,(int)$this->end);                  //防止重复提交同一课程
            }
            if(!empty($data) && isset($data))
            {
                return false;
            }
            return true;
        }
    }

    /**
     * 云运动 - 约课 - 会员绑定课种查询
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017-06-24
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function courseLimit()
    {
        $card = MemberCard::findOne(['id' => $this->memberCardId]);
        $bind = BindPack::find()
            ->where(['card_category_id' => $card['card_category_id'],'status' => 1])
            ->select('polymorphic_id as id,number')
            ->asArray()
            ->all();
     //   根据顶级课种查询 所有的低级课种
        $arrCourse = array_column($bind,"id");
       // 查询指定课程的低级课程信息
        $model = new GroupCourse();
        $data =["nowBelongType"=>null,"nowBelongId"=>null];
        $bottomData =  $model->getData($data,null,$arrCourse);
        //获取卡种绑定的课种id
        if(empty($bottomData)){
            return false;                                                                  //不能预约
        }else{
            return $bottomData;                                                                   //会员可以预约的课程id数组
        }
    }
    /**
     * 云运动 - 约课 - 会员绑定课种查询
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017-06-24
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function courseLimitArr()
    {
        $bind =  $this->getGroupBind();
        // 查询指定课程的低级课程信息
        \Yii::trace($bind,'bind');
        if(!empty($bind)){
            $group = new \backend\models\GroupClass();
            $courseId = $group->getCourseTop($this->courseId);
            foreach ($bind as $k=>$v){
                $idArr = json_decode($v['polymorphic_ids']);
                if(in_array($courseId,$idArr)){
                    return $v['number'];
                }
            }
        }
        return false;
    }
    /**
     * 云运动 - Api - 查询会员类型
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/6/27
     * @return array|null|ActiveRecord
     */
    public function getMemberInfo()
    {
        return Member::find()->where(['id'=>$this->memberId])->asArray()->one();
    }
    /**
     * 云运动 - Api - 查询会员类型
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/6/27
     * @return array|null|ActiveRecord
     */
    public function getMemberCardInfo()
    {
        return MemberCard::find()->where(['id'=>$this->memberCardId])->select('create_at,venue_id,invalid_time')->asArray()->one();
    }
    /**
     * 云运动 - Api - 团课预约保存
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @return array|\yii\db\ActiveRecord[]
     */
    public function saveGroupAbout()
    {
        // 判断该座位 是否被预约
        if($this->aboutType == 'app'|| $this->aboutT == 'mobile'){
            $result =  $this->isOrderTheSeat();                                              // 查看是否预约了该座位
            if($result!==true){
                return 'engross';              //被占用
            }
        }
        if(empty($this->aboutType)){
            $this->aboutType = $this->aboutT;
        }
        $this->getGroupClassId();                                                    //处理团课预约时间
        $memberType = $this->getMemberInfo();
        $this->getMemberId();                                                        //处理会员单条数据（获取会员卡信息）
        if($memberType['member_type'] ==2)
        {
            $memberaccountid = \backend\modules\v1\models\Member::find()->where(['id'=>$this->memberId])->asArray()->one();
            $member = \backend\modules\v1\models\Member::find()->where(['member_account_id'=>$memberaccountid['member_account_id'],'member_type'=>1])->asArray()->all();
            if (empty($member)){
                $num = \backend\models\AboutClass::find()->alias('ac')->joinWith(['groupClass gc'])->where(['ac.member_id'=>$this->memberId,'gc.venue_id'=>$this->potVenueId])->andWhere(['<>','ac.status',2])->count();
                if($num >=1){
                    return 'limitOne';                                              //潜在会员限制预约一节课
                }
            }
        }
        $memberCard = $this->getMemberCardInfo();
        $group = \backend\models\GroupClass::find()
            ->alias('gc')
            ->joinWith(['course course'])
            ->where(['gc.id'=>$this->classId])
            ->asArray()
            ->one();
        if(empty($this->is_employee)) {//不是员工，或者不是潜在会员的 走if里面
            /*if(empty($this->is_employee) && $memberType['member_type'] ==1) {   */              //不是员工，或者不是潜在会员的 走if里面
//            \Yii::trace($memberCard,'$memberCard2');
            if(time() > $memberCard['invalid_time']) return 'expired';//会员卡过期
            if($memberCard['create_at'] > strtotime('2017-6-18')){
                // 调取会员卡 和 会员卡id
//                $number        = $this->theClassNumLimitArr();
//                $limitTotal    = $this->courseLimitArr();
                // 获取顶级课种id
                $topCourseId  = $this->getTopCourseId($group);
                // 根据顶级课程id 判断会员卡是否绑定过该课程(更改)
                $limitNumData      = BindPack::find()->where(["and",
                    ["card_category_id"=>$this->cardCategoryId],
                    ["polymorphic_type"=>"class"],
                    ["or",["like","polymorphic_ids",'"'.$topCourseId.'"'],["polymorphic_id"=>$topCourseId]],
                    ["status"=>1]])->asArray()->one();
                $courseId = GroupClass::find()->where(['id' => $this->classId])->select(['course_id'])->asArray()->one();   //查询预约课种id（根据团课排课表）
                if($this->aboutType == 'app'){
                     if(empty($limitNumData)){
                         return 'noBindClass';   //  该卡没有绑定该课程
                     }
                     // 查询按照 哪一种条件限制
                     $firstLimit   = $this->firstLimit($topCourseId,$courseId);
                     if($firstLimit!==true){
                         return  $firstLimit;
                     }
                     //按照第二种方式 限制
                    $secondLimit  = $this->secondLimit($topCourseId,$courseId);
                    if($secondLimit!==true){
                        return $secondLimit;
                    }
                }
                // 如果是迈步的健身卡走里边
                $checkResult = $this->checkIsNotMB();
                // 查询 该产品的限制次数
                if($checkResult==true){
//                    if(($number >= $limitTotal) && $limitTotal != -1 && $limitTotal){
//                        return "classOver";
//                    }
//                    \Yii::trace($limitNumData,'limitNumData');
//                    \Yii::trace($limitTotal,'limitTotal');
//                    // 查询 该产品的是否绑定此课程
//                    if(empty($limitNumData) && !$limitTotal){
//                        return 'noBindClass';
//                    }
                    // 1:如果是迈步  是否绑定套餐
                    if(empty($limitNumData)){
                        return 'noBindClass';
                    }
                    // 2:查询 按照哪一种方式  限制
                    // 走第一种预约限制
                    $firstLimit   = $this->firstLimit($topCourseId,$courseId);
                    if($firstLimit!==true){
                        return  $firstLimit;
                    }
                    //按照第二种方式 限制
                    $secondLimit  = $this->secondLimit($topCourseId,$courseId);
                    if($secondLimit!==true){
                        return $secondLimit;
                    }
                }
                // 开课前多少分钟不能预约
                $aboutLimit = $this->aboutLimit();
                if($aboutLimit == null || $aboutLimit == 1) {
                    $beforeClassLimit = $this->beforeClassLimit();
                    $beforeClassLimit = (empty($beforeClassLimit)||$beforeClassLimit==0)?0:$beforeClassLimit;
                    $endAboutTime     = $group["start"] - $beforeClassLimit*60;
                    if(time()>=$endAboutTime){
                        return ["status"=>"endAboutLimit","endClassLimit"=>$beforeClassLimit];
                    }
                }
                // 预约完全一模一样的判断(同一课程不能重复预约[次卡除外（场馆反馈）])
                $judgeResult = self::judgeIsNotTimesCard($this->memberCardId);
                if($judgeResult!==true){
                    $theSameGroupClass = $this->aboutTheSameClass($group);
                    if($theSameGroupClass!=0){
                        return "not repeat";
                    }
                    // 预约交叉判断
                    $data  = $this->aboutRepeatCourse($group);
                    if($data>0){
                        return "hadClass";
                    }
                }
            }elseif (($memberCard['create_at'] < strtotime('2017-6-18'))&&($this->aboutType=="app")){       // app老会员预约课程
                // 开课前多少分钟不能预约
                $aboutLimit = $this->aboutLimit();
                if($aboutLimit == null || $aboutLimit == 1) {
                    $beforeClassLimit = $this->beforeClassLimit();
                    $beforeClassLimit = (empty($beforeClassLimit) || $beforeClassLimit == 0) ? 0 : $beforeClassLimit;
                    $endAboutTime = $group["start"] - $beforeClassLimit * 60;
                    if (time() >= $endAboutTime) {
                        return ["status" => "endAboutLimit", "endClassLimit" => $beforeClassLimit];
                    }
                }
                // 预约完全一模一样的判断(同一课程不能重复预约)
                $theSameGroupClass = $this->aboutTheSameClass($group);
                if($theSameGroupClass!=0){
                    return "not repeat";
                }
                //交叉时间段 预约课程
                $data  = $this->aboutRepeatCourse($group);
                if($data>0){
                    return "hadClass";
                }
            }
            /*   else{
                   $number        = $this->theClassNumLimitArr();
                   $limitTotal    = $this->courseLimitArr();
                   // 获取顶级课种id
                   $topCourseId   = $this->getTopCourseId($group);
                   // 根据顶级课程id 判断会员卡是否绑定过该课程
                   $limitNumData  = BindPack::find()->where(["and",["card_category_id"=>$this->cardCategoryId],["polymorphic_type"=>"class"],["polymorphic_id"=>$topCourseId],["status"=>1]])->asArray()->one();
                   $model       = new \backend\models\Course();
                   $bottomData  = $model->getBottomData($topCourseId);
                   $canCourseId = array_column($bottomData,"id");                     //获取该课程的所有团课排课id
                   $groupArr = $this->getTheBindClass($canCourseId);
                   $classId  = array_column($groupArr,"id");                            // 获取今天规定课种的所有排课id
                   $num    = $this->theClassNumLimite($classId);
                   // 查询 该产品的限制次数
                   if(($number >= $limitTotal) && $limitTotal != -1 && $limitTotal){
                       return "classOver";
                   }
                   \Yii::trace($limitNumData,'limitNumData');
                   \Yii::trace($limitTotal,'limitTotal');
                   // 查询 该产品的是否绑定此课程
                   if(empty($limitNumData) && !$limitTotal){
                       return 'noBindClass';
                   }
                   // 查询 该产品的限制次数
                   $limitNum    = $limitNumData["number"];
                   if(($num>=$limitNum)&&$limitNum!=-1){
                       return "classOver";
                   }
               } */
        }else{
            if($this->is_employee == 'true'){                                           //是员工约课
                $employee = $this->employeeInfo();                                      //给员工处理会员身份
                if( $employee === false){                                              //处理员工信息
                    return false;                                                      //出错， //事务回滚了阻止，代码往下走
                }
            }
            // 开课前多少分钟不能预约
            $beforeClassLimit = $this->beforeClassLimit();
            $endAboutTime     = $group["start"] - $beforeClassLimit*60;
            if(time()>=$endAboutTime){
                return ["status"=>"endAboutLimit","endClassLimit"=>$beforeClassLimit];
            }
            $aboutInfo = $this->memberType();                                             //潜在会员和普通会员约课信息处理
            if($aboutInfo === false)
            {
                return 'not repeat';                                                      //重复预约
            }
        }
        $about = new AboutClass();
        $about->type             = (isset($this->classType) && $this->classType=='charge') ? (string)1 : (string)2;
        $about->seat_id          = $this->seatId;
        $about->class_id         = $this->classId;
        $about->coach_id         = $this->coachId;
        $about->start            = (int)$this->start;
        $about->end              = (int)$this->end;
        $about->class_date       = $this->classDate;
        $about->create_at        = time();
        $about->status           = 1;
        $about->member_card_id   = $this->memberCardId;
        $about->member_id        = $this->memberId;
        $about->is_print_receipt = 2;                                                               //是否打印小票（1有2没有）
        $about->about_type       = 2;                         //预约方式(1电话预约2自助预约）
        $about->employee_id      = $this->employeeMemberId;                                        //员工id
        if($about->save()){
            $this->checkClassStatus();
            return $about;
        }else{
            return $about->errors;
        }

    }

    //判断是否启用团课预约设置
    public function aboutLimit()
    {
        $venueLimit = VenueLimitTimes::find()
            ->where(['member_card_id' => $this->memberCardId,'venue_id' => $this->venueId])->asArray()->one();
        if(!empty($venueLimit)){
            return $venueLimit['about_limit'];
        }else{
            $venueLimit = VenueLimitTimes::find()
                ->where(['member_card_id' => $this->memberCardId])->select('venue_ids,about_limit')->asArray()->all();
            if(!empty($venueLimit)){
                foreach ($venueLimit as $key => $value) {
                    $venues = json_decode($value['venue_ids'],true);
                    if(count($venues) > 1){
                        if(in_array($this->venueId,$venues)){
                            return $value['about_limit'];
                        }
                    }else{
                        if($this->venueId == $venues[0]){
                            return $value['about_limit'];
                        }
                    }
                }
                return null;
            }else{
                return null;
            }
        }
    }
    /**
     * 云运动 - Api - 团课预约保存
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/12/27
     * @return array|\yii\db\ActiveRecord[]
     */
    public function saveGroupClass()
    {
        // 判断该座位 是否被预约
        if($this->aboutType == 'app'|| $this->aboutT == 'mobile'){
            $result =  $this->isOrderTheSeat();                                              // 查看是否预约了该座位
            if($result!==true){
                return 'engross';              //被占用
            }
        }
        if(empty($this->aboutType)){
            $this->aboutType = $this->aboutT;
        }
        $this->getGroupClassId();                                                    //处理团课预约时间
        $memberType = $this->getMemberInfo();
        $this->getMemberId();                                                        //处理会员单条数据（获取会员卡信息）
        if($memberType['member_type'] ==2)
        {
            $num = \backend\models\AboutClass::find()->alias('ac')->joinWith(['groupClass gc'])->where(['ac.member_id'=>$this->memberId,'gc.venue_id'=>$this->potVenueId])->andWhere(['<>','ac.status',2])->count();
            if($num >= 1){
                return 'limitOne';                                                   //潜在会员限制预约一节课
            }
        }
        $memberCard = $this->getMemberCardInfo();
        $group = \backend\models\GroupClass::find()
            ->alias('gc')
            ->joinWith(['course course'])
            ->where(['gc.id'=>$this->classId])
            ->asArray()
            ->one();
        if(empty($this->is_employee) && $memberType['member_type'] ==1) {                 //不是员工，或者不是潜在会员的 走if里面
            \Yii::trace($memberCard,'$memberCard2');
            if($memberCard['create_at'] > strtotime('2017-6-18')){
                // 调取会员卡 和 会员卡id
//                $number        = $this->theClassNumLimitArr();
//                $limitTotal    = $this->courseLimitArr();
                // 获取顶级课种id
                $topCourseId  = $this->getTopCourseId($group);
                // 根据顶级课程id 判断会员卡是否绑定过该课程(更改)
                $limitNumData = BindPack::find()
                    ->where(
                        ["and",
                            ["card_category_id"=>$this->cardCategoryId],
                            ["polymorphic_type"=>"class"],
                            ["or",["like","polymorphic_ids",'"'.$topCourseId.'"'],["polymorphic_id"=>$topCourseId]],
                            ["status"=>1]
                        ])
                    ->asArray()
                    ->one();
                $courseId = GroupClass::find()->where(['id' => $this->classId])->select(['course_id'])->asArray()->one();   //查询预约课种id（根据团课排课表）
                if($this->aboutType == 'app'){
                     if(empty($limitNumData)){
                         return 'noBindClass';   //  该卡没有绑定该课程
                     }
                    // 查询按照 哪一种条件限制
                     $firstLimit   = $this->firstLimit($topCourseId,$courseId);
                     if($firstLimit!==true){
                         return  $firstLimit;
                     }
                     //按照第二种方式 限制
                     $secondLimit  = $this->secondLimit($topCourseId,$courseId);
                     if($secondLimit!==true){
                         return $secondLimit;
                     }
                 }
                // 如果是迈步的健身卡走里边
                $checkResult = $this->checkIsNotMB();
                // 查询 该产品的限制次数
                if($checkResult==true){
//                    if(($number >= $limitTotal) && $limitTotal != -1 && $limitTotal){
//                        return "classOver";
//                    }
//                    \Yii::trace($limitNumData,'limitNumData');
//                    \Yii::trace($limitTotal,'limitTotal');
//                    // 查询 该产品的是否绑定此课程
//                    if(empty($limitNumData) && !$limitTotal){
//                        return 'noBindClass';
//                    }
                    // 1:如果是迈步  是否绑定套餐
                    if(empty($limitNumData)){
                        return 'noBindClass';
                    }
                    // 2:查询 按照哪一种方式  限制
                    // 走第一种预约限制
                    $firstLimit   = $this->firstLimit($topCourseId,$courseId);
                    if($firstLimit!==true){
                        return  $firstLimit;
                    }
                    //按照第二种方式 限制
                    $secondLimit  = $this->secondLimit($topCourseId,$courseId);
                    if($secondLimit!==true){
                        return $secondLimit;
                    }
                }
                // 开课前多少分钟不能预约
                $beforeClassLimit = $this->beforeClassLimit();
                $beforeClassLimit = (empty($beforeClassLimit)||$beforeClassLimit==0)?0:$beforeClassLimit;
                $endAboutTime     = $group["start"] - $beforeClassLimit*60;
                if(time()>=$endAboutTime){
                    return ["status"=>"endAboutLimit","endClassLimit"=>$beforeClassLimit];
                }
                // 预约完全一模一样的判断(同一课程不能重复预约[次卡除外（场馆反馈）])
                $judgeResult = self::judgeIsNotTimesCard($this->memberCardId);
                if($judgeResult!==true){
                    $theSameGroupClass = $this->aboutTheSameClass($group);
                    if($theSameGroupClass!=0){
                        return "not repeat";
                    }
                    // 预约交叉判断
                    $data  = $this->aboutRepeatCourse($group);
                    if($data>0){
                        return "hadClass";
                    }
                }
            }elseif (($memberCard['create_at'] < strtotime('2017-6-18'))&&($this->aboutType=="app")){       // app老会员预约课程
                // 开课前多少分钟不能预约
                $beforeClassLimit = $this->beforeClassLimit();
                $beforeClassLimit = (empty($beforeClassLimit)||$beforeClassLimit==0)?0:$beforeClassLimit;
                $endAboutTime     = $group["start"] - $beforeClassLimit*60;
                if(time()>=$endAboutTime){
                    return ["status"=>"endAboutLimit","endClassLimit"=>$beforeClassLimit];
                }
                // 预约完全一模一样的判断(同一课程不能重复预约)
                $theSameGroupClass = $this->aboutTheSameClass($group);
                if($theSameGroupClass!=0){
                    return "not repeat";
                }
                //交叉时间段 预约课程
                $data  = $this->aboutRepeatCourse($group);
                if($data>0){
                    return "hadClass";
                }
            }
              else{
                   $number        = $this->theClassNumLimitArr();
                   $limitTotal    = $this->courseLimitArr();
                   // 获取顶级课种id
                   $topCourseId   = $this->getTopCourseId($group);
                   // 根据顶级课程id 判断会员卡是否绑定过该课程
                   $limitNumData  = BindPack::find()->where(["and",["card_category_id"=>$this->cardCategoryId],["polymorphic_type"=>"class"],["polymorphic_id"=>$topCourseId],["status"=>1]])->asArray()->one();
                   $model       = new \backend\models\Course();
                   $bottomData  = $model->getBottomData($topCourseId);
                   $canCourseId = array_column($bottomData,"id");                     //获取该课程的所有团课排课id
                   $groupArr = $this->getTheBindClass($canCourseId);
                   $classId  = array_column($groupArr,"id");                            // 获取今天规定课种的所有排课id
                   $num    = $this->theClassNumLimite($classId);
                   // 查询 该产品的限制次数
                   if(($number >= $limitTotal) && $limitTotal != -1 && $limitTotal){
                       return "classOver";
                   }
                   \Yii::trace($limitNumData,'limitNumData');
                   \Yii::trace($limitTotal,'limitTotal');
                   // 查询 该产品的是否绑定此课程
                   if(empty($limitNumData) && !$limitTotal){
                       return 'noBindClass';
                   }
                   // 查询 该产品的限制次数
                   $limitNum    = $limitNumData["number"];
                   if(($num>=$limitNum)&&$limitNum!=-1){
                       return "classOver";
                   }
               }
        }else{
            if($this->is_employee == 'true'){                                           //是员工约课
                $employee = $this->employeeInfo();                                      //给员工处理会员身份
                if( $employee === false){                                              //处理员工信息
                    return false;                                                      //出错， //事务回滚了阻止，代码往下走
                }
            }
            // 开课前多少分钟不能预约
            $beforeClassLimit = $this->beforeClassLimit();
            $endAboutTime     = $group["start"] - $beforeClassLimit*60;
            if(time()>=$endAboutTime){
                return ["status"=>"endAboutLimit","endClassLimit"=>$beforeClassLimit];
            }
            $aboutInfo = $this->memberType();                                             //潜在会员和普通会员约课信息处理
            if($aboutInfo === false)
            {
                return 'not repeat';                                                      //重复预约
            }
        }
        $about = new AboutClass();
        $about->type             = (isset($this->classType) && $this->classType=='charge') ? (string)1 : (string)2;
        $about->seat_id          = $this->seatId;
        $about->class_id         = $this->classId;
        $about->coach_id         = $this->coachId;
        $about->start            = (int)$this->start;
        $about->end              = (int)$this->end;
        $about->class_date       = $this->classDate;
        $about->create_at        = time();
        $about->status           = 1;
        $about->member_card_id   = $this->memberCardId;
        $about->member_id        = $this->memberId;
        $about->is_print_receipt = 2;                                                               //是否打印小票（1有2没有）
        $about->about_type       = 2;                         //预约方式(1电话预约2自助预约）
        $about->employee_id      = $this->employeeMemberId;                                        //员工id
        if($about->save()){
            $this->checkClassStatus();
            return $about;
        }else{
            return $about->errors;
        }

    }

    /**
     * 云运动 - Api - 判断会员卡是否是次卡
     * @author 侯凯新 <houkaixin@itsports.club>
     * @param  $memberCardId  // 会员卡id
     * @create 2017/12/07
     * @return boolean   true 代表是 次卡 false代表不是次卡
     */
    public static function judgeIsNotTimesCard($memberCardId){
        if(!isset($memberCardId)||empty($memberCardId)){
           return false;
        }
        $num = MemberCard::find()
                       ->alias("memberCard")
                       ->joinWith(["cardCategory cardCategory"=>function($query){
                           $query->joinWith(["cardCategoryType cardCategoryType"]);
                       }],false)
                       ->where(["memberCard.id"=>$memberCardId])
                       ->andWhere(['or',["cardCategoryType.type_name"=>"次卡"],["memberCard.card_name"=>"次卡"]])
                       ->count();
        if($num!=0){
            return true;
        }
        return false;
    }

    /**
     * 云运动 - Api - 第一种条件限制
     * @author 侯凯新 <houkaixin@itsports.club>
     * @param $topCourseId  // 顶级课种id
     * @param $courseId     // 预约的课程（团课排课的课程）
     * @create 2017/12/07
     * @return boolean
     */
    public function firstLimit($topCourseId,$courseId){
        //按照第一种条件查询
        $limitNumData = $this->twoWaySearch($topCourseId,1);
        if(empty($limitNumData)){   // 不是按照第一种方式限制
            return true;
        }
        if(!empty($limitNumData)&&($limitNumData["number"]==-1)){
             return true;          //第一种方式课程节数 没有限制
        }
        /**预约课程的节数限制**/
        $limitNum     = $limitNumData["number"];
        /**查询预约课程的指定日期的 当天预约节数**/
        $this->getGroupId($courseId['course_id']);         //获取group预约过的id    $this->groupId;
        // 查询预约课程的 所有底级课程
        $model       = new \backend\models\Course();
        $bottomData  = $model->getBottomData($topCourseId);
        $canCourseId = array_column($bottomData,"id");                     //获取该课程的所有团课排课id
        $groupArr = $this->getTheBindClass($canCourseId);
        $classId  = array_column($groupArr,"id");                            // 获取今天规定课种的所有排课id
        $num    = $this->theClassNumLimite($classId);
        // 查询 该产品的限制次数
        if(($num>=$limitNum)&&$limitNum!=-1){
            return "classOver";
        }
        return true;
    }
    /**
     * 云运动 - Api - 按照第二种条件限制
     * @author 侯凯新 <houkaixin@itsports.club>
     * @param $topCourseId   // 顶级课种id
     * @param $courseId     // 预约的课程（团课排课的课程）
     * @create 2017/12/07
     * @return boolean
     */
    public function secondLimit($topCourseId,$courseId){
            // 按照第二种方式限制
           $limitNumData      = BindPack::find()->where(["and",
                                                     ["card_category_id"=>$this->cardCategoryId],
                                                     ["polymorphic_type"=>"class"],
                                                     ["status"=>1]])
                                             ->andWhere(["like","polymorphic_ids",'"'.$topCourseId.'"'])
                                             ->asArray()
                                             ->one();
           $limitNum = empty($limitNumData["number"])?-1:$limitNumData["number"];
           if(empty($limitNumData)){
             return true;
           }
           if(!empty($limitNumData)&&($limitNumData["number"]==-1)){
             return true;
           }
            /**获取对应的所有顶级课程**/
            $allTopCourseIds = $limitNumData["polymorphic_ids"];
            $allTopCourseIds = empty($limitNumData["polymorphic_ids"])?[]:json_decode($allTopCourseIds);
            $this->getBottomCourseId($allTopCourseIds);
            /**查询预约课程的指定日期的 当天预约节数**/
            $this->getGroupId($courseId['course_id']);   // 不注释暂时不知道干嘛的
            // 查询顶级课程的所有底级课程id
//            $bottomData  = $this->getBottomDataS($allTopCourseIds);
//            $canCourseId = array_column($bottomData,"id");
            $canCourseId = $this->getBottomCourseId($allTopCourseIds);
            //获取所有对应的 团课排课程id
            $groupArr = $this->getTheBindClass($canCourseId);
            $classId  = array_column($groupArr,"id");
            // 获取已经预约课程节数
            $num    = $this->theClassNumLimite($classId);
            // 查询 该产品的限制次数
            if(($num>=$limitNum)&&$limitNum!=-1){
                return "classOver";
            }
           return true;
    }
    /**
     * 云运动 - Api - 查询 指定顶级课程 所有低级课程
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/12/07
     * @param $topCourseIdS  // array   数组顶级类
     * @return boolean
     */
    public function getBottomCourseId($topCourseIdS){
        // 初始化 数据
        $idS  = [];
        $pidS = [];
        $data = $this->gainAllCourseId($topCourseIdS);
        foreach($data as $keys=>$values){
            $idS[]  = $values["id"];
            $pidS[] = $values["pid"];
        }
        $pidS = array_unique($pidS);
        $idS  = array_unique($idS);
        $result = array_diff($idS,$pidS);
        return $result;
    }

    /**
     * 云运动 - Api - 获取指定顶级课种 以下的所有课种
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/12/07
     * @param $topCourseIdS  // array   数组顶级类
     * @return boolean
     */
    public function gainAllCourseId($topCourseIdS){
        //获取所有指定顶级分类信息
        $allCourse  = Course::find()->where(["class_type"=>2]);
        $where      = $this->getJudgeWhere($topCourseIdS);
        $data       = $allCourse
                       ->select("id,pid")
                       ->andWhere($where)
                       ->asArray()->all();
        return $data;
    }

    /**
     * 云运动 - Api - 获取判断条件
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/12/07
     * @param $topCourseIdS    // 顶级课种id（数组）
     * @return boolean
     */
    public function getJudgeWhere($topCourseIdS){
        $where      = [];
        if(count($topCourseIdS)>1){
            $where[0]   = "or";
            foreach ($topCourseIdS as $keys=>$topCourseId){
                $path    = "0,".$topCourseId;
                $where[] = ["like","path",$path];
            }
        }else{
            $path  = "0,".$topCourseIdS[0];
            $where = ["like","path",$path];
        }
        return $where;
    }

    /**
     * 云运动 - Api - 两种查询方式
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/12/07
     * @param $topCourseId  // 顶级课种id
     * @param $way          // 查询方式
     * @return boolean
     */
    public function twoWaySearch($topCourseId,$way){
        $limitNumData      = BindPack::find()->where(["and",
            ["card_category_id"=>$this->cardCategoryId],
            ["polymorphic_type"=>"class"],
            ["status"=>1]]);
        if($way==2){
            $limitNumData->andWhere(["like","polymorphic_ids",'"'.$topCourseId.'"']);
        }else{
            $limitNumData->andWhere(["polymorphic_id"=>$topCourseId]);
        }
        $limitNumData = $limitNumData->asArray()->one();
        return $limitNumData;
    }




    /**
     * 云运动 - Api - 所有的顶级课程id
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/12/07
     * @return boolean   true是被预约  false 是未被预约
     */
    public function checkIsNotMB(){
          // 检测是否是迈步
         $mb = \backend\models\Organization::find()
                    ->alias("venue")
                    ->joinWith(["organization organization"],false)
                    ->where(["venue.id"=>$this->venueId])
                    ->andWhere(["organization.name"=>"迈步运动健身"])
                    ->count();
         if($mb>0){
             return true;
         }
        return false;
    }
    /**
     * 云运动 - Api - 所有的顶级课程id
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/12/07
     * @param $allTopCourseIds  // 所有的顶级课程id   数组类型
     * @return boolean   true是被预约  false 是未被预约
     */
    public function getBottomDataS($allTopCourseIds){
          $arr          = [];
          // 获取包括顶级课程在内的 所有课程
          $model       = new \backend\models\Course();
          foreach ($allTopCourseIds as $keys=>$topCourseId){
              $bottomData  = $model->getBottomData($topCourseId);
              if(!empty($bottomData)){
                  $arr = array_merge($arr,$bottomData);
              }
          }
          return $arr;
    }

//    /**
//     * 云运动 - Api -  获取包括顶级课程在内的所有课程
//     * @author 侯凯新 <houkaixin@itsports.club>
//     * @create 2017/12/07
//     * @param $allTopCourseIds  // 所有的顶级课程id   数组类型
//     * @return boolean   true是被预约  false 是未被预约
//     */
//    public function gainAllCourse($allTopCourseIds){
//        $allCourseData = Course::find()
//                             ->where(["class_type"=>2]);
//        foreach($allTopCourseIds as $keys=>$courseTypeId){
//            $path = "0,".$courseTypeId;
//            $allCourseData = $allCourseData->orWhere(["like","path",$path]);
//        }
//        $data = $allCourseData->asArray()->all();
//        return $data;
//    }









    /**
     * 云运动 - Api - 判断该座位是否被预约
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/8/22
     * @return boolean   true是被预约  false 是未被预约
     */
    public function isOrderTheSeat(){
         $count = AboutClass::find()->where(["and",["class_id"=>$this->classId],["seat_id"=>$this->seatId],["status"=>1]])->count();
         if($count==0){
             return true;
         }else{
             return false;
         }
    }
    /**
     * 云运动 - Api - 预约有交叉时间段的课程
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/8/15
     * @param  $classId    array   // 团课排课id
     * @return int     // 所上的每天课程节数限制
     */
    public function theClassNumLimite($classId){
            $num = AboutClass::find()                   // 每天课程节数限制                                          // 计算该会员的 对应的卡 已经预约的数量
                ->where(['member_id' => $this->memberId])
                ->andWhere(["in",'class_id',$classId])
                ->andWhere(['class_date' => $this->classDate])
                ->andWhere(["member_card_id"=>$this->memberCardId])
                ->andWhere(['status' => 1])
                ->count();
            return $num;
    }
    /**
     * 云运动 - Api - 公共获取绑定课程
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/8/15
     * @return int     // 所上的每天课程节数限制
     */
    public function getGroupBind()
    {
        $card = MemberCard::findOne(['id' => $this->memberCardId]);
        $bind = BindPack::find()
            ->where(["card_category_id"=>$card['card_category_id'],'status' => 1])
            ->andWhere(['IS NOT','polymorphic_ids',null])
            ->select('polymorphic_ids,number')
            ->asArray()
            ->all();
        return $bind;
    }
    /**
     * 云运动 - Api - 预约有交叉时间段的课程
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/8/15
     * @return int     // 所上的每天课程节数限制
     */
    public function theClassNumLimitArr()
    {
        $bind = $this->getGroupBind();
        // 查询指定课程的低级课程信息
        $number = 0;
        if(!empty($bind)){
            $group = new \backend\models\GroupClass();
            $courseId = $group->getCourseTop($this->courseId);
            $idArrOne = [];
            foreach ($bind as $k=>$v){
                $idArr = json_decode($v['polymorphic_ids']);
                if(in_array($courseId,$idArr)){
                    $idArrOne = $idArr;
                    break;
                }
            }
            if(!empty($idArrOne)){
                foreach ($idArrOne as $k=>$v){
                    $model         = new \backend\models\Course();
                    $data          = $model->getBottomData($v);
                    $v             = array_column($data,'id');
                    $num = \backend\models\AboutClass::find()                   // 每天课程节数限制                                          // 计算该会员的 对应的卡 已经预约的数量
                    ->alias('ac')
                    ->joinWith(['groupClass gc'])
                    ->where(['ac.member_id' => $this->memberId])
                    ->andWhere(['ac.member_card_id' => $this->memberCardId])
                    ->andWhere(['between','ac.start',strtotime(date('Y-m-d',$this->start).' 00:00:00'),strtotime(date('Y-m-d',$this->start).' 23:59:59')])
                    ->andWhere(['gc.course_id'=>$v])
                    ->andWhere(['ac.status' => 1,'ac.type'=>2])->count();
                    $number = $number + $num;
                }
            }
        }
        \Yii::trace($number,'fff');
        return $number;
    }
    /**
     * 云运动 - Api - 预约有交叉时间段的课程
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/8/15
     * @param  $canCourseId   array   // 课程id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getTheBindClass($canCourseId){
          $groupArr = GroupClass::find()
                    ->where(["in",'course_id',$canCourseId])
                        ->andWhere(['class_date'=>$this->classDate])
                        ->select('id')
                        ->asArray()
                        ->all();
          return $groupArr;
    }
    /**
     * 云运动 - Api - 预约有交叉时间段的课程
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/8/15
     * @param  $group  // 课程参数
     * @return array|\yii\db\ActiveRecord[]
     */
    public function aboutRepeatCourse($group){
        $data = AboutClass::find()
            ->where(["and",["member_id"=>$this->memberId],["class_date"=>$this->classDate],["!=","status",2]])
            ->andWhere(["or",["between","start",$group["start"],$group["end"]],
                ["between","end",$group["start"],$group["end"],
                ],["and",["<","start",$group["start"]],[">","end",$group["end"]]]])
            ->asArray()->count();
        return $data;
    }
    /**
     * 云运动 - Api - 预约一模一样的课
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2017/8/15
     * @param  $group  // 课程参数
     * @return array|\yii\db\ActiveRecord[]
     */
    public function aboutTheSameClass($group){
        $theSameGroupClass = AboutClass::find()
            ->where(["and",["class_id"=>$this->classId],["member_id"=>$this->memberId],["class_date"=>$this->classDate],["!=","status",2]])
            ->andWhere(["and",["start"=>$group["start"]],["end"=>$group["end"]]])
            ->asArray()->count();
        return $theSameGroupClass;
    }
    /**
     * 云运动 - Api - 获取顶级课种id
     * @author 侯凯新 <houkaixin@itsports.club>
     * @param  $topCourseId      // 顶级课程id
     * @create 2017/8/15
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCourseName($topCourseId){
          $data = \backend\models\Course::find()->where(["id"=>$topCourseId])->select("name")->asArray()->one();
          return $data["name"];
    }
    /**
     * 云运动 - Api - 获取顶级课种id
     * @author 侯凯新 <houkaixin@itsports.club>
     * @param  $group  // 请求课程参数
     * @create 2017/8/15
     */
    public function getTopCourseId($group){
        if($group["course"]["pid"]!=0){
            $arrPath           = explode(",",json_decode($group["course"]["path"]));
            $topCourseId       = $arrPath[1];
        }else{
            $topCourseId       = $group["course"]["id"];
        }
        return $topCourseId;
    }
    /**
     * 云运动 - Api - 查询会员最新约课状态
     * @author 侯凯新 <lihuien@itsports.club>
     * @create 2017/7/21
     */
    public function checkClassStatus(){
         if(time()>=$this->start&&time()<=$this->end){
              $this->isClass = 1;
         }elseif(time()<$this->start&&time()<$this->end){
             $this->isClass =  2;
         }elseif (time()>$this->start&&time()>$this->end){
             $this->isClass =  3;
         }
        // 同时激活会员卡
        if(isset($this->memberCardId)){
             $memberCard = \common\models\base\MemberCard::findOne($this->memberCardId);
             if(!empty($memberCard)&&$memberCard->status==4){
                 return '对不起，您的会员卡还没有激活，请您去前台激活';
                 $time  = $memberCard['duration'];
                 $theData    = MemberAboutClass::editMemberCardData($memberCard, $time);
                 if (isset($theData["activeTime"])) {
                     $memberCard->active_time = $theData["activeTime"];
                 }
                 if (isset($theData["invalidTime"])) {
                     $memberCard->invalid_time = $theData["invalidTime"];
                 }
                 $memberCard->status      = 1;
                 if(!$memberCard->save()){
                     return $memberCard->errors;
                 }
             }
        }
        return true;
    }
    /**
     * 云运动 - Api - 开课欠多少分钟不能预约课程
     * @author 侯凯新 <lihuien@itsports.club>
     * @create 2017/7/21
     */
    public function beforeClassLimit(){
        if(!empty($this->venueId)){
            $beforeMinute = \common\models\base\Config::find()->where(["and",["key"=>"before_class"],["venue_id"=>$this->venueId]])->select("value")->asArray()->one();
        }else{
            return 0;
        }
        return $beforeMinute["value"];
    }



    /**
     * 云运动 - Api - 处理预约限制
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/6/24
     * @param $courseId   //课种id
     */
    public function getGroupId($courseId)
    {
        $data = GroupClass::find()->where(['class_date'=>$this->classDate])->andWhere(['course_id'=>$courseId])->asArray()->one();
        $this->groupId = $data['id'];
    }

   public function searchMemberCard(){
               $data = MemberCard::find()->where(['member_id'=>$this->memberId])->asArray()->one();
               $this->memberCardId = isset($data['id'])?$data['id']:null;
               $this->cardCategoryId = isset($data['card_category_id'])?$data['card_category_id']:null;
   }
    /**
     * 云运动 - Api - 处理团课预约时间
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getGroupClassId()
    {
        $group = GroupClass::find()->where(['id'=>$this->classId])->asArray()->one();
        $this->coachId   = $group['coach_id'];
        $this->classDate = $group['class_date'];
        $this->start     = $group['start'];
        $this->end       = $group['end'];
        $this->courseId = $group['course_id'];
        $this->potVenueId = $group['venue_id'];
    }
    /**
     * 云运动 - Api - 处理私课预约时间
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @param  $time
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getClassDate($time)
    {
        if(empty($this->start) || empty($this->end)){
            $this->classDate = date('Y-m-d',strtotime($time));
            $this->start     = strtotime($time);
            $length  = MemberCourseOrderDetails::find()->select('class_length')->where(['id'=> $this->orderDetailsId])->one();
            $str     = $length['class_length'] * 60;
            $this->end  = strtotime($time)+$str;
        }else{
            $start           = $this->classDate.' '.$this->start;
            $this->start     = strtotime($start);
            $end             = $this->classDate.' '.$this->end;
            $this->end       = strtotime($end);
        }
    }
    /**
     * 云运动 - Api - 获取会员单条数据
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMemberId()
    {
        if(empty($this->memberCardId)){
            $data = MemberCard::find()->where(['member_id'=>$this->memberId])
                   ->andWhere([">","invalid_time",time()])
                   ->asArray()->one();
            $this->memberCardId = isset($data['id'])?$data['id']:null;
            $this->cardCategoryId = isset($data['card_category_id'])?$data['card_category_id']:null;
        }else{
            $data =  MemberCard::find()->where(['id'=>$this->memberCardId])->asArray()->one();
            $this->cardCategoryId = isset($data['card_category_id'])?$data['card_category_id']:null;
        }
    }

    /**
     * 云运动 - Api - 获取会员单条数据
     * @author Huangpengju <Huangpengju@itsports.club>
     * @create 2017/5/12
     * @param $companyId   // 公司id
     * @param $venueId;    // 场馆id
     * @return array
     */
    public function sendMessage($companyId=null,$venueId=null)
    {
        $model               = new \backend\models\Member();
        $data                = $model->getMemDetailData($this->memberId);          //获取用户信息
        if(!isset($data['mobile']) && empty($data['mobile'])){
            return ['status' => 'error','data' => '该会员没有手机号'];
        }
        $info               = '恭喜您预约成功';
        $companyId          = Organization::findOne(['id'=>$companyId]);
        $venueId            = Organization::findOne(['id'=>$venueId]);
        if($this->classType=='charge'){
            $charge = MemberCourseOrderDetails::findOne(['id'=>$this->classId]);
            $course = $charge['product_name'];
        }else{
            $group  = Course::findOne(['id'=>$this->courseId]);
            $course = $group['name'];
        }
        Func::sendInfo($data['mobile'],$companyId['name'],$venueId['name'],$course,$this->classDate,date("H:i",intval($this->start)),date("H:i",intval($this->end)));
        return ['status'=>'success','data'=>$info];
    }
    /**
     * 云运动 - Api - 获取团课预约设置
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/5/12
     * @param $type
     * @param $key
     * @return array
     */
    public function getLeagueConfig($type,$key)
    {
        return  Config::find()
            ->where(['type'=>$type])
            ->andWhere(['key'=>$key])
            ->asArray()->one();
    }
    /**
     * 云运动 - Api - 获取团课预约设置
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/5/12
     * @return array
     */
    public function getLeagueAboutSet()
    {
        $config = $this->getLeagueConfig('league','before_class');
        if(!empty($config)){
            $value = $config['value'];
            if($value != 0){
                $length = intval($this->start) - intval(time());
                if($length <= (intval($value)*60)){
                    return '已经快要开始上课，禁止预约';
                }
            }
        }
        return true;
    }

    /**
     * @云运动 - 会员约课 - 判断会员是不是潜在会员
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/27
     * @param $memberId     //会员id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberType($memberId)
    {
        return  Member::find()->where(['id'=>$memberId])->andWhere(['member_type'=>1])->asArray()->one();
    }

    /**
     * @云运动 - 会员约课 - 判断潜在会员 有没有约过课
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/27
     * @param $memberId     //会员id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberAbout($memberId)
    {
        return AboutClass::find()->where(['member_id'=>$memberId])->andWhere(['status'=>1])->asArray()->one();
    }

    /**
     * @云运动 - 会员约课 - 上课记录生成  (原私课预约调用，现在没有调用)
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/1
     * @return array|bool
     */
    public function setClassRecordData()
    {
        $model                = new ClassRecord();
        $model->multiple_type = 'charge';                   //课程类型
        $model->multiple_id   = $this->orderDetailsId;      //订单详情id
        $model->member_id     = $this->memberId;            //会员id
        $model->status        = 4;                          //课程待审核状态
        $model->created_at    = time();                     //创建时间
        $model->coach_id      = $this->coachId;             //教练id
        $model->start         = $this->start;               //开始时间
        $model->end           = $this->end;                 //课程结束时间
        if(!$model->save())
        {
            return $model->errors;
        }else{
            return true;
        }
    }

    /**
     * @云运动 - 会员约课 - 订单详情信息查询
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/1
     * @param $memberId   //会员id
     * @param $coachId   //教练id
     * @param $classId   //课程id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberOrderInfo($memberId,$coachId,$classId)
    {
        $model = new MemberCourseOrder();
        return $model->getMemberOrderInfo($memberId,$coachId,$classId);
    }

    /**
     * @云运动 - 员工约课 - 生成会员
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/1
     * @return array
     * @throws \yii\base\Exception
     */
    public function setEmployeeMemberInfo()
    {
        $transaction                            = \Yii::$app->db->beginTransaction();
        try {
            $data                               = $this->getEmployeeInfo($this->memberId);      //获取员工信息

            $password                           = '123456';     //会员临时密码
            $password                           = \Yii::$app->security->generatePasswordHash($password);
            $member                             = new Member();
            $member->username                   = $data['name'];                                 //用户名（用于登录，暂存手机号）
            $member->password                   = $password;
            $member->mobile                     = $data['mobile'] ? $data['mobile'] : '0';                               //手机号
            $member->register_time              = time();                                        //注册时间
            $member->counselor_id               = $data['id'];                                   //会籍顾问id
            $member->status                     = 1;                                             //状态：正常
            $member->is_employee                = 1;                                             //员工标识
            $memberSave                         = $member->save() ? $member : $member->errors;
            if (!$memberSave->id) {
                return $member->errors;
            }
            $memberDetails                      = new MemberDetails();
            $memberDetails->member_id           = $memberSave->id;                                   //会员id
            $memberDetails->name                = $data['name'];                                     //姓名
            $memberDetails->sex                 = $data['sex'];                                      //性别
            $memberDetails->birth_date          = $data['birth_time'];                               //生日
            $memberDetails->email               = $data['email'];                                    //邮箱
            $memberDetails->created_at          = time();                                            //添加时间
            $memberDetails->recommend_member_id = $data['id'];                                       //员工id
            if (!$memberDetails->save()) {
                return $memberDetails->errors;
            }
            if($transaction->commit())                                                               //事务提交
            {
                return false;
            }else{
                $this->memberId = $memberSave->id;                                          //获取会员id
                return true;
            }
        }catch (\Exception  $e) {
            $transaction->rollBack();                                                               //事务回滚
            return $e->getMessage();                                                               //捕捉错误，返回
        }
    }

    /**
     * @云运动 - 员工约课 - 生成会员（查询会员信息）
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/1
     * @param $employeeId       //员工id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getEmployeeInfo($employeeId)
    {
        return Employee::find()->where(['id'=>$employeeId])->asArray()->one();
    }

    /**
     * @云运动 - 员工约课 - 查询员工以前是否约过课程
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/1
     * @param $employeeId       //员工id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getEmployeeAbout($employeeId)
    {
        return AboutClass::find()->where(['employee_id'=>$employeeId])->asArray()->one();
    }

    /**
     * @云运动 - 员工约课 - 查询员工以前是否生成过会员信息
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/2
     * @param $employeeId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getEmployeeMemberInfo($employeeId)
    {
        return Member::find()->where(['counselor_id'=>$employeeId])->andWhere(['is_employee'=>1])->asArray()->one();
    }
    /**
     * @云运动 - ios会员私教约课 - 会员预约成功之后返回信息
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/7/20
     * @param $venueId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function sendPrivateMessage($venueId){
           $model = Organization::findOne(["id"=>$venueId]);
           $companyId = $model->pid;
           $this->sendMessage($companyId,$venueId);
    }
    /**
     * 会员端 - API- 进馆时间限制公共方法
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/07/04
     */
    public function getCardTime($memberCardId,$venueId)
    {
        $applyTime = VenueLimitTimes::find()
            ->where(['member_card_id' => $memberCardId])
            ->andWhere(['or',['venue_id' => $venueId],['like','venue_ids','"'.$venueId.'"']])
            ->andWhere(['or', ['IS NOT','apply_start',NULL],['IS NOT','apply_end',NULL]])
            ->asArray()
            ->one();
        if(isset($applyTime) && $applyTime['apply_start'] !=  $applyTime['apply_end'] ){
            $time  = date('H:i',time());
            $start = date('H:i',$applyTime['apply_start']);
            $end   = date('H:i',$applyTime['apply_end']);
            if($end == '00:00')$end = '24:00' ;
            if(!empty($applyTime['apply_start']) && !empty($applyTime['apply_end'])){
                if($time<$start || $time>$end){
                    return false;
                }
            }
            if(empty($applyTime['apply_start']) && !empty($applyTime['apply_end'])){
                if($time>$end){
                    return false;
                }
            }
            if(!empty($applyTime['apply_start']) && empty($applyTime['apply_end'])){
                if($time<$start){
                    return false;
                }
            }
        }
        $day  = date('d', time());
        $week = date('w', time());
        $week = $week == 0 ? 7 : $week;
        $cardTime = MemberCardTime::find()->where(['member_card_id' => $memberCardId])->select('day,week')->asArray()->one();
        if (!empty($cardTime['day'])) {
            $dayArr = json_decode($cardTime['day'], true);
            if (isset($dayArr['day']) && !empty($dayArr['day'])) {
                if (in_array($day, $dayArr['day'])) {
                    if (!empty($dayArr['start'])) {
                        $start = strtotime(date('Y-m-d') . ' ' . $dayArr['start']);
                        if (time() < $start) {
                            return false;
                        }
                    }
                    if (!empty($dayArr['end'])) {
                        $end = strtotime(date('Y-m-d') . ' ' . $dayArr['end']);
                        if (time() > $end) {
                            return false;
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            }
        }
        if (!empty($cardTime['week'])) {
            $weekArr = json_decode($cardTime['week'], true);
            if (isset($weekArr['weeks']) && !empty($weekArr['weeks'])) {
                if (in_array($week, $weekArr['weeks'])) {
                    $flipArr = array_flip(array_unique($weekArr['weeks']));
                    $start = $weekArr['startTime'][$flipArr[$week]];
                    if (!empty($start)) {
                        $start = strtotime(date('Y-m-d') . ' ' . $start);
                        if (time() < $start) {
                            return false;
                        }
                    }
                    $end = $weekArr['endTime'][$flipArr[$week]];
                    if (!empty($end)) {
                        $end = strtotime(date('Y-m-d') . ' ' . $end);
                        if (time() > $end) {
                            return false;
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        }
    }
    /**
     * 会员端 - API- 根据时间卡进场次数统计
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/07/04
     */
    public function getEntryRecord($memberCardId,$memberId,$venueId)
    {
        if($this->limitType == 'week'){
            $monthStart = strtotime(Func::getGroupClassDate('w',true));
            $monthEnd   = strtotime(Func::getGroupClassDate('w',false));
        }else{
            $monthStart = mktime(0, 0, 0, date('m'), 1, date('Y'));                         //获取当月的开始时间戳
            $monthEnd = mktime(23, 59, 59, date('m'), date('t'), date('Y'));              //获取当月的最后时间戳
        }
        $venue_id = VenueLimitTimes::find()->where(['member_card_id' => $memberCardId])->andWhere(['IS NOT','venue_ids',null])->asArray()->all();
        if(!empty($venue_id)){
            foreach ($venue_id as $k=>$v){
                $idArr = json_decode($v['venue_ids']);
                if(in_array($venueId,$idArr)){
                    $this->venueIds = $idArr;
                    break;
                }
            }
        }else{
            $this->venueIds = $venueId;
        }
        return EntryRecord::find()
            ->where(['member_card_id' => $memberCardId])
            ->andWhere(['venue_id' => $this->venueIds])
            ->andWhere(['member_id' => $memberId])
            ->andWhere(['>=', 'entry_time', $monthStart])
            ->andWhere(['<=', 'entry_time', $monthEnd])
            ->count();
    }
    /**
     * 会员端 - API - 查询进场限制总次数
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/07/04
     */
    public function getVenueLimitTimes($memberCardId,$venueId)
    {
        $venue =  VenueLimitTimes::find()->where(['member_card_id' => $memberCardId])->andWhere(['venue_id' => $venueId])->andWhere(['or',['IS NOT','total_times',null],['IS NOT','week_times',null]])->select('total_times,week_times')->asArray()->one();
        if(empty($venue)){
            $venue_id = VenueLimitTimes::find()->where(['member_card_id' => $memberCardId])->andWhere(['IS NOT','venue_ids',null])->andWhere(['or',['IS NOT','total_times',null],['IS NOT','week_times',null]])->asArray()->all();
            if(!empty($venue_id)){
                foreach ($venue_id as $k=>$v){
                    $idArr = json_decode($v['venue_ids']);
                    if(in_array($venueId,$idArr)){
                        $venue = $v;
                        break;
                    }
                }
            }
        }
        if(!empty($venue['total_times'])){
            $this->limitType = 'month';
            return $venue['total_times'];
        }
        $this->limitType = 'week';
        return $venue['week_times'];
    }
}