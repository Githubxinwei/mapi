<?php
namespace backend\models;

use common\models\Func;
use common\models\relations\EntryRecordRelations;

class EntryRecord extends \common\models\base\EntryRecord
{
    use EntryRecordRelations;
    public $entryTime;
    const  TIME = 'entryTime';
    public $nowBelongId;
    public $nowBelongType;
    public $sorts;
    public $searchDateStart;
    public $searchDateEnd;
    public $venueId;
    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    const SEARCH_DATE_START = 'searchDateStart';
    const SEARCH_DATE_END = 'searchDateEnd';

    const ENTRY_TIME = 'entryTime';
    public $keywords;
    const KEY = 'keywords';
    public $startTime;
    public $endTime;
    const START = 'startTime';
    const END = 'endTime';
    public $sellId;//销售id
    const SELL_ID ='sellId';
    public $coachId;
    const COACH_ID = 'coachId';
    public $type;
    const TYPE = 'type';
    public $cardType;
    const CARD_TYPE = 'cardType';
    public function getEntryArr($id,$venueId)
    {
         $query = EntryRecord::find()
             ->alias('er')
             ->select('er.*,on.name')
             ->joinWith(['organization on'])
             ->where(['member_card_id'=>$id])
             ->andWhere(['venue_id'=>$venueId])
             ->orderBy('create_at DESC')->asArray();
         $data  = Func::getDataProvider($query,3);
         return $data;
    }
    /**
     *后台会员管理 - 会员详细信息 -  进场信息查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/20
     * @param $params
     * @return bool|string
     */
    public function EntryRecordData($params,$venueId)
    {
        if(!empty($params['entryTime'])){
            $this->customLoad($params);
            $model = EntryRecord::find()
                ->alias('er')
                ->joinWith(['memberCard memberCard'])
                ->joinWith(['organization organization'])
                ->where(['er.member_id' => $params['MemberId']])
                ->andFilterWhere(['er.venue_id' => $venueId])
                ->orderBy(['er.id' =>SORT_DESC])
                ->asArray();
            $model         = $this->getSearchWhere($model);
            $dataProvider = Func::getDataProvider($model, 8);
            return $dataProvider;
        }else{
            $model = EntryRecord::find()
                ->alias('er')
                ->joinWith(['memberCard memberCard'])
                ->joinWith(['organization organization'])
                ->where(['er.member_id' => $params['MemberId']])
                ->andFilterWhere(['er.venue_id' => $venueId])
                ->orderBy(['er.id' =>SORT_DESC])
                ->asArray();
            $dataProvider = Func::getDataProvider($model, 8);
            return $dataProvider;
        }

    }

    /**
     * 会员管理 - 会员详情到场 - 搜索数据处理数据
     * @create 2017/5/9
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return bool
     */
    public function customLoad($data)
    {
        $this->entryTime      = (isset($data[self::ENTRY_TIME]) && !empty($data[self::ENTRY_TIME])) ? (int)strtotime($data[self::ENTRY_TIME]) : null;
        return true;
    }
    /**
     * 会员管理 - 会员详情到场 - 增加搜索条件
     * @create 2017/5/9
     * @author huanghua<huanghua@itsports.club>
     * @param $model
     * @return mixed
     */
    public function getSearchWhere($model)
    {
        $model->andFilterWhere([
            'and',
            ['>=','er.entry_time',$this->entryTime],
            ['<','er.entry_time',($this->entryTime+86400)]
        ]);
        return $model;
    }

    /**
     * 销售主页 - 客流量
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/12
     * @param
     * @return int
     */
    public function getEntryCount($id,$type)
    {
        $beginDate = date('Y-m-01 00:00:00', strtotime(date("Y-m-d")));
        $endDate   = date('Y-m-d 23:59:59', strtotime("$beginDate + 1 month - 1 day"));
        $query     = EntryRecord::find()
            ->alias('er')
            ->where(['between','entry_time',strtotime($beginDate),strtotime($endDate)])
            ->count();
        $query = $this->getWhere($query,$id,$type);
        return $query;
    }

    /**
     * 后台 - 销售主页 - 处理搜索条件
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/12
     * @param $query
     * @return string
     */
    public function getWhere($query,$id,$type)
    {
        if($type && ($type == 'venue' || $type == 'department')){
            $query->andFilterWhere(['er.venue_id'=>$id]);
        }
        return $query;
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
     * 销售主页 - 客流量查询条数
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $params
     * @param $type
     * @return array
     */
    public function actionMemberTraffics($type,$params)
    {
        $this->customs($params);
        $this->getDateWhere($type);
        return $this->getEntryTimes($this->searchDateStart,$this->searchDateEnd);
    }
    /**
     * 销售主页 - 客流量列表
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $params
     * @return array
     */
    public function getEntryTime($params)
    {
        $this->custom($params);
        $query = EntryRecord::find()
            ->alias('er')
            ->joinWith(['memberCard memberCard'=>function($query){
                $query->joinWith(["memberCourseOrder as memberCourseOrder"=>function($value){
                    $value->joinWith(["employee as employees"],false);
                }]);
            }],false)
//            ->joinWith(['aboutClass aboutClass'],false)
//            ->joinWith(['aboutClass as aboutClass' =>function($query){
//                $query->joinWith(['employee employee']);
//            }])
            ->joinWith(['member as member' =>function($query){
                $query->joinWith(['memberDetails memberDetails']);
                $query->joinWith(['employee employee']);
            }])
//            ->where(['between','entry_time',strtotime($beginDate),strtotime($endDate)])
            ->andWhere(['IS NOT','er.member_card_id',null])
            ->andWhere(['member.member_type' => 1])
            ->select(
                'er.id,
                er.entry_time,
                memberCard.card_number,
                memberCard.card_name,
                member.mobile,
                memberDetails.name,
                memberDetails.sex,
                memberDetails.member_id,
                employee.name as counselorName,
                employees.name as privateName')
            ->groupBy(["er.id"])
            ->orderBy($this->sorts)
            ->asArray();
        $query       = $this->setWhereTraffic($query);         //场馆和公司判断
        return  Func::getDataProvider($query,8);
    }

    /**
     * 销售主页 - 客流量查询条数
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/28
     * @param $beginDate
     * @param $endDate
     * @return array
     */
    public function getEntryTimes($beginDate,$endDate)
    {
        $query = EntryRecord::find()
            ->alias('er')
            ->joinWith(['memberCard memberCard'=>function($query){
                $query->joinWith(["memberCourseOrder as memberCourseOrder"=>function($value){
                    $value->joinWith(["employee as employees"],false);
                }]);
            }],false)
            ->joinWith(['member as member' =>function($query){
                $query->joinWith(['memberDetails memberDetails'],false);
                $query->joinWith(['employee employee'],false);
            }])
            ->where(['between','entry_time',strtotime($beginDate),strtotime($endDate)])
            ->andWhere(['member.member_type' => 1])
            ->select(
                'er.id')
            ->groupBy(["er.id"])
            ->asArray();
        return $query       = $this->setWheres($query);         //场馆和公司判断
    }
    /**
     * 后台 - 销售主页 - 处理搜索条件
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
     * @param $query
     * @return string
     */
    public function setWhere($query)
    {
        if($this->nowBelongType && $this->nowBelongType == 'company'){
            $query->andFilterWhere(['er.company_id'=>$this->nowBelongId]);
        }
        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
            $query->andFilterWhere(['er.venue_id'=>$this->nowBelongId]);
        }
        return $query;
    }
    public function setWheres($query)
    {
        $query->andFilterWhere(['er.venue_id'=>$this->venueId]);
        return $query->count();
    }

    /**
     * 后台 - 销售主页 - 客流量处理搜索条件
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/7/31
     * @param $query
     * @return string
     */
    public function setWhereTraffic($query)
    {
        $query->andFilterWhere([
            'and',
            [
                'member.counselor_id' => $this->sellId,
            ],
        ]);
        $query->andFilterWhere([
            'and',
            [
                'memberCard.card_type' => $this->cardType,
            ],
        ]);
        $query->andFilterWhere([
            'and',
            [
                'memberCourseOrder.private_id' => $this->coachId,
            ],
        ]);
        $query->andFilterWhere([
            'or',
            ['like','memberDetails.name', $this->keywords],
            ['like','memberCard.card_number', $this->keywords],
            ['like','member.mobile',$this->keywords],
            ['like','employee.name',$this->keywords],
            ['like','employees.name',$this->keywords],
            ['like','memberCard.card_name',$this->keywords]
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','er.entry_time',$this->startTime],
            ['<','er.entry_time',$this->endTime]
        ]);
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['member.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['member.venue_id'=>$this->nowBelongId]);
//        }
        $query->andFilterWhere(['er.venue_id'=>$this->venueId]);
        return $query;
    }
    /**
     * 销售主页 - 客流量列表 - 搜索数据处理数据
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
        $this->venueId          = (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->nowBelongId      = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType    = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->startTime        = (isset($data[self::START])&& !empty($data[self::START]))? (int)strtotime($data[self::START]) : null;
        $this->endTime          = (isset($data[self::END]) && !empty($data[self::END])) ? (int)strtotime($data[self::END]) : null;
        $this->keywords         = (isset($data[self::KEY]) && !empty($data[self::KEY])) ? $data[self::KEY] : null;
        $this->sellId           = (isset($data[self::SELL_ID]) && !empty($data[self::SELL_ID])) ? $data[self::SELL_ID] : null;
        $this->coachId          = (isset($data[self::COACH_ID]) && !empty($data[self::COACH_ID])) ? $data[self::COACH_ID] : null;
        $this->type             = (isset($data[self::TYPE]) && !empty($data[self::TYPE])) ? $data[self::TYPE] : null;
        $this->cardType         = (isset($data[self::CARD_TYPE]) && !empty($data[self::CARD_TYPE])) ? $data[self::CARD_TYPE] : null;
        $this->sorts            = self::sorts($data);
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
        $this->venueId          = (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->nowBelongId      = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType    = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
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
            case 'entry_time' :
                $attr = '`er`.entry_time';
                break;
            case 'member_name' :
                $attr = '`memberDetails`.name';
                break;
            case 'member_sex' :
                $attr = '`memberDetails`.sex';
                break;
            case 'member_cardId' :
                $attr = '`memberCard`.card_number';
                break;
            case 'member_mobile' :
                $attr = '`member`.mobile';
                break;
            case 'card_name' :
                $attr = '`memberCard`.card_name';
                break;
            case 'member_counselorId' :
                $attr = '`member`.counselor_id';
                break;
            case 'member_coachId' :
                $attr = '`aboutClass`.coach_id';
                break;

            default;
                return $sorts;
        }
        return $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
    }

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
     * 后台 - 潜在会员 - 跟进
     * @author zhumengke<zhumengke@itsports.club>
     * @create 2018/2/1
     * @return object
     */
    public function followUp($data,$companyId,$venueId)
    {
        $record = new EntryRecord();
        $record->venue_id   = $venueId;
        $record->entry_time = strtotime($data['entryTime']);
        $record->create_at  = time();
        $record->member_id  = $data['memberId'];
        $record->company_id = $companyId;
        $record->entry_way  = 1;
        $record->note       = $data['note'];
        if($record->save() == true){
            return true;
        }else{
            return $record->errors;
        }
    }

    /**
     * 后台 - 潜在会员 - 最近入场记录
     * @author zhumengke<zhumengke@itsports.club>
     * @create 2018/2/1
     * @return array
     */
    public function getFollowRecord($memberId)
    {
        $data = EntryRecord::find()->where(['member_id' => $memberId])->asArray();
        return Func::getDataProvider($data,8);
    }
}