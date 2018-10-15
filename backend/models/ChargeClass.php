<?php
namespace backend\models;

use common\models\base\ChargeClassPrice;
use common\models\base\ChargeClassService;
use common\models\base\ClassSaleVenue;
use common\models\base\CoursePackageDetail;
use common\models\base\MemberCard;
use common\models\base\MemberCourseOrder;
use common\models\Func;
use common\models\relations\ChargeClassRelations;

class ChargeClass extends \common\models\ChargeClass
{
    use ChargeClassRelations;
    public $keywords;
    public $name;
    public $startTime;
    public $endTime;
    public $venueId;
    public $sorts;
    public $courseId;
    public $searchContent;
    public $nowBelongId;
    public $nowBelongType;
    public $number;
    const SEARCH_CONTENT = 'searchContent';
    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    const VENUE_ID = 'venueId';

    /**
     * 后台会员管理 - 私教课程查询 - 多表查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/10
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public function search($params)
    {
        $this->customLoad($params);
        $query = ChargeClass::find()
            ->alias('cc')
            ->joinWith(['coursePackageDetail dd' => function($query){
                $query->joinWith(['course course'],false);
            }],false)
            ->orderBy($this->sorts)
            ->where(['group'=>1])
            ->select(
                "cc.id,
                 cc.name,
                 cc.valid_time,
                 cc.total_amount,
                 cc.total_sale_num,
                 cc.sale_start_time,
                 cc.sale_end_time,
                 cc.created_at,
                 cc.status,
                 cc.app_amount,
                 cc.show,
                 course.name as courseName")
            ->groupBy('dd.charge_class_id')
            ->asArray();
        $query        = $this->getSearchWhere($query);
        $dataProvider = Func::getDataProvider($query,8);
        return $dataProvider;
    }

    /*
     * 私教管理 - 获取私教小团体 - 多表联查
     * */
    public function searchGroup($params)
    {
        $this->customLoad($params);
        $query = ChargeClass::find()
            ->alias('cc')
            ->joinWith(['coursePackageDetail dd' => function($query){
                $query->joinWith(['course course'],false);
            }],false)
            ->joinWith(['chargeClassNumber ccn' => function($query){
                $query->where(['>','ccn.surplus',0])->orderBy('ccn.surplus_sale_num');
            }])
            ->orderBy($this->sorts)
            ->where(['cc.group'=>2])
            ->where(['cc.category'=>2])
            ->select(
                "cc.*,course.name as courseName,
         ccn.sale_num,ccn.surplus_sale_num")
            ->groupBy('dd.charge_class_id')
            //->createCommand()->getRawSql();
            ->asArray();

        $query        = $this->getSearchWhere($query);
        $dataProvider = Func::getDataProvider($query,8);
        return $dataProvider;
    }
    /**
     * 会员卡管理 - 私教课程 - 搜索数据处理数据
     * @create 2017/4/12
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return bool
     */
    public function customLoad($data)
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
        $this->keywords      = (isset($data['keywords']) && !empty($data['keywords'])) ? $data['keywords'] : null;
        $this->startTime     = (isset($data['startTime'])&& !empty($data['startTime']))? (int)strtotime($data['startTime']) : null;
        $this->endTime       = (isset($data['endTime']) && !empty($data['endTime'])) ? (int)strtotime($data['endTime']) : null;
        $this->courseId      = (isset($data['courseId']) && !empty($data['courseId'])) ? (int)$data['courseId'] : null;
        $this->searchContent = (isset($data[self::SEARCH_CONTENT]) && !empty($data[self::SEARCH_CONTENT])) ? $data[self::SEARCH_CONTENT] : null;
        $this->nowBelongId   = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID])) ? $data[self::NOW_BELONG_ID] : null;
        $this->nowBelongType = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE])) ? $data[self::NOW_BELONG_TYPE] : null;
        $this->sorts          = self::loadSort($data);
        return true;
    }
    /**
     * 会员卡管理 - 会员卡 - 增加搜索条件
     * @create 2017/4/5
     * @author huanghua<huanghua@itsports.club>
     * @param $query
     * @return mixed
     */
    public function getSearchWhere($query)
    {
        $query->andFilterWhere(['course.id' => $this->courseId]);
        $query->andFilterWhere(['like','cc.name',$this->keywords]);
        $query->andFilterWhere(['cc.venue_id'=>$this->venueId]);
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['cc.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['cc.venue_id'=>$this->nowBelongId]);
//        }
        return $query;
    }
    /**
     * 会员卡管理 - 私教课程管理 - 获取搜索规格
     * @create 2017/4/12
     * @author huanghua<huanghua@itsports.club>
     * @param $sort
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
     * 会员卡管理 - 会员信息管理 - 获取排序条件
     * @create 2017/4/11
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return mixed
     */
    public static function loadSort($data)
    {
        $sorts = [
            '`cc`.id' => SORT_DESC
        ];
        if(isset($data['sortType']) && $data['sortType'] == 'name'){
            $sorts = [ '`cc`.name' => self::convertSortValue($data['sortName']) ];
        }
        if(isset($data['sortType']) && $data['sortType'] == 'valid_time'){
            $sorts = [ '`cc`.valid_time' => self::convertSortValue($data['sortName']) ];
        }
        if(isset($data['sortType']) && $data['sortType'] == 'total_amount'){
            $sorts = [ '`cc`.total_amount' => self::convertSortValue($data['sortName']) ];
        }
        if(isset($data['sortType']) && $data['sortType'] == 'total_sale_num'){
            $sorts = [ '`cc`.total_sale_num' => self::convertSortValue($data['sortName']) ];
        }
        if(isset($data['sortType']) && $data['sortType'] == 'sale_start_time'){
            $sorts = [ '`cc`.sale_start_time' => self::convertSortValue($data['sortName']) ];
        }
        if(isset($data['sortType']) && $data['sortType'] == 'status'){
            $sorts = [ '`cc`.status' => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }
    /**
     * 后台 - 私课管理 - 收费课程删除
     * @author huang hua <huanghua@itsports.club>
     * @create 2017/4/12
     * @param $id
     * @return bool
     */
    public  function getClassDel($id)
    {
        $chargeClass       =   ChargeClass::findOne($id);
        $resultDelMem      =   $chargeClass->delete();
        $resultDelMemCard  =   $this->getDelMemCard($id);
        $resultPrice       =   $this->getDelPrice($id);
        $resultServer      =   $this->getDelServer($id);
        $resultVenue       =   $this->getDelVenue($id);

        if($resultDelMem && $resultDelMemCard && $resultPrice && $resultServer && $resultVenue)
        {
            return true;
        }else{
            return false;
        }
    }
    /**
     * 后台 - 私课管理 - 课程套餐详情表删除
     * @author Huanghua <huanghua@itsports.club>
     * @create 2017/5/5
     * @param $id
     * @return bool
     */
    public  function  getDelMemCard($id){
        $memCard = CoursePackageDetail::find()->where(["charge_class_id"=>$id])->asArray()->all();
        if(empty($memCard))
        {
            return true;
        }else{
            $delResults = CoursePackageDetail::deleteAll(["charge_class_id"=>$id]);
            if($delResults){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 后台 - 私课管理 - 私课产品价格表删除
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/5/12
     * @param $id
     * @return bool
     */
    public  function  getDelPrice($id){
        $price = ChargeClassPrice::find()->where(["charge_class_id"=>$id])->asArray()->all();
        if(empty($price))
        {
            return true;
        }else{
            $delResults = ChargeClassPrice::deleteAll(["charge_class_id"=>$id]);
            if($delResults){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 后台 - 私课管理 - 私课产品价格表删除
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/5/12
     * @param $id
     * @return bool
     */
    public  function  getDelServer($id){
        $server = ChargeClassService::find()->where(["charge_class_id"=>$id])->asArray()->all();
        if(empty($server))
        {
            return true;
        }else{
            $delResults = ChargeClassService::deleteAll(["charge_class_id"=>$id]);
            if($delResults){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 后台 - 私课管理 - 课种售卖场馆表删除
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/5/12
     * @param $id
     * @return bool
     */
    public  function  getDelVenue($id){
        $venue = ClassSaleVenue::find()->where(["charge_class_id"=>$id])->asArray()->all();
        if(empty($venue))
        {
            return true;
        }else{
            $delResults = ClassSaleVenue::deleteAll(["charge_class_id"=>$id]);
            if($delResults){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 后台会员管理 - 私课管理 - 修改课程状态
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/4/12
     * @param
     * @param
     * @return boolean
     */
    public static function editStatus($id,$text)
    {
        if($text == 'ban'){
            return  self::editStatusBen($id);
        }else{
            return  self::editStatusTime($id);
        }
    }

    /**
     * 私教管理 - 私教课程 - 修改移动端价格显示
     * @create 2017/12/25
     * @author zhumengke <zhumengke@itsports.club>
     * @return bool
     */
    public function editClassShow($chargeId)
    {
        $class = ChargeClass::findOne(['id' => $chargeId]);
        if($class['show'] == 1){
            $class->show = 2;
        }else{
            $class->show = 1;
        }
        if($class->save()){
            return true;
        }else{
            return $class->errors;
        }
    }
    /**
     * 后台 - 私课管理 - 自动过期
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/17
     * @return boolean
     */
    public function overdue()
    {
        $charge = ChargeClass::find()->where(['<','sale_end_time',time()])->asArray()->all();
        if(!empty($charge)){
            foreach($charge as $k=>$v){
                $class = ChargeClass::findOne(['id' => $v['id']]);
                $class->status = 3;
                $class->save();
            }
            return true;
        }
    }
    /**
     * 后台会员管理 - 私课管理 - 修改卡种冻结状态
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/4/12
     * @param
     * @return \yii\db\ActiveQuery
     */
    public static function editStatusBen($id)
    {
        $class = ChargeClass::find()->where(['id'=>$id])->one();
        if($class && $class->status == 2){
            $class->status = 1;
        }else{
            $class->status = 2;
        }
        if($class->save()){
            return $class->status == 2?2:1;
        }else{
            return $class->errors;
        }
    }
    /**
     * 后台会员管理 - 私课管理 - 修改卡种过期状态
     * @author huanghua<huanghua@itsports.club>
     * @create 2017/4/12
     * @param
     * @return \yii\db\ActiveQuery
     */
    public function editStatusTime($id)
    {
        $class = ChargeClass::find()->where(['id'=>$id])->one();
        if($class && $class->status == 3){
            $class->status = 1;
        }else{
            $class->status = 3;
        }
        if($class->save()){
            return $class->status == 3?3:1;
        }else{
            return $class->errors;
        }
    }

    /**
     * 云运动 - 购买私课课程 - 接口
     * @author 黄鹏举 <huangpengju@itsports.club>
     * @create 2017/5/19
     * @update huangpengju
     * @update 2017/06/10
     * @param $memberId  //会员id
     * @param $id       //公司或者场馆id
     * @param $type     //类型
     * @return string
     */
    public function getClassInfo($memberId,$venueId)
    {
        $data['alone'] = $this->getClassAloneInfo($memberId,$venueId);
        $data['many']  = $this->getClassManyInfo($memberId,$venueId);
        return $data;
    }

    /**
     * 后台会员管理 - 会员管理 - 购买私课（多节课程）
     * @author Huangpengju<Huangpengju@itsports.club>
     * @create 2017/5/19
     * @update huangpengju
     * @update 2017/06/10
     * @param $memberId  //会员id
     * @param $id       //公司或者场馆id
     * @param $type     //类型
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getClassManyInfo($memberId,$venueId)
    {
        $times = date('Y-m-d 00:00',time());        //获取当前时间戳
        $data = ChargeClass::find()
            ->alias('class')
            ->joinWith(['coursePackageDetails detail'=>function($query){
                $query->joinWith(['course course']);
            }])
            ->joinWith(['classSaleVenueOne csv'])
            ->joinWith(['classSaleVenue csvs'])
            ->where(['class.status'=>1])
            ->andWhere(['>=','class.sale_end_time',strtotime($times)])
            ->andWhere(['class.type'=>1])
            ->andWhere(['class.group'=>1])
            ->andWhere(['or',['csv.venue_id' => $venueId],['csvs.id' => null]])
            ->select("
                class.id,class.name as packName,class.pic,class.original_price,class.total_amount,class.total_pos_price,class.type,csvs.id as saleVenue
            ")
            ->asArray();
//            if(isset($type) && $type == 'company'){
//                $data = $data->andFilterWhere(['class.company_id'=>$id]);
//            }
//            if(isset($type) && $type == 'venue'){
//                $data = $data->andFilterWhere(['class.venue_id'=>$id]);
//            }
        $data = $data->all();
        $data = $this->getScore($memberId,$data,'many');
        return $data;
    }
    /**
     * 后台会员管理 - 会员管理 - 购买私课（单节课程）
     * @author Huangpengju<Huangpengju@itsports.club>
     * @create 2017/5/19
     * @update huangpengju
     * @update 2017/06/10
     * @param $memberId  //会员id
     * @param $id       //公司或者场馆id
     * @param $type     //类型
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getClassAloneInfo($memberId,$venueId)
    {
        $times = date('Y-m-d 00:00',time());        //获取当前时间戳
        $data = ChargeClass::find()
            ->alias('class')
            ->joinWith(['chargeClassPriceAll price'])
            ->joinWith(['coursePackageDetailsAlone detail'=>function($query){
                $query->joinWith(['course course']);
            }])
            ->joinWith(['classSaleVenueOne csv'])
            ->joinWith(['classSaleVenue csvs'])
            ->where(['class.status'=>1])
            ->andWhere(['>=','class.sale_end_time',strtotime($times)])
            ->andWhere(['class.type'=>2])
            ->andWhere(['class.group'=>1])
            ->andWhere(['or',['csv.venue_id' => $venueId],['csvs.id' => null]])
            ->select("class.id,class.month_up_num,class.name as packName,class.pic,class.type,csvs.id as saleVenue")
//            ->select("
//                class.id,class.name as packName,class.pic,course.name,price.unitPrice
//            ")
            ->asArray();
//        if(isset($type) && $type == 'company'){
//            $data = $data->andFilterWhere(['class.company_id'=>$id]);
//        }
//        if(isset($type) && $type == 'venue'){
//            $data = $data->andFilterWhere(['class.venue_id'=>$id]);
//        }
        $data = $data->all();
        $data = $this->getScore($memberId,$data,'alone');
        return $data;
    }
    /**
     * 后台会员管理 - 会员管理 - 私课(评分和图片)
     * @author Huangpengju<Huangpengju@itsports.club>
     * @create 2017/5/19
     * @param $memberId     //会员id
     * @param $data         //课程数据
     * @param $param        //类型
     * @return mixed
     */
    public function getScore($memberId,$data,$param)
    {
        if($param == "alone"){
            foreach($data as &$v){
//                $v['packName'] = $v['name'];
                foreach ($v['chargeClassPriceAll'] as $key=>&$val)
                {
                    unset($val['id'],$val['charge_class_id'],$val['course_package_detail_id'],$val['create_time']);
//                    $v[$key] = $v['chargeClassPriceAll'][$key];
                }
                $v['original_price'] = $v['coursePackageDetailsAlone']['original_price'];   //单节原价
                $v['name']           = $v['coursePackageDetailsAlone']['course']['name'];   //课种名称
//                unset($v['chargeClassPriceAll']);
                unset($v['coursePackageDetailsAlone']);
                $order = $this->getMemberOrder($memberId,$v['id']);                          //查询会员订单
                if(empty($order))
                {
                    $v['memberOrder'] = true;                              //允许购买
                }else{
                    $v['memberOrder'] = false;                             //不允许购买（允许续费）
                }
                $memberCard = $this->getMemberCard($memberId);              //获取会员卡信息
                if(($memberCard['create_at'] + 86400) > time())             //24小时内办卡的
                {
                    $v['newMember'] = true;                                 //是新会员
                }else{
                    $v['newMember'] = false;                                //不是新会员
                }
                $score = isset($v['score'])?$v['score']:4;
                $v['score'] = $score;
                $key = ['one','two','three','four','five'];
                for($i = 1; $i<=5;$i++){
                    if($i <= $score){
                        $v['scoreImg'][$key[$i-1]] = 'img/x1.png';
                    }else{
                        $v['scoreImg'][$key[$i-1]] = 'img/x2.png';
                    }
                }
            }
        }else{
            foreach($data as &$v)
            {
                $str = '';
                foreach ($v['coursePackageDetails'] as $key=>$val)
                {
                    $num     = $val['course_num'];              //获取课量
                    $name    = $val['course']['name'];         //获取课种名称
                    $str    .= $name.$num.'节/';
                }
                $str = rtrim($str,'/');
                $v['courseStr'] = $str;
                $order = $this->getMemberOrder($memberId,$v['id']);     //查询会员订单
                if(empty($order))
                {
                    $v['memberOrder'] = true;               //允许购买
                }else{
                    $v['memberOrder'] = false;              //不允许购买（允许续费）
                }
                if(empty($v['total_amount']))                              //售价为空，用原价
                {
                    $v['totalPrice'] = $v['original_price'];                //用原价
                }else{
                    $v['totalPrice'] = $v['total_amount'];                  //用售价
                }
                $memberCard = $this->getMemberCard($memberId);              //获取会员卡信息
                if(($memberCard['create_at'] + 86400) > time())             //24小时内办卡的
                {
                    if(!empty($v['total_pos_price']))                       //pos价不为空
                    {
                        $v['totalPrice'] = $v['total_pos_price'];            //用pos价
                    }
                }
                unset($v['original_price'],$v['total_amount'],$v['total_pos_price'],$v['coursePackageDetails']);
                $score = isset($v['score'])?$v['score']:4;
                $v['score'] = $score;
                $key = ['one','two','three','four','five'];
                for($i = 1; $i<=5;$i++){
                    if($i <= $score){
                        $v['scoreImg'][$key[$i-1]] = 'img/x1.png';
                    }else{
                        $v['scoreImg'][$key[$i-1]] = 'img/x2.png';
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 云运动 - 购买私课 -  查询会员卡
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/7/7
     * @param $memberId                 //会员id
     * @return array|null|\yii\db\ActiveRecord  //会员卡信息
     */
    public function getMemberCard($memberId)
    {
        return MemberCard::find()->where(['member_id'=>$memberId])->orderBy(['create_at'=>SORT_DESC])->select('create_at')->asArray()->one();
    }
    /**
     * 云运动 - 购买私课 -  获取课程有效期id
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/5/20
     * @param $chargeId                         //课程id
     * @return array|null|\yii\db\ActiveRecord  //课程有效期
     */
    public function getClassValidTime($chargeId)
    {
        return ChargeClass::find()->where(['id'=>$chargeId])->asArray()->one();
    }

    /**
     * 云运动 - 私课管理 - 私教服务详情、私教课程详情
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/7
     * @param $chargeClassId    //私课id
     * @return array
     */
    public function chargeClassDetails($chargeClassId)
    {
        $type = ChargeClass::find()->where(['id' => $chargeClassId])->select('id,type')->asArray()->one();
        if($type['type'] == 1){
            $details            = $this->chargeDetails($chargeClassId);
            $details['course']  = $this->chargeCourse($chargeClassId);
            $details['venue']   = $this->chargeVenue($chargeClassId);
            $details['service'] = $this->chargeServer($chargeClassId);
            $details['gift']    = $this->chargeGift($chargeClassId);
        }else{
            $details            = $this->chargeDetails($chargeClassId);
            $details['price']   = $this->chargePrice($chargeClassId);
            $details['course']  = $this->chargeCourse($chargeClassId);
            $details['venue']   = $this->chargeVenue($chargeClassId);
            $details['service'] = $this->chargeServer($chargeClassId);
            $details['gift']    = $this->chargeGift($chargeClassId);
        }
        return $details;
    }
    /**
     * 云运动 - 私教课程 - 私课详情
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/11/04
     * @return array
     */
    public function chargeDetails($chargeClassId)
    {
        $details = \backend\models\ChargeClass::find()
            ->alias('cc')
            ->where(['cc.id' => $chargeClassId])
            ->joinWith(['deal deal'],false)
            ->joinWith(['organization org'],false)
            ->select(
                'cc.id,
                 cc.type,
                 cc.name,
                 cc.valid_time,
                 cc.activated_time,
                 cc.total_sale_num,
                 cc.sale_start_time,
                 cc.sale_end_time,
                 cc.course_type,
                 cc.total_amount,
                 cc.total_pos_price,
                 cc.transfer_num,
                 cc.transfer_price,
                 cc.month_up_num,
                 cc.describe,
                 cc.pic,
                 cc.product_type,
                 cc.deal_id,
                 cc.app_amount,
                 cc.show,
                 cc.venue_id,
                 deal.name as dealName,
                 org.name as venueName,
                ')
            ->asArray()
            ->one();
        return $details;
    }
    /**
     * 云运动 - 私教服务 - 区间价
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/11/04
     * @return array
     */
    public function chargePrice($chargeClassId)
    {
        $details = ChargeClassPrice::find()
            ->where(['charge_class_id' => $chargeClassId])
            ->select('intervalStart,intervalEnd,unitPrice,posPrice')
            ->asArray()
            ->all();
        return $details;
    }
    /**
     * 云运动 - 私教课程 - 私课绑定课程
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/11/04
     * @return array
     */
    public function chargeCourse($chargeClassId)
    {
        $details = \backend\models\CoursePackageDetail::find()
            ->alias('cpd')
            ->joinWith(['course course'],false)
            ->where(['cpd.charge_class_id' => $chargeClassId])
            ->select('cpd.course_id,course.name,cpd.course_length,cpd.course_num,cpd.original_price,cpd.app_original')
            ->asArray()
            ->all();
        return $details;
    }
    /**
     * 云运动 - 私教课程 - 私课售卖场馆
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/11/04
     * @return array
     */
    public function chargeVenue($chargeClassId)
    {
        $details = ClassSaleVenue::find()
            ->alias('csv')
            ->where(['csv.charge_class_id' => $chargeClassId])
            ->joinWith(['organization organ'],false)
            ->select('csv.venue_id,organ.name as venueName,csv.sale_num')
            ->asArray()
            ->all();
        return $details;
    }
    /**
     * 云运动 - 私教课程 - 私课绑定服务
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/11/04
     * @return array
     */
    public function chargeServer($chargeClassId)
    {
        $details = ChargeClassService::find()
            ->alias('ccs')
            ->where(['ccs.charge_class_id' => $chargeClassId,'type'=>1])
            ->joinWith(['server server'],false)
            ->select('ccs.service_id,server.name as service_name,ccs.service_num')
            ->asArray()
            ->all();
        return $details;
    }
    /**
     * 云运动 - 私教课程 - 私课绑定赠品
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/11/04
     * @return array
     */
    public function chargeGift($chargeClassId)
    {
        $details = ChargeClassService::find()
            ->alias('ccs')
            ->where(['ccs.charge_class_id' => $chargeClassId,'type'=>2])
            ->joinWith(['goods goods'],false)
            ->select('ccs.gift_id,goods.goods_name,goods.unit,ccs.gift_num')
            ->asArray()
            ->all();
        return $details;
    }
    /**
     * 后台会员管理 - 会员买课 - 查询会员是否买个该课程
     * @author Huangpengju<Huangpengju@itsports.club>
     * @create 2017/6/19
     * @param $memberId     //会员id
     * @param $productId            //产品id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberOrder($memberId,$productId)
    {
        return MemberCourseOrder::find()->where(['product_id'=>$productId])->andWhere(['member_id'=>$memberId])->asArray()->one();
    }

    /**
     * 云运动 - 会员管理 - 分配私教 - 根据课程类型查询课程
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/26
     * @param $courseType    //课程类型
     * @param $company
     * @return array
     */
    public function getCourseType($courseType,$company)
    {
        if($courseType == 1 || $courseType == 2){
            $charge =  $charge = $this->getChargeClassAllByType($courseType,$company);
        }else{
            if($courseType == 5){
               return $this->getChargeClassAllByType(null,$company);
            }
            $charge = $this->getChargeClassAllByType(2,$company);
        }
        return $charge;
    }
    /**
     * 云运动 - 会员管理 - 获取私教
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/7/26
     * @param  $type    //课程类型
     * @param $company
     * @return array
     */
    public function getChargeClassAllByType($type,$company)
    {
        return  ChargeClass::find()
            ->filterWhere(['course_type' => $type])
            ->andWhere(['and',['>=','sale_end_time',time()],['<=','sale_start_time',time()]])
            ->andWhere(['company_id'=>$company])
            ->select('id,name')
            ->asArray()
            ->all();
    }
    /**
     * 云运动 - 会员管理 - 修改私教图片
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/7/26
     * @param $params    //课程类型
     * @return array
     */
    public function updateChargePic($params)
    {
        $charge = self::findOne($params['classId']);
        $charge->pic      = $params['pic'];
        $charge->describe = $params['describe'];
        if($charge->save()){
            return true;
        }else{
            return $charge->errors;
        }
    }

    /**
     * @云运动 - 会员管理 - 私教课续费 计算价格
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/9/6
     * @return int
     */
    public function computePrice($chargeId,$number,$memberId)
    {
        $charge = $this->getClassValidTime($chargeId);
        if($charge['type'] == 1){
            if(empty($charge['total_amount']))                         //售价为空，用原价
            {
                $price = $charge['original_price']* $number;                //用原价
            }else{
                $price = $charge['total_amount']* $number;                  //用售价
            }
            $memberCard = $this->getMemberCard($memberId);       //获取会员卡信息
            if(($memberCard['create_at'] + 86400) > time())             //24小时内办卡的
            {
                if(!empty($charge['total_pos_price']))                   //pos价不为空
                {
                    $price = $charge['total_pos_price']* $number;            //用pos价
                }
            }
        }else{
            $model = new \backend\models\ChargeClassPrice();
            $data  = $model->getAlonePrice($chargeId,$number);          //查询私课价格（是不是在区间内）
            if(empty($data)){
                $data = $model->getAloneEndPrice($chargeId,$number);    //最高区间的数据
            }
            if(!empty($data)){
                $memberCard = $this->getMemberCard($memberId);       //获取会员卡信息
                if(($memberCard['create_at'] + 86400) > time())      //24小时内办卡的
                {
                    if(!empty($data['posPrice']))              //pos价不为空
                    {
                        $unitPrice = $data['posPrice'];        //用pos价
                    }else{
                        $unitPrice = $data['unitPrice'];       //用优惠单价
                    }
                }else{
                    $unitPrice = $data['unitPrice'];           //课程单价
                }
            }else{
                $data      = CoursePackageDetail::find()->where(['charge_class_id'=>$chargeId])->asArray()->one();     //课程信息
                $unitPrice = $data['original_price'];          //课程单节原价
            }
            $price = $unitPrice * $number;         //总价
        }
        return $price;
    }
    /**
     *会员管理- 新增私教课程 - 下拉列表（获取私课所有信息）
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/12/9
     * @param $id       //公司或者场馆id
     * @param $type     //类型
     * @return array   //返回私课的所有信息
     */
    public function groupPrivateCourse($id,$type){
        $data = ChargeClass::find()
            ->alias('cc')
            ->select("cc.id,cc.name")
            ->asArray();
        if(isset($type) && $type == 'company'){
            $data = $data->andFilterWhere(['cc.company_id'=>$id]);
        }
        if(isset($type) && $type == 'venue'){
            $data = $data->andFilterWhere(['cc.venue_id'=>$id]);
        }
        $data = $data->all();
        return $data;
    }


}