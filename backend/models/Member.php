<?php
namespace backend\models;

use common\models\base\EntryRecord;
use common\models\base\MemberBase;
use common\models\base\MemberCourseOrder;
use common\models\base\MemberDeposit;
use common\models\base\MemberDetails;
use common\models\base\AboutYard;
use common\models\base\MemberCabinet;
use common\models\base\Cabinet;
use common\models\base\Organization;
use common\models\base\ScanCodeRecord;
use common\models\base\MemberAccount;
use common\models\CardCategory;
use common\models\MemberCard;
use common\models\Func;
use common\models\VenueLimitTimes;
use dosamigos\qrcode\formats\vCard;
use Yii;
use common\models\relations\MemberRelations;

class Member extends \common\models\Member
{
    use MemberRelations;
    public $name;
    public $mobile;
    public $sex;
    public $keywords;
    public $keyword;
    public $startTime;
    public $endTime;
    public $type;
    public $cardId = array();
    public $sorts;
    public $searchParams;                                   //搜索条件（潜在会员搜索）
    public $sort;                                           //潜在会员排序
    public $wayToShop;
    public $freeze;//冻结
    public $vacate;//请假
    public $privates;//私课
    public $status;
    public $cardTimeStart;
    public $cardTimeEnd;

    public $potMember;                                    //潜在会员搜索
    public $newMember;                                    //新会员
    public $nowBelongId;
    public $nowBelongType;
    public $sellId;//销售id
    public $coachId;
    public $nowVenueId;   // 场馆id
    public $nowCompanyId; // 公司id
    public $venueId;
    public $free;
    public $birthdayClass;
    const SELL_ID ='sellId';
    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';

    public $searchDateStart;//搜索开始时间
    public $searchDateEnd;//搜索结束时间
    public $cardType;
    public $keySell;//销售主页input框搜索条件 姓名、卡号、手机号、顾问id
    public $memberType;//搜索会员类型
    public $venueIdArr;//会员卡能通店的场馆id数组

    const KEY      = 'keywords';
    const WAY      = 'wayToShop';
    const START    = 'startTime';
    const END      = 'endTime';
    const MEMBER   = 'member_id';
    const SEARCH   = 'keyword';
    const COACH_ID = 'coachId';
    const PRIVATE_TYPE = 'type';
    const CARD_TIME_START = "cardTimeStart";
    const CARD_TIME_END = "cardTimeEnd";
    const MEMBER_TYPE = 'memberType';

    const POTENTIAL_MEMBER = "potMember";
    const NEW_MEMBER       = "newMember";
    const FREEZE   = "freeze";
    const VACATE   = "vacate";
    const PRIVATES = "privates";
    const KEY_SELL = "keySell";
    const TYPE     = 'cardType';
    const STATUS   = 'status';
    const VENUE_ID = 'venueId';
    const KEY_TYPE = 'type';
    const FREE ='free';
    const BIRTHDAY_CLASS = 'birthdayClass';
    /**
     * 会员卡管理 - 会员信息管理 - 获取搜索规格
     * @create 2017/4/11
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
     * 会员卡管理 - 会员信息管理 - 获取排序条件(修改内容：优化)
     * @create 2017/4/11
     * @author huanghua<huanghua@itsports.club>
     * @update huangpengju<huangpengju@itsports.club>
     * @update 2017/4/13
     * @param $data
     * @return mixed
     */
    public static function loadSort($data)
    {
        $sorts = [
            'id' => SORT_DESC
        ];
        if (!isset($data['sortType'])) {
            return $sorts;
        }
        switch($data['sortType'])
        {
            case 'member_name'          :
                $attr = '`memberDetails`.name';
                break;
            case 'member_sex'           :
                $attr = '`memberDetails`.sex';
                break;
            case 'member_age'           :
                $attr = '`memberDetails`.birth_date';
                break;
            case 'member_mobile'        :
                $attr = '`member`.mobile';
                break;
            case 'member_active_time'   :
                $attr = '`memberCard`.active_time';
                break;
            default;
                return $sorts;
        }
        return $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];

    }

    /**
     * 后台会员管理 - 会员信息查询 - 多表查询
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/3/30
     * @update author Huang hua <huanghua@itsports.club>
     * @param $id
     * @create 2017/4/1
     * @return \yii\db\ActiveQuery
     */
    public function getMemberModel($id)
    {
        $model = Member::find()
            ->alias('member')
            ->joinWith(['memberCard'])
            ->with(['memberCard' => function($query){
                $query->select(
                    "cloud_member_card.amount_money,
                     cloud_member_card.active_time,
                     cloud_member_card.invalid_time,
                     cloud_member_card.card_category_id,
                     cloud_member_card.card_number,
                     cloud_member_card.amount_money,
                     cloud_member_card.balance,
                     cloud_member_card.member_id,
                     cloud_member_card.create_at,
                     cloud_member_card.status,
                     cloud_member_card.leave_type,");}])
            ->joinWith(['memberDetails'])
            ->joinWith(['employee employee'])
            ->joinWith(['memberCourseOrder memberCourseOrder'=>function($query){
                $query->joinWith(['employeeS employeeS'],false);
            }],false)
            ->with(['leaveRecord' => function($query){
                $query->andWhere('status=1');
                }])
            ->select(
                "cloud_member_details.member_id as memberId,
                 cloud_member_details.name,
                 cloud_member_details.sex,
                 cloud_member_details.id_card,
                 cloud_member_details.birth_date,
                 cloud_member_details.recommend_member_id,
                 cloud_member_details.profession,
                 cloud_member_details.family_address,
                 cloud_member_details.pic,
                  cloud_member_details.ic_number,
                  cloud_member_details.note,
                  cloud_member_details.document_type,
                 member.mobile,
                 member.id,
                 member.counselor_id,
                 employee.id as employeeId,
                 employee.name as employee_name,
                 employeeS.name as personalName")
            ->where(['member.id' => $id])
            ->asArray()->one();
//        return $model['memberCardData']     =  $this->getCardCategoryInfo($model);//获取卡种表卡名
          return $model;
    }
    /**
     * 后台验卡管理 - 请假获取当前卡的剩余天数和总天数
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/12/5
     * @param $id
     * @param $memberCardId
     * @return \yii\db\ActiveQuery
     */
    public function getMemberModelCheck($id,$memberCardId)
    {
        $model = Member::find()
            ->alias('member')
            ->joinWith(['memberCard'])
            ->with(['memberCard' => function($query)use($memberCardId){
                $query->andWhere(['id'=>$memberCardId])->select(
                    "cloud_member_card.amount_money,
                     cloud_member_card.active_time,
                     cloud_member_card.invalid_time,
                     cloud_member_card.card_category_id,
                     cloud_member_card.card_number,
                     cloud_member_card.amount_money,
                     cloud_member_card.balance,
                     cloud_member_card.member_id,
                     cloud_member_card.create_at,
                     cloud_member_card.status,");}])
            ->joinWith(['memberDetails'])
            ->joinWith(['employee employee'])
            ->with(['leaveRecord' => function($query){
                $query->andWhere('status=1');
            }])
            ->select(
                "cloud_member_details.member_id as memberId,
                 cloud_member_details.name,
                 cloud_member_details.sex,
                 cloud_member_details.id_card,
                 cloud_member_details.birth_date,
                 cloud_member_details.recommend_member_id,
                 cloud_member_details.profession,
                 cloud_member_details.family_address,
                 cloud_member_details.pic,
                  cloud_member_details.ic_number,
                  cloud_member_details.note,
                 member.mobile,
                 member.id,
                 member.counselor_id,
                 employee.id as employeeId,
                 employee.name as employee_name,")
            ->where(['member.id' => $id])
            ->asArray()->one();
//        return $model['memberCardData']     =  $this->getCardCategoryInfo($model);//获取卡种表卡名
        return $model;
    }
    /**
     * 后台会员管理 - 会员详细信息查询 - 关联卡种表查询卡名
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/1
     * @return \yii\db\ActiveQuery
     */
    public function getCardCategoryInfo($data)
    {
        $cardCategory=[];
        if(isset($data['memberCard'])){
            foreach ($data['memberCard']  as $k=>$v){
                $model                       =  CardCategory::findOne($v['card_category_id']);
                $cardCategory['card_name'][] = $model['card_name'];
            }
        }
        return $cardCategory;
    }

    /**
     * 后台会员管理 - 会员信息查询 - 多表查询
     * @author Huang Hua <huanghua@itsports.club>
     * @param $id
     * @create 2017/5/11
     * @return \yii\db\ActiveQuery
     */
    public function     getMemberData($id)
    {
        $model = Member::find()
            ->alias('mm')
            ->joinWith(['memberDetails md'])
            ->joinWith(['employee employee'])
            ->select(
                " mm.id,
                  mm.counselor_id,
                  md.id as memberDetailsId,
                  md.member_id,
                  md.pic,
                  md.name,
                  md.sex,
                  md.birth_date,
                  md.profession,
                  md.id_card,
                  md.family_address,
                  employee.id as employeeId,
                  employee.name as employeeName,
                "
            )
            ->where(['mm.id' => $id])
            ->asArray()->one();
        return $model;

    }

    /**
     * 后台会员管理 - 会员信息查询 - 多表查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/3/30
     * @param $params //搜索参数
     * @return \yii\db\ActiveQuery
     */
    public function search($params)
    {
        $this->customLoad($params);
        $query = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails memberDetails'],false)
//            ->joinWith(['memberCard memberCard'],false)
//            ->joinWith(['leaveRecordS leave'],false)
            ->joinWith(['employee employee'],false)
//            ->joinWith(['memberCourseOrder mco'=>function($query){
//                $query->joinWith(['chargeClass charge'],false);
//            }],false)
            ->joinWith(['memberDeposit memberDeposit'],false)
            ->joinWith(['organization organization'],false)
//            ->with([
//                'leaveRecord' => function($query) {
//                    $query->andWhere(['<>','cloud_leave_record.status',2]);
//                }
//            ])
//            ->where(['<>','member.is_employee',1])
//            ->orWhere(['member.is_employee'=>null])
            ->where([ 'member.member_type'=>['1','3']])
            ->select(
                "    
                      member.id,  
                      member.mobile,
                      member.status,
                      member.member_type,
                      member.counselor_id,
                      memberDetails.id as memberDetailsId,
                      memberDetails.name,
                      memberDetails.sex,
                      memberDetails.birth_date,
                      memberDetails.recommend_member_id,
                      memberDetails.member_id as memberId,
                      memberDetails.id_card,
                      employee.name as employee_name,
                      employee.id as employeeId,
                      organization.name as organization_name,
                      organization.id as venue_id,
                      memberDeposit.price,
                      memberDeposit.voucher,
                      memberDeposit.pay_mode,
                      memberDeposit.start_time,
                      memberDeposit.end_time,
                      "
            )
            ->groupBy(["member.id"])
            ->orderBy($this->sorts)
            ->asArray();
         $query                 = $this->getSearchWhere($query);
         $dataProvider          =  Func::getDataProvider($query,8);
         return  $dataProvider;
//        $dataProvider->models      =  $this->getCardCategoryData($dataProvider->models);
    }

    /**
     * 后台会员管理 - 会员信息查询 - 多表查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/3/30
     * @param $keywords //搜索参数
     * @return \yii\db\ActiveQuery
     */
    public function getMemberKeywords($keywords)
    {
            $query = Member::find()
                ->alias('member')
                ->joinWith(['memberDetails memberDetails'])
                ->andWhere([
                    'or',
                    ['like', 'member.username', $keywords],
                    ['like', 'memberDetails.name', $keywords],
                    ['like', 'member.mobile', $keywords],
                    ['member.id'=>$keywords],
                ])
                ->asArray()->all();
            return $query;
    }

    /**
     * 私教管理 - 会员信息搜索
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/3/30
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public function memberData($params,$bool = false,$venueId)
    {
        $this->customLoads($params);
//        if($venueId != null && !in_array($venueId,$this->venueIdArr)){
//            return 'noApply';
//        }
        $query = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails memberDetails'])
            ->joinWith(['memberCard memberCard'])
            ->select(
                "member.id,
                      member.mobile,
                      memberDetails.member_id,
                      memberDetails.name,
                      memberDetails.pic,
                      memberCard.member_id as memberId,
                      memberCard.card_number,
                      memberCard.id as memberCardId,
                      memberCard.status
                      ")
            ->where(['or',['member.mobile'=>$params['keyword']],['memberCard.card_number'=>$params['keyword']]])
            ->asArray();
        $query = $this->getSearchWheres($query);
//        if($this->type == 'bind'){
//            return $query->andWhere(['member.venue_id'=>$this->venueId])->one();
//        }
//        if($this->type == 'send'){
//            return $query->andWhere(['member.venue_id'=>$this->venueId])->one();
//        }
        if($bool){
//             $query->andWhere(['member.member_type'=>1]);
//                 ->andWhere(['memberCard.status'=>1]);
//            if($this->type == 'send'){
//                return $query->andWhere(['member.venue_id'=>$this->venueId])->one();
//            }
            if($query->count() > 1) {
                return $query->one();
            }
            return $query->one();
        }
        return $query->one();
    }
    /**
     * 会员卡管理 - 会员卡 - 搜索数据处理数据
     * @create 2017/4/7
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return bool
     */
    public function customLoad($data)
    {
        $card               =  new \backend\models\CardCategory();
        $venueIds           =  $card->getVenueIdByRole();
        $this->venueId      = (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->keywords     = (isset($data[self::KEY]) && !empty($data[self::KEY])) ? $data[self::KEY] : null;
        $this->sex          = (isset($data['sex']) && !empty($data['sex'])) ? (int)$data['sex'] : null;
        $this->type         = (isset($data['type']) && !empty($data['type'])) ? (int)$data['type'] : null;
        $this->status       = (isset($data['status']) && !empty($data['status'])) ? (int)$data['status'] : null;
        $this->startTime    = (isset($data[self::START])&& !empty($data[self::START]))? (int)strtotime($data[self::START]) : null;
        $this->endTime      = (isset($data[self::END]) && !empty($data[self::END])) ? (int)strtotime($data[self::END]) : null;
        $this->freeze       = (isset($data[self::FREEZE]) && !empty($data[self::FREEZE])) ? (int)($data[self::FREEZE]) : null;
        $this->vacate       = (isset($data[self::VACATE]) && !empty($data[self::VACATE])) ? (int)($data[self::VACATE]) : null;
        $this->privates     = (isset($data[self::PRIVATES]) && !empty($data[self::PRIVATES])) ? (int)$data[self::PRIVATES] : null;
        $this->nowBelongId  = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType= (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->sellId       = (isset($data[self::SELL_ID]) && !empty($data[self::SELL_ID])) ? $data[self::SELL_ID] : null;
        $this->free         = (isset($data[self::FREE]) && (!empty($data[self::FREE]) || $data[self::FREE] != '')) ? (int)($data[self::FREE]) : null;
        $this->cardTimeStart  = (isset($data[self::CARD_TIME_START])&& !empty($data[self::CARD_TIME_START]))? (int)strtotime($data[self::CARD_TIME_START]) : null;
        $this->cardTimeEnd    = (isset($data[self::CARD_TIME_END])&& !empty($data[self::CARD_TIME_END]))? (int)strtotime($data[self::CARD_TIME_END]) : null;
        if($this->type){
            $this->getCardCategoryType($this->type);
        }
        $this->sorts = self::loadSort($data);

        return true;
    }
    /**
     * 私课管理 - 搜索会员手机号 会员编号
     * @create 2017/5/27
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return bool
     */
    public function customLoads($data)
    {
//        $cardObj               = new \backend\models\CardCategory();
//        $venueIds              = $cardObj->getVenueIdByRole();
        $venueIds              = $this->getStoreVenueId($data);
        $this->venueIdArr      = $venueIds;
        $this->venueId         = (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->keyword         = (isset($data[self::SEARCH]) && !empty($data[self::SEARCH])) ? $data[self::SEARCH] : null;
        $this->type            = (isset($data[self::KEY_TYPE]) && !empty($data[self::KEY_TYPE])) ? $data[self::KEY_TYPE] : null;
        $this->nowBelongId     = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID])) ? $data[self::NOW_BELONG_ID] : null;
        $this->nowBelongType   = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE])) ? $data[self::NOW_BELONG_TYPE] : null;
        return true;
    }

    /**
     * 后台 - 私课排期 - 根据通店场馆获取不同场馆的会员数据
     * @create 2017/12/22
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return  mixed
     */
    public function getStoreVenueId($data)
    {
          if(!empty($data['keyword']) && strlen($data['keyword'])!=11){
              $memberCard     = \backend\models\MemberCard::findOne(['card_number'=>$data['keyword']]);
              $venueTimes     =  \common\models\base\VenueLimitTimes::find()
                  ->where(['member_card_id'=>$memberCard['id']])
                  ->orderBy('id DESC')
                  ->asArray()
                  ->all();
              $venuesDataId = [];
              foreach ($venueTimes as $k=>$v){
                  if(empty($v['venue_ids'])){
                     array_push($venuesDataId,[$v['venue_id']]);
                  }else{
                      $venueIds         =  json_decode($v['venue_ids']);
                      array_push($venuesDataId,$venueIds);
                  }
              }
              $venuesDataIdAll = [];
             foreach ($venuesDataId as $k=>$v){
                 $venuesDataIdAll= array_merge($venuesDataIdAll,$v);
             }
              return $venuesDataIdAll;

        }
    }
    /**
     * 会员信息管理 - 卡种表关联卡类别表- 遍历卡类别表卡名
     * @create 2017/4/11
     * @author huanghua<huanghua@itsports.club>
     * @param $id
     * @return mixed
     */
    public function getCardCategoryType($id)
    {
       $data = CardCategory::find()->select('id')->where(['category_type_id'=>$id])->all();
       if($data){
           foreach($data as $v){
               $this->cardId[] = $v['id'];
           }
       }
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
        if(!$this->venueId){
            die;
        }
        $query->andFilterWhere([
            'and',
            [
                'memberDetails.sex' => $this->sex,
            ],
        ]);
        $query->andFilterWhere([
            'and',
            [
                'member.counselor_id' => $this->sellId,
            ],
        ]);
        if(!empty($this->keywords)||!empty($this->startTime) || !empty($this->type) || !empty($this->status)||!empty($this->cardTimeStart) ||!empty($this->cardTimeEnd)){
            $query->joinWith(['memberCard memberCard'],false);
        }
        $query->andFilterWhere([
            'or',
            ['like','memberDetails.name', $this->keywords],
            ['memberCard.card_number'  => $this->keywords],
            ['like','member.mobile',      $this->keywords]
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','memberCard.active_time',$this->startTime],
            ['<','memberCard.active_time',$this->endTime]
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','memberCard.create_at',$this->cardTimeStart],
            ['<','memberCard.create_at',$this->cardTimeEnd]
        ]);
        if($this->type){
            $query->andWhere(['in','memberCard.card_category_id',$this->cardId]);
        }
        if(!empty($this->privates)){
            $query->joinWith(['memberCourseOrder mco'=>function($query){
                $query->joinWith(['chargeClass charge'],false);
            }],false);
        }
        if($this->privates == 1){
            $query->andWhere(['is not','mco.id',null]);
        }
        if($this->privates == 2){
            $query->andWhere(['mco.id'=>null]);
        }
        if(!empty($this->vacate)){
            $query->joinWith(['leaveRecordS leave'],false);
        }
        $query->andFilterWhere([
            'and',
            ['member.status'=>$this->freeze],
            ['leave.status'=>$this->vacate]
        ]);
        if(!empty($this->status)){
            $query->joinWith(['entryRecords ers'],false);
        }
        if($this->status == 1){
            $query->andFilterWhere(['and',
                ['<>','memberCard.status',4],
                ['>=','memberCard.invalid_time',time()],
                ['<','memberCard.invalid_time',time()+15*24*60*60]]);      //即将到期
        }
        if($this->status == 2){
            $query->andFilterWhere(['<','ers.entry_time',time()-60*24*60*60]);                                                      //沉睡会员
        }
        if($this->status == 3){
            $query->andFilterWhere(['and',['<>','memberCard.status',4],['<','memberCard.invalid_time',time()]]);            //到期会员
        }
        if($this->status != 4){
            $query->andFilterWhere(['member.venue_id'=>$this->venueId]);
        }
        if($this->status == 4){
            $query->joinWith(['memberCard memberCard'=>function($query){
                $query->joinWith(['venueLimitTimesArr venueLimitTimesArr'],false);
            }],false);
//            $query->andFilterWhere(['like','venueLimitTimesArr.venue_ids','"'.$this->venueId.'"']);
//            $query->andFilterWhere(['IS NOT','venueLimitTimesArr.venue_ids',null]);
            $query->andFilterWhere(['or',['venueLimitTimesArr.venue_id'=>$this->venueId[0]],['like','venueLimitTimesArr.venue_ids',$this->venueId[0]]]);
        }
        //获取失效会员信息
        if ($this->status == 5) {
           $query->andFilterWhere(['member.member_type'=>3]);
       }
        if(!empty($this->free) || ( !is_null($this->free) && $this->free == 0)){
            $query->andFilterWhere(['and',['mco.course_type'=>2],['mco.overage_section'=>$this->free]]);
        }
        return $query;
    }
    /**
     * 私课管理 - 搜索会员 - 增加搜索条件
     * @create 2017/5/27
     * @author huanghua<huanghua@itsports.club>
     * @param $query
     * @return mixed
     */
    public function getSearchWheres($query)
    {
        $query->andFilterWhere([
            'or',
            ['memberCard.card_number'=>$this->keyword],
            ['member.mobile'=>$this->keyword]
        ]);
//        $query->andFilterWhere(['member.venue_id'=>$this->venueId]);
        return $query;
    }

    /**
     * 后台会员管理 - 会员信息查询 - 获取卡种表数据
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/3/31
     * @return string
     */
    public function getCardCategoryData($data)
    {

        foreach ($data as &$value){
            $value['cardCategory'] =  \backend\models\MemberCard::find()->alias('mc')
                ->select('cloud_card_category.*,mc.card_category_id')
                ->joinWith(['cardCategory'])
                ->where(['mc.member_id'=>$value['id']])->asArray()->one();
        }

        return $data;
    }

    /**
     * 后台会员管理 - 会员信息查询 - 会员卡状态修改
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/3/31
     * @return bool
     */
    public static function getUpdateMemberCard($id)
    {
        $member  =  \common\models\base\Member::findOne($id);

        if($member->status == 2){

            $member->status = 1;
            $member->params = json_encode([1]);
        }else{

            $member->status = 2;
            $member->params = json_encode([1]);
        }


        if($member->save()){
            return true;
        }else{
            return $member->errors;
        }
    }
    /**
     * 员工管理 - 员工信息查询 - 员工顾问修改
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/3/31
     * @param $memberId
     * @param $counselorId
     * @param $type
     * @param $employeeId
     * @return bool
     */
    public static function getUpdateEmployee($memberId,$counselorId,$type,$employeeId)
    {
      if($type == '私教部'){
          $about   =  \common\models\base\MemberCourseOrder::updateAll(['private_id'=>$counselorId],['and',['in','member_id',$memberId],['private_id'=>$employeeId]]);
      }else{
          $about  =  \common\models\base\Member::updateAll(['counselor_id'=>$counselorId],['in','id',$memberId]);
      }
        if($about){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 后台 - 会员管理 - 会员基本信息删除（范围：会员详细信息和会员卡）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/3/31
     * @param $id
     * @param $companyId
     * @return bool
     */
   public  function  getMemBaseDel($id,$companyId)
   {
       $memberAccount = Member::find()->where(['id'=>$id])->asArray()->one();
       if($memberAccount['mobile']!= 0 && !empty($memberAccount['mobile'])){
           $memberCount = Member::find()
               ->where(['and',['mobile'=>$memberAccount['mobile']],['company_id'=>$companyId]])
               ->asArray()
               ->count();
           if($memberCount == 1 && $memberAccount['member_account_id']!= 0){
               $delMemberAccount   = MemberAccount::findOne($memberAccount['member_account_id']);
               $delMemberAccount->delete();
           }
       }
       $member             =   Member::findOne($id);
       $resultDelMem       =   $member->delete();
       $resultDelMemCard   =   $this->getDelMemCard($id);
       $getDelMemDetail    =   $this->getDelMemDetail($id);
       $getDelMemBase      =   $this->getDelMemBase($id);
       $order              =   Order::deleteAll(["member_id"=>$id]);
       $history            =   ConsumptionHistory::deleteAll(["member_id"=>$id]);
       $memberCabinet      =   MemberCabinet::findOne(["member_id"=>$id]);
       $update             =   Cabinet::updateAll(['status'=>1],['id'=>$memberCabinet['cabinet_id']]);
       $cabinet            =   MemberCabinet::deleteAll(["member_id"=>$id]);
       $charge             =   MemberCourseOrder::deleteAll(["member_id"=>$id]);
       $site               =   AboutYard::deleteAll(["member_id"=>$id]);
       if($resultDelMem && $resultDelMemCard && $getDelMemDetail && $getDelMemBase)
       {
           return true;
       }else{
           return false;
       }
   } 
    /**
     * 后台 - 会员管理 - 会员详细信息删除
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/3/31
     * @param $id
     * @return bool
     */
   public  function getDelMemDetail($id){
       $memDetail        =   MemberDetails::find()->where([self::MEMBER=>$id])->asArray()->all();
       if(empty($memDetail))
       {
           return true;
       }else{
        $delResult       =   MemberDetails::deleteAll([self::MEMBER=>$id]);
            if($delResult){
                return true;
            }else{
                return false;
            }
       }
   }
    /**
     * 后台 - 会员管理 - 会员验证设置表删除
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/3/31
     * @param $id
     * @return bool
     */
    public  function getDelMemBase($id){
        $memDetail        =   MemberBase::find()->where([self::MEMBER=>$id])->asArray()->all();
        if(empty($memDetail))
        {
            return true;
        }else{
            $delResult       =   MemberBase::deleteAll([self::MEMBER=>$id]);
            if($delResult){
                return true;
            }else{
                return false;
            }
        }
    }
    /**
     * 后台 - 会员管理 - 会员卡删除
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/3/31
     * @return bool
     */
    public  function  getDelMemCard($id){
       $memCard        =   MemberCard::find()->where([self::MEMBER=>$id])->asArray()->all();
       if(empty($memCard))
       {
           return true;
       }else{
         $delResults   =   MemberCard::deleteAll([self::MEMBER=>$id]);
           if($delResults){
               return true;
           }else{
               return false;
           }
       }
   }
    /**
     *后台会员管理 - 会员详细信息 -  消费记录查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/19
     * @return bool|string
     */
    public function consumptionHistoryData($id)
    {
        $model = Member::find()
            ->alias('mm')
            ->joinWith(['memberCard mc'])
            ->joinWith(['consumptionHistory ch'])
            ->select(
                "     mm.id,
                      mc.id as memberCardId,
                      mc.balance,
                      mc.total_times,
                      mc.consumption_times,
                      ch.id,
                      ch.member_id,
                      ch.consumption_time,
                      ch.type,
                      ch.consumption_amount,
                      ch.consumption_times,
                      ch.consumption_type,
                      ch.consumption_date,
                      ch.cashier_order,
                      ch.cash_payment,
                      ch.bank_card_payment,
                      ch.mem_card_payment,
                      ch.coupon_payment,
                      ch.other_payment,
                      ch.network_payment,
                      ch.integration_payment,
                      ch.discount_payment,
                      ch.venue_id,
                      ch.seller_id,
                      ch.describe,
               "
            )
            ->where(['mm.id' => $id])
            ->asArray();
        $dataProvider = Func::getDataProvider($model,10);

        return $dataProvider;
    }
    /**
     * 后台会员管理 - 会员柜表查询 - 关联经办人
     * @author Houkaixin <huanghua@itsports.club>
     * @create 2017/5/3
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(),['id' =>'creater_id']);
    }

    public static function getMemberOne($mobile)
    {
       return Member::find()->where(['mobile'=>$mobile])->asArray()->one();
    }
    /**
     * 前台验卡- 会员信息信息查询
     * @author Houkaixin <huanghua@itsports.club>
     * @create 2017/5/3
     * @param $memId
     * @return \yii\db\ActiveQuery
     */
    public  function getMemDetailData($memId){
        $model = Member::find()->where(["cloud_member.id"=>$memId])
            ->joinWith(["memberDetails"])
            ->select("cloud_member.id,
                     cloud_member.username,
                     cloud_member.mobile,
                     cloud_member_details.pic,
                     cloud_member_details.member_id,
                     cloud_member_details.name as memberName,")
            ->asArray()->one();
        return $model;
    }
    /**
     * 后台 - 会员管理 - 获取所有的销售顾问信息
     * @author houkaixin <houkaixin@itsports.club>
     * @param $venueId
     * @create 2017/5/12
     * @return array
     */
    public function getAdviser($venueId){

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
        $this->venueId      =    (isset($venueId) && !empty($venueId)) ? $venueId : $venueIds;
        $employee = Employee::find()
             ->alias('employee')
             ->joinWith(['organization or'],false)
             ->where(['or.style'=>'3'])
             ->andWhere(['or.code'=>'xiaoshou'])
             ->andWhere(['<>','employee.status',2])
             ->select("employee.id,employee.name")
             ->asArray();
//        if(isset($type) && $type == 'company'){
//            $employee = $employee->andFilterWhere(['employee.company_id'=>$id]);
//                }
//        if(isset($type) && $type == 'venue'){
//            $employee = $employee->andFilterWhere(['employee.venue_id'=>$id]);
//        }
        $employee = $employee->andFilterWhere(['employee.venue_id'=> $this->venueId]);
        $employee = $employee->all();
        return $employee;
    }

    /**
     * 后台 - 会员管理 - 获取所有的销售顾问信息
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/1/18
     * @param $employeeId
     * @param $companyId
     * @return array
     */
    public function getAllAdviser($employeeId,$companyId)
    {
        $employee = Employee::find()
            ->alias('employee')
            ->joinWith(['organization or'],false)
            ->where(['or.style'=>'3'])
            ->andWhere(['or.code'=>'xiaoshou'])
            ->andWhere(['<>','employee.status',2])
            ->andFilterWhere(['employee.id'=>$employeeId])
            ->andFilterWhere(['employee.company_id'=>$companyId])
            ->select("employee.id,employee.name,employee.venue_id")
            ->asArray()->all();
        return $employee;
    }

    /**
     * 后台 - 会员管理 - 获取所有的销售顾问信息
     * @author houkaixin <houkaixin@itsports.club>
     * @param $companyId
     * @param $venueId
     * @create 2017/5/12
     * @return array
     */
    public function getAdviserData($companyId,$venueId){
        $employee = Employee::find()
            ->alias('employee')
            ->joinWith(['organization or'])
            ->where(['or.style'=>'3'])
            ->andWhere(['or.code'=>'xiaoshou'])
            ->andWhere(['<>','employee.status',2])
            ->andWhere(['employee.company_id'=>$companyId])
            ->andWhere(['employee.venue_id'=>$venueId])
            ->asArray();
        $employee = $employee->all();
        return $employee;
    }
    /**
     * 后台 - 潜在会员查询 - 获取所有的潜在会员信息
     * @author huangpengj <huangpengj@itsports.club>
     * @create 2017/5/23
     * @param $params
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMemberInfo($params)
    {
        $this->customParamsLoad($params);
        $query = Member::find()
             ->alias('member')
             ->joinWith(['memberDetails details'
                =>function($query){
                     $query->joinWith(['config config'],false);
                 }
             ],false)
             ->with([
                'aboutClass'=>function($query){
                    $query->andWhere('status=1');
                }
             ])
             ->joinWith(['employee yee'],false)
             ->joinWith(['memberDeposit md'],false)
             ->joinWith(['entryRecord entry'],false)
             ->joinWith(["venue venue"=>function($query){
                    $query->joinWith(["company company"]);
             }],false)
             ->joinWith(["aboutYard aboutYard"],false)
             ->select('
                member.id,member.mobile,member.counselor_id,member.member_type,member.register_time,
                details.member_id,details.name as username,details.sex,details.birth_date,
                details.way_to_shop,
                details.document_type,
                details.id_card,
                yee.name as counselorName,
                md.price,
                venue.name as venueName,
                entry.entry_time, 
                config.value as source, 
                company.name as companyName,
                aboutYard.id as  aboutYardId,
                aboutYard.about_start as aboutStart,         
             ')
             ->where(["member.member_type"=>2])
             ->orderBy($this->sort)
             ->groupBy("member.id")
             ->asArray();
        $query  = $this->setWhereSearch($query);
        $data   = Func::getDataProvider($query,8);
        $data->models = $this->classYardStatus($data->models);
        return $data;
    }
    /**
     * 后台 - 场地预约 - 判断会员预约时间是否过期
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/11/14
     * @param  $data    // 潜在会员列表数据
     * @return array|\yii\db\ActiveRecord[]
     */
    public function classYardStatus($data){
          if(empty($data)){
               return [];
          }
          foreach ($data as $keys=>$value){
                if(empty($value["aboutStart"])){
                    $data[$keys]["yardAboutStatus"] = 3;   // 没有预约
                    continue;
                }
                if(time()>$value["aboutStart"]){
                    $data[$keys]["yardAboutStatus"] = 2;   // 课程已过期
                }else{
                    $data[$keys]["yardAboutStatus"] = 1;   // 课程未过期
                }
          }
        return $data;
    }
    /**
     * 后台 - 潜在会员查询 - 会员是否过期帅选
     * @author huangpengj <huangpengj@itsports.club>
     * @create 2017/6/1
     * @param  $query
     * @return array|\yii\db\ActiveRecord[]
     */
    public function setIsExpire($query){
        $query->andFilterWhere(['details.way_to_shop'=>$this->wayToShop]);
    }

    /**
     * 后台 - 潜在会员查询 - 获取所有的搜索条件
     * @author huangpengj <huangpengj@itsports.club>
     * @create 2017/5/23
     * @param $params
     */
    public function customParamsLoad($params)
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
        $this->venueId      = (isset($params['venueId']) && !empty($params['venueId'])) ? $params['venueId'] : $venueIds;
        $this->searchParams = (isset($params[self::KEY]) && !empty($params[self::KEY]))?$params[self::KEY]: NULL;           //获取搜索条件
        $this->wayToShop    = (isset($params[self::WAY]) && !empty($params[self::WAY]))?$params[self::WAY]: NULL;           //获取搜索条件
        $this->potMember    = (isset($params[self::POTENTIAL_MEMBER ]) && !empty($params[self::POTENTIAL_MEMBER ]))?$params[self::POTENTIAL_MEMBER ]: NULL;   //潜在会员
        $this->newMember    = (isset($params[self::NEW_MEMBER]) && !empty($params[self::NEW_MEMBER]))?$params[self::NEW_MEMBER]: NULL;
        $this->nowVenueId   = (isset($params["nowVenueId"]) && !empty($params["nowVenueId"]))?$params["nowVenueId"]: NULL;
        $this->nowCompanyId = (isset($params["nowCompanyId"]) && !empty($params["nowCompanyId"]))?$params["nowCompanyId"]: NULL;
        $this->sort         = self::loadSorts($params);
    }

    /**
     * 后台 - 潜在会员查询 - 处理所有的搜索条件
     * @author huangpengj <huangpengj@itsports.club>
     * @create 2017/5/23
     * @param $query
     * @return mixed
     */
    public function setWhereSearch($query)
    {
        if(!$this->venueId){
            $this->venueId = 0;
        }
        $query->andFilterWhere([
            'or',
            ['like','details.name',$this->searchParams],
            ['like','member.mobile',$this->searchParams]
        ]);
        $query->andFilterWhere(['details.way_to_shop'=>$this->wayToShop]);
        
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['member.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['member.venue_id'=>$this->nowBelongId]);
//        }
        // 场馆id搜索
        $query->andFilterWhere(['member.venue_id'=>$this->venueId]);
        if(empty($this->venueId)){
            $query->andFilterWhere(['member.company_id'=>$this->nowCompanyId]);
        }
        // 公司id搜索
//        if(!empty($this->nowCompanyId)){
//            $query->andFilterWhere(['member.company_id'=>$this->nowCompanyId]);
//        }
        return $query;
    }
    /**
     * 会员管理 - 潜在会员 - 获取排序条件
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
     * @param $data
     * @return mixed
     */
    public static function loadSorts($data)
    {
        $sort = ['member.id' => SORT_DESC];
        if(!isset($data['sortType'])){ return $sort; }
        switch ($data['sortType']){
            case 'userName'  :
                $attr = '`details`.name';
                break;
            case 'sex'  :
                $attr = '`details`.sex';
                break;
            case 'age':
                $attr = '`details`.birth_date';
                break;
            case 'mobile':
                $attr = '`member`.mobile';
                break;
            case 'wayToShop' :
                $attr = '`details`.way_to_shop';
                break;
            case 'counselorName' :
                $attr = '`yee`.name';
                break;
            case  'entryTime' :
                $attr = '`entry`.entry_time';
                break;
            case  'entryTimes' :
                $attr = '`entry`.entry_time';
                break;
            default:
                $attr = NULL;
        };
        if($attr){
            $sort = [ $attr  => self::convertSortValue($data['sort']) ];
        }
        return $sort;
    }
    /**
     * @潜在会员 - 详细信息查询 - 查询
     * @author Huang pengju <huangpengju@itsports.club>
     * @create 2017/5/25
     * @param $id       //会员id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function  getMemberInformation($id)
    {
        $model = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails details'])
            ->joinWith(["memberDeposit memberDeposit"])
            ->joinWith(["yardAbout yardAbout"=>function($query){
                $query->joinWith(["venueYard venueYard"=>function($query){
                    $query->joinWith(['organization or']);
                }]);
            }],false)
            ->joinWith(['aboutClass aboutClass'=>function($q){
                   $q->joinWith(["groupClass groupClass" => function($q){
                        $q->joinWith(["course course","classroom classroom"]);
                        $q->joinWith(['organization org']);
                   }]);
                   $q->joinWith(['seat seat']);
                   $q->orderBy('end DESC');
                }])
            ->andWhere(['aboutClass.status'=>1])
            ->select("
                member.id,member.mobile,member.params,member.counselor_id,details.way_to_shop as toShopId,
                details.name,details.sex,details.birth_date,details.id_card,details.pic,memberDeposit.price,
                aboutClass.start,aboutClass.end,course.name as courseName,classroom.name as classroomName,seat.seat_number,or.name as groundVenue,
                org.name as venueName,yardAbout.id as yardAboutId,yardAbout.about_interval_section,yardAbout.aboutDate,venueYard.yard_name,
                yardAbout.create_at as yardAboutDate")
            ->where(['member.id' => $id])
            ->asArray()->one();
        unset($model['memberDetails']);
        if(empty($model['aboutClass'])){
            $model['aboutClassId'] = false;       //会员没有id
            $model['aboutClass']   = 1;           //剩余节数
        }else{
            $model['aboutClassId'] = $model['aboutClass']['id'];
            $model['aboutClass']   = 0;           //剩余节数
        }
        return $model;
    }
    /**
     * 云运动 - 会员修改 - 查询手机号是否存在
     * @author Huang Hua <huanghua@itsports.club>
     * @create 2017-5-26
     * @param $mobile   //手机号
     * @param $id
     * @param $type
     * @param $memberId  // 会员id
     * @param $venueId //场馆id
     * @return array|null|\yii\db\ActiveRecord  //查询结果
     */
    public function getMobileInfo($mobile,$id,$type,$memberId,$venueId)
    {

        $data = Member::find()->where(['and',['mobile'=>$mobile],['venue_id'=>$venueId]]);
        // 判断提交的手机号是否一样 一样的话直接过去
        if(!empty($memberId)){
            $memberDetail = \common\models\base\Member::findOne($memberId);
            if($memberDetail->mobile==$mobile){
                return true;
            }
        }
        if(isset($type) && $type == 'company'){
            $data = $data->andFilterWhere(['cloud_member.company_id'=>$id]);
        }
        if(isset($type) && $type == 'venue'){
            $data = $data->andFilterWhere(['cloud_member.company_id'=>$id]);
        }
        $data = $data->asArray()->one();
        return $data;
    }
    /**
     * 私教管理 - 会员信息搜索
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/3/30
     * @param $MemberId
     * @param $aboutId
     * @param $classId
     * @return \yii\db\ActiveQuery
     */
    public function memberDetailsData($MemberId,$aboutId,$classId)
    {
        $query = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails memberDetails'])
            ->joinWith(['aboutClass aboutClass'])
            ->select(
                "     member.id,
                      member.mobile,
                      memberDetails.member_id,
                      memberDetails.name,
                      aboutClass.id as aboutId,
                      aboutClass.member_id as memberId,
                      aboutClass.class_date,
                      aboutClass.start,
                      aboutClass.status,
                      aboutClass.class_id,
                      aboutClass.create_at
                      "
            )
            ->where(['member.id' => $MemberId])
            ->andWhere(['aboutClass.id' => $aboutId])
            ->andWhere(['aboutClass.class_id' => $classId])
            ->asArray()->one();
        $charge = new CommonChange();
        $query['memberCourseOrderDetails']    = $charge->handleChangeClass($aboutId);
        return $query;
    }
    /**
     * 员工管理 - 员工详情 - 批量转移会员数据
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/6
     * @param $id
     * @return \yii\db\ActiveQuery
     */
    public function getMemberTransfer($id)
    {
        $query = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails memberDetails'])
            ->joinWith(['memberCard memberCard'])
            ->where(['mm.id' => $id])
            ->asArray()->all();
        return $query;
    }

    /**
     * 员工管理 - 员工详情 - 根据ID获取会员
     * @author  houkaixin<huanghua@itsports.club>
     * @create 2017/6/6
     * @param $id          // 会员id
     * @param $venueName   // 场馆名称
     * @param $cardId     // 会员卡id
     * @param $requestType  // 请求类型
     * @param $current_time // 传过来的二维码时间
     * @param $venueId     // 进入场馆的 场馆id
     * @return \yii\db\ActiveQuery
     */
    public static function getMemberOneById($id,$venueName = "",$cardId="",$requestType = "",$current_time="",$venueId="")
    {
        // 判断会员是否生效
        if(!empty($requestType)){
            if(empty($current_time)){
                return null;
            }
            // 去数据库获取被录入的时间
            $diffTime = time()-$current_time;
            if($diffTime>16){
                return null;
            }
        }
//        // 这个逻辑是 会员卡是空  也就是找不到会员卡的情况（当时ios上架 出了问题 补加逻辑）
//        if(empty($cardId)&&!empty($requestType)){
//          $endCheckResult  = self::gainMemberCardId($id,$venueIds);
//          if($endCheckResult!==true){  // 检测有问题
//             return null;
//          }
//        }
        // 检索会员是否能进馆
        $result = self::checkIsNotGoVenue($venueName,$id,$venueId,$cardId);
        if(empty($result)){      // 寻找是否有对应的会员
             return null;
        }
        // 当会员卡不为空的时候  会员卡的自动激活
        if(!empty($requestType)&&!empty($cardId)){
           $checkMemberCard = self::activeMemberCard($cardId);
           if($checkMemberCard!==true){
               return null;      //  会员卡检索有问题 直接返回空
           }
        }
        return $result;
    }

    /**
     * 闸机  --- 判断会员卡是否能进馆
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/6/6
     * @param $id          // 会员id
     * @param $venueName   // 场馆名称
     * @param $venueId     //  要进场馆的场馆id
     * @param $cardId      // 会员卡id
     * @return \yii\db\ActiveQuery
     */
    public static function checkIsNotGoVenue($venueName,$id,$venueId,$cardId){
        if($venueName!="maibu"){
           // 根据会员卡id 搜 能通店的场馆
           $endCheckResult = self::gainVenueLimitTimes($cardId,$venueId);
           if($endCheckResult===false){
              return null;         // 不可以进场馆
           }
            return true;           // 可以进场馆
        }
        $venueIds = [];
        if(!empty($venueName)){
            $venueIds =  Member::gainTheVenueS($venueName);
        }
        $member = Member::find()
            ->alias("member");
        if(!empty($venueName)){      // 关联公司名称进行搜索
            // 获取公司下面的所有场馆
            $member   =  $member->andWhere(["member.venue_id"=>$venueIds]);
        }
        $member = $member->andWhere(['member.id' =>$id])->asArray()->one();
        return $member;
    }
    /**
     * 闸机  --- 判断会员卡是否能进馆 业务逻辑
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/6/6
     * @param $cardId          // 会员卡id
     * @param $venueId        // 场馆id
     * @return \yii\db\ActiveQuery
     */
    public static function gainVenueLimitTimes($cardId,$venueId){
        // 搜索是否能  通这个场馆
        $venueIdS = VenueLimitTimes::find()
            ->where(["member_card_id"=>$cardId])
            ->andWhere(["or",["venue_id"=>$venueId],["like","venue_ids",'"'.$venueId.'"']])
            ->select("venue_id,venue_ids,week_times,total_times")
            ->asArray()
            ->one();
        //按周  或者 按月 次数是否已经用完
        if(empty($venueIdS)){
            return false;
        }
        // 不限制次数
        if($venueIdS["total_times"]==-1||$venueIdS["week_times"]==-1){
            return true;
        }
        //获取通用场馆
        $venueId     = empty($venueIdS["venue_id"])?[]:[$venueIdS["venue_id"]];
        $venue_ids   = empty($venueIdS["venue_ids"])?[]:json_decode($venueIdS["venue_ids"]);
        $endVenueIds = array_merge($venueId,$venue_ids);
        //按周按月 进场馆的限制次数判断
        //按周限制
        if(!empty($venueIdS["week_times"])){
            // 获取当前周 周一 到现在 进场馆的次数
            $startTime  = time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600;
            $num        = self::gainEntryNum($cardId,$endVenueIds,$startTime);
            if($num>$venueIdS["week_times"]){
                 return false;
            }
        }
        // 按月限制
        if(!empty($venueIdS["total_times"])){
             $startTime = strtotime(date("Y-m",time())."-"."01");
             $num        = self::gainEntryNum($cardId,$endVenueIds,$startTime);
            if($num>$venueIdS["total_times"]){
                return false;
            }
        }
        return true;
    }

    /**
     * 闸机  --- 获取通用场馆的进场次数
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/6/6
     * @param $cardId          // 会员卡id
     * @param $endVenueIds     // 通用场馆id
     * @param $startTime       // 查询开始时间
     * @return \yii\db\ActiveQuery
     */
    public function gainEntryNum($cardId,$endVenueIds,$startTime){
        $num = EntryRecord::find()
                 ->where(["member_card_id"=>$cardId])
                 ->andWhere(["venue_id"=>$endVenueIds])
                 ->andWhere(["between","create_at",$startTime,time()])
                 ->count();
        return $num;
    }
    /**
     *  闸机 - 没有会员卡 找会员卡id
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/11/27
     * @param  $memberId         //  会员id
     * @param  $venueIds         // 所属场馆id
     * @return \yii\db\ActiveQuery
     */
    public static function gainMemberCardId($memberId,$venueIds){
          $data = \backend\models\MemberCard::find()
                               ->alias("memberCard")
                               ->joinWith(["member member"],false)
                               ->joinWith(["cardCategory cardCategory"=>function($query){
                                   $query->joinWith(["cardCategoryType cardCategoryType"]);
                               }],false)
                               ->where(["and",["memberCard.status"=>[1,4]],[">=","memberCard.invalid_time",time()]])
                               ->andWhere(["member_id"=>$memberId]);
          if(!empty($venueIds)){
             $data = $data->andWhere(["member.venue_id"=>$venueIds]);
          }
          $dataS = $data;   // sql交换
          // 先找时间卡
            $timeCard    = $data->andWhere(["type_name"=>"时间卡"])->one();
          if(empty($timeCard)){
            $timesCard   = $dataS->andWhere(["type_name"=>"次卡"])->one();
            if(empty($timesCard)){
               return false;
            }
            self::activeCard($timesCard->id,$timesCard->status);
          }else{
            self::activeCard($timeCard->id,$timeCard->status);
          }
          return true;
    }
    /**
     *  闸机 - 卡种激活
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/11/27
     * @param  $id         //  会员卡id
     * @param $status     // 会员卡状态
     * @return \yii\db\ActiveQuery
     */
    public static  function activeCard($id,$status){
         if($status==1){
            return true;
         }
         $model = MemberCard::findOne($id);
         if(!empty($model)){
             $model->status = 1;
             if(!$model->save()){
                 return $model->errors;
             }
         }
        return true;
    }






    /**
     *  闸机 - 闸机刷卡进场记录
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/11/27
     * @param  $id         //  会员id
     * @param  $cardId     // 会员卡id
     * @param  $venueId    // 场馆id
     * @param  $companyId  // 公司id
     * @param  $opendoor    // 代表进场还是 出场
     * @return \yii\db\ActiveQuery
     */
    public static function enterRecord($id,$cardId="",$venueId,$companyId,$opendoor){
         if($opendoor!=1){   // 代表是 出场
             self::updateEntryTime($id,$cardId,$venueId,$companyId);
             return true;   // 出场完毕
         }
         // 判断是否有入场记录20秒之内
         $judgeEntryTime = EntryRecord::find()
                             ->where(["between","entry_time",time()-20,time()])
                             ->andWhere(["member_card_id"=>$cardId])
                             ->andWhere(["venue_id"=>$venueId])
                             ->one();
         if(!empty($judgeEntryTime)){
             return true;
         }
         // 判断没有进场的 记录情况下 录入进场
         $model = new EntryRecord();
         $model->member_card_id = $cardId;
         $model->entry_time      = time();
         $model->create_at       = time();
         $model->member_id       = $id;
         $model->entry_way       = 2;
         if(!empty($venueId)){
            $model->venue_id     = $venueId;
         }
         if(!empty($companyId)){
            $model->company_id   =  $companyId;
         }
         if(!$model->save()){
            return $model->errors;
         }
        return true;
    }
    /**
     *  闸机 - 删除所有的二维码
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/6/6
     * @param     $cardId   // 会员卡id
     * @param     $id      // 会员id  
     * @return \yii\db\ActiveQuery
     */
    public static function deleteAllScanCode($cardId="",$id="")
    {
           if(!empty($cardId)&&($cardId!="000000")){
               ScanCodeRecord::deleteAll(["member_card_id"=>$cardId,"identify"=>1]);
           }
           if($cardId=="000000"){
               ScanCodeRecord::deleteAll(["member_id"=>$id]);
           }
    }
    /**
     *  闸机 - 会员出场的时候 修改出场时间
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/6/6
     * @param     $id       //  会员id
     * @param     $cardId   // 会员卡id
     * @param     $venueId   // 场馆id
     * @param     $companyId  // 公司id
     * @return \yii\db\ActiveQuery
     */
    public static function updateEntryTime($id,$cardId,$venueId,$companyId){
           $nowTime    = strtotime(date("Y-m-d",time()));
           $afterTime  = strtotime(date("Y-m-d",time()) .'1 day');
           $model = EntryRecord::find()
                        ->where(["and",
                                ["member_id"=>$id],
                                ["member_card_id"=>$cardId],
                                ["venue_id"=>$venueId],
                                ["company_id"=>$companyId],
                                ["between","create_at",$nowTime,$afterTime]
                                ])
                        ->orderBy(["create_at"=>SORT_DESC])
                        ->limit(1)
                        ->one();
           if(empty($model)){
              return true;
           }
           $model->leaving_time = time();
           if(!$model->save()){
               return $model->errors;
           }
           return true;
    }
    /**
     *  闸机 - 相关会员卡的激活
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/6/6
     * @param $cardId       //  会员卡id
     * @return \yii\db\ActiveQuery
     */
    public static function activeMemberCard($cardId){
         $memberCard = MemberCard::find()
                          ->where(["id"=>$cardId])
                          ->andWhere(["and",["status"=>[1,4]],[">=","invalid_time",time()]])
                          ->one();
         if(empty($memberCard)||(in_array($memberCard->status,[2,3]))){
              return false;
         }
         if($memberCard->status==4){
             $memberCard->status = 1;
             $memberCard->active_time = time();
             if(!$memberCard->save()){
                 return $memberCard->errors;
             }
         }
        return true;
    }
    /**
     *  闸机 - 获取会员信息
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/6
     * @param $cardid       //  手环id
     * @param $venueName    // 场馆名称
     * @return \yii\db\ActiveQuery
     */
    public static  function gainMemberMessage($cardid,$venueName){
        $memberDetail = \backend\models\MemberDetails::find()
                         ->alias("memberDetail")
                         ->where(["ic_number"=>$cardid])
                         ->joinWith(["member member"]);
        if(empty($cardid)){
           return ["status"=>"error","result"=>"参数缺少缺省"];
        }
        if(!empty($venueName)){
            $venueIds =  Member::gainTheVenueS($venueName);
            $memberDetail   =  $memberDetail->andWhere(["member.venue_id"=>$venueIds]);
        }
        $memberDetail    = $memberDetail->select("memberDetail.member_id,member.venue_id,memberDetail.ic_number")->one();
        if(empty($memberDetail)){
            return ["status"=>"error","result"=>"会员卡不存在"];
        }
        $memberId  = $memberDetail->member_id;
        return ["status"=>"success","result"=>$memberId];
    }





    /**
     * 闸机 -  获取指定公司下面的场馆id
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/11/14
     * @param $venueName   // 场馆名称
     * @return array
     */
    public static  function  gainTheVenueS($venueName){
                // 获取指定公司下的所有场馆
               if($venueName=="maibu"){
                       $venueName = "迈步运动健身";
               }
               if($venueName=="wayd"){
                       $venueName = "我爱运动瑜伽健身";
               }
               $venueIds = \backend\models\Organization::find()
                                ->alias("or")
                                ->where(["or.style"=>2])
                                ->joinWith(["organization organization"])
                                ->andWhere(["organization.name"=>$venueName])
                                ->select("or.id")
                                ->asArray()
                                ->column();
               return $venueIds;
    }
    /**
     * 闸机 - 根据会员卡 获取 卡，会员信息
     * @author houkaixn<houkaixn@itsports.club>
     * @create 2017/12/11
     * @param $cardId      //会员卡id
     * @param $venue      //场馆名称
     * @return array
     */
    public function gainMemberMessageByCardId($cardId,$venue){
           //根据场馆名称获取所有场馆
        $venueIds = Member::gainTheVenueS($venue);
          // 先查询该店的 是否有该会员卡的信息
        $memberMessage  = \backend\models\MemberCard::find()
                                         ->alias("memberCard")
                                         ->joinWith(["member member"=>function($query){
                                              $query->joinWith(["memberDetails memberDetail"]);
                                         }],false)
                                         ->joinWith(["leaveRecordIos leaveRecord"])
                                         ->where(["memberCard.id"=>$cardId])
                                         ->andWhere(["member.venue_id"=>$venueIds])
                                         ->select("
                                         member.id,
                                         memberDetail.name,
                                         member.mobile,
                                         memberCard.status,
                                         memberCard.invalid_time,
                                         memberDetail.pic,
                                           ")
                                         ->asArray()
                                         ->one();
       //验卡身份判断
        $validate = $this->validateMember($memberMessage);
        $data     = ["memberMessage"=>$memberMessage,"validate"=>$validate];
        return $data;
    }
    /**
     * 闸机 - 根据会员信息验证
     * @author houkaixn<houkaixn@itsports.club>
     * @create 2017/12/11
     * @param $memberMessage      //会员信息
     * @return array
     */
    public function validateMember($memberMessage){
         if(empty($memberMessage)){    // 该场馆没有本会员
             return 0;
         }
         if(empty($memberMessage["invalid_time"])||($memberMessage["invalid_time"])<time()){
             return 2;                 // 卡已过期
         }
         if($memberMessage["status"]==3){
             return 3;                 // 卡已经冻结
         }
         if(!empty($memberMessage["leaveRecordIos"])){
             return 4;                // 请假中
         }
         return 1;                    // 正状态 （验卡通过）
    }






    /**
     * 销售主页 - 本月新增会员条数
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/29
     * @param $type
     * @param $params
     * @return array
     */
    public function getNewMembers($type,$params)
    {
        $this->customs($params);
        $this->getDateWhere($type);
        return $this->getNewMemberDates($this->searchDateStart,$this->searchDateEnd);
    }

    /**
     * 销售主页 - 新增会员
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $params
     * @return array
     */
    public function getNewMemberDate($params)
    {
        $this->custom($params);
        $query = Member::find()
                ->alias('member')
                ->joinWith(['memberDetails as md'])
                ->joinWith(['memberCard as mc' =>function($query){
                    $query->joinWith(['employee employee']);
                }])
                ->where(['member.member_type' => 1])
                ->select(
                    "member.id,
                member.mobile,
                member.register_time,
                md.name,
                md.sex,
                mc.card_name,
                mc.amount_money,
                mc.card_number,
                mc.create_at,
                employee.name as ename")
                ->groupBy(["member.id"])
                ->orderBy($this->sorts)
                ->asArray();
            $query     = $this->setWhereNew($query);         //场馆和公司判断
            return  $newMember = Func::getDataProvider($query,8);

    }
    /**
     * 销售主页 - 新增会员查询条数
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/28
     * @param $beginDate
     * @param $endDate
     * @return array
     */
    public function getNewMemberDates($beginDate,$endDate)
    {
        $query = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails as md'],false)
            ->joinWith(['memberCard as mc' =>function($query){
                $query->joinWith(['employee employee'],false);
            }])
            ->where(['between','member.register_time',strtotime($beginDate),strtotime($endDate)])
            ->andWhere(['member.member_type' => 1])
            ->select(
                "member.id,")
            ->groupBy(["member.id"])
            ->asArray();
       return  $query     = $this->setWheres($query);         //场馆和公司判断


    }
    /**
     * 销售主页 - 本月新增会员 - 搜索数据处理数据
     * @create 2017/6/9
     * @author 朱梦珂 <zhumengke@itsports.club>
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
        $this->type          = (isset($data['type']) && !empty($data['type'])) ? $data['type'] : NULL;
        $this->nowBelongId   = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->startTime     = (isset($data[self::START])&& !empty($data[self::START]))? (int)strtotime($data[self::START]) : null;
        $this->endTime       = (isset($data[self::END]) && !empty($data[self::END])) ? (int)strtotime($data[self::END]) : null;
        $this->keywords      = (isset($data[self::KEY]) && !empty($data[self::KEY])) ? $data[self::KEY] : null;
        $this->sellId        = (isset($data[self::SELL_ID]) && !empty($data[self::SELL_ID])) ? $data[self::SELL_ID] : null;
        $this->coachId       = (isset($data[self::COACH_ID]) && !empty($data[self::COACH_ID])) ? $data[self::COACH_ID] : null;
        $this->cardType      = (isset($data[self::TYPE]) && !empty($data[self::TYPE])) ? $data[self::TYPE] : null;
        $this->status        = (isset($data[self::STATUS]) && !empty($data[self::STATUS])) ? $data[self::STATUS] : null;
        $this->birthdayClass = (isset($data[self::BIRTHDAY_CLASS]) && (!empty($data[self::BIRTHDAY_CLASS]) || $data[self::BIRTHDAY_CLASS] != '')) ? (int)($data[self::BIRTHDAY_CLASS]) : null;
        $this->memberType    = (isset($data[self::MEMBER_TYPE]) && !empty($data[self::MEMBER_TYPE])) ? $data[self::MEMBER_TYPE] : null;
        $this->sorts         = self::sorts($data);
        return true;
    }
    public function customs($data)
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
        $this->venueId       =  (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->nowBelongId   =  (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType =  (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        return true;
    }

    /**
     * 销售主页 - 本月新增会员 - 获取排序条件
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/9
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
            case 'member_name' :
                $attr = '`md`.name';
                break;
            case 'member_sex' :
                $attr = '`md`.sex';
                break;
            case 'birthday' :
                $attr = '`md`.birth_date';
                break;
            case 'age' :
                $attr = '`md`.birth_date';
                break;
            case 'member_mobile' :
                $attr = '`member`.mobile';
                break;
            case 'card_name' :
                $attr = '`mc`.card_name';
                break;
            case 'price' :
                $attr = '`mc`.amount_money';
                break;
            case 'card_num' :
                $attr = '`mc`.card_number';
                break;
            case 'seller' :
                $attr = '`employee`.name';
                break;
            case 'card_time' :
                $attr = '`mc`.create_at';
                break;
            case 'invalid_time' :
                $attr = '`mc`.invalid_time';
                break;
            case 'entry_time' :
                $attr = '`er`.entry_time';
                break;
            default;
                return $sorts;
        }
        return $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
    }

    /**
     * 销售主页 - 本月生日会员查询条数
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/29
     * @param $params
     * @param $type
     * @return array
     */
    public function getBirthdayMembers($type,$params)
    {
        $this->customs($params);
        $this->getDateWhere($type);
        return $this->getBirthdays($this->searchDateStart,$this->searchDateEnd);
    }
    /**
     * 销售主页 - 生日会员列表
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $params
     * @return array
     */
    public function getBirthday($params)
    {
        $this->custom($params);
        if(isset($params['startTime']) && isset($params['endTime'])){
            $query = Member::find()
                ->alias('member')
                ->joinWith(['memberDetails md'])
                ->joinWith(['employee employee'])
                ->joinWith(['memberCard mc'],false)
                ->where(['member.member_type' => 1])
                ->select(
                    "member.id,
                     member.mobile,
                     member.counselor_id,
                     md.name,
                     md.sex,
                     md.birth_date,
                     employee.name as ename,
                     mc.invalid_time,
                    ")
                ->orderBy($this->sorts)
                ->groupBy('member.id')
                ->asArray();
        }else{
            $this->getDateWhere($params['type']);
            $start = date("m-d",strtotime($this->searchDateStart));
            $end   = date("m-d",strtotime($this->searchDateEnd));
            $query = Member::find()
                ->alias('member')
                ->joinWith(['memberDetails md'])
                ->joinWith(['employee employee'])
                ->joinWith(['memberCard mc'],false)
                ->where(['member.member_type' => 1])
                ->andWhere(["and",[">=",'date_format(md.birth_date,"%m-%d")',$start],["<=",'date_format(md.birth_date,"%m-%d")',$end]])
                ->select(
                    "member.id,
                     member.mobile,
                     member.counselor_id,
                     md.name,
                     md.sex,
                     md.birth_date,
                     employee.name as ename,
                     mc.invalid_time,
                    ")
                ->orderBy($this->sorts)
                ->groupBy('member.id')
                ->asArray();
        }

        $query = $this->setWhereBirthday($query);         //场馆和公司判断
        return $birthdayMember = Func::getDataProvider($query,8);
    }
    /**
     * 销售主页 - 生日会员查询条数
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/28
     * @param $beginDate
     * @param $endDate
     * @return array
     */
    public function getBirthdays($beginDate,$endDate)
    {
        $start = date("m-d",strtotime($beginDate));
        $end   = date("m-d",strtotime($endDate));
        $query = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails  md'],false)
            ->joinWith(['employee employee'],false)
            ->where(["and",[">=",'date_format(md.birth_date,"%m-%d")',$start],["<=",'date_format(md.birth_date,"%m-%d")',$end]])
            ->andWhere(['member.member_type' => 1])
            ->select(
                "member.id
                    ")
            ->asArray();
        return $query = $this->setWheres($query);         //场馆和公司判断

    }

    /**
     * 销售主页 - 即将到期会员卡查询条数
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/29
     * @param $type
     * @param $params
     * @return array
     */
    public function getSoonDueCards($type,$params)
    {
        $this->customs($params);
        $this->getDateWhere($type);
        return $this->getSoonDues($this->searchDateStart,$this->searchDateEnd);
    }

    /**
     * 销售主页 - 即将到期列表
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $params
     * @return array
     */
    public function getSoonDue($params)
    {
        $this->custom($params);
        if(isset($params['startTime']) && isset($params['endTime'])){
            $query = Member::find()
                ->alias('member')
                ->joinWith(['memberDetails as md'],false)
                ->joinWith(['employee as employee'],false)
                ->joinWith(['memberCard as mc' =>function($query){
                    $query->joinWith(['employee employees']);
                }],false)
                ->andWhere(['member.member_type' => 1])
                ->select(
                    "member.id,
                    member.counselor_id,
                   member.mobile,
                   md.name,
                   md.sex,
                   mc.create_at,
                   mc.invalid_time,
                   employee.name as ename")
                ->groupBy(["member.id"])
                ->orderBy($this->sorts)
                ->asArray();
        }else{
            $this->getDateWhere($params['type']);
            $query = Member::find()
                ->alias('member')
                ->joinWith(['memberDetails as md'],false)
                ->joinWith(['employee as employee'],false)
                ->joinWith(['memberCard as mc' =>function($query){
                    $query->joinWith(['employee employees']);
                }],false)
                ->where(['between','mc.invalid_time',strtotime($this->searchDateStart),strtotime($this->searchDateEnd)])
                ->andWhere(['member.member_type' => 1])
                ->select(
                    "member.id,
                    member.counselor_id,
                   member.mobile,
                   md.name,
                   md.sex,
                   mc.create_at,
                   mc.invalid_time,
                   employee.name as ename")
                ->groupBy(["member.id"])
                ->orderBy($this->sorts)
                ->asArray();
        }

            $query               = $this->setWhere($query);         //场馆和公司判断
            return  $soonDueCard = Func::getDataProvider($query,8);

    }
    /**
     * 销售主页 - 即将到期条数
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $beginDate
     * @param $endDate
     * @return array
     */
    public function getSoonDues($beginDate,$endDate)
    {
        $query = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails as md'],false)
            ->joinWith(['memberCard as mc' =>function($query){
                $query->joinWith(['employee employee'],false);
            }],false)
            ->where(['between','mc.invalid_time',strtotime($beginDate),strtotime($endDate)])
            ->andWhere(['member.member_type' => 1])
            ->select(
                "member.id")
            ->groupBy(["member.id"])
            ->asArray();
        return   $query       = $this->setWheres($query);         //场馆和公司判断

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
            $this->searchDateStart = Func::getGroupClassDate($attr,true);
            $this->searchDateEnd   = Func::getGroupClassDate($attr,false);
        }elseif($attr == 'd'){
            $this->searchDateStart = Func::getGroupClassDate($attr,true);
            $this->searchDateEnd   = Func::getGroupClassDate($attr,false);
        }else{
            $this->searchDateStart = Func::getGroupClassDate($attr,true);
            $this->searchDateEnd   = Func::getGroupClassDate($attr,false);
        }
    }
    /**
     * 销售主页 - 未签到会员列表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/10
     * @param $params
     * @return array
     */
    public function getNotEntry($params)
    {
            $this->custom($params);
            $query = Member::find()
                ->alias('member')
                ->joinWith(['memberDetails as md'])
                ->joinWith(['employee as employee'])
                ->joinWith(['memberCard as mc' =>function($query){
                    $query->joinWith(['employee employees']);
                }])
                ->joinWith(['entryRecord as er'])
                ->where(['member.member_type' => 1])
                ->select(
                    "member.id,
                    member.counselor_id,
                   member.mobile,
                   md.name,
                   md.sex,
                   er.entry_time,
                   employee.name as ename")
                ->groupBy(['member.id'])
                ->orderBy($this->sorts)
                ->asArray();
            $query    = $this->setWhereSign($query);         //场馆和公司判断
            $notEntry = Func::getDataProvider($query,8);
        return $notEntry;
    }
    /**
     * 销售主页 - 未签到会员查询条数
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/29
     * @param $type
     * @param $params
     * @return array
     */
    public function getNotEntryMember($type,$params)
    {
        $this->customs($params);
        $date = time() - ($type*24*60*60);
        $query = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails as md'],false)
            ->joinWith(['memberCard as mc' =>function($query){
                $query->joinWith(['employee employee'],false);
            }])
            ->joinWith(['entryRecord as er'])
            ->where(['>=', 'er.entry_time', $date])
            ->andWhere(['member.member_type' => 1])
            ->select(
                "member.id")
            ->groupBy(['member.id'])
            ->asArray();
        return   $query    = $this->setWheres($query);         //场馆和公司判断

    }
    /**
     * 后台 - 销售主页 - 即将到期处理搜索条件
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/7/31
     * @param $query
     * @return string
     */
    public function setWhere($query)
    {
        $query->andFilterWhere([
            'and',
            [
                'member.counselor_id' => $this->sellId,
            ],
        ]);
        $query->andFilterWhere([
            'or',
            ['like','md.name', $this->keywords],
            ['like','mc.card_number', $this->keywords],
            ['like','member.mobile',$this->keywords],
            ['like','employee.name',$this->keywords]
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','mc.invalid_time',$this->startTime],
            ['<','mc.invalid_time',$this->endTime]
        ]);
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['member.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['member.venue_id'=>$this->nowBelongId]);
//        }
        $query->andFilterWhere(['member.venue_id'=>$this->venueId]);
        return $query;
    }
    /**
     * 后台 - 销售主页 - 未签到处理搜索条件
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/7/31
     * @param $query
     * @return string
     */
    public function setWhereSign($query)
    {
        $query->andFilterWhere([
            'and',
            [
                'member.counselor_id' => $this->sellId,
            ],
        ]);
        $query->andFilterWhere([
            'or',
            ['like','md.name', $this->keywords],
            ['like','mc.card_number', $this->keywords],
            ['like','member.mobile',$this->keywords],
            ['like','employee.name',$this->keywords]
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','er.entry_time',$this->startTime],
            ['<=','er.entry_time',$this->endTime]
        ]);
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['member.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['member.venue_id'=>$this->nowBelongId]);
//        }
        $query->andFilterWhere(['member.venue_id'=>$this->venueId]);
        return $query;
    }

    /**
     * @私教统计 - 上课量统计 - 获取收费课程
     * @create 2017/8/30
     * @author zhumengke <zhumengke@itsports.club>
     * @return array
     */
    public function getNotDeal()
    {
        $data =  Member::find()
            ->alias('member')
            ->joinWith(['memberCourseOrder mco'])
            ->where(['or',['mco.course_type'=>1],['mco.course_type'=>null]])
            ->andWhere(['>','mco.money_amount',0])
            ->andFilterWhere(['mco.private_id'=>$this->coachId])
            ->asArray()->all();
        return array_column($data,'mco.id');
    }
    public function getDeal()
    {
        $data =  MemberCourseOrder::find()
            ->where(['or',['course_type'=>1],['course_type'=>null]])
            ->andWhere(['>','money_amount',0])
            ->andFilterWhere(['private_id'=>$this->coachId])
            ->asArray()->all();
        return array_column($data,'id');
    }
    /**
     * 后台 - 销售主页 - 未签到处理搜索条件
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/7/31
     * @param $query
     * @return string
     */
    public function setWhereBirthday($query)
    {
        $query->andFilterWhere(['member.venue_id' => $this->venueId]);
        $query->andFilterWhere(['member.counselor_id' => $this->sellId]);
        $query->andFilterWhere(['mco.private_id' => $this->coachId]);
        if(!empty($this->status)){
            $arrId    = $this->getDeal();
            $arrIdNot = $this->getNotDeal();
            if($this->status == 2){
                $query->andWhere(['NOT IN','mco.id',$arrId])
                    ->orWhere(['mco.id' => null,'member.member_type' => 1]);
            }else{
                $query->andWhere(['mco.id'=>$arrId]);
            }
        }
        if(!empty($this->birthdayClass) || ( !is_null($this->birthdayClass) && $this->birthdayClass == 0)){
            $query->andFilterWhere(['and',['mco.course_type'=>3],['mco.overage_section'=>$this->birthdayClass]]);
        }
        $query->andFilterWhere([
            'or',
            ['like','md.name', $this->keywords],
            ['like','member.mobile',$this->keywords],
            ['like','employee.name',$this->keywords]
        ]);
        if(!empty($this->startTime)){
            $query->andFilterWhere([
                'and',
                ['>=','date_format(md.birth_date,"%m-%d")',date("m-d",$this->startTime)],
                ['<=','date_format(md.birth_date,"%m-%d")',date("m-d",$this->endTime)]
            ]);
        }
        if(!empty($this->memberType)){
            if($this->memberType == '1'){
                $query->andWhere(['>=','mc.invalid_time',time()]);    //有效会员
                $query->andWhere(['IS NOT','mc.invalid_time',null]);
            }else{
                $query->andWhere(['<','mc.invalid_time',time()]);    //到期会员
                $query->andWhere(['IS NOT','mc.invalid_time',null]);
            }
        }
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['member.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['member.venue_id'=>$this->nowBelongId]);
//        }
        return $query;
    }
    /**
     * 后台 - 销售主页 - 新增会员处理搜索条件
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/7/31
     * @param $query
     * @return string
     */
    public function setWhereNew($query)
    {
        $query->andFilterWhere([
            'and',
            [
                'mc.employee_id' => $this->sellId,
            ],
        ]);
        $query->andFilterWhere([
            'and',
            [
                'mc.card_type' => $this->cardType,
            ],
        ]);
        $query->andFilterWhere([
            'or',
            ['like','md.name', $this->keywords],
            ['like','mc.card_number', $this->keywords],
            ['like','member.mobile',$this->keywords],
            ['like','employee.name',$this->keywords]
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','member.register_time',$this->startTime],
            ['<','member.register_time',$this->endTime]
        ]);
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['member.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['member.venue_id'=>$this->nowBelongId]);
//        }
        $query->andFilterWhere(['member.venue_id'=>$this->venueId]);
        return $query;
    }
    /**
     * 后台 - 销售主页 - 处理搜索条件条数
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/7/31
     * @param $query
     * @return string
     */
    public function setWheres($query)
    {
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['member.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['member.venue_id'=>$this->nowBelongId]);
//        }
        $query->andFilterWhere(['member.venue_id'=>$this->venueId]);
        return $query->count();
    }
    /**
     * 后台私教管理 - 私教上下课 - 确认上课
     * @author Huang hua <huangpengju@itsports.club>
     * @create 2017/6/14
     * @param $id
     * @param $venueId
     * @return \yii\db\ActiveQuery
     */
    public function     getMemberId($id,$venueId)
    {
            $start                = strtotime(date('Y-m-d',time()).' 00:00:00');
            $end                  = strtotime(date('Y-m-d',time()).' 23:59:59');
            $model                = EntryRecord::find()
                ->where(['member_id' => $id])
                ->andWhere(['between', 'entry_time',$start,$end])
                ->andWhere(['venue_id'=>$venueId])
                ->asArray()->one();

        if(!empty($model)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 后台私教管理 - 私教上下课 - 确认上课
     * @author Huang hua <huangpengju@itsports.club>
     * @create 2017/6/14
     * @param $id
     * @return \yii\db\ActiveQuery
     */
    public static  function  getMemberDepositOne($id)
    {
        return  MemberDeposit::find()->where(['member_id'=>$id])->asArray()->one();
    }
    public function updateMemberPic($post)
    {
          if(!isset($post['id'])){
              return false;
          }
        $member = MemberDetails::findOne(['member_id'=>$post['id']]);
        $member->pic = $post['pic'];
        if($member->save()){
            return true;
        }
        return $member->errors;
    }
    /**
     * 后台潜在会员- 删除 - 数据库信息
     * @author Huang pengju <huangpengju@itsports.club>
     * @create 2017/6/23
     * @param $memberId         //会员id
     * @return bool|string
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function delMemberInfo($memberId,$companyId)
    {
        $transaction                  = \Yii::$app->db->beginTransaction();
        try {
            //删除 member_base表中的第三方登录
            $delMemberBase = MemberBase::deleteAll(["member_id"=>$memberId]);
            $data          = MemberDetails::find()->where(['member_id'=>$memberId])->asArray()->one();     //查找订单详情
            if(!empty($data))
            {
                $memberDetail = MemberDetails::findOne($data['id']);                                //准备删除会员详情的对象
                $detail       = $memberDetail->delete();                                            //执行删除会员详细信息
                if(!$detail)
                {
                    return false;
                }
            }
            $memberAccount = Member::find()->where(['id'=>$memberId])->asArray()->one();
            if($memberAccount['mobile']!= 0 && !empty($memberAccount['mobile'])){
              $memberCount = Member::find()
                  ->where(['and',['mobile'=>$memberAccount['mobile']],['company_id'=>$companyId]])
                  ->asArray()
                  ->count();
                if($memberCount == 1 && $memberAccount['member_account_id']!= 0){
                    $delMemberAccount   = MemberAccount::findOne($memberAccount['member_account_id']);
                    $delMemberAccount->delete();
                }
            }
            $order        = Order::deleteAll(["member_id"=>$memberId]);
            $history      = ConsumptionHistory::deleteAll(["member_id"=>$memberId]);
                            \backend\models\MemberCard::deleteAll(["member_id"=>$memberId]);
            $memberData   = \common\models\base\Member::findOne($memberId);
            $member       = $memberData->delete();                                                    //删除会员基本信息
            if(!$member && !$delMemberBase)
            {
                return false;
            }
            if($transaction->commit() !== null)                    //事务提交
            {
                return false;
            }
        }catch (\Exception $e)
        {
            $transaction->rollBack();       //回滚
            return $e->getMessage();
        }
    }
    /**
     * 后台潜在会员- 登记 - 根据手机号 公司id判重
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/23
     * @param $mobile
     * @param $companyId
     * @param $venueId
     * @return string
     */
    public function getRegisterInfo($mobile,$companyId,$venueId)
    {
        $data = Member::find()->where(['and',['mobile'=>$mobile],['venue_id'=>$venueId]])->andWhere(['company_id'=>$companyId])->asArray()->one();
        return $data;
    }

    /**
     * 潜在会员管理 - 关联组织架构表
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/7/12
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization(){
        return $this->hasOne(Organization::className(), ['id'=>'venue_id']);
    }
    /**
     * 验卡管理 - 根据会员手机号查询会员id
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/23
     * @param $mobile
     * @param $identify
     * @return string
     */
    public function getMobileId($mobile,$identify){
        $data = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails memberDetails'])
            ->joinWith(['memberCard mc'])
            ->select("member.id")
            ->where(['or',["member.mobile"=>$mobile],["memberDetails.ic_number"=>$mobile],['mc.card_number'=>$mobile]])
            ->asArray();
        if($identify["nowBelongType"] && $identify["nowBelongType"] == 'company'){
            $data->andFilterWhere(['member.company_id'=> $identify['nowBelongId']]);
        }
        if($identify["nowBelongType"] && $identify["nowBelongType"] == 'venue'){
            $data->andFilterWhere(['member.venue_id'=>$identify['nowBelongId']]);
        }
        $data = $data->one();
        return $data;
    }
    /**
     * 私教管理 - 私教排课 - 搜索姓名
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2018/2/27
     * @return bool|string
     */
    public function getMemberByName($name,$identify){
        $data = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails memberDetails'],false)
            ->where(["memberDetails.name" => $name])
            ->select("member.id as member_id,member.mobile,memberDetails.name,memberDetails.sex")
            ->asArray();
        if($identify["nowBelongType"] && $identify["nowBelongType"] == 'company'){
            $data->andFilterWhere(['member.company_id'=> $identify['nowBelongId']]);
        }
        if($identify["nowBelongType"] && $identify["nowBelongType"] == 'venue'){
            $data->andFilterWhere(['member.venue_id'=>$identify['nowBelongId']]);
        }
        $data = $data->all();
        return $data;
    }
    /**
     * 验卡管理 - 根据查询公司下所有会员
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/23
     * @param $companyId
     * @return string
     */
    public static function getMemberAll($companyId)
    {
        return Member::find()->alias('mm')->select('mm.id,mm.mobile,md.name')->joinWith(['memberDetails md'],false)->andFilterWhere(['mm.company_id'=>$companyId])->asArray()->all();
    }

    /**
     * @私教统计 - 生日会员
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/8/14
     * @param  $params
     * @return array
     */
    public function birthdayMember($params)
    {
        $this->custom($params);
        $query = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails md'], false)
            ->joinWith(['employee employee'], false)
            ->joinWith(['memberCourseOrder mco' => function ($query) {
//                $query->joinWith(['employee employee'], false);
                $query->joinWith(['memberCourseOrderDetails mcod'], false);
                $query->joinWith(['chargeClass cc'], false);
            }], false)
            ->joinWith(['memberCard mc'],false)
            ->where(['member.member_type' => 1])
            ->orWhere(['mco.id' => null,'member.member_type' => 1])
            ->select(
                "member.id,
                 member.mobile,
                 md.name,
                 md.sex,
                 md.birth_date,
                 mco.private_id,
                 employee.name as coachName,
                 mcod.product_name,
                 mcod.course_name,
                 mc.invalid_time,
                 ")
            ->groupBy('member.id')
            ->asArray();
        $query = $this->setWhereBirthday($query);         //场馆和公司判断
        return Func::getDataProvider($query,8);
    }
    /**
     * 验卡管理 - 根据查询爽约会员
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/23
     * @param  $params
     * @return string
     */
    public function loadParams($params)
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
        $this->venueId   = (isset($params[self::VENUE_ID]) && !empty($params[self::VENUE_ID])) ? $params[self::VENUE_ID] : $venueIds;
        $this->keyword   = (isset($params[self::SEARCH]) && !empty($params[self::SEARCH])) ? $params[self::SEARCH] : null;
        $this->startTime = (isset($params[self::START])  && !empty($params[self::START]))  ? strtotime($params[self::START]) : null;
        $this->endTime   = (isset($params[self::END])    && !empty($params[self::END]))     ? strtotime($params[self::END]) : null;
        $this->type      = (isset($params[self::PRIVATE_TYPE]) && !empty($params[self::PRIVATE_TYPE])) ? $params[self::PRIVATE_TYPE] : null;
        $this->nowBelongType    = (isset($params[self::NOW_BELONG_TYPE]) && !empty($params[self::NOW_BELONG_TYPE])) ? $params[self::NOW_BELONG_TYPE] : null;
        $this->nowBelongId      = (isset($params[self::NOW_BELONG_ID]) && !empty($params[self::NOW_BELONG_ID])) ? $params[self::NOW_BELONG_ID] : null;
        if(isset($params['sort']) && !empty($params['sort'])){
            $this->sorts = [
                'total' => SORT_DESC
            ];
        }
    }
    /**
     * 验卡管理 - 根据查询公司下所有会员
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/23
     * @param  $params
     * @return string
     */
    public function getMemberMissRecord($params)
    {
        $this->loadParams($params);
//         $query =   self::find()->alias('mm')
//            ->joinWith(['aboutClass ac'=>function($query){
//                $query->joinWith(['memberCourseOrderDetails mcod'=>function($query){
//                    $query->joinWith(['memberCourseOrder mco']);
//                }],false);
//            }],false)
//            ->joinWith(['memberDetails md'],false)
//            ->where(['ac.type'=>1,'ac.status'=>6])
//            ->andWhere(['<=','ac.start',time()])
//            ->select('mm.id,md.sex,md.name,mm.mobile,count(ac.member_id) as total,mco.course_type,mco.type')
//            ->groupBy('ac.member_id')
//            ->orderBy($this->sorts)
//            ->asArray();
        $query = \backend\models\AboutClass::find()->alias('ac')
            ->joinWith(['member mm' => function($query){
                $query->joinWith(['memberDetails md'],false);
            }],false)
            ->joinWith(['memberCourseOrderDetails mcod' => function($query){
                $query->joinWith(['memberCourseOrder mco'],false);
            }],false)
            ->where(['ac.type'=>1,'ac.status'=>6])
            ->select('mm.id,md.sex,md.name,mm.mobile,ac.member_id,count(ac.id) as total,mco.course_type,mco.type')
            ->groupBy('ac.member_id')
            ->orderBy($this->sorts)
            ->asArray();
         $query = $this->getMemberMissWhere($query);
         return Func::getDataProvider($query,8);
    }
    /**
     * @私教爽约 - 课种分类搜索 - 获取课程类型（PT、HS、生日课）
     * @create 2017/10/31
     * @author zhumengke <zhumengke@itsports.club>
     * @return array
     */
    public function getCourseTypeTwo()
    {
        $data = MemberCourseOrder::find()
            ->where(['course_type'=>2])
            ->orWhere(['course_type'=>null,'type'=>2])
            ->asArray()->all();
        return array_column($data,'id');
    }
    public function getCourseTypeThree()
    {
        $data = MemberCourseOrder::find()
            ->where(['course_type'=>3])
            ->orWhere(['course_type'=>null,'type'=>3])
            ->asArray()->all();
        return array_column($data,'id');
    }
    /**
     * 验卡管理 - 根据查询公司下所有会员
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/23
     * @param  $query
     * @return string
     */
    public function getMemberMissWhere($query)
    {
        $query->andFilterWhere([
            'or',
            ['like','md.name',$this->keyword],
            ['mm.mobile'=>$this->keyword],
            ['mm.id'=>$this->keyword],
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','ac.start',$this->startTime],
            ['<=','ac.end',$this->endTime]
        ]);
        if(!empty($this->type)){
            $two   = $this->getCourseTypeTwo();
            $three = $this->getCourseTypeThree();
            if($this->type == 2){
                $query->andWhere(['mco.id'=>$two]);      //HS
            }elseif($this->type == 3){
                $query->andWhere(['mco.id'=>$three]);    //生日课
            }else{
                $query->andWhere(['and',['NOT IN','mco.id',$two],['NOT IN','mco.id',$three]]);    //PT
            }
        }
        $query->andFilterWhere(['mm.venue_id'=>$this->venueId]);
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['mm.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && $this->nowBelongType == 'venue'){
//            $query->andFilterWhere(['mm.venue_id'=>$this->nowBelongId]);
//        }
        return $query;
    }
    /**
     * 验卡管理 - 根据查询公司下所有会员
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/23
     * @param  $params
     * @return string
     */
    public function getMissRecordDetail($params)
    {
        $this->loadParams($params);
        $query = \backend\models\AboutClass::find()->alias('ac')
            ->joinWith(['memberCourseOrderDetails mcod'=>function($query){
                $query->joinWith(['memberCourseOrder mco'],false);
            }],false)
            ->joinWith(['employee employee'],false)
            ->joinWith(['member mm'],false)
            ->where(['ac.type'=>1])
            ->andWhere(['ac.status'=>6,'ac.member_id'=>$params['memberId']])
            ->select('ac.id,ac.member_id,ac.start,employee.name,mco.course_amount,mco.overage_section,mco.type,mcod.product_name')
            ->groupBy('ac.id')
            ->asArray();
        $query = $this->getMemberMissDetailWhere($query);
        return Func::getDataProvider($query,8);
    }
    /**
     * 验卡管理 - 根据查询公司下所有会员
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/23
     * @param  $query
     * @return string
     */
    public function getMemberMissDetailWhere($query)
    {
        $query->andFilterWhere([
            'and',
            ['>=','ac.start',$this->startTime],
            ['<=','ac.end',$this->endTime]
        ]);
        $query->andFilterWhere(['mco.type'=>$this->type]);
        $query->andFilterWhere(['mm.venue_id'=>$this->venueId]);
        return $query;
    }
    /**
     * 后台 - 更柜管理 - 根据通店场馆获取不同场馆的会员数据
     * @create 2017/12/26
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return  mixed
     */
    public function getCabinetId($data)
    {
        if(!empty($data) && strlen($data)!=11){
            $memberCard     = \backend\models\MemberCard::findOne(['card_number'=>$data]);
            $venueTimes     =  \common\models\base\VenueLimitTimes::find()
                ->where(['member_card_id'=>$memberCard['id']])
                ->asArray()
                ->all();
            $venuesDataId = [];
            foreach ($venueTimes as $k=>$v){
                if(empty($v['venue_ids'])){
                    array_push($venuesDataId,[$v['venue_id']]);
                }else{
                    $venueIds         =  json_decode($v['venue_ids']);
                    array_push($venuesDataId,$venueIds);
                }
            }
            $venuesDataIdAll = [];
            foreach ($venuesDataId as $k=>$v){
                $venuesDataIdAll= array_merge($venuesDataIdAll,$v);
            }
            return $venuesDataIdAll;


        }
    }
    /**
     * 后台 -  新柜子管理  - 根据手机号搜索会员信息
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/6/5
     * @param $phone    //搜索电话号码
     * @param $id       //搜索id
     * @param $type      //搜索权限级别
     * @return array
     */
    public  function searchMember($phone,$id,$type){
        $data  = Member::find()->joinWith(["memberDetails"],false)
            ->joinWith(["memberCard"],false)
            ->select("cloud_member.*, 
                          cloud_member.mobile,
                          cloud_member_details.name,                      
                          cloud_member_details.pic,                      
                          cloud_member_details.member_id,
                          ")
            ->where(['or',["cloud_member.mobile"=>$phone],['cloud_member_card.card_number'=>$phone]])->andWhere(['<>','cloud_member.status','2'])->asArray();
        if(!empty($phone) && strlen($phone)!=11){
            $venueIds              = $this->getCabinetId($phone);
        }else{
            $cardObj     = new \backend\models\CardCategory();
            $venueIds    = $cardObj->getVenueIdByRole();
        }
        if(isset($type) && $type == 'company'){
            $data = $data->andFilterWhere(['cloud_member.company_id'=>$id]);
        }
        if(isset($type) && $type == 'venue'){
//            $data = $data->andFilterWhere(['cloud_member.venue_id'=>$id]);
            $data = $data->andFilterWhere(['in','cloud_member.venue_id',$venueIds]);

        }
        $query = $data->one();
        return $query;
    }

    public function memberMessage($accountId){
        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        foreach ($members as $member) {
            $ids[] = $member['id'];
        }
        $member = MemberDetails::find()
            ->where(["member_id"=>$ids])
            ->select("
                    name,
                    sex,
                    id_card,
                 ")
            ->asArray()
            ->one();
        return $member;
    }
    /**
     * 后台 -  闸机  - 获取数据库的当前时间
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/6/5
     * @param $cardId    //会员卡id
     * @param $id        // 员工 或则 会员id
     * @return array
     */
    public static function dataBaseCurrentTime($cardId,$id){
        $data = ScanCodeRecord::find()
            ->orderBy(["id"=>SORT_DESC])
            ->select("create_at");
       if($cardId=="000000"){         // 员工条件判断
            $data->where(["member_id"=>$id]);
       }else{                         // 会员条件判断
            $data->where(["member_card_id"=>$cardId]);
       }
       $data = $data->limit(1)
                    ->one();
       if(empty($data)){
          return null;
       }
       return  $data->create_at;
    }

    /**
     * 后台 -  闸机  - 两次进场的时间差判断
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/12/18
     * @param $id              //会员id
     * @param $venueId         // 场馆id
     * @return boolean
     */
    public static function gainTheDiffTime($id,$venueId){
     // 判断 5分钟钟之前是否有进场记录
     $entryNum =  EntryRecord::find()
                    ->where(["and",["member_id"=>$id],["venue_id"=>$venueId]])
                    ->andWhere(["between","entry_time",time()-5*60,time()])
                    ->count();
     if($entryNum==0){
        return true;    // 5分钟之前 没有进场记录 可以进场
     }
        return false;   // 当前时间往前 推5分钟有进场记录 不可以进场
    }

    /**
     * 后台 -  闸机  - 修正场馆信息
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/01/04
     * @param $venueName  // 场馆名称
     * @return boolean
     */
     public static function gainOrganizationMessage($venueName=""){
           switch($venueName){
               case "maibu":
                   $organization = [49,56];     // 迈步娱乐健身馆
               break;
               case "wayd-huadan":
                   $organization = [1,59];      //花园路丹尼斯店
               break;
               case "wayd-dxlwdjs":
                   $organization = [1,10];      // 大学路舞蹈健身馆
               break;
               default:
                   $organization = ["",""];
               break;
           }
         return $organization;
     }

    /**
     * @desc: 会员管理-会员信息修改-判断手机号是否存在
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/02/28
     * @param $mobile
     * @param $venueId
     * @param $companyId
     * @return bool
     */
    public function judgeMobile($mobile,$memberId,$venueId,$companyId)
    {
        $data = \common\models\base\Member::find()
            ->where([
                'and',
                ['mobile'=>$mobile],
                ['venue_id'=>$venueId],
                ['company_id'=>$companyId],
                ['!=','id',$memberId]
            ])
            ->asArray()->one();
        if ($data){
            return true;
        }
        return false;
    }

    /**
     * @desc: 会员管理-会员转卡-通过手机号搜索所有会员
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/03/02
     * @param $mobile
     * @param $venueId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function searchMemberByMobile($mobile,$venueId)
    {
        $data = Member::find()
            ->alias('member')
            ->joinWith(['memberDetails ms'],false)
            ->joinWith(['venue venue'],false)
            ->select('
            member.id,
            ms.name as memberName,
            venue.name as venueName,
            ')
            ->where([
                'and',
                ['member.mobile'=>$mobile],
                ['member.venue_id'=>$venueId]
            ])
            ->asArray()->all();
        return $data;
    }

    /**
     *后台会员管理 - 会员卡详情 -  会员id获取姓名场馆
     * @author Huang hua <huanghua@itsports.club>
     * @create 2018/3/15
     * @param $memberId
     * @return bool|string
     */
    public function MemberVenueName($memberId)
    {
        $model = Member::find()
            ->alias('mm')
            ->joinWith(['memberDetails memberDetails'])
            ->joinWith(['organization organization'])
            ->where(['mm.id'=>$memberId])
            ->select('
            mm.id,
            mm.venue_id,
            memberDetails.name,
            organization.name as venueName,
            ')
            ->asArray()
            ->one();
        return $model;
    }
}
