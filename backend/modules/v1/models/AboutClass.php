<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/5/2
 * Time: 17:17
 */

namespace backend\modules\v1\models;

use backend\models\ClassRoom;
use backend\models\Course;
use backend\models\CoursePackageDetail;
use backend\models\GroupClass;
use backend\models\MemberCourseOrderDetails;
use backend\models\Seat;
use backend\models\SeatType;
use common\models\Config;
use common\models\MemberDetails;

class AboutClass extends \common\models\base\AboutClass
{
    public $classId; //课程ID
    public $coachName; //教练id
    public $orderCourseName;  // 订单课程名称
    const  LEVEL = [1=>'初级',2=>'中级',3=>'高级'];
    const GROUP_PIC    = 'http://oo0oj2qmr.bkt.clouddn.com/20160803094909_6316.jpg?e=1498813628&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:Upr08u-YYiwjVZWPQQlW9sN-CbQ=';
    const CHARGE_PIC   = 'http://oo0oj2qmr.bkt.clouddn.com/128432235.jpg?e=1498813979&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:bEAj4Ds31eV7hOHvPU6idkJnYKU=';
    const  COACH_PIC   = 'http://oo0oj2qmr.bkt.clouddn.com/2x.png?e=1498893108&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:fG0NvcZgGoWgOdCxiMM4stcDX_U=';

    /**
     * 云运动 - api接口 - 修改预约状态
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @update huangpengju ,修改了判断是否快上课
     * @update 2017/6/12
     * @param $id
     * @param  $type
     * @param $venueId;
     * @return array|\yii\db\ActiveRecord[]
     */
    public function updateStatus($id,$venueId,$type = "group")
    {
        $about = \common\models\base\AboutClass::findOne(['id'=>$id]);           //查询约课信息
        if(!empty($about)){
            if($type = "group"){
                $config = $this->getLeagueAboutSet($about->start,$venueId);                   //判断是否快上课
                if($config !== true){
                    return $config;
                }
                if ($about->start < time()) {
                    return '对不起，此课程已开始您不可以取消';
                }
            }
            $about->status       = 2;
            $about->cancel_time = time();
            if($about->save()){
                return true;
            }else{
                return $about->errors;
            }
        }
        return '没有此预约数据';
    }
    /**
     * 云运动 - Api - 获取团课预约设置
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/5/12
     * @param $type
     * @param $key
     * @return array
     */
    public function getLeagueConfig($type,$key,$venueId=null)
    {
        return  Config::find()
            ->where(['type'=>$type])
            ->andWhere(['key'=>$key])
            ->andWhere(['venue_id'=>$venueId])
            ->asArray()->one();
    }
    /**
     * 云运动 - Api - 获取团课预约设置
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/5/12
     * @update huang peng ju ,修改了传过来的值
     * @update 2017/6/12
     * @param  $start       //上课时间
     * @param  $venueId
     * @return array
     */
    public function getLeagueAboutSet($start,$venueId)
    {
        $config = $this->getLeagueConfig('league','cancel_time',$venueId);
        if(!empty($config) && $start){
            $value = $config['value'];
            if($value != 0){
                $length = intval($start) - intval(time());
                if($length <= (intval($value)*60)){
                    return "开课前".$value."分钟不能取消预约";
                }
            }
        }
        return true;
    }
    /**
     * 云运动 - Api - 获取团课预约设置(判断是否快上课)
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/7/5
     * @param  $start       //上课时间
     * @return array
     */
    public function getLeagueAbout($start)
    {
        $config = $this->getLeagueConfig('league','before_class');
        if(!empty($config) && $start){
            $value = $config['value'];
            if($value != 0){
                $length = intval($start) - intval(time());
                if($length <= (intval($value)*60)){
                    return false;
                }
            }
        }
        return true;
    }
    /**
     * 云运动 - api接口 - 查询私教课程套餐详情
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param  $id
     * @param  $venueId    // 场馆id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAboutOne($id,$venueId)
    {
        $data = \backend\models\AboutClass::find()
            ->alias("aboutClass")
            ->where(['aboutClass.id'=>$id])
            ->joinWith(["groupClass groupClass"=>function($query){
                $query->joinWith(["classroom classroom"],false);
            }])
            ->joinWith("memberCourseOrderDetails memberCourseOrderDetails")
            ->select('aboutClass.id,aboutClass.member_card_id,aboutClass.class_id,aboutClass.status,aboutClass.create_at,aboutClass.coach_id,aboutClass.class_date,aboutClass.start,
                   aboutClass.end,aboutClass.cancel_time,aboutClass.member_id,classroom.name as classroomName')
            ->asArray()
            ->one();
        // 获取会员订单id( 暂时更改为这个样子)
        $data["memberCourseOrderDetails"]["course_order_id"] = !empty($data["memberCourseOrderDetails"]["course_order_id"])?$data["memberCourseOrderDetails"]["course_order_id"]:"";
        $data['iosMemberCourseOrderDetails'] = MemberCourseOrderDetails::find()->where(["course_order_id"=>$data["memberCourseOrderDetails"]["course_order_id"]])->asArray()->all();
        // 返回所有预约课程(根据约课信息返回)
        if(isset($data) && $data){
            $time = time();
            if($data["status"]!=2&&$data['start'] > $time && $data['end'] > $time)
            {
                $data['unusedFlag']  = true;            //待使用
                $data["isClass"]     = 2;                // 待使用
            }else if($data["status"]!=2&&$data['start'] < $time && $data['end'] < $time){
                $data['unusedFlag']  = false;            //已使用
                $data["isClass"]     = 3;                 // 已使用
            }else if($data["status"]!=2&&$data['start']>= $time&&$data['end']<=$time){
                $data['unusedFlag'] = "classing";      // 正在上课
                $data["isClass"]     = 1;                // 上课中
            }elseif($data["status"] ==2){
                $data['unusedFlag'] = 3;                // 取消上课
            }
            $start   = $data['start'];
            $group   = new \backend\modules\v1\models\GroupClass();
            $coach                    = $group->getFieldsByOne($data,'coach');                                        //获取教练信息
            $this->coachName          = $coach['name']; //教练姓名
            $this->classId            = $data['class_id'];                                                            //私课订单id
            $order = $this->getMemberOrder();
            $data['orderId']            = $order['orderId'];                              //订单id
            $data['type']               = $order['type'] == 1 ? 'charge':'group';         //类型
            $data['productName']        = $order['product_name'];                         //产品名称
            $data['courseName']         = $order['course_name'];                          //课程名称
            $data['coachName']          = $coach['name'];                                 //教练名称
            $data['classLength']        = $order['class_length'];                         //时长
            if(empty($order['course_num']))
            {
                $data['courseNum']      = 1;                                              //单节
                $data['category']       = $order['category'];                            //类型（单节）
            }else{
                $data['category']       = $order['category'];                            //类型（多节）
                $data['courseNum']      = $order['course_num'];                          //课程套装节数
            }
            $data['courseAmount']       = $order['course_amount'];                        //总数量
            $data['type']                = $order['type'] == 1 ? 'charge':'group';         //类型
            $data['pic']                = empty($order['pic'])?self::CHARGE_PIC:$order['pic'];//图片处理
            $data['originalPrice']      = $order['original_price'];                       //单价
            //     $data['totalPrice']         = $order['money_amount'];                         //总价(场馆要求暂时隐藏)
            $data['totalPrice']        = "暂无数据";
            $data['venueId']            = $order['venueId'];                              //场馆id
            $data['venueName']          = $order['venueName'];                            //场馆名称
            $data['venueAddress']       = empty($order['venueAddress'])?'场馆地址:暂无数据':$order['venueAddress']; //场馆地址
//            $data['start']            = date('m月d日 H:i',$data['start']);
            $data['create_at']        = date('Y-m-d H:i',$data['create_at']);
            $data['cancel_time']      = $data['cancel_time']? (date('Y-m-d H:i',$data['cancel_time'])):NULL;
            $data['chargeNum']        = count($this->getChargeNum($data['class_id'],1,$data['member_card_id'],$id));  //正在上第几节课
            $model = new ChargeClass();
            $score               = $model->getFieldsByOne($v = [],'score');
            $data['score']     = $score['score'];
            $data['scoreImg'] = $score['scoreImg'];
            // 数据整理 统计上了 几节课
            $packageClass = $this->getAboutCombineData($data,$coach,$order);
            $data['packageClass']     = $packageClass;
            $data['courseFlag']       = true;                                     //可以跳转页面标识
            $config = $this->getLeagueAboutSet($start,$venueId);                   //判断是否快上课
            if($config === true)
            {
                $data['limit'] = false;    //不限制取消预约
            }else{
                $data['limit'] = true;     //限制取消预约
            }
            return $data;
        }
    }
    /**
     * 云运动 - api接口 - 查询私教课程套餐详情NEW
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param  $id
     * @param $start
     * @param  $venueId    // 场馆id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getNewAboutOne($id,$start,$coaId,$mcId,$mId,$venueId,$aboutClassId)
    {
        $data = [];
        $arr  = [];
        $mco  = \backend\modules\v1\models\MemberCourseOrderDetails::find()->where(['id'=>$id])->asArray()->one();
        // 获取会员订单id
        $data['iosMemberCourseOrderDetails'] = MemberCourseOrderDetails::find()->where(["course_order_id"=>$mco["course_order_id"]])->asArray()->all();
        // 返回所有预约课程(根据约课信息返回)
        if(isset($data) && $data){
            $data['coach_id'] = $coaId;
            $data['member_card_id'] = $mcId;
            $data['member_id']      = $mId;
            $group   = new \backend\modules\v1\models\GroupClass();
            $coach                      = $group->getFieldsByOne($data,'coach');                                        //获取教练信息
            $this->coachName            = $coach['name']; //教练姓名
            $this->classId              = $id;
            $order = $this->getMemberOrder();
            $data['orderId']            = $order['orderId'];                              //订单id
            $arr['type']               = $order['type'] == 1 ? 'charge':'group';         //类型
            $arr['productName']        = $order['product_name'];                         //产品名称
            $arr['courseName']         = $order['course_name'];                          //课程名称
            $arr['classLength']        = $order['class_length'];                         //时长
            if(empty($order['course_num']))
            {
                $arr['courseNum']      = 1;                                              //单节
                $arr['category']       = $order['category'];                            //类型（单节）
            }else{
                $arr['category']       = $order['category'];                            //类型（多节）
                $arr['courseNum']      = $order['course_num'];                          //课程套装节数
            }
            $arr['courseAmount']       = $order['course_amount'];                        //总数量
            $arr['type']                = $order['type'] == 1 ? 'charge':'group';         //类型
            $arr['pic']                = empty($order['pic'])?self::CHARGE_PIC:$order['pic'];//图片处理
            $arr['originalPrice']      = $order['original_price'];                       //单价
            $arr['totalPrice']        = "暂无数据";
            $arr['venue_id']            = $order['venueId'];                              //场馆id
            $arr['venue_name']          = !empty($order['venue_name'])?$order['venue_name']:$order['venueName'];                            //场馆名称
            $arr['venue_address']       = empty($order['venueAddress'])?'场馆地址:暂无数据':$order['venueAddress']; //场馆地址
//            $arr['chargeNum']        = intval(implode("",array_keys(array_column($this->getChargeNum($id,1,$data['member_card_id'],$id),'id'),$aboutClassId))) + 1;  //正在上第几节课
            $arr['chargeNum']        = count($this->getChargeNum($id,1,$data['member_card_id'],$id,$aboutClassId));  //正在上第几节课

            $model = new ChargeClass();
            $score                   = $model->getFieldsByOne($v = [],'score');
            $arr['score']           = $score['score'];
            $arr['scoreImg']        = $score['scoreImg'];
            // 数据整理 统计上了 几节课
            $packageClass = $this->getAboutCombineData($data,$coach,$order);
            $arr['packageClass']     = $packageClass;
            $arr['courseFlag']       = true;                                     //可以跳转页面标识
            $config = $this->getLeagueAboutSet($start,$venueId);                   //判断是否快上课
            if($config === true)
            {
                $arr['limit'] = false;    //不限制取消预约
            }else{
                $arr['limit'] = true;     //限制取消预约
            }
            return $arr;
        }
    }
    /**
     * 云运动 - api接口 - 重新组装数据（统计上了几节课）哪一结上过 哪一节没上过
     * @author houkaixin<houkaixin@sports.club>
     * @param  $data
     * @param  $coach
     * @param  $order
     * @create 2017/8/30
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getAboutCombineData($data,$coach,$order){
        $packageClass = [];
        $orderPrice = "暂无数据";     //场馆要求暂时隐藏数据 本来应该是$order['original_price']
        foreach ($data['iosMemberCourseOrderDetails'] as $k => $v) {
            $model = new ChargeClass();
            $num   = $model->getChargeClassInfo($v,$data["member_id"]);                       //统计上了几节课
            for ($i = 0; $i < $v['course_num']; $i++) {
                if ($num >= $i + 1) {
                    $packageClass[] = ['coachName'=>$coach['name'],'name'=>$v['course_name'],'times' => $i + 1, 'course_length' => $order['class_length'],'sale_price' =>$orderPrice, 'is_member' => '1', 'status' => '1'];  //status = 1 表示上过课
                } else {
                    $packageClass[] = ['name' =>$v['course_name'],'times' => $i + 1,'course_length'=>$order['class_length'],'sale_price' => $orderPrice,'is_member' => '1', 'status' => '2'];                                   //status = 2 表示没上过课
                }
            }
        }
        return $packageClass;
    }
    /**
     * 云运动 - api接口 - 查询私教课程订单
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/6/14
     * @return array|null|\yii\db\ActiveRecord
     */

    public function getMemberOrder()
    {
        $data =  MemberCourseOrderDetails::find()
            ->alias('details')
            ->joinWith(['memberCourseOrder order'=>function($query){
                $query->joinWith(["orderList orderList"=>function($query){
                    $query->joinWith(["organization or"]);
                }])->joinWith(['employeeS  e'=>function($q){
                    $q->joinWith(["organizations orr"]);
                }]);
            }],false)
            ->joinWith(['course course'=>function($query){
//                $query->joinWith(['organizations or']);
            }
            ],false)
            ->where(['details.id'=>$this->classId])
            ->select('
                details.*,order.id as orderId,order.course_amount,order.create_at,order.deadline_time,order.money_amount,order.private_id,
                course.venue_id as courseVenueId,or.id as venueId,or.name as venue_name,or.address as venueAddress,orderList.venue_id,
               orr.name as venueName,
              ')
            ->asArray()
            ->one();
//        if($data["category"]==1){   // 表示多课程  （在不改变原来逻辑的情况下  新增多课程情况）
//            $this->orderCourseName = $this->gainOrderCourseName($data["course_order_id"]);
//        }
        return  $data;
    }
    /**
     * 云运动 - api接口 - 获取订单课程名称
     * @author lihuien<lihuien@sports.club>
     * @param $courseOrderId  // 课程订单id
     * @create 2017/4/24
     * @return array|\yii\db\ActiveRecord[]
     */
    public function gainOrderCourseName($courseOrderId){
        $data =  MemberCourseOrderDetails::find()
            ->alias('details')
            ->where(['details.course_order_id'=>$courseOrderId])
            ->orderBy(["details.id"=>SORT_ASC])
            ->select("course_name")
            ->asArray()
            ->column();
        return $data;
    }
    /**
     * 云运动 - api接口 - 查询私教课程套餐节数
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param  $classId int//课程ID
     * @param  $type    int  课程类型
     * @param  $memberId int 会员卡ID
     * @param  id          //预约ID
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCancelChargeNum($classId,$type,$memberId,$id)
    {
        $num = AboutClass::find()->where(['class_id'=>$classId])
            ->andWhere(['type'=>$type])
            ->andWhere(['member_card_id'=>$memberId])
            ->andWhere(['<=','id',$id])
            ->asArray()->all();
        return $num;
    }
    /**
     * 云运动 - api接口 - 查询私教课程套餐节数
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/7/5
     * @param  $classId int//课程ID
     * @param  $type    int  课程类型
     * @param  $memberId int 会员卡ID
     * @param  id          //预约ID
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChargeNum($classId,$type,$memberId,$id,$aboutClassId)
    {
        $num = AboutClass::find()->where(['class_id'=>$classId])
            ->andWhere(['type'=>$type])
//            ->andWhere(['member_card_id'=>$memberId])
            ->andWhere(['<=','id',$aboutClassId])
//            ->andWhere(['<>','status',[2,]])
//            ->andWhere(['<','id',$id])
            ->andWhere(['not in', 'status', [2,5,6,7]])
            ->asArray()->all();
        return $num;
    }
    /**
     * 云运动 - api接口 - 查询cloud_course_package_detail"课程套餐详情表
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChargeClassOne()
    {
        return CoursePackageDetail::find()
            ->alias('cpd')
            ->joinWith(['course course'=>function($query){
                $query->joinWith(['organizations or']);
            }
            ],false)
//            ->joinWith(['organization or'],false)
            ->select('
            cpd.course_num,
            cpd.course_id,
            cpd.id,
            cpd.charge_class_id,
            cpd.course_length,cpd.sale_price,
            course.name,course.venue_id,
            or.name as venueName,or.address as venueAddress,
            ')
            ->where(['cpd.id'=>$this->classId])
            ->asArray()->one();
        //查询课量，课程id,时长，单售价（课程套餐详情表），课种名(课种表)；
    }
    /**
     * 云运动 - api接口 - 查询课程套餐详情表
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $aboutId int  预约ID
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getGroupClassMemberOne($aboutId,$venueId=null)
    {
        $about              =  \backend\models\AboutClass::find()->alias("aboutClass")->where(['aboutClass.id'=>$aboutId])
            ->joinWith(["seat seat"],false)
            ->select("aboutClass.*,seat.seat_number as seatNum")
            ->asArray()->one();
        if(!empty($about)) {
            $start = $about['start'];                                                            //上课开始时间
            $data = $this->getGroupClassDetail($about['class_id']);                             //获取团课信息
            $time = time();
            if ($about["status"]!=2&&$about['start'] > $time && $about['end'] > $time) {
                $data['unusedFlag'] = true;            //待使用
                $data["isClass"]     = 2;
            } else if ($about["status"]!=2&&$about['start'] < $time && $about['end'] < $time) {
                $data['unusedFlag'] = false;            //已使用
                $data["isClass"]     = 3;
            }elseif($about["status"]!=2&&(time()>$about['start'])&&(time()<$about['end'])){
                $data["isClass"]    = 1;                  // 上课中
            }
            $data['pic'] = empty($data['coursePic']) ? self::GROUP_PIC : $data['coursePic'];                       //团课图片处理
            $data['coachPic'] = empty($data['coachPic']) ? self::COACH_PIC : $data['coachPic'];                       //团课图片处理
            $data['venueAddress'] = empty($data['venueAddress'])?'场馆地址:暂无数据':$data['venueAddress'];
            $data['aboutId'] = $aboutId;
            $data['courseFlag'] = false;
            $level = $data['difficulty'];
            $data['level'] = self::LEVEL[$level];
            $data['create_at'] = date('Y-m-d H:i', $about['create_at']);
            $data['cancel_time'] = $about['cancel_time'] ? date('Y-m-d H:i', $about['cancel_time']) : NULL;
            $data['classLength'] = (($data['end'] - $data['start']) / 60);
            $data["seatNum"]     =  !empty($about["seatNum"])&&isset($about["seatNum"])?$about["seatNum"]:"暂无数据";
            $model = new ChargeClass();
            $score = $model->getFieldsByOne($v = [], 'score');
            $data['score'] = $score['score'];
            $data['scoreImg'] = $score['scoreImg'];
            $config = $this->getLeagueAboutSet($start,$venueId);                   //判断是否快上课
            if ($config === true) {
                $data['limit'] = false;    //不限制取消预约
            } else {
                $data['limit'] = true;     //限制取消预约
            }
            return $data;
        }else{
            return NULL;
        }
    }

    /**
     * 云运动 - api接口 - 查询课程套餐详情表
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/6/12
     * @param $id   //团课课程id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getGroupClassDetail($id)
    {
        $data = GroupClass::find()->alias('gc')
            ->joinWith(['aboutClass aboutClass'],false)
            ->joinWith(['employee employee'],false)
            ->joinWith(['classroom classroom'],false)
            ->joinWith(['course course'],false)
            ->joinWith(['organization or'],false)
            ->select(" 
                     gc.id,
                     gc.coach_id,
                     gc.course_id,
                     gc.classroom_id,
                     gc.start,
                     gc.end,
                     gc.class_date,
                     gc.difficulty,
                     gc.pic,
                     course.pic as coursePic,
                     aboutClass.id as aboutId,
                     aboutClass.class_id,
                     employee.id as coachId,
                     employee.name as coachName,
                     employee.age,
                     employee.pic as coachPic,
                     employee.entry_date,
                     classroom.id as classroomId,
                     classroom.total_seat,
                     classroom.venue_id,
                     classroom.name as classroomName,   
                     course.id as courseId,                  
                     course.name,
                     course.course_desrc,
                     or.name as venueName,
                     or.address as venueAddress,
                     "
            )
            ->where(['gc.id'=>$id])
            ->asArray()->one();
        return $data;
    }

    /**
     * @云运动 - app - 获取会员课程评论信息（私课）
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/13
     * @param $aboutId  //约课id
     * @return mixed
     */
    public function getChargeClassComment($aboutId)
    {
        $classInfo              = $this->getAboutOne($aboutId,$venueId="");
        if(!empty($classInfo)) {
            $data['coachName'] = $this->coachName;                     //教练姓名
            $data['productName'] = $classInfo['productName'];            //产品名称
            $data['chargeNum'] = $classInfo['chargeNum'];              //第几节课
            $data['courseName'] = $classInfo['courseName'];             //课程名称
            $data['classLength'] = $classInfo['classLength'];            //时长
            $data['classPic'] = $classInfo['pic'];                    //产品图片
            $data['totalPrice'] = $classInfo['totalPrice'];             //产品价格
            $data['classCount'] = $classInfo['courseNum'];              //课程节数
            $data['score'] = $classInfo['score'];                   //评分
            $data['scoreImg'] = $classInfo['scoreImg'];                //图片
            $data['venueName'] = $classInfo['venueName'];               //场馆名称
            $data['venueAddress'] = $classInfo['venueAddress'];            //场馆地址
            $data['endTime'] = date('Y-m-d H:i', $classInfo['end']);   //下课时间
            $data['classType'] = 'charge';                              //课程类型
            return $data;
        }else{
            return NULL;
        }
    }

    /**
     * @云运动 - app - 获取会员课程评论信息(团课)
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/13
     * @param $aboutId  //约课id
     * @return mixed
     */
    public function getGroupClassComment($aboutId)
    {
        $classInfo = $this->getGroupClassMemberOne($aboutId);
        if(!empty($classInfo)) {
            $data['coachName'] = $classInfo['coachName'];               //教练姓名
            $data['courseName'] = $classInfo['name'];                    //产品名称
            $data['classPic'] = $classInfo['pic'];                     //产品图片
            $data['score'] = $classInfo['score'];                   //评分
            $data['scoreImg'] = $classInfo['scoreImg'];                //图片
            $data['venueName'] = $classInfo['venueName'];               //场馆名称
            $data['venueAddress'] = $classInfo['venueAddress'];            //场馆地址
            $data['endTime'] = date('Y-m-d H:i', $classInfo['end']);   //下课时间
            $data['classType'] = 'group';                               //课程类型
            return $data;
        }else{
            return NULL;
        }
    }

    /**
     * 团课详情页获取已经预约该课程的用户信息
     * @param id 团课id
     *
     */
    public function getClassUserInfo($groupOne){
        $course_id = $groupOne['course_id'];
        $groupids = \backend\modules\v1\models\GroupClass::find()->where(['course_id'=>$course_id])->select('id')->asArray()->all();
        $allcount = 0;
        if (!empty($groupids)){
            $groupidss = array_column($groupids,'id');
            $allcount = AboutClass::find()->where(['class_id'=>$groupidss,'type'=>2])->andWhere(['status'=>[3,4]])->count();
        }
        $result = AboutClass::find()->where(['class_id'=>$groupOne['id'],'type'=>2])->andWhere(['status'=>1]);
        $resModel = clone $result;
        //本课程参课人数
        $data['already_class_num']  = $allcount;
        //获取现在已经预约的人数
        $data['now_class_num'] = $resModel->count();//本节课已报的人数
        //获取座位信息
        $res = Seat::find()->where(['classroom_id'=>$groupOne['classroom_id'],'seat_type_id'=>$groupOne['seat_type_id'],'seat_type'=>[1,2,3]])->count();
        $data['total_seat'] = $res;
        $member_ids = array_column($result->select('member_id')->asArray()->all(),'member_id');
//        $member_ids = $resModel->where(['status'=>1])
//            ->andWhere(['between','create_at',strtotime($groupOne['class_date'].' '.$groupOne['start']),strtotime($groupOne['class_date'].' '.$groupOne['end'])])->select('member_id')->all();
//        $member_ids = [97709,97753];
        if (!empty($member_ids)){
            $data['user_pic'] = array_values(array_filter(array_column(\common\models\base\MemberDetails::find()->where(['member_id'=>$member_ids])->select('motto')->asArray()->all(),'motto')));
        }else{
            $data['user_pic'] = [];
        }
        //评价列表
//        $groupOne['id'] = [22899,26852];
//        $groupOne['venue_id'] = [59,13,11];
         $rs = Evaluate::find()
            ->alias('e')->joinWith('memberDetails md',false)
            ->select('md.motto,md.nickname,md.id,e.create_at,e.enclosure,e.star_level,e.content')
            ->where(['consumption_type_id'=>$groupOne['id'],'consumption_type'=>'teamClass','venue_id'=>$groupOne['venue_id']])
            ->orderBy('e.id desc')
            ->limit(5)
            ->asArray()
            ->all();
         if (!empty($rs)){
             foreach ($rs as $v){
                 $tmp['evaluate'][] = [
                     'motto'=>$v['motto']== 0 ? '' : $v['motto'],
                     'nickname'=>is_null($v['nickname']) ? '默认昵称' : $v['nickname'],
                     'create_at'=>date('Y.m.d',$v['create_at']),
                     'enclosure'=>is_null($v['enclosure']) || $v['enclosure']=='null' ? [] : $v['enclosure'],
                     'star_level'=>$v['star_level'],
                     'content'=>$v['content'],
                 ];
             }
             $data['evaluate_list']['item']=$tmp['evaluate'];
         }
        $data['evaluate_list']['evaluate_count'] = Evaluate::find()->where(['consumption_type_id'=>$groupOne['id'],'consumption_type'=>'teamClass','venue_id'=>$groupOne['venue_id']])->count();
        return $data;
    }

}