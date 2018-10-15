<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/25
 * Time: 14:58
 */

namespace backend\modules\v1\models;

use backend\models\ClassRecord;
use backend\models\Course;
use backend\models\CoursePackageDetail;
use backend\models\MemberCourseOrder;
use backend\modules\v1\models\Member;
use common\models\base\AboutClass;
use common\models\base\MemberCard;
use common\models\relations\ChargeClassRelations;
use common\models\relations\MemberCourseOrderDetailsRelations;

class ChargeClass extends  \common\models\base\ChargeClass
{
    use ChargeClassRelations;
    public $course;  //课种ID
    public $venueId;  //场馆ID
    public $memberId;   //会员id
    public static $cid;
    public $chargeIdArr = array();    //产品id数组
    public $chargeId = 'true';      //表示产品id存在
    public $endPage;                  // 判断当前页是否最后一页
    public $venueName;
    public $priceShow;                // 价格是否显示

    public $isNewMember;  // 是否是新会员
    const CHARGE_PIC = 'http://oo0oj2qmr.bkt.clouddn.com/128432235.jpg?e=1498813979&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:bEAj4Ds31eV7hOHvPU6idkJnYKU=';
    const PIC = 'http://oo0oj2qmr.bkt.clouddn.com/blurred-background-1@3x.png?e=1499053675&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:Jo7qD9pH1IdE75al-SlQvlt4wqk=';
    /**
     * 云运动 - api接口 - 获取数据（所有私教课程）
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param  $data //处理过滤参数
     * @return array|\yii\db\ActiveRecord[]
     */
    public function loadParams($data)
    {
        $this->venueId   = isset($data['venueId'])?$data['venueId'] : null;
        $this->course    = isset($data['course'])? $data['course'] : null;
//        $this->venueName = !empty($data['venueName'])?$data['venueName'] : null;
//        if(empty($this->venueId)&&!empty($this->venueName)&&($this->venueName=="maibu")){
//               $venueId = GroupClass::getVenueId();
//               $this->venueId = $venueId["id"];
//        }
        if (!empty($this->course)) {
            $this->getCoursePackageDetail();
        }
        self::$cid = $this->course;
        return true;
    }

    /**
     * 云运动 - api接口 - 获取课种的产品id
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCoursePackageDetail()
    {
        $data = \common\models\base\CoursePackageDetail::find()->where(['course_id' => $this->course])->select('charge_class_id')->asArray()->all();
        if (empty($data)) {
            $this->chargeId = 'false';      //课种没有，对应的产品
        } else {
            foreach ($data as $k => $v) {
                $this->chargeIdArr[] = $v['charge_class_id'];   //产品id数组
            }
        }
    }

    /**
     * 云运动 - api接口 - 获取数据（所有私教课程）
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param  params //传的过滤参数
     * @return array|\yii\db\ActiveRecord[]
     */
    public function search($params)
    {
        if(($params["type"]=="maibu")&&(empty($params["versionNum"]))){
            return "upgrade";
        }
        if(empty($params["type"])||$params["type"]!=="maibu"){
            $this->endPage =2;
            return "";
        }
        $this->loadParams($params);
        if ($this->chargeId == 'false') {
            return $query = NULL;
        }
        $query = ChargeClass::find()          //查询收费课程表
                ->alias("private")
                 ->joinWith(["coursePackageDetails coursePackageDetails"=>function($query){
                        /*$query->andWhere([">","coursePackageDetails.original_price",0]);*/
                 }],false);
        $query = $query
                 ->select('
                         private.id,
                         private.sale_start_time,
                         private.sale_end_time,
                         private.name,
                         private.pic,
                         private.original_price,
                         private.total_amount,
                         private.type,
                         private.app_amount,
                         private.app_original_price,
                         private.show,
                         count(coursePackageDetails.id) as num,
                         '
                         )
                 ->groupBy("private.id")
                 ->orderBy(["private.id"=>SORT_DESC])
                 ->Having([">","num",0]);
        $query  = $this->getWhere($query);
        $maxNum = $query->count();
        // 分段加载
        $params["page"] = (!isset($params["page"])||empty($params["page"]))?1:$params["page"]+1;
        $page  = $params["page"]*10;
        $this->endPage = ($page>=$maxNum)?2:1;
        if(!empty($params["requestSign"])){   //不为空 表示只请求  两条数据
            $query = $query->limit(2);
        }else{
            $query = $query->limit($page);       // 为空  按照标识  分页加载
        }
        $query = $query->all();
        return $query;
    }
    /**
     * 会员端 - api接口 - 获取数据（所有私教课程不分页）
     * @author 辛威<xinwei@sports.club>
     * @create 2018/6/6
     * @param  params //传的过滤参数
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAllPrivateCourse($params)
    {
        $this->loadParams($params);
        if ($this->chargeId == 'false') {
            return $query = NULL;
        }
        $query = ChargeClass::find()          //查询收费课程表
        ->alias("private")
            ->joinWith(["coursePackageDetails coursePackageDetails"=>function($query){
            }],false);
        $query = $query
            ->select('
                         private.id,
                         private.sale_start_time,
                         private.sale_end_time,
                         private.name,
                         private.pic,
                         private.original_price,
                         private.total_amount,
                         private.type,
                         private.app_amount,
                         private.app_original_price,
                         private.show,
                         count(coursePackageDetails.id) as num,
                         '
            )
            ->groupBy("private.id")
            ->orderBy(["private.id"=>SORT_DESC])
            ->Having([">","num",0]);
        $query  = $this->getWhere($query);
        $query = $query->all();
        return $query;
    }
    /**
     * 云运动 - api接口 - 获取搜索条件
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param  $query //传的对象
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getWhere($query)
    {

        $query->where(["and",["and",["<=","private.sale_start_time",time()],[">=","private.sale_end_time",time()]],['private.status' => 1]])       // 当前时间 在销售中
            ->andFilterWhere(['private.group'=>1])
            ->andFilterWhere(['in', 'private.id', $this->chargeIdArr])
            ->andFilterWhere(['private.venue_id' => $this->venueId]);
        return $query;
    }

    /**
     * 云运动 - api接口 - 过滤
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();
        $fields['classCount'] = function () {
            $data = $this->getChargeTotalNum($this->id);
            return $data;                                            //返回总课程量
        };
        if($this->type == 2)
        {
                $fields['totalPrice'] = function () {
//                    $data = $this->getChargeClassOne();
//                    $data = $this->getCharge($data);
//                    $data['totalPrice'] = isset($data['totalPrice'])&&!empty($data['totalPrice'])?$data['totalPrice']:"暂无数据";
//                    return $data['totalPrice'];                            //返回课程套餐总价
                    // 按照新需求逻辑写
                     $price  = $this->gainAppCoursePrice($this->id,$this->show);
                     return $price;
                };
        }
        if($this->type == 1) {
//            if (!empty($this->total_amount)) {
//                $fields['totalPrice'] = function () {
//                    return $this->total_amount;
//                };
//            } else {
//                $fields['totalPrice'] = function () {
//                    return $this->original_price;
//                };
//            }
            // 按照新需求逻辑写
            $fields['totalPrice'] = function () {
                    $charge = ["show"=>$this->show,"app_amount"=>$this->app_amount,"app_original_price"=>$this->app_original_price];
                    $price  = $this->priceJudge($charge);
                    return $price;
                };
        }
        // 图片重新赋值
//            $fields['pic'] = function () {
//                $detail = CoursePackageDetail::find()->where(['charge_class_id' => $this->id])->asArray()->one();
//                if ($detail && isset($detail['course_id'])) {
//                    $pic = Course::find()->where(['id' => $detail['course_id']])->asArray()->one();
//                } else {
//                    return null;
//                }
//                return !empty($pic['pic'])?$pic['pic']:self::CHARGE_PIC;
//            };
//        $fields['pic'] = !empty($pic['pic'])?$pic['pic']:self::CHARGE_PIC;
        $fields['charge'] = function () {
            $data = $this->getChargeClassOne();
            $data = $this->getChargeClassApiDate($data);
            $data['classNameNum'] = isset($data['classNameNum'])&&!empty($data['classNameNum'])?$data['classNameNum']:"暂无数据";
            return $data['classNameNum'];                            //返回课程名和数量组成的数组
        };
        $fields['score'] = function () {
            $data = $this->getFieldsByOne($v = [], 'score');
            return $data['score'];                                     //返回评论星级图的数量
        };
        $fields['scoreImg'] = function () {
            $data = $this->getFieldsByOne($v = [], 'score');
            return $data['scoreImg'];                                   //返回评论星级图
        };
        // 接口临时修正（线上）
        $fields['tag'] = function () {
            $data  = ["塑形","减脂"];
            return $data;                                                //返回特色类型死数据
        };
        unset($fields['total_amount'], $fields['original_price']);
        return $fields;
    }
    /**
     * 云运动 - api接口 - 单节私教课课程价格修正
     * @author houkaixn<houkaixn@sports.club>
     * @create 2017/12/29
     * @param $id    //私教课程id
     * @param $show  // 课程价格是否显示
     * @return int|mixed
     */
    public function gainAppCoursePrice($id,$show){
          $data = \common\models\base\CoursePackageDetail::findOne(["charge_class_id"=>$id]);
          $price = empty($data->app_original)?"":$data->app_original;
          if($show!=2){
              $price = "";
          }
          return $price;
    }
    /**
     * 云运动 - api接口 - cloud_course_package_detail
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $id //私教课程表id(收费课程表id)
     * @return int|mixed  总课量
     */
    public function getChargeTotalNum($id)
    {
        $data = CoursePackageDetail::find()
            ->select('sum(course_num) as course_num')
            ->where(['charge_class_id' => $id])->asArray()->all();
        return isset($data[0]['course_num']) ? $data[0]['course_num'] : 1;
    }

    /**
     * 云运动 - api接口 - 获取数据
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $data array
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChargeClassApiDate($data)
    {
        if (isset($data) && !empty($data) && is_array($data)) {
            $html = '';                                                    //定义变量
            foreach ($data as &$v) {
                if (!empty($v['course_num'])) {
                    $html .= $v['name'] . '课程' . $v['course_num'] . '节' . ' /';    //拼接字符串
                } else {
                    $html .= $v['name'] . '课程' . '1节' . ' /';    //拼接字符串
                }
            };
            $data['classNameNum'] = $html;                                  //给数组赋值
            $data['classNameNum'] = rtrim($data['classNameNum'], "/");      //去掉最后一个 ’/‘
        }
        return $data;
    }

    /**
     * 云运动 - api接口 - 查询团课详情
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $data array
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getGroupClassApiDate($data)
    {
        if (isset($data) && !empty($data) && is_array($data)) {
            foreach ($data as &$v) {
                $v['start'] = date('H:i', $v['start']);
                $v['end'] = date('H:i', $v['end']);
                $v['name'] = $this->getFieldsByOne($v, 'course');
                $coach = $this->getFieldsByOne($v, 'coach');
                $v['coach'] = $coach['name'];
                $v['about'] = $this->getFieldsByOne($v, 'about');
                $v['classroom'] = $this->getFieldsByOne($v, 'classroom');
                unset($v['course_id'], $v['created_at'], $v['coach_id'], $v['classroom_id'], $v['classroom_id']);
            };
        }
        return $data;
    }

    /**
     * 云运动 - api接口 - 获取处理数据（处理评论图片）
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $v  array
     * @param $type string
     * @return array|\yii\db\ActiveRecord[]  //星星图处理
     */
    public function getFieldsByOne($v, $type)
    {
        switch ($type) {
            case 'score':
                $score = isset($v['score']) ? $v['score'] : 4;
                $scoreArr = [];
                $scoreArr['score'] = $score;
                $key = ['one', 'two', 'three', 'four', 'five'];
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $score) {
                        $scoreArr['scoreImg'][$key[$i - 1]] = 'http://oo0oj2qmr.bkt.clouddn.com/x1.png?e=1497241578&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-mWeOtqLBC56lg63U-1EZN17xhI=';
                    } else {
                        $scoreArr['scoreImg'][$key[$i - 1]] = 'http://oo0oj2qmr.bkt.clouddn.com/x2.png?e=1497241610&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dh27FM6Djr3vNdMcJ-DXSkxBnp4=';
                    }
                }
                return $scoreArr;
            default:
                ;
        }
    }

    /**
     * 云运动 - api接口 - 查询cloud_course_package_detail"课程套餐详情表
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChargeClassOne()
    {
        //查询课量，课程id,时长，单售价（课程套餐详情表），课种名(课种表)；
        $data = CoursePackageDetail::find()
            ->alias('cpd')
//            ->select('
//                cpd.course_num,cpd.course_id,cpd.id,cpd.charge_class_id,cpd.course_length,cpd.original_price,cpd.category,
//                course.name,cpd.app_original'
//            )
            ->select('
                cpd.course_num,cpd.course_id,cpd.id,cpd.charge_class_id,cpd.course_length,cpd.category,
                course.name,cpd.app_original as original_price'
            )
            ->joinWith(['course course'], false)
            ->andFilterWhere(['cpd.course_id' => self::$cid])
            ->where(['cpd.charge_class_id' => $this->id])->asArray()->all();
        $data = $this->controlAppOriginalPrice($data);
        return $data;
    }
    /**
     * 云运动 - api接口 - 控制多课程单节原价显示问题
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $data   //私教价格显示问题
     * @return array|\yii\db\ActiveRecord[]
     */
    public function controlAppOriginalPrice($data){
        if(empty($data)){
            return [];
        };
        foreach($data as $keys=>$value){
            $data[$keys]["original_price"] = isset($data[$keys]["original_price"])?$data[$keys]["original_price"]:"";
            if($this->priceShow!=2){
                $data[$keys]["original_price"] = "";
            }
        }
        return $data;
    }
    /**
     * 云运动 - api接口 - 查询私教套餐详情
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param  $id int //订单ID
     * @param  $memberId int //会员Id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChargeDetail($id, $memberId = 0)
    {
        $this->id = $id;
        $charge = $this->getMemberOrderDetails();                                //获取订单详细信息
        if (!empty($charge)) {
            //1.处理星星
            $score = $this->getFieldsByOne($v = [],'score');                    //处理评论图片
            $charge['score'] = $score['score'];                                  //去除图为x1的数量
            $charge['scoreImg'] = $score['scoreImg'];                            //取出图的数组
            //2.处理预约第几节课程
            $aboutNum = $this->getAboutNum($charge['memberCourseOrderDetails'],$memberId); //返回预约和上过的总数
            if($aboutNum == 0)
            {
                $charge['aboutNum']     = 1;                                      //提示预约第一节课
                $charge['orderStatus']  = true;                                   //课程未用完
            }else if($aboutNum != $charge['course_amount'] && $aboutNum < $charge['course_amount']){
                $charge['aboutNum']     = $aboutNum + 1;                          //预约节数+1
                $charge['orderStatus']  = true;                                   //课程未用完
            }else if($aboutNum == $charge['course_amount']){
                $charge['orderStatus']  = false;                                  //课程已用完
                $charge['aboutNum']     = 0;                                      //没有课程了
            }
            if (empty($charge['pic'])) {
                $charge['pic'] = "";
            }
            if (empty($charge['desc'])) {
                $charge['desc'] = '健身不仅仅可以让自己的身体变得唯美,而且可以变得更加健康,提高自我生活质量,相信这节课你会体会到不一样的自己!';
            }
        }
        $charge = $this->dealChargeClass($charge,"buyCourse");
        return $charge;
    }
    public function getAboutNum($data,$memberId)
    {
        // 判断初始数据 是否为0
        $num = 0;
        if(empty($data)){
            return  0;
        }
        foreach ($data as $k=>$v)
        {
            $num += AboutClass::find()->where(["and",['class_id'=>$v['id']],["member_id"=>$memberId]])->andWhere(['<>','status',2])->count();
        }
        return $num;
    }
    /**
     * 云运动 - api接口 - 查询私教订单套餐详情
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/6/14
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberOrderDetails()
    {
       $data = MemberCourseOrder::find()
            ->alias('order')
            ->where(['order.id' => $this->id])
            ->joinWith(["memberCard memberCard"=>function($query){
                $query->select("memberCard.create_at,memberCard.member_id");
            }])
            ->joinWith(['chargeClass class' => function ($query) {
                $query->joinWith(['organization or'], false);
                $query->joinWith(["chargeClassPrice chargeClass"]);
            }])
            ->joinWith(['employeeS e'],false)
            ->joinWith(['memberCourseOrderDetails details'])
            ->select('
                order.private_id,
                order.id,
                order.member_id,
                order.create_at,
                order.product_name,
                order.course_amount,
                order.overage_section,
                order.course_amount,
                class.type as category,
                ("") as money_amount,
                order.product_id,
                details.course_order_id,
                details.course_id,
                details.pic,
                details.desc,
                class.id as chargeClassId,
                class.venue_id,
                or.name as venueName,or.address as venueAddress,
                or.longitude,or.latitude,
                e.name
              ')
            ->asArray()
            ->one();
        //里边价格控制为不显示（memberCourseOrderDetails）
        $data  = $this->memberCourseOrderDetailsPrice($data);
        foreach ($data["memberCourseOrderDetails"] as $key=>$value){
            $num = AboutClass::find()->where(['class_id'=>$value['id'],'status'=>4])->count();
            $data["memberCourseOrderDetails"][$key]['clickable'] = false;
            if ($num > 0 ){
                $data["memberCourseOrderDetails"][$key]['course_num']  =  $value['course_num']-$num.'/'.$value['course_num'];
                $data["memberCourseOrderDetails"][$key]['clickable'] = true;
            }
            $data["memberCourseOrderDetails"][$key]['class_sike']  = AboutClass::find()->select('id,class_id,member_id,class_date')->where(['class_id'=>$value['id'],'status'=>4])->asArray()->all();
        }
        $data["intervalArr"] = $data["chargeClass"]["chargeClassPrice"];    // 重新组装值
        if((count($data["memberCard"])!=0)&&(time()<=($data["memberCard"][0]["create_at"]+24*60*60))){            // 24小时之内购买卡
           $data["newMember"] = true;
        }else{
           $data["newMember"] = false;   //没卡不是新会员
        }
        $data["longitude"] = !empty($data["longitude"])?$data["longitude"]:"113.675505";     // 精度
        $data["latitude"] = !empty($data["latitude"])?$data["latitude"]:"34.763269";         // 维度
        unset($data["memberCard"]);     // 去除冗余数据（会员卡）
        unset($data["chargeClass"]);    // 去除冗余数据（私课）
        return $data;
    }
    /**
     *  云运动 - api接口 - 价格控制显示问题
     * @author houkaixin<houkaixin@sports.club>
     * @param $data     // 需要处理的参数
     * @create 2017/12/29
     * @return array|null|\yii\db\ActiveRecord
     */
    public function memberCourseOrderDetailsPrice($data){
          foreach ($data["memberCourseOrderDetails"] as $key=>$values){
              $data["memberCourseOrderDetails"][$key]["original_price"] = "暂无数据";
          }
        return $data;
    }
    /**
     *  云运动 - api接口 - 查询上课记录表
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $id //多态id
     * @param $memberId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberClassRecordAll($id, $memberId)
    {
        return ClassRecord::find()
            ->where(['multiple_type' => 'charge'])
            ->andWhere(['multiple_id' => $id])
            ->andWhere(['member_id' => $memberId])
            ->andWhere(['status' => 1])
            ->all();
    }

    /**
     *  云运动 - api接口 - 查询上课记录表
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $id //多态id
     * @param $memberId // 会员ID
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberAboutClassAll($id, $memberId)
    {
        return AboutClass::find()
            ->where(['type' => (int)1])
            ->andWhere(['class_id' => $id])
            ->andWhere(['member_id' => $memberId])
            ->andWhere(['status' => 1])
            ->all();
    }

    /**
     *  云运动 - api接口 - 查询课程节数
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $data //array
     * @param $memberId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getAboutClassIdByCharge($data, $memberId)
    {
        if (isset($data) && !empty($data) && is_array($data)) {
            foreach ($data as &$v) {
                if ($num = count($this->getMemberAboutClassAll($v['id'], $memberId))) {
                    if ($num >= $v['course_num']) {
                        continue;
                    } else {
                        return $v['id'];
                    }
                } else {
                    return $v['id'];
                }
            };
        }
        return [];
    }

    /**
     *  云运动 - api接口 - 查询课程节数
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $data //array
     * @param $memberId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getClassRecordIdByCharge($data, $memberId)
    {
        if (isset($data) && !empty($data) && is_array($data)) {
            foreach ($data as &$v) {
                $number = count($this->getMemberAboutClassAll($v['id'], $memberId));
                if (!$number) {
                    return $v['id'];
                } else {
                    $num = count($this->getMemberClassRecordAll($v['id'], $memberId));
                    if ($number > $num) {
                        continue;
                    } else {
                        if ($num) {
                            if ($num >= $v['course_num']) {
                                continue;
                            } else {
                                return $v['id'];
                            }
                        } else {
                            return $v['id'];
                        }
                    }
                }
            };
        }
        return [];
    }

    /**
     * 云运动 - api接口 - 计算每个课程的总价格
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $data
     * @return mixed
     */
    public function getCharge($data)
    {
        $classTotalPrice = 0;
        if (isset($data) && !empty($data) && is_array($data)) {
            foreach ($data as &$v) {
                $v['classTotalPrice'] = (empty($v['course_num']) ? 1 : $v['course_num']) * $v['original_price'];
                $classTotalPrice += $v['classTotalPrice'];
                unset($v['course']);
            };
            $data['totalPrice'] = $classTotalPrice;
        }
        return $data;
    }

    /**
     * 云运动 - api接口 - 查询产品
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @update huangpengju
     * @update 2017/06/15
     * @param $id //订单id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getChargePackageDetail($id,$memberId)
    {
        $this->id = $id;                                              //订单id
        $orderData = $this->getMemberOrderDetails();
        $score = $this->getFieldsByOne($orderData,'score');
        $orderData['score'] = $score['score'];
        $orderData['scoreImg'] = $score['scoreImg'];
        $orderData['tag'] = ['减脂', '特色'];
        $packageClass = [];
        foreach ($orderData['memberCourseOrderDetails'] as $k => $v) {
            $num = $this->getChargeClassInfo($v,$memberId);                       //统计上了几节课
            for ($i = 0; $i < $v['course_num']; $i++) {
                if ($num >= $i + 1) {
                    $packageClass[] = ['name' => $v['course_name'], 'times' => $i + 1, 'class_length' => $v['class_length'], 'sale_price' => $v['original_price'], 'is_member' => '1', 'status' => '1'];  //status = 1 表示上过课
                } else {
                    $packageClass[] = ['name' => $v['course_name'], 'times' => $i + 1, 'class_length' => $v['class_length'], 'sale_price' => $v['original_price'], 'is_member' => '1', 'status' => '2'];  //status = 2 表示没上过课
                }
            }
        }
        //暂时 删除手机端，用不到数据
        unset($orderData['memberCourseOrderDetails'], $orderData['tag']);                              //暂时删除数据
        $orderData['packageClass'] = $packageClass;
        return $orderData;
    }

    /**
     *  云运动 - api接口 - 查询上了几节课
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/6/14
     * @param $v //订单信息
     * @param $memberId   // 会员id
     * @return int|string
     */
    public function getChargeClassInfo($v,$memberId)
    {
        $num = AboutClass::find()->where(['class_id' => $v['id']])
            /*->andWhere(["and",['<>', 'status', 2],["member_id"=>$memberId]])*/
            /*->andWhere(["and",['not in', 'status', [2,5,6,7]],["member_id"=>$memberId]])*/
            ->andWhere(['and',['status' => 4],['member_id' => $memberId]])
            ->count();  //获取上了几节课
        return $num;
    }

    /**
     * 云运动 - api接口 - 查询课程是否上过
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $data
     * @return mixed
     */
    public function getChargeClassByPackage($data)
    {
        if (isset($data) && !empty($data) && is_array($data)) {
            $packageClass = [];
            $totalPrice = 0;
            foreach ($data as $k => &$v) {
                $data[$k]['classTotalPrice'] = (empty($v['course_num']) ? 1 : $v['course_num']) * $v['original_price'];     //计算每个课程的总价
                $totalPrice += $data[$k]['classTotalPrice'];
                $j = empty($v['course_num']) ? 1 : $v['course_num'];
                for ($i = 0; $i < $j; $i++) {
                    $record = $this->getMemberClassRecord($v['id']);
                    if (!empty($record) && $record) {
                        $packageClass[] = ['name' => $v['name'], 'times' => $i + 1, 'course_length' => $v['course_length'], 'sale_price' => $v['original_price'], 'is_member' => '1', 'status' => '1'];
                    } else {
                        $packageClass[] = ['name' => $v['name'], 'times' => $i + 1, 'course_length' => $v['course_length'], 'sale_price' => $v['original_price'], 'is_member' => '2', 'status' => '2'];
                    }
                }
                unset($v['course']);
            };
            $data['packageClass'] = $packageClass;              //课程数组
            $data['totalPrice'] = $totalPrice;                  //计算总价
        }
        return $data;
    }

    /**
     *  云运动 - api接口 - 查询上课记录表
     * @author lihuien<lihuien@sports.club>
     * @create 2017/4/24
     * @param $id //多态id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberClassRecord($id)
    {
        return ClassRecord::find()
            ->where(['multiple_type' => 'charge'])
            ->andWhere(['multiple_id' => $id])
            ->andWhere(['status' => 1])
            ->one();
    }

    /**
     *  云运动 - api接口 - 查询所有私课订单
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/6/14
     * @param accountId //账户id
     * @param $course //课种id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMemberOrder($accountId, $course = '')
    {
        // 根据场馆需求 暂时隐藏数据
        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        foreach ($members as $member) {
            $ids[] = $member['id'];
        }
        $data = MemberCourseOrder::find()
            ->alias('order')
            ->joinWith(['memberCourseOrderDetails details'])
            ->joinWith(['chargeClass chargeClass'=>function($query){
                $query->joinWith(["organization o"],false);
                $query->select("o.name as venue_name");
            }],false)
            ->where(['order.member_id' => $ids, 'order.class_number_id'=>NULL])//过滤掉小团体课
//            ->andWhere(['>', 'order.overage_section', 0])
            ->andFilterWhere(['details.course_id' => $course])
            ->select('
                    order.id,
                    order.course_amount as classCount,
                    ("暂无数据") as totalPrice,
                    order.overage_section as surplusCount,
                    order.product_name,
                    order.set_number,
                    details.course_order_id,
                    details.pic,
                    details.category,
                    o.name as venue_name,
                ')
            ->asArray()
            ->all();
        $data = $this->setOrderData($data);
        return $data;
    }

    /**
     *  云运动 - api接口 - 查询所有私课订单
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/6/14
     * @param $data //订单数据
     * @return mixed
     */
    public function setOrderData($data)
    {
        foreach ($data as &$v) {
            if (empty($v['pic'])) {
                $v['pic'] = "";
            }
            if ($v['category'] == 1) {

                $v['package'] = $v['set_number'];       //套餐的套数
                unset($v['set_number']);
            }
            if($v['category'] == 2)
            {
                $v['package'] = 0;                        //单节课的套数
                unset($v['set_number']);
            }

            $v['score'] = $this->getFieldsByOne($v, 'score');            //返回评论星级
            $v['tag'] = ['减脂', '特色'];                                //返回特色类型死数据
        };
        return $data;
    }

    /**
     *  云运动 - api接口 - 查询会员卡是否存在
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/7/8
     * @param $memberId                         //会员id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberCard($memberId)
    {
        return MemberCard::find()->where(["and",['member_id'=>$memberId],[">","invalid_time",time()]])->asArray()->one();
    }

    /**
     *  云运动 - api接口 - 处理服务套餐总价
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/7/12
     * @param $chargeClass          //服务套餐价格处理
     * @param $memberId             //会员id
     */
    public function setManyClassPrice(&$chargeClass,$memberId)
    {
        // 这里走的是 pc端的流程
//        if(empty($chargeClass['total_amount']))
//        {
//            $chargeClass['money_amount'] = $chargeClass['original_price'];        //售价为空，用原价
//        }else{
//            $chargeClass['money_amount'] = $chargeClass['total_amount'];          //售价不为空，用售价
//        }
        $price = $this->priceJudge($chargeClass);
        $chargeClass['money_amount'] = $price;                                     // 私教价格控制显示问题
        // 24小时 之内办卡  享受 优惠价的 需求暂时 先取消
//        $model = new ChargeClass();
//        $memberCard = $model->getMemberCard($memberId);                                //获取会员卡信息
//        if(($memberCard['create_at'] + 86400) > time())                               //24小时内办卡的
//        {
//            if(!empty($chargeClass['total_pos_price']))                                   //pos价不为空
//            {
//                $chargeClass['money_amount'] = $chargeClass['total_pos_price'];           //用pos价
//            }
//        }
        unset($chargeClass['original_price'],$chargeClass['total_amount'],$chargeClass['total_pos_price']);
    }

    /**
     *  云运动 - api接口 - 私教价格显示的控制
     * @author houkaixin<houkaixin@sports.club>
     * @param $chargeClass // 私教详情数据
     * @create 2017/12/28
     * @return string
     */
    public function priceJudge($chargeClass){
          if($chargeClass["show"]==2){    // app设置显示的情况下
              // 两个价格都有的情况下 或则 优惠价存在 原价不存在
              if((isset($chargeClass["app_amount"])&&isset($chargeClass["app_original_price"]))||(isset($chargeClass["app_amount"])&&!isset($chargeClass["app_original_price"]))){
                  $price = $chargeClass["app_amount"];
              }elseif(!isset($chargeClass["app_amount"])&&isset($chargeClass["app_original_price"])) {
                  $price = $chargeClass["app_original_price"];
              }else{
                  $price = "";
              }
          }else{          // 不显示的情况下 未为空
                  $price = "";
          }
            return $price;
    }

    /**
     *  云运动 - api接口 - 处理单节课价格
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/7/12
     * @return array|\yii\db\ActiveRecord[]  //区间数据
     */
    public function setAloneClassPrice()
    {
        //根据海山要求需添加money字段
        $res = \common\models\base\ChargeClassPrice::find()->where(['charge_class_id'=>$this->id])->select('intervalStart,intervalEnd,unitPrice,posPrice,app_discount')->asArray()->all();
        return $res;
    }
    /**
     *  云运动 - api接口 - 查询课程详情
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/7/3
     * @param $id       //产品id
     * @param $memberId //会员id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getPrivateClass($id,$memberId)
    {
        $this->id               = $id;
        $memberCard             = $this->getMemberCard($memberId);
        $cardStus = $memberCard['status'];
        if(!empty($memberCard))
        {
            $memberCard = true;                 //有会员卡
        }else{
            $memberCard = false;                //没有会员卡
        }
        $productIdArr = $this->getMemberProductId($memberId);
        if(empty($productIdArr))
        {
            $product = false;                   //false表示会员不能预约
            $orderIds = false;                  //订单id不存在    
        }else{
            if(in_array($id,$productIdArr))
            {
                $order = $this->getMemberOrderIdArr($id,$memberId);
                $orderIds = $order['id'];            //订单id
                $product  = true;                //true表示会员可以预约
            }else{
                $product = false;               //false表示不能预约
                $orderIds = false;              //订单id不存在
            }
        }
        $chargeClass                   = $this->getChargeClass();
        $chargeClass['course_id'] = $chargeClass['id'];//课程id
        $chargeClass['carousel_pic'] = \Qiniu\json_decode($chargeClass['pic']);
        $this->priceShow              = $chargeClass["show"];
        $chargeClass["venueAddress"] = !empty($chargeClass["venueAddress"])?$chargeClass["venueAddress"]:"暂无数据";
        $chargeClass['intervalArr'] = [];  //根据ios利运要求必须要此字段
        $model = new ChargeClass();
        $card = $model->getMemberCard($memberId);//获取会员卡信息
        $chargeClass['newMember'] = ($card['create_at'] + 86400) > time() ? $chargeClass['newMember'] = true : $chargeClass['newMember'] = false;
        if($chargeClass['category'] == 1)                       //判断是不是套餐
        {
            $this->setManyClassPrice($chargeClass,$memberId);    //套餐走这里
        }else if($chargeClass['category'] == 2) {
            //单节课程走这里
           $intervalArr = $this->setAloneClassPrice();
            if(empty($intervalArr))
            {
                $chargeClass['intervalArr'] = $intervalArr;                       //表示没有区间价
            }else{
                $chargeClass['intervalArr'] = $intervalArr;                      //获取会员区间价
            }
//            $model = new ChargeClass();
//            $card = $model->getMemberCard($memberId);                           //获取会员卡信息
//            if(($card['create_at'] + 86400) > time())                         //24小时内办卡的
//            {
//                $chargeClass['newMember'] = true;                             //是新会员
//            }else{
//                $chargeClass['newMember'] = false;                           //是老会员
//            }
             $model->judgeIsNewMemberCard($memberId);                 //判断是否是新会员(没有卡 按照老会员)
             $chargeClass["newMember"] = $model->isNewMember;    // 参数赋值
             unset($chargeClass['original_price'],$chargeClass['total_amount'],$chargeClass['total_pos_price']);
        }
        if(!empty($chargeClass)) {
            if (empty($chargeClass['desc'])) {
                $chargeClass['desc'] = '健身不仅仅可以让自己的身体变得唯美,而且可以变得更加健康,提高自我生活质量,相信这节课你会体会到不一样的自己!';                                 //描述处理
            }
            if (empty($chargeClass['pic'])) {
                $chargeClass['pic'] = self::PIC;                                   //图片处理
            }
            if (empty($chargeClass['venuePic'])) {
                $chargeClass['venuePic'] = self::PIC;                              //场馆图片处理
            }
            $chargeClass['memberCard'] = $memberCard;                               //是否有会员卡
            $chargeClass['product'] = $product;                                     //会员是否买课
            $chargeClass['orderId'] = $orderIds;                                    //买课的情况下，有订单id
            $data = $this->getChargeClassOne();                                     //获取套餐详情
            $chargeClass['course_amount'] = 0;
            foreach ($data as $k => &$v) {
                if ($v['category'] == 2) {
                    $v['course_num'] = empty($v['course_num']) ? 1 : $v['course_num'];
                }
                $chargeClass['course_amount'] += $v['course_num'];                  //获取课量
                if($chargeClass['category'] == 2)
                {   // 单节课程 （单节原价价格不为空 并且 设置为显示的情况下（2） 为显示）
                    $singlePrice  = !empty($v["original_price"])&&($chargeClass["show"]==2)?$v["original_price"]:"";
                    $chargeClass['money_amount'] = $singlePrice;            //获取单节原价
                }
            }
            $chargeClass['memberCourseOrderDetails'] = $data;                       //套餐详情赋值

            $score                      = $this->getFieldsByOne($chargeClass['score'] = [],'score');                  //处理评论图片
            $chargeClass['score']      = $score['score'];                                    //去除图为x1的数量
            $chargeClass['scoreImg']  = $score['scoreImg'];                                //取出图的数组
            $chargeClass['tag']        = ['减脂','特色'];
            // 课程时长 外层重新赋值
            $chargeClass = $this->dealChargeClass($chargeClass);  // 外层课程时长（2017-11-06定义）
            $this->venueId = $chargeClass['venue_id'];
            $rs = Evaluate::find()->alias('e')
                ->select('e.content,e.enclosure,e.star_level,e.create_at,e.reply,e.reply_at,e.display_status,e.id,md.motto,md.nickname,md.member_id')
                ->joinWith('memberDetails md',false)
                ->andWhere(['consumption_type_id'=>$id,'consumption_type'=>'privateClass','venue_id'=>$this->venueId])
                ->orderBy('e.id desc');
            $res = clone $rs;
            $chargeClass['evaluate_count'] = $res->count();//评论总数量
            $result =$rs->limit(5)
                ->asArray()
                ->all();
            $data = [];
            foreach ($result as $v){
                $data[]=[
                        'id'=>$v['id'],
                        'member_id'=>$v['member_id'],
                        "display_status"=>$v['display_status'],
                        "content"=>$v['content'],
                        "star_level"=>$v['star_level'],
                        "create_at"=>date('Y.m.d',$v['create_at']),
                        "enclosure"=>!empty(\Qiniu\json_decode(trim($v['enclosure']))) ? \Qiniu\json_decode(trim($v['enclosure'])) :[],
                          "motto"=>!empty($v['motto']) ? $v['motto'] : '',
                        "nickname"=>is_null($v['nickname']) ? '默认昵称' : $v['nickname'],
                    ];
            }
            $chargeClass['evaluate_list']['item'] = $data;
            $chargeClass['card_status'] = $cardStus;
            $tmp = $this->getChargeDetail($id,$memberId);//处理预约第几节课程
             $chargeClass['aboutNum'] =$tmp['aboutNum'] ;
             $arrrrr[] = $chargeClass;
            return $arrrrr;
        }else{
            return NULL;
        }
    }
    /**
     *  云运动 -  私课课程时长 放到最外层 - 单节时长的 重新赋值
     * @author houkaixin<houkaixin@sports.club>
     * @param $chargeClass  // 私课 课程 处理
     * @param $requestSource // 请求来源（是否是下单的私课详情）
     * @create 2017/11/04
     * @return array     //返回
     */
      public function dealChargeClass($chargeClass,$requestSource = "isNotBuy"){
          $courseLength = ($requestSource=="isNotBuy")?"course_length":"class_length";
          if($chargeClass['category']==2){
              // 课程时长的重置（2017-11-04） 新原型 影响
              $chargeClass["courseLength"] = isset($chargeClass['memberCourseOrderDetails'][0][$courseLength])&&!empty($chargeClass['memberCourseOrderDetails'][0][$courseLength])?$chargeClass['memberCourseOrderDetails'][0][$courseLength]:null;
              // 2：价格 修复
              $chargeClass = $this->dealPrivatePrice($chargeClass);
          }
         return  $chargeClass;
      }
    /**
     *  云运动 - 如果在单节课的情况下 未设置 posPrice价 或posPrice 等于0 重置为单节原价
     * @author houkaixin<houkaixin@sports.club>
     * @param $chargeClass  // 私课 课程 处理
     * @create 2017/11/04
     * @return array     //返回
     */
    public function dealPrivatePrice($chargeClass){
        $chargeClass["intervalArr"] = !empty($chargeClass["intervalArr"])?$chargeClass["intervalArr"]:[];
        if(empty($chargeClass["intervalArr"])){
               return $chargeClass;
        }
        foreach($chargeClass["intervalArr"] as $keys=>$values){
             if(empty($values["posPrice"])){
                 $chargeClass["intervalArr"][$keys]["posPrice"] =$values["unitPrice"];
             }
        }
        return $chargeClass;
    }
    /**
     *  云运动 - api接口 - 查询课程详情
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/7/3
     * @return array|null|\yii\db\ActiveRecord  //获取产品信息
     */
    public function getChargeClass()
    {
        return \backend\models\ChargeClass::find()
            ->alias('charge')
            ->joinWith(['organization or'],false)
            ->joinWith(["chargeClassPrice as interValStart"=>function($query){
                $query->select("interValStart.charge_class_id,interValStart.intervalStart,interValStart.intervalEnd,
                            interValStart.unitPrice,interValStart.posPrice");
            }])
            ->joinWith(['deal deal'],false)
            ->where(['charge.id'=>$this->id])
            ->andWhere(['charge.status'=>1])
            ->select('
                    charge.id,
                    charge.name as product_name,
                    charge.original_price,
                    charge.total_amount,
                    charge.total_pos_price,
                    charge.describe as desc,
                    charge.pic,
                    charge.type as category,
                    charge.venue_id,
                    charge.app_amount,
                    charge.show,
                    charge.app_original_price,	
                    or.name as venueName,
                    or.address as venueAddress,
                    or.pic as venuePic,
                    deal.id as deal_id,
                    deal.name as deal_name
                    ')
            ->asArray()->one();
    }
    /**
     *  云运动 - api接口 - 查询会员购买产品id
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/7/3
     * @param $memberId   //会员id
     * @return array      //返回会员购买产品数组
     */
    public function getMemberProductId($memberId)
    {
        $data =  \common\models\base\MemberCourseOrder::find()->where(['member_id'=>$memberId])->select('product_id')->asArray()->all();
        $productIdArr = [];
        foreach($data as $k=>$v)
        {
            $productIdArr[] = $v['product_id'];
        }
        return $productIdArr;
    }

    /**
     *  云运动 - api接口 - 查询会员购买课程订单id
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/7/3
     * @param $memberId     //会员id
     * @param $productId    //产品id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberOrderIdArr($productId,$memberId)
    {
        return \common\models\base\MemberCourseOrder::find()->where(['member_id'=>$memberId])->andWhere(['product_id'=>$productId])->select('id')->asArray()->one();
    }
    /**
     * 云运动 - api接口 - 查询产品课程详情
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/7/3
     * @param $id               //产品id
     * @param $memberId         //会员id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function GetPrivateDetail($id,$memberId = '')
    {
        $this->id               = $id;                                              //订单id
        $chargeClass            = $this->getChargeClass();
        // 判断新会员 还是老会员
        $this->judgeIsNewMemberCard($memberId);
        $chargeClass["newMember"] = $this->isNewMember;
        if(empty($chargeClass))
        {
            return NULL;
        }
        if(!empty($chargeClass)) {
            if (empty($chargeClass['desc'])) {
                $chargeClass['desc'] = '暂无数据';
            }
            if (empty($chargeClass['pic'])) {
                $chargeClass['pic'] = self::PIC;
            }
            if (empty($chargeClass['venuePic'])) {
                $chargeClass['venuePic'] = self::PIC;
            }
            $data = $this->getChargeClassOne();
            $packageClass = [];
            $chargeClass['memberCourseOrderDetails'] = $data;
            foreach ($data as $k=>$v) {
                if ($v['category'] == 2) {
                    $v['course_num'] = empty($v['course_num']) ? 1 : $v['course_num'];
                }
                $orderDetails = $this->getMemberOrderId($v['charge_class_id'],$v['course_id'],$memberId);                       //统计上了几节课
                if(!empty($orderDetails))
                {
                    $num = AboutClass::find()->where(['class_id'=>$orderDetails['detailId']])->andWhere(['<>','status',2])->andWhere(['member_id'=>$memberId])->count();  //获取上了几节课
                    $v['course_num'] =  $orderDetails['course_num'];
                }else{
                    $num = 0;
                }
                for ($i = 0; $i < $v['course_num']; $i++) {
                    if ($num >= $i + 1) {
                        $packageClass[] = ['name' => $v['name'], 'times' => $i + 1, 'class_length' => $v['course_length'], 'sale_price' => $v['original_price'], 'is_member' => '1', 'status' => '1'];  //status = 1 表示上过课
                    } else {
                        $packageClass[] = ['name' => $v['name'], 'times' => $i + 1, 'class_length' => $v['course_length'], 'sale_price' => $v['original_price'], 'is_member' => '1', 'status' => '2'];  //status = 2 表示没上过课
                    }
                }
            }
            $chargeClass['packageClass'] = $packageClass;
            $chargeClass['money_amount'] = "";
            $chargeClass['course_amount'] = 0;
            foreach ($data as $k => &$v) {
                if ($v['category'] == 2) {
                    $v['course_num'] = empty($v['course_num']) ? 1 : $v['course_num'];
                }
                $chargeClass['money_amount'] += (int)$v['course_num'] * (int)$v['original_price'];
                $chargeClass['course_amount'] += $v['course_num'];
            }
            $score = $this->getFieldsByOne($chargeClass, 'score');
            $chargeClass['score'] = $score['score'];
            $chargeClass['scoreImg'] = $score['scoreImg'];
//            $chargeClass['tag'] = ['减脂', '特色'];
//            //暂时 删除手机端，用不到数据
            unset($chargeClass['desc'],$chargeClass['venue_id'],$chargeClass['venueName'],$chargeClass['venueAddress'],$chargeClass['venuePic'],$chargeClass['memberCourseOrderDetails']);                              //暂时删除数据
            return $chargeClass;
        }
    }
    /**
     * 云运动 - api接口 - 判断是否新会员
     * @author 侯凯新<houkaixin@sports.club>
     * @create 2017/7/3
     * @param $memberId       //会员id
     * @return array|null|\yii\db\ActiveRecord   //订单详情表id
     */
    public function judgeIsNewMemberCard($memberId){
         $memberCard = MemberCard::find()->where(["and",["member_id"=>$memberId],[">","invalid_time",time()]])->select("create_at")->orderBy(["id"=>SORT_DESC])->limit(1)->asArray()->one();
         if(!empty($memberCard)&&(time()<($memberCard["create_at"]+24*60*60))){
             // 根据最新买的卡 并且购卡时间在 系统24小时之内
              $newMember = true;       // 有卡的新会员
         }elseif(empty($memberCard)){
             // 没有卡  按照老会员算价格
              $newMember  = false;     // 没卡的老会员
         }else{
             //有卡  但是购卡价格已经不再24小时之内
              $newMember  = false;     // 有卡的 老会员
        }
         $this->isNewMember = $newMember;
    }
    /**
     * 云运动 - api接口 - 查询产品课程详情
     * @author huangpengju<huangpengju@sports.club>
     * @create 2017/7/3
     * @param $chargeClassId        //产品id
     * @param $courseId             //课种id
     * @param $memberId             //会员id
     * @return array|null|\yii\db\ActiveRecord   //订单详情表id
     */
    public function getMemberOrderId($chargeClassId,$courseId,$memberId)
    {
         $data =  MemberCourseOrder::find()
                ->alias('order')
                ->joinWith(['memberCourseOrderDetails detail'],false)
                ->where(['order.product_id'=>$chargeClassId])
                ->andWhere(['order.member_id'=>$memberId])
                ->andWhere(['detail.course_id'=>$courseId])
                ->select('order.id,detail.id as detailId,detail.course_num')
                ->asArray()
                ->one();
        return $data;
    }
}