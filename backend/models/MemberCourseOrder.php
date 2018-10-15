<?php
namespace backend\models;

use \common\models\relations\MemberCourseOrderRelations;
use common\models\Func;
class MemberCourseOrder extends \common\models\MemberCourseOrder
{
    use MemberCourseOrderRelations;
    public $nowBelongId;
    public $nowBelongType;
    public $keywords;
    public $dateStart;
    public $dateEnd;
    public $coachId;
    public $venueId;
    public $searchDateStart;
    public $searchDateEnd;
    public $status;
    public $classType;
    const NOW_BELONG_ID   = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    const KEYWORDS        = 'keywords';
    const COACH_ID        = 'coachId';
    const VENUE_ID        = 'venueId';
    const STATUS          = 'status';
    const CLASS_TYPE      = 'classType';
    /**
     * 云运动 - 会员课程转让 - 订单详情
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/25
     * @param $memberInfo               //会员信息
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberOrder($memberInfo)
    {
        $query = MemberCourseOrder::find()
            ->alias('order')
            ->joinWith(['order cloudOrder' => function($query){
                $query->where(['cloudOrder.status' => 5,'cloudOrder.consumption_type' => 'charge']);
            }])
            ->joinWith(['chargeClass class'])
            ->joinWith(['memberCourseOrderDetails memberCourseOrderDetails'])
            ->where(['order.id'=>$memberInfo['chargeId']])
            ->andWhere(['order.member_id'=>$memberInfo['memberId']])
            ->select(
                "order.id,
                 order.member_id,
                 order.id as orderId,
                 order.course_amount,
                 order.money_amount,
                 order.overage_section,
                 order.create_at,
                 order.deadline_time,
                 order.note,
                 order.product_id,
                 order.private_id,
                 order.activeTime,
                 class.id as chargeId,
                 order.product_name as name,class.pic,
                 memberCourseOrderDetails.course_order_id,
                 memberCourseOrderDetails.original_price,
                 memberCourseOrderDetails.course_id,
                 cloudOrder.id as cloudOrderId,
                 cloudOrder.status as cloudOrderStatus
                ")
            ->asArray()->one();
        if($query !== null){
            $data = $query;
        }else{
            $data = MemberCourseOrder::find()
                ->alias('order')
                ->joinWith(['chargeClass class'])
                ->joinWith(['memberCourseOrderDetails memberCourseOrderDetails'])
                ->where(['order.id'=>$memberInfo['chargeId']])
                ->andWhere(['order.member_id'=>$memberInfo['memberId']])
                ->select(
                    "order.id,
                     order.id as orderId,
                     order.member_id,
                     order.course_amount,
                     order.money_amount,
                     order.overage_section,
                     order.create_at,
                     order.deadline_time,
                     order.note,
                     order.product_id,
                     order.private_id,
                     order.activeTime,
                     class.id as chargeId,
                     order.product_name as name,class.pic,
                     memberCourseOrderDetails.course_order_id,
                     memberCourseOrderDetails.original_price,
                     memberCourseOrderDetails.course_id,
                    ")
                ->asArray()->one();
        }
        unset($data['chargeClass']);
        unset($data['memberCourseOrderDetails']);
        //获取课程延期次数
        $delayTimes = $this->getCourseDelayNum($data['id'],$memberInfo['memberId']);
        $data['delayTimes'] = $delayTimes;
        return $data;
    }
    /**
     * 云运动 - 会员课程转让 - 订单详情
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/25
     * @param $memberId    //会员信息
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberChargeArray($memberId,$venueId,$type = '')
    {
        $query = MemberCourseOrder::find()
            ->alias('order')
            ->joinWith(['chargeClass class'],false)
            ->joinWith(['employeeS ee'],false)
            ->andFilterWhere(['ee.venue_id' => $venueId])
            ->where(['order.member_id'=>$memberId,'order.pay_status'=>1])
            ->select("
                order.id as orderId,
                order.course_amount,
                order.overage_section,
                order.product_id,
                order.create_at,
                class.id as chargeId,
                order.product_name as name,
                class.pic,
            ")
            ->asArray();
            if($type == 'bugCharge'){
                $query->andWhere(['and',
                    ['>','order.deadline_time',time()],
                    ['order.type'=>1],
                    ['>','overage_section',0]
                ]);
            }
            $data = $query->all();
            return $data;
    }

    /**
     * 云运动 - 会员订单 - 订单详情查询
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/25
     * @param $memberId         //会员id
     * @param $coachId          //教练id
     * @param $classId          //课程id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberOrderInfo($memberId,$coachId,$classId)
    {
        return MemberCourseOrder::find()
            ->alias('order')
            ->joinWith(['memberCourseOrderDetails details'],false)
            ->where(['order.member_id'=>$memberId])
            ->andWhere(['order.private_id'=>$coachId])
            ->andWhere(['details.course_id'=>$classId])
            ->select('order.id,details.id as detailsId')
            ->where(['order.pay_status'=>1])
            ->asArray()
            ->one();
    }

    /**
     * 后台 - 会员管理 - 私教数据遍历
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/2
     * @param $id int //会员Id
     * @return  mixed
     */
    public function getChargeClassData($id,$venueId)
    {
        $courseOrder = MemberCourseOrder::find()
            ->alias('mc')
            ->joinWith(['employeeS ee'])
//            ->joinWith(['chargeClass cc'])
            ->andFilterWhere(['ee.venue_id' => $venueId])
            ->where(['mc.member_id' => $id,'mc.pay_status'=>1])
            ->select(
                'mc.id,
                 mc.product_name,
                 mc.seller_id,
                 mc.product_id,
                 mc.overage_section,
                 mc.course_amount,
                 mc.create_at,
                 mc.deadline_time,
                 mc.money_amount,
                 mc.course_type,
                 mc.activeTime,
                 mc.type,
                 mc.private_id,
                 ee.name,
                ')
            ->orderBy(['mc.id' =>SORT_DESC])
            ->asArray();
        $data = Func::getDataProvider($courseOrder,8);
        return $data;
    }
    /**
     * 私教管理 - 私课排期 - 预约课程所有私课
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/2
     * @param $id
     * @return \yii\db\ActiveQuery
     */
    public function memberClassData($id)
    {
        $query = MemberCourseOrder::find()
            ->alias('mco')
            ->joinWith(['member member'])
            ->joinWith(['memberCourseOrderDetails memberCourseOrderDetails'])
            ->where(['mco.member_id' => $id,'mco.pay_status'=>1])
            ->asArray()->all();
        return $query;
    }
    /**
     * 私教管理 - 私课排期 - 预约课程选择产品
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/14
     * @param $id
     * @return \yii\db\ActiveQuery
     */
    public function ChooseClass($id)
    {
        $data = MemberCourseOrder::find()
            ->alias('mco')
            ->joinWith(['order order' => function($query){
                $query->where(['order.status' => 5,'order.consumption_type' => 'charge']);
            }])
            ->where(['mco.id' => $id])
            ->select('order.id,order.status')
            ->asArray()->one();
        if($data == null){
            $order = new CommonChange();
            $query = $order->handleChangeMemberClass($id,null,null);
            if($query == false){
                $model[] = ['status'=>'error','data'=>'操作失败'];
            }elseif($query == 'none'){
                $model[] = ['status'=>'error','data'=>'此课程已上完'];
            }elseif($query == 'aboutNone'){
                $model[] = ['status'=>'error','data'=>'此课程已预约完'];
            }
            $model[] = ['status'=>'success','data'=>$query];
        }else{
            $model[] = ['status'=>'error','data'=>'此课程已退费'];
        }
        return $model;
    }
    /**
     * 云运动 - 选择私课课程 - 接口
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/6/3
     * @param $id int
     * @param $str
     * @return string
     */
    public function getClassInfo($id,$venueId,$str = NULL)
    {
        if($str == 'app'){
            $alone = $this->getClassAloneInfo($id,$venueId);
            $many = $this->getClassManyInfo($id);
            return array_merge($alone,$many);
        }else{
            $data['alone'] = $this->getClassAloneInfo($id,$venueId);
            $data['many'] = $this->getClassManyInfo($id);
        }
        return $data;
    }
    /**
     * 后台会员管理 - 私课管理 - 选择以购买私课（单节课程）
     * @author Huanghua<Huanghua@itsports.club>
     * @create 2017/6/3
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getClassAloneInfo($id,$venueId)
    {
        $times = time();        //获取当前时间戳
        $data = MemberCourseOrder::find()
            ->alias('mco')
            ->joinWith(['member member'])
            ->joinWith(['chargeClass chargeClass'])
            ->joinWith(['memberCourseOrderDetails memberCourseOrderDetails'])
            ->where(['memberCourseOrderDetails.category'=>2])
            ->andWhere(['memberCourseOrderDetails.type' => 1])
            ->andWhere(['>','mco.deadline_time',$times])
            ->andWhere(['mco.member_id' => $id])
//            ->andWhere(['chargeClass.venue_id' => $venueId])
            ->andWhere(['mco.pay_status'=>1])
            ->andWhere(['!=','mco.overage_section',0])
            ->asArray()
            ->all();
        return $data;
    }
    /**
     * 后台会员管理 - 私课管理 - 选择以购买私课（多节课程）
     * @author Huanghua<Huanghua@itsports.club>
     * @create 2017/6/3
     * @param $id int
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getClassManyInfo($id)
    {
        $times = time();        //获取当前时间戳
        $data = MemberCourseOrder::find()
            ->alias('mco')
            ->joinWith(['member member'])
            ->joinWith(['memberCourseOrderDetails memberCourseOrderDetails'])
            ->where(['memberCourseOrderDetails.category'=>1])
            ->andWhere(['memberCourseOrderDetails.type' => 1])
            ->andWhere(['>','mco.deadline_time',$times])
            ->andWhere(['mco.member_id' => $id])
            ->andWhere(['mco.pay_status'=>1])
            ->andWhere(['!=','mco.overage_section',0])
            ->asArray()
            ->all();
        return $data;
    }

    /**
     * 验卡管理 - 会员私课 - 到期时间
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/7/12
     * @param $id
     * @return \yii\db\ActiveQuery
     */
    public function expireTime($id)
    {
        $time  = time()+ (15*24*60*60);
        $query = MemberCourseOrder::find()
            ->alias('mco')
            ->select("
            mco.product_name,
            mco.deadline_time
            ")
            ->where(['mco.member_id' => $id])
            ->andWhere(['mco.pay_status'=>1])
            ->andFilterWhere(['and',['>=','mco.deadline_time',time()],['<=','mco.deadline_time',$time]])
            ->asArray()->all();
        return $query;
    }

    /**
     *后台会员管理 - 会员详细信息 -  上课记录信息查询
     * @author Huang hua <huanghua@itsports.club>
     * @param  $id
     * @param  $cid
     * @create 2017/4/20
     * @return bool|string
     */
    public function MemberCourseOrderData($id,$cid)
    {
        $model = AboutClass::find()
            ->alias('ac')
            ->joinWith(['employee employee'],false)
            ->joinWith(['memberCourseOrderDetails mcod' => function($query){
                    $query->joinWith(['memberCourseOrder mco'],false);
                }],false)
            ->where(['ac.member_id' => $id,'ac.type' => '1'])
            ->andWhere(['mcod.course_order_id'=>$cid])
            ->select('ac.id,ac.start,ac.end,ac.status,ac.type,employee.name,mco.course_amount,mco.overage_section,mcod.category')
            ->asArray()->all();
        $query = AboutClass::find()
            ->alias('ac')
            ->joinWith(['employee employee'],false)
            ->joinWith(['chargeGroupClassB cgc' => function($query){
                $query->joinWith(['chargeClassNumber ccn'],false);
            }],false)
            ->where(['ac.member_id' => $id,'ac.type' => '3'])
            ->select('ac.id,ac.start,ac.end,ac.status,ac.type,employee.name,ccn.total_class_num as course_amount,ccn.attend_class_num as overage_section')
            ->asArray()->all();
        if(empty($model) && !empty($query)){
            return $query;
        }elseif(!empty($model) && empty($query)){
            return $model;
        }elseif(!empty($model) && !empty($query)){
            $data = array_merge($model,$query);
            return $data;
        }
        return null;
//        $dataProvider = Func::getDataProvider($data, 8000000);
    }

    /**
     * @私教统计 - 所有私教的卖课量统计
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/8/12
     * @param  $params
     * @return array
     */
    public function sellPrivateClassAll($params)
    {
        $this->customLoads($params);
        $query = MemberCourseOrder::find()
            ->alias('mco')
            ->joinWith(['employeeS ee'=>function($query){
                $query->joinWith(['organizations org'],false);
            }],false)
            ->joinWith(['chargeClass cc'],false)
            ->joinWith(['order order'=>function($query){
                $query->where(['order.consumption_type' => ['charge', 'chargeGroup']]);
            }],false)
            ->where(['mco.product_type' => 1])
            ->andWhere(['mco.pay_status' => 1])
            ->select(
                'mco.id,
                 mco.private_id,
                 ee.name as coachName,
                 ee.pic,
                 org.name as venueName,
                 sum(mco.course_amount) as num,
                 sum(mco.money_amount) as money,
                 count(mco.id) as memberNum,
                 ')
            ->groupBy('mco.private_id')
            ->asArray();
        $query = $this->searchWhere($query);
        return $query;
    }
    /**
     * @私教统计 - 所有私教的卖课量统计分页
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/8/15
     * @param  $query
     * @return array
     */
    public function getSumData($query)
    {
        return Func::getDataProvider($query,8);
    }
    /**
     * @私教统计 - 所有私教的卖课量统计 - 总计
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/8/15
     * @param  $query
     * @return array
     */
    public function getSumDataMoney($query)
    {
        $class = $query->all();
        $class['totalNum']   = array_sum(array_column($class,'num'));        //num节数，totalNum总节数
        $class['totalMoney'] = array_sum(array_column($class,'money'));      //money金额，totalMoney总金额
        $coachArr            = array_column($class,'private_id');            //取出所有私教id
        //第一种方法
        $memberNumArr        = array_column($class,'memberNum');             //取出所有会员量
        $class['finished']   = [];
        $class['unfinished'] = [];
        $class['ratio']      = [];
        foreach($coachArr as $key => $value){
            $data = MemberCourseOrder::find()
                ->alias('mco')
                ->joinWith(['employeeS ee'])
                ->joinWith(['order order'=>function($query){
                    $query->where(['order.consumption_type' => ['charge', 'chargeGroup']]);
                }],false)
                ->where(['mco.product_type' => 1,'mco.private_id' => $value,'mco.pay_status'=>1])
                ->andWhere(['or',['mco.course_type' => null],['mco.course_type' => 1]])
                ->andWhere(['>','mco.money_amount',0])
                ->select('mco.id,mco.product_id,mco.private_id,mco.member_id,mco.course_type')
//                ->groupBy('mco.member_id')
                ->asArray();
            if(isset($this->dateStart) && isset($this->dateEnd)){
                //搜索时间以订单时间为准
                $data->andFilterWhere(['and',['>=','order.order_time',$this->dateStart],['<=','order.order_time',$this->dateEnd]]);
            }
            $data->andFilterWhere(['ee.venue_id' => $this->venueId]);
            $datas = $data->count();
            if($datas >= 1){
                array_push($class['finished'],$datas);                               //成交量
                array_push($class['unfinished'],$memberNumArr[$key]-$datas);     //未成交
                array_push($class['ratio'],($datas/$memberNumArr[$key])*100);    //成交率
            }else{
                array_push($class['finished'],0);                                //成交量
                array_push($class['unfinished'],$memberNumArr[$key]);                //未成交
                array_push($class['ratio'],0);                                   //成交率
            }
        }

        //第二种方法
//        $class['memberNum']  = [];
//        $class['finished']   = [];
//        $class['unfinished'] = [];
//        $class['ratio']      = [];
//        foreach($coachArr as $key=>$value){
//            $data = MemberCourseOrder::find()
//                ->alias('mco')
////                ->joinWith(['chargeClass cc'],false)
//                ->where(['mco.product_type' => 1,'mco.private_id' => $value])
//                ->select('mco.id,mco.product_id,mco.private_id,mco.member_id,mco.course_type')
////                ->groupBy('mco.member_id')
//                ->asArray();
//            if(isset($this->dateStart) && isset($this->dateEnd)){
//                $data->andFilterWhere(['and',['>=','mco.create_at',$this->dateStart],['<=','mco.create_at',$this->dateEnd]]);
//            }
//            $member = $data->count();                                    //会员量
//            array_push($class['memberNum'],$member);                     //会员量
//            $done = $data->andWhere(['or',['mco.course_type' => null],['mco.course_type' => 1]])
//                ->andWhere(['>','mco.money_amount',0])->count();         //已成交
//            array_push($class['finished'],$done);                        //成交
//            array_push($class['unfinished'],$member-$done);              //未成交
//            array_push($class['ratio'],($done/$member)*100);             //成交率
//        }

        //第三种方法
//        $data = MemberCourseOrder::find()
//            ->alias('mco')
//            ->where(['mco.product_type' => 1,'mco.private_id' => $coachArr,'mco.pay_status'=>1])
//            ->select('mco.id,mco.product_id,mco.private_id,mco.member_id,mco.course_type')
//            ->groupBy('mco.member_id')
//            ->asArray();
//        if(isset($this->dateStart) && isset($this->dateEnd)){
//            $data->andFilterWhere(['and',['>=','mco.create_at',$this->dateStart],['<=','mco.create_at',$this->dateEnd]]);
//        }
//        $datas      = $data->all();                          //这些私教的所有数据
//        $privateArr = array_column($datas,'private_id');     //拿出私教一列
//        $member     = array_count_values($privateArr);       //每个私教id对应的会员量
//        $done = $data->andWhere(['>','mco.money_amount',0])->all();
////            ->andWhere(['or',['mco.course_type' => null],['mco.course_type' => 1]]);    //查询出已成交的数据
//        $doneArr = array_column($done,'private_id');          //拿出私教一列
//        $doneNum = array_count_values($doneArr);              //每个私教id对应的成交量
//        $class['finished']   = [];
//        $class['unfinished'] = [];
//        $class['ratio']      = [];
//        foreach($coachArr as $key=>$value){
////            array_push($class['memberNum'],$member[$value]);                           //会员量
//            if(isset($doneNum[$value])){
//                array_push($class['finished'],$doneNum[$value]);                       //成交量
//                array_push($class['unfinished'],$member[$value]-$doneNum[$value]);     //未成交
//                array_push($class['ratio'],($doneNum[$value]/$member[$value])*100);    //成交率
//            }else{
//                array_push($class['finished'],0);                                      //成交量
//                array_push($class['unfinished'],$member[$value]);                      //未成交
//                array_push($class['ratio'],0);                                         //成交率
//            }
//        }

        $class['memberNumTotal']  = array_sum(array_column($class,'memberNum'));      //总会员量
        $class['finishedTotal']   = array_sum($class['finished']);                    //总成交
        $class['unfinishedTotal'] = array_sum($class['unfinished']);                  //总未成交
        if($class['memberNumTotal'] == 0){
            $class['ratioTotal']  = 0;
        }else{
            $class['ratioTotal']  = ($class['finishedTotal']/$class['memberNumTotal'])*100;   //总成交率
        }
        return $class;
    }
    /**
     * @私教统计 - 获取已成交会员
     * @create 2017/8/12
     * @author zhumengke <zhumengke@itsports.club>
     * @return bool
     */
    public function getDeal()
    {
        $data =  MemberCourseOrder::find()
              ->alias('mco')
              ->where(['or',['mco.course_type'=>1],['mco.course_type'=>null]])
              ->andWhere(['>','mco.money_amount',0])
              ->andFilterWhere(['mco.private_id'=>$this->coachId])
              ->andWhere(['mco.pay_status'=>1])
              ->select('mco.*')
              ->asArray()->all();
        return array_column($data,'id');
    }

    /**
     * @私教统计 - 私教卖课量统计搜索字段
     * @create 2017/8/12
     * @author zhumengke <zhumengke@itsports.club>
     * @param $data
     * @return bool
     */
    public function customLoads($data)
    {
        if(isset($data['dateStart']) && $data['dateEnd']){
            $this->dateStart  = strtotime($data['dateStart'].' 00:00:00');
            $this->dateEnd    = strtotime($data['dateEnd'].' 23:59:59');
        }
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
        $this->venueId       = (isset($data[self::VENUE_ID]) && !empty($data[self::VENUE_ID]))?$data[self::VENUE_ID] : $venueIds;
        $this->keywords      = (isset($data[self::KEYWORDS]) && !empty($data[self::KEYWORDS])) ? $data[self::KEYWORDS] : null;
        $this->nowBelongId   = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->coachId       = (isset($data[self::COACH_ID]) && !empty($data[self::COACH_ID]))?$data[self::COACH_ID]: NULL;
        $this->status        = (isset($data[self::STATUS]) && !empty($data[self::STATUS]))?$data[self::STATUS]: NULL;
        $this->classType     = (isset($data[self::CLASS_TYPE]) && !empty($data[self::CLASS_TYPE]))?$data[self::CLASS_TYPE]: NULL;
    }

    /**
     * @私教统计 - 卖课量统计 - 获取课程类型（PT、HS、生日课）
     * @create 2017/10/18
     * @author zhumengke <zhumengke@itsports.club>
     * @return array
     */
    public function getCourseTypeTwo()
    {
        $data = MemberCourseOrder::find()
            ->alias('mco')
            ->where(['mco.course_type'=>2,'mco.pay_status'=>1])
            ->orWhere(['mco.course_type'=>null,'type'=>2,'mco.pay_status'=>1])
            ->select('mco.*')
            ->asArray()->all();
        return array_column($data,'id');
    }
    public function getCourseTypeThree()
    {
        $data = MemberCourseOrder::find()
            ->alias('mco')
            ->where(['mco.course_type'=>3,'mco.pay_status'=>1])
            ->orWhere(['mco.course_type'=>null,'mco.type'=>3,'mco.pay_status'=>1])
            ->select('mco.*')
            ->asArray()->all();
        return array_column($data,'id');
    }
    public function getCourseTypeFour()
    {
        $data = MemberCourseOrder::find()
            ->alias('mco')
            ->where(['mco.course_type'=>4,'mco.pay_status'=>1])
            ->orWhere(['mco.course_type'=>null,'mco.type'=>4,'mco.pay_status'=>1])
            ->select('mco.*')
            ->asArray()->all();
        return array_column($data,'id');
    }

    /**
     * @私教统计 - 私教搜索字段
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/8/11
     * @param  $query
     * @return array
     */
    public function searchWhere($query)
    {
        $query->andFilterWhere(['and',['>=','order.order_time',$this->dateStart],['<=','order.order_time',$this->dateEnd]]);
        $query->andFilterWhere(['like','ee.name',$this->keywords]);
        $query->andFilterWhere(['ee.venue_id' => $this->venueId]);
        $query->andFilterWhere(['mco.private_id' => $this->coachId]);
        if(!empty($this->classType)){
            $two   = $this->getCourseTypeTwo();
            $three = $this->getCourseTypeThree();
            $four  = $this->getCourseTypeFour();
            if($this->classType == 2){
                $query->andWhere(['mco.id'=>$two]);      //HS 2
            }elseif($this->classType == 3){
                $query->andWhere(['mco.id'=>$three]);    //生日课 3
            }elseif($this->classType == 4){
                $query->andWhere(['mco.id'=>$four]);    //生日课 3
            }else{
                $query->andWhere(['and',['NOT IN','mco.id',$two],['NOT IN','mco.id',$three],['NOT IN','mco.id',$four]]);    //PT 1
            }
        }
        if(!empty($this->status)){
            $arrId = $this->getDeal();
            if($this->status == 2){
                $query->andWhere(['NOT IN','mco.id',$arrId]);     //未成交
            }else{
                $query->andWhere(['mco.id'=>$arrId]);             //成交
            }
        }
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['ee.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['ee.venue_id'=>$this->nowBelongId]);
//        }
        return $query;
    }
    /**
     * @私教统计 - 某个私教私教的卖课量统计详情
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/15
     * @param $params
     * @return int
     */
    public function sellPrivateClassOne($params)
    {
        $this->customLoads($params);
//        $query = \backend\models\Member::find()
//            ->alias('member')
//            ->joinWith(['memberDetails md'],false)
//            ->joinWith(['memberCourseOrder mco'=>function($query){
//                $query->joinWith(['memberCourseOrderDetails mcod'],false);
//                $query->joinWith(['employee ee'],false);
//                $query->joinWith(['memberCardS mc'],false);
//                $query->joinWith(['chargeClass cc'],false);
//            }],false)
//            ->where(['mco.product_type' => 1,'mco.private_id' => $this->coachId])
//            ->select(
//                'mco.id,
//                 mco.type,
//                 mco.create_at,
//                 mco.member_id,
//                 md.name,
//                 mc.card_number,
//                 mcod.product_name,
//                 mcod.course_name,
//                 mco.course_type,
//                 mco.course_amount as courseNum,
//                 mco.money_amount as courseMoney,
//                 ')
//            ->groupBy('mco.id')
//            ->asArray();

        $query = MemberCourseOrder::find()
            ->alias('mco')
            ->joinWith(['member member'=>function($query){
                $query->joinWith(['memberDetails md'],false);
            }],false)
            ->joinWith(['memberCourseOrderDetails mcod'],false)
            ->joinWith(['employeeS ee'],false)
            ->joinWith(['memberCardS mc'],false)
            ->joinWith(['chargeClass cc'],false)
            ->joinWith(['order order'=>function($query){
                $query->where(['order.consumption_type' => ['charge', 'chargeGroup']]);
            }],false)
            ->where(['mco.product_type' => 1,'mco.private_id' => $this->coachId,'mco.pay_status'=>1])
            ->select(
                'mco.id,
                 mco.type,
                 mco.create_at,
                 mco.member_id,
                 md.name,
                 mc.card_number,
                 mcod.product_name,
                 mcod.course_name,
                 mco.course_type,
                 mco.money_amount,
                 mco.course_amount as courseNum,
                 mco.money_amount as courseMoney,
                 ')
            ->groupBy('mco.id')
            ->asArray();
        $query = $this->searchWhere($query);
        return $query;
    }
    /**
     * @私教统计 - 某个私教私教的卖课量统计详情 - 总计
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/8/15
     * @param  $query
     * @return array
     */
    public function getCountDataMoney($query,$total)
    {
        $class = $query->all();
        $class['totalNum']   = array_sum(array_column($class,'courseNum'));
        $class['totalMoney'] = array_sum(array_column($class,'courseMoney'));
        $class['finished']   = [];
        foreach($class as $key=>$value){
            if(isset($value['money_amount'])){
                if($value['money_amount'] > 0){
                    array_push($class['finished'],1);   //1代表成交
                }else{
                    array_push($class['finished'],0);   //0代表未成交
                }
            }
        }
//        $idArr = array_column($class,'id');
//        $class['finished']   = [];
//        foreach($idArr as $key=>$value){
//            $order = MemberCourseOrder::findOne(['id' => $value]);
//            if(($order['course_type'] == 1 || $order['course_type'] == null) && $order['money_amount'] > 0){
//                array_push($class['finished'],1);   //1代表成交
//            }elseif (($order['course_type'] == 2 || $order['course_type'] == 3) && $order['money_amount'] == 0){
//                array_push($class['finished'],0);   //0代表未成交
//            }
//        }
        $class['finishedTotal']   = array_sum($class['finished']);     //总成交
        $class['unfinishedTotal'] = $total-$class['finishedTotal'];    //总未成交
        if($total == 0){
            $class['ratioTotal']  = 0;
        }else{
            $class['ratioTotal']  = ($class['finishedTotal']/$total)*100;      //总成交率
        }
        return $class;
    }

    /**
     * @私教统计 - 卖课量统计图(日、周、月)
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/14
     * @param $param
     * @return int
     */
    public function sellClass($param)
    {
        $this->autoLoad($param);
        $query = MemberCourseOrder::find()
            ->alias('mco')
            ->joinWith(['employee ee'],false)
            ->joinWith(['chargeClass cc'],false)
            ->joinWith(['memberCourseOrderDetails mcod'],false)
            ->where(['and',['>=','mco.create_at',$this->searchDateStart],['<=','mco.create_at',$this->searchDateEnd]])
            ->andWhere(['mco.product_type' => 1,'mco.pay_status'=>1])
            ->select('mco.create_at,mco.course_amount')
            ->asArray();
        $this->searchWhere($query);
        $query = $query->all();
        if($param['timeType'] == 'd'){
            $data = $this->sellClassDay($query);
        }elseif ($param['timeType'] == 'w'){
            $data = $this->sellClassWeek($query);
        }else{
            $data = $this->sellClassMonth($query);
        }
        return $data;
    }
    /**
     * @私教统计 - 卖课量统计图(日)
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/14
     * @param $query
     * @return int
     */
    public function sellClassDay($query)
    {
        $sellData = $this->sellClassDayTime();
        if(isset($query)&&!empty($query)){
            foreach($query as $keys=>$values){
                $sellDate                      = intval(date("H",$values['create_at']));
                $sellData['sellDay'.$sellDate] = $sellData['sellDay'.$sellDate]+$values['course_amount'];
            }
        }
        return $sellData;
    }

    /**
     * @私教统计 - 卖课量统计图(周)
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/14
     * @param $query
     * @return int
     */
    public function sellClassWeek($query)
    {
        $sellData = $this->sellClassWeekTime();
        if(isset($query)&&!empty($query)){
            foreach($query as $keys=>$values){
                $sellDate                       = intval(date("w",$values['create_at']));
                $sellData['sellWeek'.$sellDate] = $sellData['sellWeek'.$sellDate]+$values['course_amount'];
            }
        }
        return $sellData;
    }

    /**
     * @私教统计 - 卖课量统计图(月)
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/14
     * @param $query
     * @return int
     */
    public function sellClassMonth($query)
    {
        $sellData = $this->sellClassMonthTime();
        if(isset($query)&&!empty($query)){
            foreach($query as $keys=>$values){
                $sellDate                        = intval(date("d",$values['create_at']));
                $sellData['sellMonth'.$sellDate] = $sellData['sellMonth'.$sellDate]+$values['course_amount'];
            }
        }
        return $sellData;
    }

    /**
     * @私教统计 - 初始化日期数据
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/14
     * @param $param
     */
    public function autoLoad($param)
    {
        if(isset($param['timeType']) && $param['timeType'] == 'd'){
            $this->searchDateStart = strtotime(Func::getGroupClassDate($param['timeType'],true));
            $this->searchDateEnd   = strtotime(Func::getGroupClassDate($param['timeType'],false));
        }elseif(isset($param['timeType']) && $param['timeType'] == 'w'){
            $this->searchDateStart = strtotime(Func::getGroupClassDate($param['timeType'],true));
            $this->searchDateEnd   = strtotime(Func::getGroupClassDate($param['timeType'],false));
        }elseif(isset($param['timeType']) && $param['timeType'] == 'm'){
            $this->searchDateStart = strtotime(Func::getGroupClassDate($param['timeType'],true));
            $this->searchDateEnd   = strtotime(Func::getGroupClassDate($param['timeType'],false));
        }
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
        $this->venueId       = (isset($param[self::VENUE_ID]) && !empty($param[self::VENUE_ID])) ? $param[self::VENUE_ID] : $venueIds;
        $this->nowBelongId   = (isset($param[self::NOW_BELONG_ID]) && !empty($param[self::NOW_BELONG_ID])) ? $param[self::NOW_BELONG_ID] : null;
        $this->nowBelongType = (isset($param[self::NOW_BELONG_TYPE]) && !empty($param[self::NOW_BELONG_TYPE])) ? $param[self::NOW_BELONG_TYPE] : null;
    }

    /**
     * @私教统计 - 统计一天之内的卖课量(组装初始数据)
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/13
     * @return array
     */
    public function sellClassDayTime(){
        $data = [];
        for($i=0;$i<=23;$i++){
            $key = "sellDay".$i;
            $data[$key] = 0;
        }
        return $data;
    }

    /**
     * @私教统计 - 统计一周之内的卖课量(组装初始数据)
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/14
     * @return array
     */
    public function sellClassWeekTime(){
        $dateData = [];
        for($i=1;$i<=7;$i++){
            $key = "sellWeek".$i;
            $dateData[$key] = 0;
        }
        return $dateData;
    }

    /**
     * @私教统计 - 统计一月之内的卖课量(组装初始数据)
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/14
     * @return array
     */
    public function sellClassMonthTime()
    {
        $dateData = [];
        for($i=1;$i<=31;$i++){
            $key = "sellMonth".$i;
            $dateData[$key] = 0;
        }
        return $dateData;
    }


    /**
     * @云运动 - 后台 - 查询一条订单
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/11/11
     * @param $id
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function  getClassOrderData($id)
    {
        $query = MemberCourseOrder::find()
            ->alias("mco")
            ->joinWith(["member member"=>function($query){
                $query->joinWith(["memberDetails memberDetails"],false);
            }],false)
            ->joinWith(["employeeS ee"],false)
          ->joinWith(['order order'],false)
            /*->joinWith(['order order' => function($query){
                $query->where(['order.consumption_type' => 'charge'])
                    ->andWhere(['<>','order.status', 5]);
            }],false)*/
            ->where(['mco.id'=>$id])
            ->select(
                "
                member.mobile,
                member.id as memberId,
                memberDetails.sex,
                memberDetails.name,
                memberDetails.pic,
                mco.id,
                mco.product_name,
                mco.business_remarks,
                mco.type,
                mco.course_amount,
                mco.money_amount,
                ee.name as personalName,
                mco.create_at,
                order.many_pay_mode,
                order.pay_money_mode,
                ")
            ->asArray()
            ->one();
        return $query;
    }

    /**
     * @desc: 业务后台 - 会员私教课程 - 获取私教课程延期次数
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/03/14
     * @param $id
     * @param $memberId
     * @return int|string
     */
    public function getCourseDelayNum($id,$memberId)
    {
        $delayTimes = ExtensionRecord::find()
            ->where([
            'and',
            ['course_order_id'=>$id],
            ['member_id'=>$memberId]
            ])
            ->asArray()->count();
        return $delayTimes;
    }
}