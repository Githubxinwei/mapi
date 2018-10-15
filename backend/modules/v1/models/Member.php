<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/27
 * Time: 11:21
 */

namespace backend\modules\v1\models;

use backend\models\AboutClass;
use backend\models\ClassRecord;
use backend\models\ClassRoom;
use backend\models\CoursePackageDetail;
use backend\models\Employee;
use backend\models\MemberCard;
use backend\models\Course;
use backend\models\MemberCourseOrder;
use backend\models\MemberCourseOrderDetails;
use backend\models\Organization;
use backend\models\LeaveRecord;
use common\models\base\Config;
use common\models\GroupClass;
use common\models\relations\MemberRelations;
use common\models\relations\LeaveRecordRelations;

class Member extends \common\models\base\Member
{
    use MemberRelations;
    public $status;  //状态
    public $type;    //类型
    public $password_hash; //密码哈希
    public $password;      //密码
    public $member_card_id; //会员卡ID
    public $memberId;        //会员id
    public $accountId;        //账户id
    public $aboutClass = 1;
    public $requestSource;   // 请求来源 （会员最近课程 或 全部订单 取消的订单 等）

    public $page;        // 请求页码
    public $endPrivatePage;  // 判断私课是否最后一页
    public $endGroupPage;    // 判断团课是否最后一页
    public $endPage;          // 判断是否最后一页
    const GROUP_PIC    = '';
    const CHARGE_PIC   = '';

    // 公司标识
     public  $companySignId;   // 公司标识id

    /**
     * 云运动 - api接口 - 获取会员课程数据
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $accountId
     * @param $arr   //类型，判断是求最近今天的数据，还是全部的
     * @param $type // 请求类型
     * @return array
     */
    public function getMemberClassData($accountId,$arr,$page,$type = "")
    {
       $this->accountId  = $accountId;                                     // 账户id
       $this->status    = '';                                            // 约课课程状态
       $this->page      = $page;                                         // 分页
       $this->requestSource ="recentCourse";                          // 会员最近课程
       $classData        = $this->getDataMemberClassById($accountId,$arr);  //会员预约团课课程
       $data             = empty($type)?$this->dataSort($classData):$this->dataSort($classData,"wholeMemberClass");                    // 排序数据
       return $data;
    }
    /**
     * 云运动 - api接口 - 对已经预约的课程进行排序
     * @author houkaixin<lihuien@sports.club>
     * @create 2017/7/24
     * @param $data  // 订单数据
     * @param $order  // 全部订单
     * @return array
     */
    public function  dataSort($data,$order = "isNotWholeMemberClass"){
          if(empty($data)){
             return $data;
          }
        // 全部课程订单排序（全部订单）
         if($order!="isNotWholeMemberClass"){
           $data   =  $this->orderWholeMemberClass($data);
           return $data;
         }
         $data = $this->dataSortIsNotWholeMemberClass($data);
         return  $data;
    }
    /**
     * 云运动 - api接口 - 不是最近课程的会员数据排序
     * @author houkaixin<houkaixin@sports.club>
     * @create 2017/8/28
     * @param $data   // 需要排序的数据(会员所有的课程)
     * @return array
     */
    public function dataSortIsNotWholeMemberClass($data){
        foreach ($data as $keys=>$values){
            foreach ($data as $key=>$value){
                $value1 = $data[$keys];
                $value2 = $data[$key];
                // 先按照 订单状态进行排序  未使用的排在前面
                if($value2["isClass"]>$value1["isClass"]){
                    $data[$keys] = $value2;
                    $data[$key]  = $value1;
                }
                // 订单状态相等的情况下
                if(($value2["isClass"]==$value1["isClass"])&&($value2["class_date"]>$value1["class_date"])){
                    $data[$keys] = $value2;
                    $data[$key]  = $value1;
                }
                // 日期相等的 情况下 按照 开课时间排序
                if(($value2["isClass"]==$value1["isClass"])&&($value2["class_date"]==$value1["class_date"])&&($value2["start"]>$value1["start"])){
                    $data[$keys] = $value2;
                    $data[$key]  = $value1;
                }
            }
        }
        return $data;
    }
    /**
     * 云运动 - api接口 - 会员全部课程数据排序
     * @author houkaixin<houkaixin@sports.club>
     * @create 2017/8/28
     * @param $data   // 需要排序的数据
     * @return array
     */
    public function orderWholeMemberClass($data){
        foreach ($data as $keys=>$values){
            foreach ($data as $key=>$value){
                $value1 = $data[$keys];
                $value2 = $data[$key];
                // 先按照 订单状态进行排序  未使用的排在前面
                if($value2["create_at"]<$value1["create_at"]){
                    $data[$keys] = $value2;
                    $data[$key]  = $value1;
                }
            }
        }
        return $data;
    }
    /**
     * 云运动 - api接口 - 获取会员未使用数据
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $accountId
     * @return array
     */
    public function getMemberNotClassData($accountId,$page)
    {
        $this->accountId = $accountId;
        $this->status   = 1;
        $this->requestSource = "unusedCourse";
        $this->page      = $page;
        $classData       = $this->getDataMemberClassById($accountId,'last');                 //会员预约团课课程
        $data            = $this->dataSort($classData);
        $data            = $this->dealOverdueCourse($data);
        return $data;
    }
    /**
     * 云运动 - api接口 - 处理未使用的课程
     * @author houkaixin<houkaixin@sports.club>
     * @create 2017/12/04
     * @param $classData
     * @return array
     */
    public function dealOverdueCourse($classData){
        if(empty($classData)){
            return [];
        }
        foreach($classData as $keys=>$value){
             $date = $value["class_date"]." ".$value["start"];
             if(time()>strtotime($date)){
                 unset($classData[$keys]);
             }
        }
        return $classData;
    }
    /**
     * 云运动 - api接口 - 获取会员取消数据
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $accountId
     * @return array
     */
    public function getMemberCancelClassData($accountId,$page)
    {
        $this->status   = 2;            // 取消的课程
        $this->type     = 'cancel';
        $this->accountId = $accountId;    // 账户id
        $this->page     = $page;        // 数据分页
        $this->requestSource = "cancelCourse";   // 请求来源
        $classData       = $this->getDataMemberClassById($accountId,'');          //会员预约团课课程
        $data            = $this->cancelDataSort($classData);
        return $data;
    }
    /**
     * 云运动 - api接口 - 已取消预约课程数据排序
     * @author houkaixin<houkaixin@sports.club>
     * @create 2017/8/2
     * @param  $data   // 需要排序的数据
     * @return array
     */
    public function cancelDataSort($data){
          if(empty($data)){
              return $data;
          }
        foreach ($data as $keys=>$values){
            foreach ($data as $key=>$value){
                $value1 = $data[$keys];
                $value2 = $data[$key];
                // 按照取消日期排序
                if($value2["cancel_time"]<$value1["cancel_time"]){
                    $data[$keys] = $value2;
                    $data[$key]  = $value1;
                }
            }
        }
        return $data;
    }
    /**
     * 云运动 - api接口 - 获取会员课程标识
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $accountId     //账户id
     * @return array        //会员卡id
     */
    public function getMemberId($accountId)
    {
        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        foreach ($members as $member) {
            $ids[] = $member['id'];
        }
        $member =  MemberCard::find()->select(['id'])->where(['member_id'=>$ids])->asArray()->one();
        if($member){
            return $member['id'];
        }else{
            return NULL;
        }
    }
    /**
     * 云运动 - api接口 - 获取会员团课课程数据
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $memberId
     * @param $time         //3天前的时间戳
     * @return array
     */
    public function getAboutClassId($accountId,$time)
    {
        $id                   = $this->getMemberId($accountId);         //获取会员卡id
        $this->member_card_id = $id;
        $classId              = $this->getAboutIdData($id,2,$time);     //获取所有预约课程id
        return $classId;
    }
    /**
     * 云运动 - api接口 - 获取会员产品课程数据
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $memberId
     * @param $time         //3天前的时间戳
     * @return array
     */
    public function getAboutChargeId($accountId,$time)
    {
        $id                   = $this->getMemberId($accountId);                //获取会员卡id
        $this->member_card_id = $id;
        $classId              = $this->getAboutIdData($id,1,$time);            //获取约课订单id（数组），类型是私课的
        return $classId;
    }
    /**
     * 云运动 - api接口 - 获取会员团课课程数据
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $accountId 账户id
     * @return array
     */
    public function getClassGroupId($accountId)
    {
        $id      = $this->getMemberId($accountId);
        $classId = $this->getClassIdData($id,'group');
        return $classId;
    }
    /**
     * 云运动 - api接口 - 获取会员产品课程数据
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $accountId
     * @return array
     */
    public function getClassChargeId($accountId)
    {
        $id      = $this->getMemberId($accountId);
        $classId = $this->getClassIdData($id,'charge');
        return $classId;
    }
    /**
     * 后台 - 登录 - 获取会员团课预约课程数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $memberId int 会员
     * @param  $arr  //获取数据，条件
     * @return array
     */
    public function getDataMemberClassById($accountId,$arr)
    {
        if($arr == 'limit')
        {
            $time  = strtotime('-3 day');
            $classId = $this->getAboutClassId($accountId,$time);                                //获取预约团课的课程id（数组）
        }else if($arr == 'last'){
            $classId = $this->getAboutClassId($accountId,'');                                   //获取预约团课的课程id（数组）
        }else{
            $classId = $this->getAboutClassId($accountId,'');                                   //获取预约团课的课程id（数组）
        }
        //  执行团课部分数据逻辑代码
        $groupClassId     = $this->setArrId($classId,2);       // 获取会员约课的 约课ID
        $createAtArr      = $this->setCreateAt($classId,2);    // 获取会员约课的下单时间
        if(!isset($this->type) && !$this->type){
            $type = 'about';
        }else{
            $type = $this->type;
        }
        $groupData = $this->getCoachData($groupClassId,$type,$createAtArr);          //处理所有预约课程
        //  执行私课所需逻辑代码
        $classPrivateId     = $this->setArrId($classId,1);
        $classPrivateData   = $this->getAboutInfoAlone($classPrivateId);                                                   //获取订单信息
        $privateData        = $this->getChargeClassDate($classPrivateData,$type);
        $data = array_merge($groupData,$privateData);
        return $data;
    }
    /**
     * 后台 - 登录 - 获取会员私教预约课程数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $memberId int 会员
     * @param  $arr  //获取数据，条件
     * @return array
     */
    public function getDataMemberChargeById($memberId,$arr)
    {
        if($arr == 'limit')
        {
            $time  = strtotime('-3 day');
            $aboutInfo = $this->getAboutChargeId($memberId,$time);                                      //获取预约私课订单id（倒序数组）
        }else if($arr == 'last'){
            $this->status = 1;
            $aboutInfo = $this->getAboutChargeId($memberId,'');                                         //获取预约私课订单id（倒序数组）
        }else{
            $aboutInfo = $this->getAboutChargeId($memberId,'');                                         //获取预约私课订单id（倒序数组）
        }
        //$class_id = array_column($aboutInfo,"class_id");
        $data = $this->getAboutInfoAlone($aboutInfo);                                                   //获取订单信息
        if(!isset($this->type) && !$this->type){
            $type = 'about';
        }else{
            $type = $this->type;
        }
        $data     = $this->getChargeClassDate($data,$type);
        return $data;
    }

    /**
     * 后台 - app接口 - 修改预约状态
     * @author  huangpengju <huangpengju@itsport.club>
     * @create 2017/6/15
     * @param $aboutId  //预约id
     * @param $status  //预约id
     * @return mixed
     */
    public function updateOrderStatus($aboutId,$status)
    {
        if(empty($aboutId)){
             return false;
        }
        $aboutId = \common\models\base\AboutClass::findOne($aboutId);
        $aboutId->status = $status;       //课程状态
        $aboutId->save();                //修改预约状态
    }

    /**
     * 后台 - app接口 - 修改课程剩余量
     * @author  huangpengju <huangpengju@itsport.club>
     * @create 2017/6/16
     * @param $orderId    //订单id
     */
    public function updateCourseNum($orderId)
    {
        $order = MemberCourseOrder::findOne($orderId);
        $order->overage_section = ($order->overage_section>0) ? ($order->overage_section - 1):0;   //剩余课量减1
        $order->save();
    }
    /**
     * 后台 - app接口 - 获取会员私教预约课程数据
     * @author  huangpengju <huangpengju@itsport.club>
     * @create 2017/6/14
     * @param $data     //订单数组
     * @param $type     //预约类型
     * @return mixed
     */
    public function getChargeClassDate($data,$type)
    {
        if(isset($data) && !empty($data) && is_array($data)){
            foreach ($data as $key=>&$v){
                    $time              = time();                                                    //获取当前时间戳
                  if($v['status'] != 2){
                      if ($v['start'] < $time && $time < $v['end']) {
                          $v['classStatus'] = true;            //上课中
                          $v["unusedFlag"]  = false;
                          $this->updateOrderStatus($v['aboutId'],3);                              //修改预约记录状态，3上课中
                          $v["isClass"]      = 1;                //  上课标识     1 表示上课中 2 表示未使用3：已使用下课
                      } else if ($v['start'] < $time && $v['end'] < $time) {
                          if($v['status'] != 4 && $v['status'] != 5){
                           //   $this->updateOrderStatus($v['aboutId'],4);                           //修改预约记录状态 4 下课
                              $this->updateCourseNum($v['course_order_id']);                        //修改剩余课量
                          }
                          $v['classStatus'] = false;            //课程结束
                          $v["isClass"]      = 3;                // 课程结束标识
                      }elseif($time < $v['start'] && $time < $v['end']){
                          $v["isClass"]      = 2;                // 未上课标识
                      }
                  }
                    $v['start']        = date('H:i',$v['start']);
                    $coach             = $this->getFieldsByOne($v,'coach');                     //获取教练信息
                    $v['coachName']    = $coach['name'];
                if(empty($v['pic']))
                {
                    $v['pic'] = self::CHARGE_PIC;                                              //默认私课产品图片
                }
                if($v['status'] == 2){              //取消课程，计算是第几节
                    if($v['aboutId']){
                        $abouts = new \backend\modules\v1\models\AboutClass();
                        $v['chargeNum']  = count($abouts->getCancelChargeNum($v['id'],1,$this->member_card_id,$v['aboutId']));    //统计这是第几节课
                    }
                }else{
                    if($v['aboutId']){
                        $abouts = new \backend\modules\v1\models\AboutClass();
                        $v['chargeNum']  = count($abouts->getChargeNum($v['id'],1,$this->member_card_id,$v['aboutId']));    //统计这是第几节课
                    }
                }
                $v['courseFlag']   = true;             //true,表示是私课
                if($type == 'about'){
                    if($v['status'] == 1){
                        if(!isset($v['classStatus'])){
                            $v['unusedFlag'] = true;            //待使用
                            // $v["isClass"]      = 1;               // 未使用
                        }else{
                            $v['unusedFlag'] = false;           //已使用
                            //$v["isClass"]     = 3;               // 已使用
                        }
                    }else if($v['status'] == 2){
                        $v['cancelFlag'] = true;               //取消
                        unset($data[$key]);
                        if(isset($v['classStatus']))
                        {
                            unset($v['classStatus']);          //如果已经取消了，课程状态存在，则删除
                        }
                    }else if( $v['status'] == 3 || $v['status'] == 4 ||$v['status'] == 5){
                        $v['unusedFlag'] = false;           //已使用
                        if ($v['start'] < $time && $time < $v['end']) {
                            $v["isClass"]      = 1;              // 上课中
                        }elseif($v['start'] < $time && $v['end'] < $time){
                            $v["isClass"]      = 3;              // 下课标识
                        }
                    }
                }else if($type == 'cancel'){
                    $v['cancelFlag'] = true;                //取消
                    if(isset($v['classStatus']))
                    {
                        unset($v['classStatus']);            //如果已经取消了，课程状态存在，则删除
                    }
                }else{
                    $v['completeFlag'] = true;              //完成
                    $v["isClass"]      = 3;              // 下课标识
                }
            }
        }
        return $data;
    }
    /**
     * 后台 - 预约私课 - 获取会员私教订单详细信息
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param $orderId    //订单详情表id
     * @return array      //订单详细信息
     */
    public function getAboutInfoAlone($orderId)
    {
        $arr = [];
        $orderId        = array_values($orderId);
        if(empty($orderId)){
            return $arr;
        }
        $orderClassId = array_column($orderId,"class_id");
        $order  = MemberCourseOrderDetails::find()->where(['id'=>$orderClassId])->asArray()->all();
        // $order重组数组 （订单id作 为key值,value值不变,方便查找）
         $result =  $this->combineData($order);
        foreach ($orderId as $k=>$v)
        {
            $arr[] = $result[ $v['class_id']];
            $arr[$k]['aboutId']         = $v['id'];                                                          //约课记录id
            $arr[$k]['memberCardId']    = $v['member_card_id'];                                              //会员卡id
            $arr[$k]['classId']         = $v['class_id'];                                                    //订单id
            $arr[$k]['status']          = $v['status'];                                                      //约课状态
            $arr[$k]['classType']       = $v['type'] == 1?'charge':'group';                                  //类型（私课）
            $arr[$k]['create_at']       = $v['create_at'];                                                   //约课记录创建时间
            $arr[$k]['seatId']          = $v['seat_id'];                                                     //座位号id
            $arr[$k]['coach_id']        = $v['coach_id'];                                                    //教练id
            $arr[$k]['class_date']      = $v['class_date'];                                                  //上课日期
            $arr[$k]['start']           = $v['start'];                                                       //上课时间点
            $arr[$k]['end']             = $v['end'];                                                         //下课时间点
            $arr[$k]['cancel_time']     = $v['cancel_time'];                                                 //取消时间
            $arr[$k]['memberId']        = $v['member_id'];                                                   //会员id
//            $arr[$k]['isPrintReceipt']  = $v['is_print_receipt'];                                            //是否打印过小票
//            $arr[$k]['aboutType']       = $v['about_type'];                                                  //预约类型
//            $arr[$k]['employeeId']      = $v['employee_id'];                                                 //员工id
        }
        return $arr;
    }
    /**
     * 后台 -  重组数据 - 会员订单（class_id变为键值）
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $memberId int 会员
     * @return array
     */
    public function combineData($order){
         $result = [];
         foreach($order as $keys=>$values){
             $result[$values["id"]] = $values ;
         }
         return $result;
    }

    /**
     * 后台 - 登录 - 获取会员团课课程数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $memberId int 会员
     * @return array
     */
    public function getDataMemberGroupClassById($memberId)
    {
        $classId = $this->getClassGroupId($memberId);
        $classId = $this->setArrId($classId);
        $data = GroupClass::find()->where(['in','id',$classId])->asArray()->all();
        $data = $this->getCoachData($data,'');
        return $data;
    }
    /**
     * 后台 - 登录 - 获取会员私教课程数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $memberId int 会员
     * @return array
     */
    public function getDataMemberChargeClassById($memberId)
    {
        $classId = $this->getClassChargeId($memberId);
        $classId = $this->setArrId($classId);
        $data    = CoursePackageDetail::find()->where(['in','id',$classId])->asArray()->all();
        $data    = $this->getChargeData($data,'');
        return $data;
    }
    /**
     * 后台 - 登录 - 获取团课详情数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $data array
     * @param  $type int
     * @param  $createAtArr array
     * @return array
     */
    public function getCoachData($data,$type,$createAtArr)
    {
        // 关联判断
        if(isset($data) && !empty($data) && is_array($data)){
            foreach ($data as $k=>&$val) {
                $v = \backend\models\GroupClass::find()->alias("group")->where(['group.id'=>$val])
                    ->joinWith(["course course"],false)
                    ->select("group.*,course.pic as coursePic")
                    ->asArray()->one();                                               //查询classId数组中的对应的团课课程
                $val = [];
                $course = $this->getFieldsByOne($v, 'course');                              //查询课程的名称
                $coach = $this->getFieldsByOne($v, 'coach');                          //查询教练信息
                $about = $this->getMemberAbout($v['id'],$createAtArr[$k],2);              //获取约课信息
                if(empty($about)){
                    unset($data[$k]);
                    continue;
                }
                $val["cancel_time"] = $about["cancel_time"];
                $val['name'] = $course['name'];
                $val['aboutId'] = isset($about['id']) ? $about['id'] : NULL;
                $val['aboutStatus'] = isset($about['status']) ? $about['status'] : NULL;
                $time = time();                                                       //获取当前时间戳
                if ($about['status'] != 2) {
                    if ($v['start'] < $time && $time < $v['end']) {
                        $val['classStatus'] = true;            //上课中
                        $val["unusedFlag"]  = false;
                        $val["isClass"]      = 1;                //  上课标识     1 表示上课中 2 表示未使用 3：已使用下课
                        $this->updateOrderStatus($about['id'], 3);    //修改预约记录状态 ,3上课
                    } else if ($v['start'] < $time && $v['end'] < $time) {
                        if ($about['status'] != 4 && $about['status'] != 5) {
                            //    $this->updateOrderStatus($about['id'], 4);    //修改预约记录状态 ,4下课
                        }
                        $val['classStatus']  = false;            //课程结束
                        $val["isClass"]       = 3;                //下课标识
                        $val["unusedFlag"]   = false;            // 待使用
                    }
                }
                $val['id']          = $v['id'];
                $val['end']         = $v['end'];
                $val['class_date'] = $v['class_date'];
                $val['create_at']  = $v['created_at'];
                $val['status']      = $v['status'];
                $val['course_id']   = $v['course_id'];
                $val['coach_id']    = $v['coach_id'];
                $val['aboutStatus'] = $about['status'];
                if (empty($v['coursePic']))
                {
                    $val['pic']      = self::GROUP_PIC;                               //处理团课默认图片
                }else{
                    $val["pic"]      = $v['coursePic'];
                }
                $val['start']        = date('H:i',$v['start']);
                $val['coachName']    = $coach['name'];
                $val['classType']    = 'group';                           // 团课
                $val['type']         = 2;                                      //团课类型
                $val['courseFlag']   = false;                                  //false,表示是团课
                if($type == 'about'){
                    if($about['status'] == 1){
                        if($v['start'] > $time){    // 还未开课
                            if(!isset($val['classStatus']))
                            {
                                $val['unusedFlag']  = true;                       //待使用，已预约
                                $val["isClass"]      = 2;                          // 未上课
                            }else{
                                $val['unusedFlag']  = false;                      //过期（完成）
                                $val["isClass"]      = 3;                          // 已下课标识
                            }
                        }
                    }else if($about['status'] == 2){
                        $val['cancelFlag'] = true;                           //取消状态
                        unset($data[$k]);                                      // 删除取消状态
                        if(isset($val['classStatus']))
                        {
                            unset($val['classStatus']);
                        }
                    }else if($about['status'] == 4 || $about['status'] == 5){
                        $val['unusedFlag'] = false;                           //过期（完成）状态
                        $val["isClass"]      = 3;                              // 已下课
                    }
                }else if($type == 'cancel'){
                    $val['cancelFlag'] = true;                                //取消状态
                    if(isset($val['classStatus']))
                    {
                        unset($val['classStatus']);
                    }
                }else{
                    $val['completeFlag'] = true;                              //完成或者过期
                    $val['unusedFlag'] = false;                           //过期（完成）状态
                    $val["isClass"]      = 3;                                 // 已下课
                }
                //删除暂时没有用到的数据
                // unset($val['classroom_id'],$val['create_id'],$val['difficulty'],$val['desc'],$val['class_limit_time'],$val['cancel_limit_time'],$val['least_people'],$val['company_id'],$val['venue_id']);
            }
        }
        return $data;
    }
    /**
     * 后台 - 登录 - 获取私教详情数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $data array
     * @param  $type int
     * @return array
     */
    public function getChargeData($data,$type)
    {
        if(isset($data) && !empty($data) && is_array($data)){
            foreach ($data as &$v){
                $about                 = $this->getFieldsByOne($v,'aboutCharge');                   //获取约课信息
                if($about){
                    $v['class_date']   = $about['class_date'];                                      //上课日期

                    $time              = time();                                                    //获取当前时间戳
                    if($about['status'] == 1) {
                        if ($about['start'] < $time && $time < $about['end']) {
                            $v['classStatus'] = true;            //上课中
                        } else if ($about['start'] < $time && $about['end'] < $time) {
                            $v['classStatus'] = false;            //课程结束
                        }
                    }
                    $v['start']        = date('h:i',$about['start']);
                    $coach             = $this->getFieldsByOne($about,'coach');                     //获取教练信息
                    $v['coachName']    = $coach['name'];
                }else{
                    $v['class_date']   = '';
                    $v['start']        = '';
                    $v['coachName']    = '';
                }
                $about             = $this->getMemberAbout($v['id'],$v['class_date'],1);           //获取约课信息
                $v['aboutId']      = isset($about['id'])?$about['id']:NULL;
                $course            = $this->getFieldsByOne($v,'course');                    //获取种信息
                $v['chargeName']   = $course['name'];
                $v['chargePic']    = $course['pic'];
                if($v['aboutId']){
                    $abouts = new \backend\modules\v1\models\AboutClass();
                    $v['chargeNum']  = count($abouts->getChargeNum($v['id'],1,$this->member_card_id,$v['aboutId']));    //统计这是第几节课
                }
                $v['name']         = $this->getFieldsByOne($v['charge_class_id'],'charge');                             //获取私课产品名称
                $v['type']         = 'charge';
                $v['courseFlag']   = true;
                if($type == 'about'){
                    if($about['status'] == 1){
                        if(!isset($v['classStatus'])){
                            $v['unusedFlag'] = true;
                        }else{
                            $v['unusedFlag'] = false;
                        }
                    }else if($about['status'] == 2){
                        $v['cancelFlag'] = true;
                    }
                }else if($type == 'cancel'){
                    $v['cancelFlag'] = true;
                }else{
                    $v['completeFlag'] = true;
                }
            }
        }
        return $data;
    }

    /**
     * 云运动 - api接口 - 处理课程数组id
     * @author lihuien<lihuien@sports.club>
     * @update 2017/6/13
     * @param $data //课程id 数组类型
     * @param $type //课程类型  1 私课 2 团课
     * @return array
     */
    public function setArrId($data,$type)
    {
        $arrId = [];
        $i= 0;
        if(is_array($data) && $data && !empty($data)){
            foreach ($data as $keys=>$v){
                if(($v["type"]== $type)&&($type==2)){
                    $arrId[] = $v['class_id'];
                }
                if(($v["type"]== $type)&&($type==1)){
                    $arrId[$i] = $v;
                }
                $i++;
            }
        }
        return $arrId;
    }
    /**
     * 云运动 - api接口 - 处理预约课程创建时间id
     * @author huangpengju<lihuien@sports.club>
     * @update 2017/6/13
     * @param $data //课程id 数组类型
     * @param $type // 课程类型
     * @return array
     */
    public function setCreateAt($data,$type)
    {
        $createArr = [];
        if(is_array($data) && $data && !empty($data)){
            foreach ($data as $v){
                if($v["type"]==$type){
                    $createArr[] = $v['create_at'];
                }
            }
        }
        return $createArr;
    }
    /**
     * 云运动 - api接口 - 获取处理数据
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $v  array
     * @param $type string
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getFieldsByOne($v,$type)
    {
        switch ($type){
            case 'course':
                $course  = new Course();
                $class         = $course->courseDetail($v['course_id']);
                return $class;
            case 'classroom':
                $classroom   = ClassRoom::getClassroomOneById($v['classroom_id']);
                return $classroom['total_seat'];
            case 'coach':
                $coach = Employee::getCoachOneById($v['coach_id']);
                return $coach;
            case 'venue':
                $classroom   = ClassRoom::getClassroomOneById($v['classroom_id']);
                $venue       = Organization::getOrganizationById($classroom['venue_id']);
                $venueArr['name']    = $venue['name'];
                $venueArr['id']      = $venue['id'];
                return $venueArr;
            case 'about':
                $about       = AboutClass::getAboutClassOneById($v['id']);
                return count($about);
            case 'charge':
                $about       = ChargeClass::find()->where(['id'=>$v])->asArray()->one();
                return $about['name'];
            case 'aboutData':
                $about       = AboutClass::getAboutClassOneById($v['id']);
                if($about)return $about[0];
                return $about;
            case 'aboutCharge':
                $about       = AboutClass::getAboutClassOneBy($v['id'],$this->accountId,1);
                if($about)return $about[0];
                return $about;
            case 'score':
                $score = isset($v['score'])?$v['score']:mt_rand(4,5);
                $scoreArr = [];
                $scoreArr['score'] = $score;
                $key = ['one','two','three','four','five'];
                for($i = 1; $i<=5;$i++){
                    if($i <= $score){
                        $scoreArr['scoreImg'][$key[$i-1]] = 'img/x1.png';
                    }else{
                        $scoreArr['scoreImg'][$key[$i-1]] = 'img/x2.png';
                    }
                }
                return $scoreArr;
            default:;
        }
    }
    /**
     * 后台 - 登录 - 获取会员预约课程数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $id int 会员
     * @param  $type int 类型
     * @param  $time //时间
     * @return array
     */
    public function getAboutIdData($id,$type = 1,$time = '')
    {
        if(!empty($time))
        {
            $nowTime = time();
        }else{
            $nowTime = '';
        }
        $data =  AboutClass::find()
            ->alias("aboutClass")
            /*->where(['aboutClass.member_id'=>$this->memberId])*/
            ->where(['aboutClass.member_id' => $id])
            ->joinWith(["groupClass groupClass"],false)
            ->select("aboutClass.*,count(groupClass.id) as groupClassNum")
            ->having(["or",["and",["!=","groupClassNum",0],["type"=>2]],["type"=>1]])    //过滤 已经预约会员（但突然删除团课）
            ->groupBy("aboutClass.id")
            ->andFilterWhere(['and',['>=','aboutClass.create_at',$time],['<=','aboutClass.create_at',$nowTime]]);
         //请求来源（会员最近课程）
         if($this->requestSource=="recentCourse"){
            $data  = $data->andWhere(["!=",'aboutClass.status',2]);
         }
         // 请求来源（取消的课程）
         if($this->requestSource == "cancelCourse"){
             $data  = $data->andWhere(["aboutClass.status"=>$this->status])->orderBy(['aboutClass.cancel_time'=>SORT_DESC]);
         }
        // 请求来源(待使用课程)
         if($this->requestSource =="unusedCourse"){
             $data  = $data->andWhere(["aboutClass.status"=>$this->status])->orderBy(['aboutClass.start'=>SORT_ASC]);
         }
        // 分段数据加载
        if(($this->requestSource == "cancelCourse")||($this->requestSource =="unusedCourse")){
            $maxAboutNum             = $data->count();
            $this->page              = (!isset($this->page)||empty($this->page))?1:$this->page+1;
            $page                    = $this->page*8;
            $page                    = ($page>=$maxAboutNum)?$maxAboutNum:$page;
            // 判断请求是否最后一页
            $this->endPage = ($page>=$maxAboutNum)?2:1;
            $data                       =  $data->limit($page)->asArray()->all();          // 私课数据
        }else{
            $data                       =  $data->asArray()->all();                       // 私课数据
            $this->endPage             =  2;
        }
        return $data;
    }
    /**
     * 后台 - 登录 - 获取会员上课课程数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $id int 会员
     * @param  $type string 类型
     * @return array
     */
    public function getClassIdData($id,$type = 'charge')
    {
        return  ClassRecord::find()->select('multiple_id')
            ->where(['member_id'=>$id])
            ->andWhere(['multiple_type'=>$type])
            ->asArray()->all();
    }
    /**
     * 后台 - 登录 - 获取会员数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $mid
     * @return array
     */
    public function getMemberOneData($mid)
    {
        $mainSql =  \common\models\Member::find()
            ->alias('member')
            ->joinWith(["venue venue"=>function($query){
                $query->joinWith(["company company"]);
            }],false)
            ->joinWith(['memberDetails details'],false)
            ->where(['member.id'=>$mid]);
//        if(!empty($this->companySignId)){
//            $mainSql = $mainSql->andWhere(["member.company_id"=>$this->companySignId]);
//        }
//        if(!empty($venueId)){
//            $mainSql = $mainSql->andWhere(["member.venue_id"=>$venueId]);
//        }
        $mainSql->orderBy(["member_type"=>SORT_ASC]);
        $endData =  $this->nextSql($mainSql);
        return $endData;
    }
    /**
     * 后台 - 登录 - 获取登录数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $company
     * @param  $identification    int    会员手机号
     * @param  $type
     * @return array
     */
    public function getMemberAccOneData($identification,$company,$type = '')
    {
        $mainSql =  \backend\models\Member::find()
            ->alias('mm')
            ->select('mm.id,mm.member_account_id,mm.venue_id,memberAccount.mobile,memberAccount.company_id,venue.name as venueName,venue.id as venueId')
            ->joinWith(['venue venue'],false)
            ->joinWith(['memberAccount memberAccount'],false)
            ->asArray();
        if($type){
            $mainSql->where(['memberAccount.id'=>$identification,'memberAccount.company_id'=>$company]);
        }else{
            $mainSql->where(['memberAccount.mobile'=>$identification,'memberAccount.company_id'=>$company]);
        }
        return $mainSql->all();
    }
    /**
     * 后台 - 登录 - 获取会员数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $mid
     * @return array
     */
    public function getMemberOneDataTwo($mid)
    {
        $mainSql =  \common\models\Member::find()
            ->alias('member')
            ->joinWith(["venue venue"=>function($query){
                $query->joinWith(["company company"]);
            }],false)
            ->joinWith(['memberDetails details'],false)
            ->where(['member.id'=>$mid]);
        $mainSql->orderBy(["member_type"=>SORT_ASC]);
        $endData =  $this->nextSql($mainSql);
        return $endData;
    }
    /**
     * 后台 - 登录 - 获取会员数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $mainSql  // 获取会员信息 主sql
     * @return array
     */
    public function nextSql($mainSql){
        $totalSql = $mainSql->select('
                        member.*,
                        details.id as detailsId,
                        details.name as detailsName,
                        details.member_id,
                        details.pic,
                        details.nickname,
                        details.motto, 
                        details.sex,
                        details.birth_date,
                        details.profession,
                        details.now_address,
                        details.introduction,
                        venue.name as venueName,
                        company.name as companyName,           
                    ')
            ->limit(1)
            ->asArray()
            ->one();
        return $totalSql;
    }
    /**
     * 后台 - 登录 - 获取会员预约情况
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $id int
     * @param  $date int
     * @param  $type int
     * @return array
     */
    public function getMemberAbout($id,$date,$type)
    {
        $members = Member::find()->where(['member_account_id' => $this->accountId])->asArray()->all();
        foreach ($members as $member) {
            $ids[] = $member['id'];
        }
       return AboutClass::find()->where(['class_id' => $ids])
            /*->andWhere(['member_id'=>$this->memberId])*/
            ->andWhere(['member_id'=>$ids])
            ->andWhere(['create_at'=>$date])->andWhere(['type'=>$type])->andFilterWhere(['status'=>$this->status])
            ->asArray()->one();
    }
    /**
     * 后台 - 登录 - 获取会员详情数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param  $memberId int 会员ID
     * @return array
     */
    public function getMemberDetail($memberId)
    {
       return Member::find()
           ->alias('member')
           ->select('member.id,member.username,member.venue_id as venueId,md.pic,md.name,md.nickname')
           ->joinWith(['memberDetails md'],false)
           ->where(['member.id'=>$memberId])->asArray()->one();
    }
    /**
     * 后台 - 个人资料信息完善
     * @author  houkaixin <houkaixin@itsport.club>
     * @create 2017/11/27
     * @param  $param   // 个人资料信息提交
     * @return array
     */
    public function saveMemberMessage($param){
          $model           = \common\models\base\MemberDetails::findOne(["member_id"=>$param["id"]]);
          $endResult       =  $this->judgeParam($param);
          if($endResult!==true){
                return $endResult;
          }
          $model->sex      = $param["sex"];
          $model->name     = $param["name"];
          $model->id_card  = $param["idCard"];
          if(!$model->save()){
               return $model->errors;
          }
          return true;
    }
    /**
     * 后台 - 判断存储信息数据合法性
     * @author houkaixin <houkaixin@itsport.club>
     * @create 2017/11/29
     * @param  $param   // 个人资料信息提交
     * @return boolean
     */
    public function judgeParam($param){
        if(empty($param["sex"])){
            return "性别不能为空";
        }
        if(empty($param["name"])){
            return "姓名不能为空";
        }
        if(empty($param["idCard"])){
            return "身份证不能为空";
        }
        $endResult = preg_match('/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/',$param["idCard"]);
        if($endResult===false){
             return "您输入的身份证不合法";
        }
        return true;
    }
    /**
     * 后台 - 判断存储信息数据合法性
     * @author houkaixin <houkaixin@itsport.club>
     * @create 2017/11/29
     * @param  $appType         // app类型（安卓还是ios）
     * @param  $venue          // 对应场馆
     * @param $isMustUpdate    // 是否必须更新  1必须更新 2不必须更新
     * @return boolean         // 返回是否必须更新
     */
    public function appConfig($appType,$venue,$isMustUpdate){
            if(($appType!="android"&&$appType!="ios")||($venue!="maibu"&&$venue!="WAYD")||($isMustUpdate!=1&&$isMustUpdate!=2)){
                return ["status"=>false,"data"=>"参数不合法"];
            }
           $check = Config::find()
                           ->where(["and",["key"=>$appType],["type"=>$venue]])
                           ->one();
           if(empty($check)){
               $check = new Config();
           }
            $check->key   = $appType;        // app类型
            $check->value = $isMustUpdate;  // 是否必须更新
            $check->type  = $venue;         //场馆标志
            if(!$check->save()){
                return ["status"=>false,"data"=>$check->errors];
            }
            return ["status"=>true,"data"=>$check];
    }
    /**
     * 后台 - 柜子 - 柜子结果数据处理
     * @author houkaixin <houkaixin@itsport.club>
     * @create 2017/01/08
     * @param  $endDataS         // pc端柜子类型
     * @return boolean         // 返回是否必须更新
     */
    public static function mobileCabinetDeal($endDataS){
         if(empty($endDataS)){
              return [];
         }
         $endArr = [];
         foreach ($endDataS as $keys=>$endData){
// ------------------------------------赠送金额 数据处理 -------------------
             $signData1          = [];  // 目标数组 1
             $signData2          = []; // 目标数组 2
             if(!empty($endData["give_month"])&&!empty($endData["cabinet_money"])){
                 $resultDataMonth    = !empty($endData["give_month"])?json_decode($endData["give_month"],true):[];
                 $resultDataMoney    = !empty($endData["cabinet_money"])?json_decode($endData["cabinet_money"],true):[];
                 $endResultMonth     = !empty($resultDataMoney)?array_keys($resultDataMonth):[];
                 $endResultGiveMonth = !empty($resultDataMonth)?array_values($resultDataMonth):[];
                 $endResultMoney     = !empty($resultDataMoney)?array_values($resultDataMoney):[];
                 foreach($endResultMonth as $key=>$value){
                         $signData1["month"] = isset($endResultMonth[$key])?$endResultMonth[$key]:0;
                         $signData1["give"]  = (isset($endResultGiveMonth[$key])&&(!in_array($endResultGiveMonth[$key],[""])))?$endResultGiveMonth[$key]:0;
                         $signData1["money"] = isset($endResultMoney[$key])?$endResultMoney[$key]:0;
                         $signData2[]         = $signData1;
                 }
             }
             $endArr[$keys]["give_month"]       = $signData2;        // 赠送月数
// ------------------------------------赠送金额 数据处理 -------------------
             if(empty($endArr[$keys]["give_month"])){
                 $endArr[$keys]["old_give_month"]  = (!isset($endData["give_month"])||in_array($endData["give_month"],["null",""]))?0:$endData["give_month"];    // 赠送月数
             }
             $endArr[$keys]["type_name"]        =  $endData["type_name"];         // 柜子类型名字
             $endArr[$keys]["id"]               = $endData["id"];                 // 柜子id
             $endArr[$keys]["cabinet_type_id"] = $endData["cabinet_type_id"];   //柜子类型id
             $endArr[$keys]["cabinet_number"]  = $endData["cabinet_number"];    // 柜子编号
             $endArr[$keys]["monthRentPrice"]  = $endData["monthRentPrice"];    // 更柜月租金
             $endArr[$keys]["yearRentPrice"]   = $endData["yearRentPrice"];     // 更柜年租金
             $endArr[$keys]["venue_id"]         = $endData["venue_id"];          // 更柜场馆id
             $endArr[$keys]["deposit"]          = $endData["deposit"];            // 柜子押金
             $endArr[$keys]["status"]            = $endData["status"];           // 柜子状态
             $endArr[$keys]["cabinetModel"]     = $endData["cabinetModel"];     //1:临时2:正式
             $endArr[$keys]["cabinetType"]      = $endData["cabinetType"];      // 柜子规格 1:大柜2:中柜3:小柜
             $endArr[$keys]["halfYearRentPrice"]  = empty($endData["halfYearRentPrice"])?"":$endData["halfYearRentPrice"];      // 半年租金
         }
         return $endArr;
    }

    /**
     * 后台 - 获取会员的请假记录
     * @author houkaixin <houkaixin@itsport.club>
     * @create 2017/11/29
     * @param  $accountId         // 会员id
     * @return mixed         // 请求过来的数据
     */
    public function memberLeaveRecord($accountId){
        // 	leaveProperty  1代表未审核 2代表已经审核 （针对特殊请假 正常请假直接是2）
        // leaveType  1表示正常 请假   2代表特殊请假
        // leaveStatus 1代表假期中 2代表 已销假
        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        foreach ($members as $member) {
            $ids[] = $member['id'];
        }
        $data = \backend\models\LeaveRecord::find()
            ->alias("leaveRecord")
            ->where(["leaveRecord.leave_employee_id" => $ids])
            /*->joinWith(["memberCard memberCard"],false)*/
            ->joinWith(['memberCard memberCard'=>function($query){
                $query->joinWith(["organization as o"],false);
                $query->select("o.name as venue_name");
            }],false)
            ->select("               
                      leaveRecord.member_card_id,
                      leaveRecord.leave_property as leaveProperty,
                      leaveRecord.status as leaveStatus,
                      leaveRecord.leave_start_time,
                      leaveRecord.leave_end_time,
                      leaveRecord.leave_type as leaveType,
                      leaveRecord.create_at,
                      leaveRecord.note,
                      memberCard.card_name,
                      leaveRecord.reject_note,
                      leaveRecord.type,
                      o.name as venue_name
                                   ")
            ->orderBy(["leaveRecord.create_at"=>SORT_ASC])
            ->asArray()
            ->all();
        if(empty($data)){
            return [];
        }
        foreach($data as $keys=>$value){
            $data[$keys]["leaveStatus"] =($data[$keys]["leaveStatus"]==2)?2:$this->gainLeaveStatus($data[$keys]["leave_start_time"],$data[$keys]["leave_end_time"]);
            $data[$keys]["leaveDays"] = ceil(($data[$keys]["leave_end_time"]-$data[$keys]["leave_start_time"])/(60*60*24));
            $data[$keys]["leaveType"] = empty($data[$keys]["leaveType"])?1:$data[$keys]["leaveType"];
            $data[$keys]["note"]      = empty($data[$keys]["note"])?"":$data[$keys]["note"];
            $data[$keys]["hour"]      = !empty($value["create_at"])?ltrim(date("H:i:s",$value["create_at"]),"0"):"";
            $data[$keys]["date"]      = !empty($value["create_at"])?date("Y-m-d",$value["create_at"]):"";
          //  $data[$keys]["status"]    = (time()>$value["leave_end_time"])?3:$value["leaveStatus"];
            $data[$keys]["leave_start_time"] = !empty($data[$keys]["leave_start_time"])?date('Y-m-d',$data[$keys]["leave_start_time"]):null;
            $data[$keys]["leave_end_time"]   = !empty($data[$keys]["leave_end_time"])?date('Y-m-d',$data[$keys]["leave_end_time"]):null;
            $data[$keys]["reject_note"]     = !empty($data[$keys]["reject_note"])?$data[$keys]["reject_note"]:"";
            // 处理会员请假状态
        }
        return $data;
    }
    public function gainLeaveStatus($startTime="",$endTime=""){
         if(time()<=$startTime){
               return 4;                   //已登记
         }
         return 1;                         // 请假中
    }
    /**
     * 会员端 - 判断会员是否有卡
     * @author xinwei <xinwei@itsport.club>
     * @create 2018/4/10
     * @param  accountId         //会员id
     * @return mixed         // 请求过来的数据
     */
    public function getMemberCard($accountId)
    {
        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        if ($members) {
            foreach ($members as $member) {
                $ids[] = $member['id'];
            }
        }
        $counts = count($ids);
        if ($counts > 100) {
            return ['code' => 0,'status' => 'error','message' => '会员记录过多，存在异常，请联系管理员!'];
        }
        $data = MemberCard::find()->where(['member_id' => $ids])->select('id')->asArray()->one();
        return $data;
    }
}