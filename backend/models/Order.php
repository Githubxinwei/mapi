<?php
namespace backend\models;
use common\models\base\Cabinet;
use common\models\base\ConsumptionHistory;
use common\models\base\Employee;
use common\models\base\MemberCabinetRentHistory;
use common\models\base\MemberCard;
use common\models\base\MemberCourseOrder;
use common\models\base\MemberDeposit;
use common\models\base\Organization;
use common\models\relations\OrderRelations;
use common\models\Func;
use Yii;

class Order extends \common\models\Order
{
    use OrderRelations;
    public $keywords;                           //搜索条件
    public $sorts;                              //排序
    public $searchContent;
    public $nowBelongId;
    public $nowBelongType;
    public $searchDateStart;//搜索开始时间
    public $searchDateEnd;//搜索结束时间
    public $searchGood; //商品类型
    public $startTime;
    public $endTime;
    public $status;//订单状态搜索1未付款2已付款3其他
    public $payWay;//订单支付方式1现金2支付宝3微信4pos刷卡
    public $highest;//最高价
    public $lowest;//最低价
    public $sellName;//售卖人姓名
    public $venueId;//售卖场馆
    public $note;   //缴费行为
    public $businessBehavior;   //业务行为
    public $payMoneyMode;
    public $payMoneyTime;
    public $isReceipt;
    public $pending;
    public $businessRemarks;
    public $productType;
    public $courseType;
    const SEARCH_GOOD = 'searchGood';
    const SEARCH_CONTENT = 'searchContent';
    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    const KEY = 'keywords';
    const START = 'startTime';
    const END = 'endTime';
    const STATUS = 'status';
    const PAY_WAY = 'payWay';
    const HIGHEST = 'highest';
    const LOWEST = 'lowest';
    const SELL_NAME = 'sellName';
    const VENUE_ID = 'venueId';
    const PENDING = 'pending';
    const PRODUCT_TYPE = 'productType';
    const COURSE_TYPE = 'courseType';
    const BUSINESS_BEHAVIOR = 'businessBehavior';
    /**
     * @云运动 - 后台 - 查询所有订单
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
     * @param $params
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function getOrderInfo($params)
    {
        $this->customLoad($params);                     //搜索、排序条件处理
        $query = Order::find()
            ->alias("or")
            ->joinWith(["memberCard memberCard"=>function($query){
                $query->joinWith(['cardCategory cc'],false);
            }])
            ->joinWith(["member mem"=>function($query){
                $query->joinWith(['memberDetails md'],false);
            }])
            ->joinWith(["organization org"])
            ->joinWith(["employee ee"],false)
            ->joinWith(["employeeS eeS"],false)
            ->select("or.*,mem.id as mem_id,mem.username,org.id as org_id,org.name as venue_name,ee.name as createName,eeS.name as sellName, cc.single,md.pic")
            ->orderBy($this->sorts)
            ->asArray();
        $query = $this->setWhereSearch($query);
        return $query;
    }
    /**
     * @订单管理 - 所有订单数据分页
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/9/12
     * @param  $query
     * @return array
     */
    public function getSumData($query)
    {
        return Func::getDataProvider($query,8);
    }
    /**
     * 后台 - 订单管理 - 导出EXCEL
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/12/8
     * @param $query
     * @return string
     */
    public function getAllData($query)
    {
        return $query->all();
    }
    /**
     * @订单管理 - 所有订单金额
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/9/12
     * @param  $query
     * @return int
     */
    public function getSumDataMoney($query)
    {
        $class      = $query->all();
        $totalPrice = array_sum(array_column($class,'total_price'));
        return $totalPrice;
    }

    /**
     * @云运动 - 财务管理 - 卡种收入订单
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/21
     * @param $params
     * @param $isNotDisplay                     // 是否显示
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function getCardOrderInfo($params,$isNotDisplay="")
    {
        $this->cardOrderLoad($params);                     //搜索、排序条件处理
        $query = Order::find()
            ->alias("or")
            ->joinWith(["memberCard mc"=>function($query){
                $query->joinWith(['cardCategory cc'],false);
            }],false)
            ->joinWith(["member mem"],false)
            ->joinWith(["organization org"],false)
            ->joinWith(["employee ee"],false)
            ->where(['in','or.note',['售卡','续费','升级','办卡',"迈步一体机售卡"]]);
            if(empty($isNotDisplay)){
                $query->andWhere(['<>','or.status',5]);
            }else{
                $query->andWhere(["or.status"=>2]);
            }
            $query = $query->select("or.*,mc.card_number,mc.active_time,mc.invalid_time,mem.id as mem_id,mem.username,mem.mobile,org.id as org_id,org.name as venue_name,ee.name as createName,cc.card_name as card_category_name, cc.single")
            ->orderBy($this->sorts)
            ->groupBy('or.id')
            ->asArray();
        $query = $this->setOrderWhereSearch($query);
        return $query;
    }


    /**
     * @云运动 - 财务管理 - 卡种收入订单搜索数据处理
     * @author 焦冰洋 <jiaopbingyang@itsports.club>
     * @create 2017/8/21
     * @param $data
     * @return bool
     */
    public function cardOrderLoad($data)
    {
        $roleId                = \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId               = Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds          = array_column($vId, 'id');
        }else{
            $venuesId          = Auth::findOne(['role_id' => $roleId])->venue_id;
            $venueIds          = json_decode($venuesId);
        }
        $this->venueId         = (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->keywords        = (isset($data['keywords']) && !empty($data['keywords'])) ? $data['keywords'] : null;
        $this->sorts           = self::loadCardSort($data);        //排序
        $this->isReceipt       = isset($data['isReceipt']) ? $data['isReceipt'] : null;
        $this->note            = (isset($data['note']) && !empty($data['note'])) ? $data['note'] : null;
        $this->startTime       = (isset($data[self::START])&& !empty($data[self::START]))? (int)strtotime($data[self::START]) : null;
        $this->endTime         = (isset($data[self::END]) && !empty($data[self::END])) ? (int)strtotime($data[self::END]) : null;
        $this->status          = (isset($data[self::STATUS]) && !empty($data[self::STATUS])) ? (int)($data[self::STATUS]) : null;
        $this->payWay          = (isset($data[self::PAY_WAY]) && !empty($data[self::PAY_WAY])) ? (int)($data[self::PAY_WAY]) : null;
        $this->highest         = (isset($data[self::HIGHEST]) && !empty($data[self::HIGHEST])) ? (int)($data[self::HIGHEST]) : null;
        $this->lowest          = (isset($data[self::LOWEST]) && !empty($data[self::LOWEST])) ? (int)($data[self::LOWEST]) : null;
        $this->sellName        = (isset($data[self::SELL_NAME]) && !empty($data[self::SELL_NAME])) ? $data[self::SELL_NAME] : null;
        return true;
    }

    /**
     * 财务管理 - 卡种收入 - 获取排序条件
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/9/2
     * @param $data
     * @return mixed
     */
    public static function loadCardSort($data)
    {
        $sorts = ['id' => SORT_DESC];
        if(!isset($data['sortType'])){ return $sorts; }
        switch ($data['sortType']){
            case 'total_money'  :
                $attr = '`or`.total_price';
                break;
            case 'single'  :
                $attr = '`cc`.single';
                break;
            case 'pay_money_time' :
                $attr = '`or`.pay_money_time';
                break;
            default:
                $attr = NULL;
        };
        if($attr){
            $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }
    
    /**
     * @云运动 - 财务管理 - 卡种收入订单
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/21
     * @param $query
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function getCardOrderList($query)
    {
        $dataProvider         =     Func::getDataProvider($query,8);
        return $dataProvider;
    }
    /**
     * @云运动 - 财务管理 - 卡种收入订单
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/21
     * @param $query
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function getCardOrderTotal($query)
    {
        return $query->all();
    }

    /**
     * @云运动 - 财务管理 - 分摊金额列表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/22
     * @param $params
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function getShareInfo($params)
    {
        $this->shareLoad($params);                     //搜索、排序条件处理
        $query = Order::find()
            ->alias("or")
            ->joinWith(["memberCard mc"=>function($query){
                $query->joinWith(['cardCategory cc'],false);
                $query->joinWith(['entryRecord er'],false);
            }],false)
            ->joinWith(["member mem"],false)
            ->joinWith(["organization org"],false)
            ->joinWith(["employee ee"],false)
            ->joinWith(["leaveRecord lr"],false)
            ->andWhere(['or.consumption_type' => 'card'])
            ->andWhere(['<>','or.status',5])
            ->select("or.*,er.member_card_id as entry_card_id,er.entry_time,or.status as order_status,lr.is_approval,lr.leave_start_time,lr.leave_end_time,lr.status as leave_status,mem.id as mem_id,mem.username,mc.card_number,mc.status as card_status,mc.active_time,org.id as org_id,org.name as venue_name,ee.name as createName,cc.category_type_id,cc.card_name as card_category_name,cc.single,cc.duration,mc.create_at as create_card_time,mc.total_times,mc.invalid_time")
            ->orderBy($this->sorts)
            ->groupBy('or.id')
            ->asArray();
        $query     =     $this->setShareWhereSearch($query);
        return $query;
    }

    /**
     * @云运动 - 财务管理 - 分摊金额列表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/22
     * @param $query
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function getShareInfoList($query)
    {
        $dataProvider     =     Func::getDataProvider($query,8);
        return $dataProvider;
    }
    /**
     * @云运动 - 财务管理 - 分摊金额列表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/22
     * @param $query
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function getShareInfoTotal($query)
    {
        return $query->all();
    }

    /**
     * @云运动 - 财务管理 - 分摊搜索数据处理
     * @author 焦冰洋 <jiaopbingyang@itsports.club>
     * @create 2017/8/21
     * @param $data
     * @return bool
     */
    public function shareLoad($data)
    {
        $roleId            =   \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId           =   Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds      =   array_column($vId, 'id');
        }else{
            $venuesId      =   Auth::findOne(['role_id' => $roleId])->venue_id;
            $venueIds      =   json_decode($venuesId);
        }
        $this->venueId     =   (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->keywords    =   (isset($data['keywords']) && !empty($data['keywords'])) ? $data['keywords'] : null;
        $this->sorts       =   self::loadShareSort($data);        //排序
        $this->startTime   =   (isset($data[self::START])&& !empty($data[self::START]))? (int)strtotime($data[self::START]) : null;
        $this->endTime     =   (isset($data[self::END]) && !empty($data[self::END])) ? (int)strtotime($data[self::END]) : time();
        return true;
    }

    /**
     * 财务管理 - 分摊收入 - 获取排序条件
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/9/2
     * @param $data
     * @return mixed
     */
    public static function loadShareSort($data)
    {
        $sorts = ['id' => SORT_DESC];
        if(!isset($data['sortType'])){ return $sorts; }
        switch ($data['sortType']){
            case 'card_number'  :
                $attr = '`mc`.card_number';
                break;
            case 'total_price'  :
                $attr = '`or`.total_price';
                break;
            case 'pay_money_time' :
                $attr = '`or`.pay_money_time';
                break;
            default:
                $attr = NULL;
        };
        if($attr){
            $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }

    /**
     * @云运动 - 财务管理 - 卖课收入列表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/25
     * @param $params
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function  getSellClassInfo($params)
    {
        $this->classLoad($params);                     //搜索、排序条件处理
        $query = \backend\models\MemberCourseOrder::find()->alias("mco")
            ->joinWith(["member mem"=>function($query){
                $query->joinWith(["memberDetails memberDetails"],false);
            }],false)
            ->joinWith(["memberCardS mc"=>function($query){
                $query->joinWith(['cardCategory cc']);
            }],false)
            ->joinWith(["employeeS ee"=>function($query){
                $query->joinWith(["organizations org"]);
            }],false)
            ->joinWith(['chargeClass charge'],false)
            ->joinWith(['order order'=>function($query){
                $query->where(['in','order.consumption_type',['charge','chargeGroup']]);
            }],false)
            ->select(
                "mco.*,
                 mco.create_at as pay_money_time,
                 (mco.money_amount/mco.course_amount) as unit_price,
                 mco.overage_section as overage_num,
                 ee.name as private_name,
                 mem.mobile,
                 mem.id as mem_id,
                 memberDetails.name as member_name,
                 mc.card_number,
                 mc.status as card_status,
                 mc.active_time,
                 org.id as org_id,
                 org.name as venue_name,
                 ee.name as employeeName,
                 cc.card_name as card_category_name,
                 charge.product_type as productType,
                 order.order_time,
                ")
            ->orderBy($this->sorts)
            ->where([
                'or',
                ['<>','mco.course_amount',null],
                ['<>','mco.course_amount', 0]
            ])
            ->andWhere(['mco.pay_status'=>1])
            ->groupBy('mco.id')
            ->asArray();
        $query = $this->setClassWhereSearch($query);
        return $query;
    }

    /**
     * @云运动 - 财务管理 - 卖课搜索数据处理
     * @author 焦冰洋 <jiaopbingyang@itsports.club>
     * @create 2017/8/25
     * @param $data
     * @return bool
     */
    public function classLoad($data)
    {
        $roleId                = \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId               = Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds          = array_column($vId, 'id');
        }else{
            $venuesId          = Auth::findOne(['role_id' => $roleId])->venue_id;
            $venueIds          = json_decode($venuesId);
        }
        $this->venueId         = (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->keywords        = (isset($data['keywords']) && !empty($data['keywords'])) ? $data['keywords'] : null;
        $this->businessRemarks = (isset($data['businessRemarks']) && !empty($data['businessRemarks'])) ? $data['businessRemarks'] : null;
        $this->startTime       = (isset($data[self::START])&& !empty($data[self::START]))? (int)strtotime($data[self::START]) : null;
        $this->endTime         = (isset($data[self::END]) && !empty($data[self::END])) ? (int)strtotime($data[self::END]) : null;
        $this->highest         = (isset($data[self::HIGHEST]) && !empty($data[self::HIGHEST])) ? (int)($data[self::HIGHEST]) : null;
        $this->lowest          = (isset($data[self::LOWEST]) && !empty($data[self::LOWEST])) ? (int)($data[self::LOWEST]) : null;
        $this->productType     = (isset($data[self::PRODUCT_TYPE]) && !empty($data[self::PRODUCT_TYPE])) ? ($data[self::PRODUCT_TYPE]) : null;
        $this->courseType      = (isset($data[self::COURSE_TYPE]) && !empty($data[self::COURSE_TYPE])) ? ($data[self::COURSE_TYPE]) : null;
        $this->sorts           = self::loadSellSort($data);        //排序
        return true;
    }

    /**
     * 财务管理 - 卖课收入 - 获取排序条件
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/9/2
     * @param $data
     * @return mixed
     */
    public static function loadSellSort($data)
    {
        $sorts = ['id' => SORT_DESC];
        if(!isset($data['sortType'])){ return $sorts; }
        switch ($data['sortType']){
            case 'card_number'  :
                $attr = '`mc`.card_number';
                break;
            case 'course_amount' :
                $attr = '`mco`.course_amount';
                break;
            case 'unit_price'  :
                $attr = '`mco`.money_amount';
                break;
            case 'total_price'  :
                $attr = '`or`.total_price';
                break;
            case 'pay_money_time' :
                $attr = '`or`.pay_money_time';
                break;
            case 'overage_section' :
                $attr = '`mco`.overage_section';
                break;
            default:
                $attr = NULL;
        };
        if($attr){
            $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }

    /**
     * @云运动 - 财务管理 - 卖课收入列表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/25
     * @param $query
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function getSellClassInfoList($query)
    {
        $dataProvider         =     Func::getDataProvider($query,8);
        return $dataProvider;
    }

    /**
     * @云运动 - 财务管理 - 卖课收入列表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/25
     * @param $query
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function getSellClassInfoTotal($query)
    {
        return $query->all();
    }
    
    /**
     * @云运动 - 财务管理 - 分摊收入
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/21
     * @param $query
     * @return string
     */
    public function setShareWhereSearch($query)
    {
        $query->andFilterWhere(['or.venue_id' => $this->venueId]);
        $query->andFilterWhere([
            'or',
            ['like','or.sell_people_name',$this->keywords],
            ['like','or.member_name',$this->keywords],
            ['like','mc.card_number',$this->keywords]
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','mc.create_at',$this->startTime],
            ['<=','mc.create_at',$this->endTime]
        ]);
        return $query;
    }

    //获取已上过的课程
    public function attendClass()
    {
        $course = \backend\models\MemberCourseOrder::find()
            ->joinWith(['memberCourseOrderDetailsOne mcod'=>function($query){
                $query->joinWith(['aboutClass ac'],false);
            }],false)
            ->where(['ac.status'=>4])
            ->asArray()->all();
        $idArr  = array_column($course,'id');
        return $idArr;
    }
    /**
     * @云运动 - 财务管理 - 卡种收入订单
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/21
     * @param $query
     * @return string
     */
    public function setClassWhereSearch($query)
    {
        $query->andFilterWhere(['org.id' => $this->venueId]);
        $query->andFilterWhere(['like','mco.business_remarks',$this->businessRemarks]);
        $query->andFilterWhere([
            'or',
            ['like','mem.username',$this->keywords],
            ['=','mem.mobile',$this->keywords],
            ['=','mc.card_number',$this->keywords],
            ['like','ee.name',$this->keywords],
            ['like','mco.product_name',$this->keywords]
        ]);
        //私教课程订单根据订单的时间显示,搜索
        $query->andFilterWhere([
            'and',
            ['>=','order.order_time',$this->startTime],
            ['<=','order.order_time',$this->endTime]
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','mco.money_amount',$this->lowest],
            ['<=','mco.money_amount',$this->highest]
        ]);
        $query->andFilterWhere(['charge.product_type' => $this->productType]);
        if(!empty($this->courseType)){
            $idArr = $this->attendClass();
            if($this->courseType == '1'){          //已上过的课程
                $query->andWhere(['mco.id' => $idArr]);
            }elseif($this->courseType == '2'){     //未上过的课程
                $query->andWhere(['NOT IN','mco.id',$idArr]);
            }elseif($this->courseType == '3'){     //已过期的课程
                $query->andWhere(['<','mco.deadline_time',time()]);
            }
        }
        return $query;
    }

    /**
     * @云运动 - 财务管理 - 卡种收入订单
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/21
     * @param $query
     * @return string
     */
    public function setOrderWhereSearch($query)
    {
//        $query->andFilterWhere(['or.consumption_type' => 'card']);
//        $query->andFilterWhere(['or.status' => '2']);
        $query->andFilterWhere([
            'or',
            ['like','mem.username',$this->keywords],
            ['like','mem.mobile',$this->keywords],
            ['like','mc.card_number',$this->keywords],
            ['like','or.member_name',$this->keywords],
            ['like','or.sell_people_name',$this->keywords],
            ['like','or.card_name',$this->keywords],
        ]);
        $query->andFilterWhere(['or.venue_id' => $this->venueId]);
        $query->andFilterWhere([
            'and',
            ['>=','or.pay_money_time',$this->startTime],
            ['<','or.pay_money_time',$this->endTime]
        ]);
        $query->andFilterWhere(['or.note'=>$this->note]);
        if(!empty($this->payWay)){
            $query->where(['or',['or.pay_money_mode'=>$this->payWay],['IS NOT',"JSON_SEARCH(many_pay_mode,'all',$this->payWay,null,'$[*].type')",null]])
                ->andFilterWhere([
                'and',
                ['>=','or.pay_money_time',$this->startTime],
                ['<','or.pay_money_time',$this->endTime]
            ])
                ->andFilterWhere(['or.venue_id' => $this->venueId])
                ->andFilterWhere(['or.is_receipt'=> $this->isReceipt])
                ->andFilterWhere(['or.note'=>$this->note])
                ->andFilterWhere([
                    'and',
                    ['>=','or.total_price',$this->lowest],
                    ['<=','or.total_price',$this->highest]
                ]);
        }
//        $query->where(['IS NOT',"JSON_SEARCH(many_pay_mode,'all',$this->payWay,null,'$[*].type')",null]);
//        $query->andFilterWhere(['or.pay_money_mode'=>$this->payWay]);
        $query->andFilterWhere(['or.is_receipt'=> $this->isReceipt]);
        $query->andFilterWhere([
            'and',
            ['>=','or.total_price',$this->lowest],
            ['<=','or.total_price',$this->highest]
        ]);
        return $query;
    }

    /**
     * 后台 - 约课管理 - 处理搜索条件
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
     * @param $query
     * @return string
     */
    public function setWhereSearch($query)
    {
        if(!$this->venueId){
            $this->venueId = 0;
        }
        $query->andFilterWhere([
            'or',
            ['like','or.member_name',$this->keywords],
            ['like','or.sell_people_name',$this->keywords],
            ['or.order_number'=>$this->keywords]
        ]);
        $query->andFilterWhere([
            'and',
            [
                'or.status' => $this->status,
            ],
        ]);
        $query->andFilterWhere([
            'and',
            [
                'or.pay_money_mode' => $this->payWay,
            ],
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','or.pay_money_time',$this->startTime],
            ['<','or.pay_money_time',$this->endTime]
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','or.total_price',$this->lowest],
            ['<=','or.total_price',$this->highest]
        ]);
        if(!empty($this->pending) && $this->pending == 2){
            $query->andFilterWhere([
                'or',
                ['or.status'=>4],
                ['or.status'=>5],
                ['or.status'=>6]
            ]);
        }
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['or.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['or.venue_id'=>$this->nowBelongId]);
//        }

        $query->andFilterWhere(['or.venue_id' => $this->venueId]);
        if ($this->businessBehavior != null) {
            //业务行为搜索
            switch ($this->businessBehavior) {
                case 1 : $query->andFilterWhere([
                    'and',
                    ['or.consumption_type' => 'charge'],
                    ['or.note' => '私教产品']
                ]);break;
                case 2 : $query->andFilterWhere([
                    'and',
                    ['or.consumption_type' => 'chargeGroup'],
                    ['or.note' => '私教小团体']
                ]);break;
                case 3 : $query->andFilterWhere([
                    'and',
                    ['or.consumption_type' => 'card'],
                    ['or.note' => '办卡']
                ]);break;
                case 4 : $query->andFilterWhere([
                    'and',
                    ['or.consumption_type' => 'cardRenew'],
                    ['or.note' => '续费']
                ]);break;
                case 5 : $query->andFilterWhere([
                    'and',
                    ['or.consumption_type' => 'card'],
                    ['or.note' => '升级']
                ]);break;
                case 6 : $query->andFilterWhere([
                    'and',
                    ['or.consumption_type' => 'cabinet'],
                    ['or.note' => '租柜']
                ]);break;
                case 7 : $query->andFilterWhere([
                    'and',
                    ['or.consumption_type' => 'cabinet'],
                    ['or.note' => '续柜']
                ]);break;
                case 10 : $query->andFilterWhere([
                    'not in','or.note',['私教产品','私教小团体','办卡','续费','升级','租柜','续柜']
                ]);break;
            }
        }
        return $query;
    }
    /**
     * @云运动 - 后台 - 订单搜索数据处理
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
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
        $this->venueId         = (isset($data[self::VENUE_ID]) && !empty($data[self::VENUE_ID])) ? (int)($data[self::VENUE_ID]) : $venueIds;
        $this->keywords        = (isset($data['keywords']) && !empty($data['keywords'])) ? $data['keywords'] : null;  //搜索条件
        $this->searchContent   = (isset($data[self::SEARCH_CONTENT]) && !empty($data[self::SEARCH_CONTENT])) ? $data[self::SEARCH_CONTENT] : null;
        $this->nowBelongId     = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID])) ? $data[self::NOW_BELONG_ID] : null;
        $this->nowBelongType   = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE])) ? $data[self::NOW_BELONG_TYPE] : null;
        $this->startTime       = (isset($data[self::START])&& !empty($data[self::START]))? (int)strtotime($data[self::START]) : null;
        if (isset($data[self::END]) && !empty($data[self::END])) {
            $time = (int)strtotime($data[self::END]);
            $str = date('Y-m-d',$time);
            $this->endTime = (int)strtotime($str)+24*60*60-1;
        } else {
            $this->endTime = null;
        }
        $this->status          = (isset($data[self::STATUS]) && !empty($data[self::STATUS])) ? (int)($data[self::STATUS]) : null;
        $this->payWay          = (isset($data[self::PAY_WAY]) && !empty($data[self::PAY_WAY])) ? (int)($data[self::PAY_WAY]) : null;
        $this->highest         = (isset($data[self::HIGHEST]) && !empty($data[self::HIGHEST])) ? (int)($data[self::HIGHEST]) : null;
        $this->lowest          = (isset($data[self::LOWEST]) && !empty($data[self::LOWEST])) ? (int)($data[self::LOWEST]) : null;
        $this->sellName        = (isset($data[self::SELL_NAME]) && !empty($data[self::SELL_NAME])) ? $data[self::SELL_NAME] : null;
//        $this->venueId         = (isset($data[self::VENUE_ID]) && !empty($data[self::VENUE_ID])) ? (int)($data[self::VENUE_ID]) : null;
        $this->pending         = (isset($data[self::PENDING]) && !empty($data[self::PENDING])) ? (int)($data[self::PENDING]) : null;
        $this->sorts           = self::loadSort($data);        //排序
        $this->businessBehavior = (isset($data[self::BUSINESS_BEHAVIOR]) && !empty($data[self::BUSINESS_BEHAVIOR])) ? (int)$data[self::BUSINESS_BEHAVIOR] : null;
        return true;
    }

    /**
     * 会员卡管理 - 订单 - 获取排序条件
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
     * @param $data
     * @return mixed
     */
    public static function loadSort($data)
    {

        $sorts = ['id' => SORT_DESC];
        if(!isset($data['sortType'])){ return $sorts; }
        switch ($data['sortType']){
            case 'venue'  :
                $attr = '`or`.venue_id';
                break;
            case 'order_number'  :
                $attr = '`or`.order_number';
                break;
            case 'member_name':
                $attr = '`or`.member_name';
                break;
            case 'card_name':
                $attr = '`or`.card_name';
                break;
            case 'total_price' :
                $attr = '`or`.total_price';
                break;
            case 'sell_people_name' :
                $attr = '`or`.sell_people_name';
                break;
            case  'payee_name' :
                $attr = '`or`.payee_name';
                break;
            case  'order_time' :
                $attr = '`or`.order_time';
                break;
            case 'status':
                $attr = '`or`.status';
                break;
            default:
                $attr = '`or`.order_time';
        };
        if($attr){
            $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }
    /**
     * 会员卡管理 - 会员卡 - 获取搜索规格
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
     * @param $sort
     * @return mixed
     */
    public static function convertSortValue($sort)
    {
        if ($sort == 'ASC') {
            return SORT_ASC;
        } elseif ($sort == 'DES') {
            return SORT_DESC;
        } else {
            return SORT_DESC;
        }
    }
    /**
     * @云运动 - 后台 - 查询一条订单
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function getOrderOneData($id)
    {
        $data = Order::find()
            ->alias("or")
            ->joinWith(["organization org"],false)
            ->joinWith(["employeeM ee"],false)
            ->joinWith(["employeeS ees"],false)
            ->select("or.*,org.name as venue_name,ee.name as createName,ees.name as sellName")
            ->where(["or.id"=>$id])
            ->asArray()
            ->one();
        return $data;
    }
    /**
     * @云运动 - 后台 - 查询一条订单
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/24
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function getOrderData($id)
    {
        $data = Order::find()
            ->alias("or")
            ->joinWith(["memberCard mc"=>function($query){
                $query->joinWith(['cardCategory cc'],false);
            }])
            ->joinWith(["member mm"],false)
            ->joinWith(["memberDetails md"],false)
            ->select("or.*,md.pic,md.sex,mm.username,mm.id as mem_id,mm.mobile,cc.duration,mc.card_number,cc.single,mc.note as mcNote")
            ->where(["or.id"=>$id])
            ->asArray()
            ->one();
        return $data;
    }
    /**
     * @云运动 - 后台 - 修改取消订单的状态
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/3
     * @param $id       //订单id
     * @return array|null|\yii\db\ActiveRecord      //结果
     */
    public function setOrderOneData($id)
    {
        $data         = \common\models\base\Order::findOne($id);
        $data->status = 3;            //修改订单状态(取消订单状态)
        if($data->save())
        {
            return $data;
        }else{
            return $data->errors;
        }
    }
    /**
     * @云运动 - 卡种收入 - 开票操作
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/21
     * @param $id       //订单id
     * @return array|null|\yii\db\ActiveRecord      //结果
     */
    public function setOrderIsReceipt($id)
    {
        $data         = \common\models\base\Order::findOne($id);
        $data->is_receipt = 1;            //开票动作
        if($data->save())
        {
            return $data;
        }else{
            return $data->errors;
        }
    }
    /**
     * @云运动 - 后台 - 确认订单
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/3
     * @param $data     //确认信息
     * @return bool    //返回值
     */
    public function OrderPaymentData($data)
    {
        if($data['payMoneyMode']){
            $order                  = \common\models\base\Order::findOne($data['orderId']);
            $order->status          = 2;                                        //修改订单状态（已付款状态）
            $order->note            = $data['note'];                            //订单备注
            $order->pay_money_mode  = $data['payMoneyMode'];                    //付款方式
//            $order->pay_people_name = $data['payPeopleName'];                   //付款人姓名
//            $order->payee_name      = $data['payeeName'];                       //收款人姓名
            $order->pay_money_time  = time();                                   //付款时间
            $order->create_id       =  \Yii::$app->user->identity->id;          // 操作人id
//            $order->payee_id        =  \Yii::$app->user->identity->id;          // 收款人id

            if(!$order->save())
            {
                return $data->errors;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    /**
     * 后台 - 销售主页 - 处理搜索条件
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $attr
     * @return string
     */
    public function getDateWhere($attr)
    {
        if($attr == 'w'){
            $nowDate =  date("Y-m-d");
            $first   =  1;
            $w       =  date('w',strtotime($nowDate));
            $this->searchDateStart = Func::getTokenClassDate($attr,true);
            $this->searchDateEnd   = Func::getTokenClassDate($attr,false);
        }elseif($attr == 'd'){
            $this->searchDateStart = Func::getGroupClassDate($attr,true);
            $this->searchDateEnd   = Func::getGroupClassDate($attr,false);
        }else{
            $this->searchDateStart = Func::getGroupClassDate($attr,true);
            $this->searchDateEnd   = Func::getGroupClassDate($attr,false);
        }
    }


    
    /**
     * 销售主页 - 销售额
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $params
     * @param $isNotDisplay
     * @return array
     */
    public function getSalesMoney($params,$isNotDisplay="")
    {
        $this->custom($params);
            $query  =  Order::find()
                ->joinWith([
                    'member member'=>function($query){
                        $query->joinWith(['memberDetails memberDetails']);
                    }])
                ->select("cloud_order.*");
//                ->where(["<>","cloud_order.status",5])
//                ->where(["cloud_order.status"=>2])
                if(empty($isNotDisplay)){
                    $query->where(["cloud_order.status"=>2]);
                }else{
                    $query->where(["cloud_order.status"=>2]);
                }
               $query =$query->orderBy($this->sorts)
                ->asArray();
       return  $query    = $this->setWhere($query);         //场馆和公司判断
    }

    public function getSalesMoneyList($query)
    {
        return Func::getDataProvider($query,8);
    }
    
    public function getSalesMoneyAll($query)
    {
        return $query->andWhere(['<>','cloud_order.note','回款'])->all();
    }
    
    /**
     * 后台 - 销售主页 - 销售额处理搜索条件
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/7/31
     * @param $query
     * @return string
     */
    public function setWhere($query)
    {
        $query->andFilterWhere([
            'or',
            ['like','cloud_order.member_name', $this->keywords],
            ['like','cloud_order.order_number', $this->keywords]
        ]);
        if($this->searchGood == '其它'){
            $query->andFilterWhere(['NOT LIKE','cloud_order.note', ['售卡','购卡','办卡','续费','私教产品']]);
        }elseif($this->searchGood == '售卡'){    //会员卡
            $query->andFilterWhere(['or',
                ['like','cloud_order.note', '售卡'],
                ['like','cloud_order.note', '购卡'],
                ['like','cloud_order.note', '办卡']
            ]);
        }else{
            $query->andFilterWhere(['like','cloud_order.note', $this->searchGood]);
        }

        $query->andFilterWhere([
            'and',
            ['>=','cloud_order.pay_money_time',$this->startTime],
            ['<','cloud_order.pay_money_time',$this->endTime]
        ]);
        $query->andFilterWhere(['cloud_order.venue_id'=>$this->venueId]);
        return $query;
    }

    /**
     * 销售主页 - 销售额 - 搜索数据处理数据
     * @create 2017/7/21
     * @author 黄华 <huanghua@itsports.club>
     * @param $data
     * @return bool
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
        $this->venueId       = (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->nowBelongId   = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->startTime     = (isset($data[self::START])&& !empty($data[self::START]))? (int)strtotime($data[self::START]) : null;
        $this->endTime       = (isset($data[self::END]) && !empty($data[self::END])) ? (int)strtotime($data[self::END]) : null;
        $this->keywords      = (isset($data[self::KEY]) && !empty($data[self::KEY])) ? $data[self::KEY] : null;
        $this->searchGood    = (isset($data[self::SEARCH_GOOD]) && !empty($data[self::SEARCH_GOOD]))?$data[self::SEARCH_GOOD]: NULL;
        $this->sorts         = self::sorts($data);
        return true;
    }

    /**
     * 销售主页 - 客流量列表 - 获取排序条件
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $data
     * @return mixed
     */
    public static function sorts($data)
    {
        $sorts = [
            'id' => SORT_DESC
        ];
        if (!isset($data['sortType'])) {
            return $sorts;
        }
        switch($data['sortType'])
        {
            case 'order_number' :
                $attr = '`cloud_order`.order_number';
                break;
            case 'member_name' :
                $attr = '`cloud_order`.member_name';
                break;
            case 'goods_type' :
                $attr = '`cloud_order`.note';
                break;
            case 'goods_name' :
                $attr = '`cloud_order`.card_name';
                break;
            case 'total_price' :
                $attr = '`cloud_order`.total_price';
                break;
            case 'pay_moneyTime' :
                $attr = '`cloud_order`.pay_money_time';
                break;
            default;
                return $sorts;
        }
        return $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
    }

    /**
     * 销售主页 - 销售额总价
     * @author huanghua<huanghua@itsports.club>
     * @create 2017/7/22
     * @param $id
     * @param $dateType
     * @return int
     */
    public function TotalMoney($id,$dateType)
    {
        $this->getDateWhere($dateType);
        $query  = Order::find()
            ->select(['total_price'])
            ->where(['between','pay_money_time',strtotime($this->searchDateStart),strtotime($this->searchDateEnd)])
            ->andWhere(["status"=>2])
            ->andWhere(['<>','cloud_order.note','回款'])
            ->asArray();
        $query = $this->getWhere($query,$id);
        $date  = 0;
        foreach ($query as $k =>$v){
            $date += (int)$v['total_price'];
        }
        return $date;
    }

    /**
     * 后台 - 销售主页 - 处理搜索条件
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/7/22
     * @param $query
     * @param $id
     * @return string
     */
    public function getWhere($query,$id)
    {
        $card     =     new CardCategory();
        $venueId  =    (isset($id) && !empty($id)) ? $id : $card->getVenueIdByRole();

        $query->andFilterWhere(['cloud_order.venue_id' => $venueId]);
        return $query->all();
    }

    /**
     * @后台 - 订单管理 - 申请退款
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/26
     * @return bool
     */
    public function applyRefund($data)
    {
        $order = \common\models\base\Order::findOne(['id' => $data['orderId']]);
        $orderTwo = Order::find()->where(['id'=>$data['orderId']])
            ->andWhere(['and',['consumption_type'=>'deposit'],['note'=>'订金']])
            ->asArray()
            ->one();
        if(!empty($orderTwo)){
            $deposit = MemberDeposit::findOne(['member_id'=>$orderTwo['member_id']]);
            if(empty($deposit)){
                return "该会员已经用过订金,不能退款";
            }
        }
        if(!empty($order)){
            $adminModel = Employee::findOne(['admin_user_id'=>\Yii::$app->user->identity->id]);
            $order->create_id   = isset($adminModel->id)?intval($adminModel->id):0;     //操作人
            $order->status      = 4;                      //退款
            $order->refund_note = $data['refundNote'];    //退款理由
            $order->apply_time  = time();                 //申请退款时间
            if($order->save()){
                return true;
            }else{
                return $order->errors;
            }
        }
    }

    /**
     * @后台 - 订单管理 - 拒绝申请
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/27
     * @return bool
     */
    public function refuseApply($data)
    {
        $order = \common\models\base\Order::findOne(['id' => $data['orderId']]);
        if(!empty($order)){
            $adminModel = Employee::findOne(['admin_user_id'=>\Yii::$app->user->identity->id]);
            $order->create_id   = isset($adminModel->id)?intval($adminModel->id):0;     //操作人
            $order->status      = 6;                      //拒绝申请
            $order->refuse_note = $data['refuseNote'];    //拒绝原因
            $order->review_time = time();                 //拒绝退款时间
            if($order->save()){
                return true;
            }else{
                return $order->errors;
            }
        }
    }

    /**
     * @后台 - 订单管理 - 同意申请
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/27
     * @return bool
     */
    public function agreeApply($orderId,$venueId,$companyId)
    {
        $order = \common\models\base\Order::findOne(['id' => $orderId]);
        if(!empty($order)){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $adminModel = Employee::findOne(['admin_user_id'=>\Yii::$app->user->identity->id]);
                $order->create_id   = isset($adminModel->id)?intval($adminModel->id):0;     //操作人
                $order->status      = 5;                      //同意申请
                $order->review_time = time();                 //同意退款时间
                if($order->save() !== true){
                    return $order->errors;
                }
                $relation = $this->relation($order,$venueId,$companyId);
                if($relation !== true){
                    return $relation;
                }

                if ($transaction->commit() === null) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                return  $e->getMessage();
            }
        }
    }

    /**
     * @后台 - 订单管理 - 同意退费的相关问题
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/31
     * @return bool
     */
    public function relation($order,$venueId,$companyId)
    {
        if ($order['consumption_type'] == 'card') {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $card = MemberCard::findOne(['id' => $order['consumption_type_id']]);
                $card->status = 2;          //会员卡退费，会员卡状态改为异常
                if($card->save() !== true){
                    return $card->errors;
                }
                //生成会员卡退费记录
                $consumption                      = new ConsumptionHistory();
                $consumption->member_id           = $order['member_id'];
                $consumption->consumption_type    = $order['consumption_type'];
                $consumption->consumption_type_id = $order['consumption_type_id'];
                $consumption->type                = 1;
                $consumption->consumption_date    = time();
                $consumption->consumption_time    = time();
                $consumption->consumption_times   = 1;
                $consumption->venue_id            = $venueId;
                $consumption->company_id          = $companyId;
                $consumption->seller_id           = $order['create_id'];
                $consumption->describe            = json_encode('会员卡退费');
                $consumption->category            = '会员卡退费';
                $consumption->consume_describe    = json_encode('会员卡退费');
                if($consumption->save() !== true){
                    return $consumption->errors;
                }

                if ($transaction->commit() === null) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $e->getMessage();
            }
        } elseif ($order['consumption_type'] == 'cabinet') {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if ($order['note'] == '租柜') {
                $memberCabinetModel  = MemberCabinet::findOne($order['consumption_type_id']);
                $cabinet = Cabinet::findOne(['id' => $memberCabinetModel['cabinet_id']]);
                $cabinet->status = 1;          //柜子退费，柜子状态改为未租
                if($cabinet->save() !== true){
                    return $cabinet->errors;
                }
                //生成柜子退费记录
                $consumption                      = new ConsumptionHistory();
                $consumption->member_id           = $order['member_id'];
                $consumption->consumption_type    = $order['consumption_type'];
                $consumption->consumption_type_id = $order['consumption_type_id'];
                $consumption->type                = 1;
                $consumption->consumption_date    = time();
                $consumption->consumption_time    = time();
                $consumption->consumption_times   = 1;
                $consumption->venue_id            = $venueId;
                $consumption->company_id          = $companyId;
                $consumption->seller_id           = $order['create_id'];
                $consumption->describe            = json_encode('柜子退费');
                $consumption->category            = '柜子退费';
                $consumption->consume_describe    = json_encode('柜子退费');
                if($consumption->save() !== true){
                    return $consumption->errors;
                }

                // 会员柜子历史表的数据录入
                $cabinetHis                 = new MemberCabinetRentHistory();
                $cabinetHis->member_id      = $order['member_id'];
                $cabinetHis->start_rent     = $memberCabinetModel['start_rent'];
                $cabinetHis->end_rent       = $memberCabinetModel['end_rent'];
                $cabinetHis->back_rent      = time();
                $cabinetHis->create_at      = time();
                $cabinetHis->cabinet_id     = $order['consumption_type_id'];
                $cabinetHis->member_card_id = $memberCabinetModel['member_card_id'];
                $cabinetHis->rent_type      = '柜子退费';
                if($cabinetHis->save() !== true){
                    return $cabinetHis->errors;
                }
                //对操作柜子的 执行删除
                $deleteResult  = $memberCabinetModel->delete();
                if($deleteResult!=1){
                    return "当前会员柜子表删除失败";
                }
                if ($transaction->commit() === null) {
                    return true;
                } else {
                    return false;
                }
                }
                if ($order['note'] == '续柜') {
                    $memberCabinetModel  = MemberCabinet::findOne($order['consumption_type_id']);
                    //生成柜子退费记录
                    $consumption                      = new ConsumptionHistory();
                    $consumption->member_id           = $order['member_id'];
                    $consumption->consumption_type    = $order['consumption_type'];
                    $consumption->consumption_type_id = $order['consumption_type_id'];
                    $consumption->type                = 1;
                    $consumption->consumption_date    = time();
                    $consumption->consumption_time    = time();
                    $consumption->consumption_times   = 1;
                    $consumption->venue_id            = $venueId;
                    $consumption->consumption_amount        = $order['total_price'];
                    $consumption->company_id          = $companyId;
                    $consumption->seller_id           = $order['create_id'];
                    $consumption->describe            = json_encode('续柜退费');
                    $consumption->cash_payment        = $order['total_price'];
                    $consumption->category            = '续柜退费';
                    $consumption->consume_describe    = json_encode('续柜退费');
                    if($consumption->save() !== true){
                        return $consumption->errors;
                    }
                    $memberCabinetHistory = MemberCabinetRentHistory::findOne(['member_id'=>$order['member_id']]);
                    // 会员柜子历史表的数据录入
                    $cabinetHis                 = new MemberCabinetRentHistory();
                    $cabinetHis->member_id      = $order['member_id'];
                    $cabinetHis->start_rent     = $memberCabinetHistory['start_rent'];
                    $cabinetHis->end_rent       = $order['new_note'];
                    $cabinetHis->back_rent      = $memberCabinetHistory['back_rent'];
                    $cabinetHis->create_at      = $memberCabinetHistory['create_at'];
                    $cabinetHis->cabinet_id     = $order['consumption_type_id'];
                    $cabinetHis->price          = $memberCabinetHistory['price'];
                    $cabinetHis->member_card_id = $memberCabinetHistory['member_card_id'];
                    $cabinetHis->rent_type      = '柜子续费退款后';
                    if($cabinetHis->save() !== true){
                        return $cabinetHis->errors;
                    }
                    $memberCabinetModel->end_rent = $memberCabinetHistory['end_rent'];
                    $memberCabinetModel->price    = $memberCabinetModel->price - $memberCabinetHistory['price'];
                    if (!$memberCabinetModel->save()) {
                        return $memberCabinetModel->errors;
                    }
                    if ($transaction->commit() === null) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $e->getMessage();
            }


        } elseif ($order['consumption_type'] == 'charge') {
            MemberCourseOrder::updateAll(['pay_status'=>2],['id'=>$order['consumption_type_id']]);
            //生成私教课退费记录
            $consumption                      = new ConsumptionHistory();
            $consumption->member_id           = $order['member_id'];
            $consumption->consumption_type    = $order['consumption_type'];
            $consumption->consumption_type_id = $order['consumption_type_id'];
            $consumption->type                = 1;
            $consumption->consumption_date    = time();
            $consumption->consumption_time    = time();
            $consumption->consumption_times   = 1;
            $consumption->venue_id            = $venueId;
            $consumption->company_id          = $companyId;
            $consumption->seller_id           = $order['create_id'];
            $consumption->describe            = json_encode('私课退费');
            $consumption->category            = '私课退费';
            $consumption->consume_describe    = json_encode('私课退费');
            if($consumption->save() !== true){
                return $consumption->errors;
            }else{
                return true;
            }
        } elseif ($order['consumption_type'] == 'deposit') {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $deposit = MemberDeposit::findOne(['id' => $order['consumption_type_id']]);
                $deposit->delete();
                //生成会员定金退费记录
                $consumption                      = new ConsumptionHistory();
                $consumption->member_id           = $order['member_id'];
                $consumption->consumption_type    = $order['consumption_type'];
                $consumption->consumption_type_id = $order['consumption_type_id'];
                $consumption->type                = 1;
                $consumption->consumption_date    = time();
                $consumption->consumption_time    = time();
                $consumption->consumption_times   = 1;
                $consumption->venue_id            = $venueId;
                $consumption->company_id          = $companyId;
                $consumption->seller_id           = $order['create_id'];
                $consumption->describe            = json_encode('定金退费');
                $consumption->category            = '定金退费';
                $consumption->consume_describe    = json_encode('定金退费');
                if($consumption->save() !== true){
                    return $consumption->errors;
                }

                if ($transaction->commit() === null) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $e->getMessage();
            }
        } elseif ($order['consumption_type'] == 'cardRenew') {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $card = MemberCard::findOne(['id' => $order['consumption_type_id']]);
                $historyOne = ConsumptionHistory::find()->where(['consumption_type'=>'cardRenew'])->andWhere(['consumption_type_id'=>$card['id']])->orderBy('id DESC')->one();
                $historyOne->delete();
                $historyTwo = ConsumptionHistory::find()
                    ->where(['consumption_type'=>'cardRenew'])
                    ->andWhere(['consumption_type_id'=>$card['id']])
                    ->orderBy('id DESC')
                    ->asArray()->one();
                if(!empty($historyTwo)){
                    $card->invalid_time = $historyTwo['due_date'];       //会员卡失效时间改成续费前的失效时间
                }else{
                    if($card['status'] == 4){
                        $card->invalid_time = $card['create_at'] + $card['duration']*24*60*60;       //会员卡失效时间改成续费前的失效时间
                    }else{
                        $times = $card['active_time'] - $card['create_at'];
                        if($times <= ($card['active_limit_time']*24*60*60)){
                            $card->invalid_time = $card['create_at'] + $times + $card['duration']*24*60*60;       //会员卡失效时间改成续费前的失效时间
                        }else{
                            $card->invalid_time = $card['create_at'] + $card['active_limit_time']*24*60*60 + $card['duration']*24*60*60;       //会员卡失效时间改成续费前的失效时间
                        }
                    }
                }
                if($card->save() !== true){
                    return $card->errors;
                }
                //生成会员卡退费记录
                $consumption                      = new ConsumptionHistory();
                $consumption->member_id           = $order['member_id'];
                $consumption->consumption_type    = 'card';
                $consumption->consumption_type_id = $order['consumption_type_id'];
                $consumption->type                = 1;
                $consumption->consumption_date    = time();
                $consumption->consumption_time    = time();
                $consumption->consumption_times   = 1;
                $consumption->venue_id            = $venueId;
                $consumption->company_id          = $companyId;
                $consumption->seller_id           = $order['create_id'];
                $consumption->describe            = json_encode('增加卡有效期退费');
                $consumption->category            = '增加卡有效期退费';
                $consumption->consume_describe    = json_encode('增加卡有效期退费');
                if($consumption->save() !== true){
                    return $consumption->errors;
                }

                if ($transaction->commit() === null) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $e->getMessage();
            }
        }
//        return true;
    }

    /**
     * @云运动 - 财务管理 - 其他收入搜索数据处理
     * @author 焦冰洋 <jiaopbingyang@itsports.club>
     * @create 2017/9/1
     * @param $data
     * @return bool
     */
    public function otherLoad($data)
    {
        $roleId               =   \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId              =   Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds         =   array_column($vId, 'id');
        }else{
            $venuesId         =   Auth::findOne(['role_id' => $roleId])->venue_id;
            $venueIds         =   json_decode($venuesId);
        }
        $this->venueId        =   (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->keywords       =   (isset($data['keywords']) && !empty($data['keywords'])) ? $data['keywords'] : null;
        $this->sorts          =   self::loadOtherSort($data);        //排序
        $this->startTime      =   (isset($data[self::START])&& !empty($data[self::START]))? (int)strtotime($data[self::START]) : null;
        $this->endTime        =   (isset($data[self::END]) && !empty($data[self::END])) ? (int)strtotime($data[self::END]) : null;
        $this->payMoneyMode   =   (isset($data['pay_money_mode']) && !empty($data['pay_money_mode'])) ? $data['pay_money_mode'] : null;
        $this->note           =   (isset($data['note']) && !empty($data['note'])) ? $data['note'] : null;
        return true;
    }

    /**
     * 财务管理 - 其他收入 - 获取排序条件
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/9/2
     * @param $data
     * @return mixed
     */
    public static function loadOtherSort($data)
    {
        $sorts = ['id' => SORT_DESC];
        if(!isset($data['sortType'])){ return $sorts; }
        switch ($data['sortType']){
            case 'order_number'  :
                $attr = '`or`.order_number';
                break;
            case 'total_price' :
                $attr = '`or`.total_price';
                break;
            case 'pay_money_time' :
                $attr = '`or`.pay_money_time';
                break;
            default:
                $attr = NULL;
        };
        if($attr){
            $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }

    /**
     * @云运动 - 财务管理 - 其他收入列表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/9/1
     * @param $params
     * @return array|\yii\db\ActiveRecord[]     //查询结果
     */
    public function getOtherInfo($params)
    {
        $this->otherLoad($params);
        $query = Order::find()
            ->alias("or")
            ->joinWith(["organization org"],false)
            ->joinWith(["memberCard mc"],false)
            ->joinWith(["member mm"],false)
            ->select("or.*,org.name as venue_name,mm.mobile")
            ->orderBy($this->sorts)
            ->where(['in','or.note',['租柜','会员卡转卡','订金','手环工本费','续柜']])
            ->andWhere(['<>','or.status',5])
            ->asArray();
        $query     =     $this->setOtherWhereSearch($query);
        return $query;
    }

    /**
     * 后台 - 财务管理 - 其他收入
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/9/1
     * @param $query
     * @return string
     */
    public function getOtherList($query)
    {
        $dataProvider  =  Func::getDataProvider($query,8);
        return  $dataProvider;
    }
    /**
     * 后台 - 财务管理 - 其他收入 - 用于统计
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/9/1
     * @param $query
     * @return string
     */
    public function getOtherTotal($query)
    {
        return $query->all();
    }

    /**
     *@云运动 - 财务管理 - 其他收入列表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/9/1
     * @param $query
     * @return string
     */
    public function setOtherWhereSearch($query)
    {
        $query->andFilterWhere([
            'or',
            ['like','or.pay_people_name',$this->keywords],
            ['like','mm.mobile',$this->keywords],
            ['like','or.sell_people_name',$this->keywords],
            ['like','or.order_number',$this->keywords],
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','or.pay_money_time',$this->startTime],
            ['<','or.pay_money_time',$this->endTime]
        ]);
        $query->andFilterWhere(['or.note' => $this->note]);
        $query->andFilterWhere(['or.pay_money_mode' => $this->payMoneyMode]);
        $query->andFilterWhere(['or.venue_id' => $this->venueId]);
        return $query;
    }

    /**
     * @云运动 - 潜在会员购卡 - 获取登记时选择的卡种
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/17
     */
    public function selectedCard($idCard)
    {
        $memberId = MemberDetails::findOne(['id_card' => $idCard]);
        $order    = Order::find()->alias('or')
            ->joinWith(['cardCategory cardCategory'])
            ->where(['or.member_id' => $memberId['member_id']])
            ->andWhere(['or.status'=>1])
            ->select('or.venue_id,or.card_category_id,cardCategory.card_name')
            ->asArray()
            ->one();
        return $order;
    }
    /**
     * 后台 - 会员管理 - 订单基本信息删除
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/11/27
     * @param $id
     * @return bool
     */
    public  function  getOrderDel($id)
    {
        $order = Order::findOne($id);
        $resultDelMem = $order->delete();
        if ($resultDelMem) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * @云运动 - 订单管理 - 修改订单的参数
     * @author 侯凯新 <houkaixn@itsports.club>
     * @param  $param  // 修改订单的 参数
     * @create 2017/7/17
     * @return  mixed
     */
    public function updateOrder($param){
         if(empty($param["orderId"])){
            return false;
         }
         $orderTime  = !empty($param["orderTime"])?strtotime($param["orderTime"]):null;
         $order = \common\models\base\Order::findOne($param["orderId"]);
         // 获取所属公司id
         $company  = Organization::findOne($param["venueId"]);
         // 获取所属公司员工姓名
         $employee = Employee::findOne($param["employeeId"]);
         $order->total_price      = $param["orderPrice"];
         $order->all_price        = $param['orderPrice'];
         $order->net_price        = $param['orderPrice'];
         $order->pay_money_time   = (int)$orderTime;
         $order->order_time       = (int)$orderTime;
         $order->note              = $param["businessNote"];
         $order->venue_id          = $param["venueId"];
         $order->company_id        = $company->pid;
         if(!empty($employee)){
             $order->sell_people_id = $employee->id;
         }
         if(!empty($employee->name)){
             $order->sell_people_name = $employee->name;
         }
         if(!$order->save()){
             return $order->errors;
         }
        if($order->consumption_type == 'card'){
            $memberCard = MemberCard::findOne(['id'=>$order->consumption_type_id]);
            $memberCard->amount_money = $param['orderPrice'];
            $memberCard->create_at    = (int)$orderTime;
            $memberCard->save();
            $history  = ConsumptionHistory::findOne(['consumption_type'=>'card','consumption_type_id'=>$memberCard->id]);
            if(!empty($history)){
                $history->consumption_date = (int)$orderTime;
                $history->consumption_time = (int)$orderTime;
                $history->consumption_amount = $param['orderPrice'];
                $history->save();
            }
        }
        if($order->consumption_type == 'charge'){
            $memberOrder = MemberCourseOrder::findOne(['id'=>$order->consumption_type_id]);
            $memberOrder->money_amount = $param['orderPrice'];
            $memberOrder->create_at    = (int)$orderTime;
            $memberOrder->save();
            $history  = ConsumptionHistory::findOne(['consumption_type'=>'charge','consumption_type_id'=>$memberOrder->id]);
            if(!empty($history)){
                $history->consumption_date = (int)$orderTime;
                $history->consumption_time = (int)$orderTime;
                $history->consumption_amount = $param['orderPrice'];
                $history->save();
            }
        }
        return true;
    }
}