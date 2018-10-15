<?php
namespace backend\models;
use common\models\base\Course;
use common\models\base\MemberCard;
use common\models\base\Organization;
use common\models\BindPack;
use common\models\Func;
use common\models\relations\CardCategoryRelations;
use common\models\VenueLimitTimes;

class CardCategory extends \common\models\base\CardCategory
{
    use CardCategoryRelations;
    public $search_card_name;
    public $sell_start_time;
    public $start_time_hour;
    public $sell_end_time;
    public $end_time_hour;
    public $search_venue_id;
    public $card_type;
    public $min_price;
    public $max_price;
    public $type;
    public $sorts;
    public $searchStatus;
    public $attr;
    public $searchContent;
    public $nowBelongId;
    public $nowBelongType;
    public $isCheck;
    public $companyId;
    public $venueId;
    public $isBindDeal; // 新增(是否绑定合同: 1绑定, 2没有绑定);

    const IS_BIND_DEAL = 'isBindDeal'; // 新增(是否绑定合同: 1绑定, 2没有绑定);
    const SEARCH_CONTENT = 'searchContent';
    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    const  NAME   = 'cardName';
    const  START  = 'startTime';
    const  END    = 'endTime';
    const  TYPE   = 'cardType';
    const  VENUE  = 'venue_id';
    const  MIN    = 'minPrice';
    const  MAX    = 'maxPrice';
    const  STATUS = 'status';
    const  IS_CHECK = 'isCheck';
    const  COMPANY = 'companyId';
    const  WEEK   = [1=>'周一',2=>'周二',3=>'周三',4=>'周四',5=>'周五',6=>'周六',7=>'周日'];


    public $keywords;//属性匹配卡名和卡号
    public $status;//会员卡状态
    public $attribute;//会员卡属性

    const  KEY = 'keywords';
    const  VENUE_ID = 'venueId';
    const  TYPES   = 'type';
    const ATTRIBUTE = 'attribute';
    
    /**
     * 会员卡管理 - 会员卡 - 获取搜索规格
     * @create 2017/4/5
     * @author lihuien<lihuien@itsports.club>
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
     * 会员卡管理 - 会员卡 - 获取排序条件
     * @create 2017/4/5
     * @author lihuien<lihuien@itsports.club>
     * @update huangpengju<huangpengju@itsports.club>
     * @update 2017/4/13
     * @param $data
     * @return mixed
     */
    public static function loadSort($data)
    {
        $sorts = ['id' => SORT_DESC];
        if (!isset($data['sortType'])) {
            return $sorts;
        }
        switch ($data['sortType']){
            case 'card_type'  :
                $attr = '`cardCategoryType`.type_name';
                break;
            case 'card_name'  :
                $attr = '`card`.card_name';
                break;
            case 'card_status':
                $attr = '`card`.status';
                break;
            case 'card_active':
                $attr = '`card`.active_time';
                break;
            case 'card_times' :
                $attr = '`card`.times';
                break;
            case 'card_price' :
                $attr = '`card`.sell_price';
                break;
            case  'card_sell_time' :
                $attr = '`card`.sell_end_time';
                break;
            default:
                $attr = NULL;
        }
        if($attr){
            $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }
    /**
     * 会员卡管理 - 会员卡 - 搜索数据处理数据
     * @create 2017/4/5
     * @author lihuien<lihuien@itsports.club>
     * @param $data
     * @return bool
     */
    public function customLoad($data)
    {
        $venueIds               = $this->getVenueIdByRole();
        $this->venueId          = (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->search_card_name = (isset($data[self::NAME]) && !empty($data[self::NAME])) ? $data[self::NAME] : null;
        $this->sell_start_time  = (isset($data[self::START])&& !empty($data[self::START]))? (int)strtotime($data[self::START]) : null;
        $this->sell_end_time    = (isset($data[self::END]) && !empty($data[self::END])) ? (int)strtotime($data[self::END].' 23:59:59') : null;
        $this->card_type        = (isset($data[self::TYPE]) && !empty($data[self::TYPE])) ? $data[self::TYPE] : null;
        $this->search_venue_id  = (isset($data[self::VENUE])&& !empty($data[self::VENUE])) ? $data[self::VENUE] : null;
        $this->min_price        = (isset($data[self::MIN])&& !empty($data[self::MIN])) ? (int)$data[self::MIN] : null;
        $this->max_price        = (isset($data[self::MAX])&& !empty($data[self::MAX])) ? $data[self::MAX] : null;
        $this->searchStatus     = (isset($data[self::STATUS])&& !empty($data[self::STATUS])) ? $data[self::STATUS] : null;
        $this->searchContent    = (isset($data[self::SEARCH_CONTENT]) && !empty($data[self::SEARCH_CONTENT])) ? $data[self::SEARCH_CONTENT] : null;
        $this->nowBelongId      = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID])) ? $data[self::NOW_BELONG_ID] : null;
        $this->nowBelongType    = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE])) ? $data[self::NOW_BELONG_TYPE] : null;
        $this->isCheck          = (isset($data[self::IS_CHECK]) && !empty($data[self::IS_CHECK])) ? $data[self::IS_CHECK] : null;
        $this->companyId        = (isset($data[self::COMPANY]) && !empty($data[self::COMPANY])) ? $data[self::COMPANY] : null;
        $this->type             = (isset($data['type']) && !empty($data['type'])) ? $data['type'] : null;
        $this->isBindDeal       = (isset($data[self::IS_BIND_DEAL]) && !empty($data[self::IS_BIND_DEAL])) ? $data[self::IS_BIND_DEAL] : null;
        $this->sorts = self::loadSort($data);
        return true;
    }

    /**
     * 会员卡管理 - 会员卡 - 获取价钱搜索条件
     * @create 2017/4/5
     * @author lihuien<lihuien@itsports.club>
     * @param $query
     * @return mixed
     */
    public function setWherePrice($query)
    {
        if($this->min_price && $this->max_price){
            $query->andFilterWhere([
                'or',
                ['between','sell_price',$this->min_price,$this->max_price],
                ['between','min_price',$this->min_price,$this->max_price],
            ]);
        }elseif ($this->min_price){
            $query->andFilterWhere([
                'or',
                ['>=','sell_price',$this->min_price],
                ['>=','min_price',$this->min_price],
            ]);
        }elseif ($this->max_price){
            $query->andFilterWhere([
                'or',
                ['<=','sell_price',$this->max_price],
                ['<=','min_price',$this->max_price],
            ]);
        }
        return $query;
    }
    /**
     *  会员卡管理 - 会员卡 - 获取数据
     * @create 2017/4/5
     * @author lihuien<lihuien@itsports.club>
     * @param $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $this->customLoad($params);
        $query  =  CardCategory::find()
                 ->alias('card')
                 ->joinWith(['cardCategoryType cardCategoryType'])
                 ->joinWith(['classServer classServer'])
                 ->joinWith(['serverCombo serverCombo'])
                 ->joinWith(['limitCardNumber limitCardNumber'])
                 ->joinWith(['deal deal'])
                 ->groupBy('limitCardNumber.card_category_id')
                 ->orderBy($this->sorts)
                 ->asArray();
        $query                = $this->getSearchWhere($query);
        return $dataProvider  = Func::getDataProvider($query,8);

    }
 
    /**
     * 会员卡管理 - 会员卡 - 增加搜索条件
     * @create 2017/4/5
     * @author lihuien<lihuien@itsports.club>
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
                'or',
                ['like','card.card_name', $this->search_card_name],
                ['like','deal.name', $this->search_card_name],
            ],
            [
                'card.venue_id' => $this->search_venue_id,
                'card.category_type_id'=>$this->card_type,
                'card.status'=>$this->searchStatus
            ],
        ]);
        if(!$this->isCheck){
            $query->andFilterWhere([
                'and',
                ['<=','limitCardNumber.sell_start_time',time()],
                ['>=','limitCardNumber.sell_end_time',time()]
            ]);
            $query->andFilterWhere(['card.status'=>[1,4]]);
        }else{
            $query->andFilterWhere(['card.status'=>[1,2,3,4,5,6]]);
        }
        $query->andFilterWhere([
            'and',
            ['>=','card.create_at',$this->sell_start_time],
            ['<=','card.create_at',$this->sell_end_time]
        ]);
        if($this->companyId){
            $query->andFilterWhere([
                'and',
                ['card.company_id'=>$this->companyId],
                ['card.venue_id'  =>$this->venueId]
            ]);
        }else{
            $query->andFilterWhere(['card.venue_id'=>$this->venueId]);
        }
        //新增是否绑定合同
        if($this->isBindDeal == 1){
            $query->andFilterWhere(['<>','card.deal_id', 0]);
        }else{
            if($this->isBindDeal == 2){
                $query->andFilterWhere(['card.deal_id'=> 0]);
            }
        }
        $query->andFilterWhere(['card.card_type'=>$this->type]);
        return $query = $this->setWherePrice($query);

    }
    /**
     * 后台会员管理 - 会员详细信息查询 - 多表查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/6
     * @return \yii\db\ActiveQuery
     */
    public function getCardCategory($id)
    {
        $model = CardCategory::find()
            ->alias('cc')
            ->joinWith(['classServer'],false)
            ->joinWith(['serverCombo'],false)
            ->joinWith(['cardTime'],false)
            ->joinWith(['memberCard mc'],false)
            ->joinWith(['organization'],false)
            ->select(
                "cc.card_name,
                 cc.attributes,
                 cc.sell_price,
                 cc.payment,
                 cc.times,
                 cc.class_server_id,
                 cc.server_combo_id,
                 cc.card_name,transfer_number,
                 cc.total_store_times,
                 cc.venue_id,
                 cc.transfer_price,
                 mc.amount_money,
                 mc.balance,
                 mc.create_at,
                 mc.active_time,
                 mc.invalid_time,
                 mc.card_category_id,
                 mc.total_times,
                 mc.consumption_times,
                 mc.member_id,
                 mc.id,
                 cloud_class_server.server_name,
                 cloud_server_combo.name,
                 cloud_card_time.day,
                 cloud_card_time.week,
                 cloud_card_time.start,
                 cloud_card_time.end,
                 cloud_organization.id,
                 cloud_organization.name,")
            ->where(['mc.id'=> $id])
            ->asArray()->one();
        return $model = $this->getCounselorData($model);
    }

    /**
     * 后台会员管理 - 会员详细信息查询 - 关联进场馆次数核算表查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/8
     * @param $id
     * @return \yii\db\ActiveQuery
     */
    public function getVenueLimitTimes($id)
    {
        return   $model         =  VenueLimitTimes::find()
                    ->alias('vlt')
                    ->joinWith(['memberCard mc'])
                    ->select(
                        "vlt.member_card_id,
                         vlt.venue_id,
                         vlt.overplus_times,
                         mc.id,
                        "
                    )
                    ->where(['mc.id'=> $id])
                    ->asArray()->one();

    }
    /**
     * 后台会员管理 - 会员详细信息查询 - 关联进场馆次数核算表查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/8
     * @param
     * @return \yii\db\ActiveQuery
     */
    public function getCounselorData($data)
    {
        if($data && isset($data['member_id'])){
             $data['counselor'] = Member::find()->alias('member')
                 ->select('member.id,member.counselor_id,')
                 ->joinWith(['employee'])->where(['member.id'=>$data['member_id']])->asArray()->one();
        }else{
            $data['counselor'] = [];
        }
        return $data;
    }
    /**
     * 后台会员管理 - 卡种信息 - 修改卡种状态
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/4/8
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
     * 后台会员管理 - 卡种信息 - 修改卡种冻结状态
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/4/8
     * @param
     * @return \yii\db\ActiveQuery
     */
    public static function editStatusBen($id)
    {
        $card = CardCategory::find()->where(['id'=>$id])->one();
        if($card && $card->status == 2){
            $card->status = 1;
        }else{
            $card->status = 2;
        }
        if($card->save()){
            return $card->status == 2?2:1;
        }else{
            return $card->errors;
        }
    }
    /**
     * 后台会员管理 - 卡种信息 - 修改卡种过期状态
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/4/8
     * @param  $id int 卡种ID
     * @return \yii\db\ActiveQuery
     */
    public function editStatusTime($id)
    {
        $card = \common\models\base\CardCategory::findOne(['id'=>$id]);
        if($card && $card->status == 3){
            $card->status = 1;
        }else{
            $card->status = 3;
        }
        if($card->save()){
            return $card->status == 3?3:1;
        }else{
            return $card->errors;
        }
    }
    /**
     * 后台会员管理 - 会员信息查询 - 获取卡种表数据
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/17
     * @return string
     */
    public function getCardCategoryData($data)
    {
        foreach ($data as &$value){
            $value['cardCategory'] =  MemberCard::find()->alias('mc')
                ->select('cloud_card_category.*,mc.card_category_id')
                ->joinWith(['cardCategory'])
                ->where(['mc.member_id'=>$value['id']])->asArray()->one();
        }
        return $data;
    }

    /**
     * 云运动 - 模板处理 - 获取模板
     * @author 李慧恩<lihuien@itsports.club>
     * @create 2017/4/9
     * @param $attr //请求的模板值
     * @return string
     */
    public static function getTemplate($attr)
    {
        if($attr == 'saleVenue'){
            $html = 'add-children/venue';
        }else if($attr == 'applyVenue'){
            $html = 'add-children/apply';
        }else if($attr == 'discount123'){
            $html = 'add-children/discount123';
        }else if($attr == 'class'){
            $html = 'add-children/class';
        }else if($attr == 'setRole'){
            $html = 'add-children/setGive';
        }else if($attr == 'server'){
            $html = 'add-children/server';
        }else if($attr == 'shopping'){
            $html = 'add-children/shoping';
        }else if($attr == 'donation'){
            $html = 'add-children/donation';
        }else if($attr == 'leaveVenue'){
            $html = 'add-children/leave';
        }else if($attr == 'discount'){
            $html = 'add-children/discount';
        }else if($attr == 'price'){
            $html = 'add-children/price';
        }else if($attr == 'addNewDiscount'){
            $html = 'add-children/addDiscount';
        }else if($attr == 'addNewValidRenew'){
            $html = 'add-children/validRenew';
        }else if($attr == 'validRenewEdit'){
            $html = 'add-children/validRenewEdit';
        }else if($attr == 'discountEdit'){
            $html = 'add-children/discountEdit';
        }else if($attr == 'applyVenueEdit'){
            $html = 'add-children/applyVenueEdit';
        }else if($attr == 'saleVenueEdit'){
            $html = 'add-children/saleVenueEdit';
        }else if($attr == 'addLeagueClassEdit'){
            $html = 'add-children/leagueClassEdit';
        }else if($attr == 'donationEdit'){
            $html = 'add-children/donationEdit';
        }else if($attr == 'leaveEdit'){
            $html = 'add-children/leaveEdit';
        }else if($attr == 'addPayWay'){
            $html = 'add-children/addPayWay';
        }else if($attr == 'addBuyCard'){
            $html = 'add-children/addBuyCard';
        }else if($attr == 'addBuyClass'){
            $html = 'add-children/addBuyClass';
        }else if($attr == 'personHtml123'){
            $html = 'add-children/personHtml';
        }else if($attr == 'numHtml'){
            $html = 'add-children/numHtml';
        }else if($attr == 'donationHtml'){
            $html = 'add-children/donationHtml';
        }else if($attr == 'venueHtml'){
            $html = 'add-children/venueHtml';
        }else if($attr == 'giftShopEdit1111'){
            $html = 'add-children/gift';
        }else if($attr == 'addCourseSection123'){
            $html = 'add-children/courseSection';
        }else if($attr == 'addServeClass123'){
            $html = 'add-children/ptServeClassSelect';
        }else if($attr == 'addServeSellVenue123'){
            $html = 'add-children/serveSellVenue';
        }elseif($attr == 'addCabinetMonth'){
            $html = '/new-cabinet/_add_month_cabinet';
        }elseif($attr == 'modifyPlugins'){
            $html = '/new-cabinet/new_add_cabinet';
        }elseif($attr == 'svenueHtml123'){
            $html = 'add-children/svenueHtml';
        }elseif($attr == 'limitHtml123'){
            $html = 'add-children/limitHtml';
        }elseif($attr == 'courseSelect123'){
            $html = 'add-children/courseHtml';
        }elseif($attr == 'giftHtml123'){
            $html = 'add-children/giftHtml';
        }elseif($attr == 'payWaysHtml'){
            $html = 'add-children/payHtml';
        }else{
        $html = 'add-children/venue';
    }
        return $html;
    }
    /**
     * 后台会员管理 - 卡种信息 - 修改卡种是否显示上下课
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/4/8
     * @param
     * @return \yii\db\ActiveQuery
     */
    public static function updateIsAppShow($id)
    {
        $card = CardCategory::find()->where(['id'=>$id])->one();
        if($card && $card->is_app_show == 1){
            $card->is_app_show = 2;
        }else{
            $card->is_app_show = 1;
        }
        if($card->save()){
            return $card->is_app_show == 2?2:1;
        }else{
            return $card->errors;
        }
    }
    /**
     * 云运动 - 模板处理 - 判断是否已经录入
     * @author 李慧恩<lihuien@itsports.club>
     * @create 2017/4/9
     * @param $name //请求的卡种名称
     * @param  $venueId // 场馆ID
     * @return string
     */
    public static function setCardName($name,$venueId)
    {
         $card = CardCategory::find()->where(['card_name'=>$name])->andWhere(['venue_id'=>$venueId])->asArray()->one();
         if(RechargeRuleForm::commonJudgment($card)){
             return true;
         }else{
             return false;
         }
    }

    public function getCardDetail($id)
    {
        $data = CardCategory::find()->alias('cc')
            ->joinWith(['cardCategoryType cct'])
            ->joinWith(['cardTime ct'])
            ->joinWith(['limitCardNumberAll lcn'=>function($venue){
                $venue->joinWith(['organization org']);
            }])
            ->joinWith(['limitCardNumber lcn'])
            ->joinWith(['deal deal'])
            ->joinWith(['bindPack bp'])
            ->joinWith(['organization orga'])
            ->where(['cc.id'=>$id])
            ->asArray()->one();
        return $data;
    }
    /**
     * 云运动 - 获取卡种场馆信息 - 获取卡种关联 售卖场馆信息 通用场馆信息
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/5/11
     * @param $id //请求的卡种id
     * @return string
     */
    public function getCardVenueData($id){
        //获取访问的卡种信息
        $data = $this->getCardDetail($id);
        //整理售卖场馆信息
        $sellVenueData =LimitCardNumber::find()
            ->where(["and",["card_category_id"=>$id],['IS NOT','limit',NULL]])
            ->joinWith(["organization"])
            ->joinWith(['cardDiscount cd'])
            ->select( "cloud_limit_card_number.*,cloud_organization.name")
            ->asArray()->all();
        //筛选 最大售卖日期 最小售卖日期
        $sellStartTime = [];
        $sellEndTime   = [];
        $sellTime      = [];
        foreach($sellVenueData as $key=>$values){
            $sellStartTime[] = $values["sell_start_time"];
            $sellEndTime[]   = $values["sell_end_time"];
        }
        sort($sellStartTime);
        rsort($sellEndTime);
        if(!empty($sellStartTime)&&!empty($sellEndTime)){
            $sellMinTime = $sellStartTime[0];
            $sellMaxTime = $sellEndTime[0];
        }else{
            $sellMaxTime = null;
            $sellMinTime = null;
        }

        $sellTime["sellMaxTime"] = $sellMaxTime;
        $sellTime["sellMinTime"] = $sellMinTime;
        //整理通用场馆信息
        $goVenueData = LimitCardNumber::find()
            ->alias('lcn')
            ->where(["lcn.card_category_id"=>$id])
            ->andWhere(['or',['IS NOT','lcn.times',NULL],['IS NOT','lcn.week_times',NULL]])
            ->joinWith(["organization"],false)
            ->select("lcn.*,cloud_organization.name,cloud_organization.identity")
            ->asArray()->all();
        $data["goVenue"] = [];
        foreach ($goVenueData as $key => $value){
            if(empty($value['name'])){
                $venueIds = json_decode($value['venue_ids'],true);
                $value['organization'] = Organization::find()->where(['id'=>$venueIds])->select('id,name,identity')->asArray()->all();
                array_push($data["goVenue"],$value);
            }else{
                array_push($data["goVenue"],$value);
            }
        }
        //整理团课套餐信息
        $groupClassData = \backend\models\BindPack::find()
            ->alias('bp')
            ->where(["and",["bp.card_category_id"=>$id],["bp.polymorphic_type"=>"class"]])
            ->joinWith(["course course"],false)
             ->select("bp.*,course.name")
             ->asArray()->all();
        $data["serverClass"]  = [];
        foreach ($groupClassData as $key => $value){
            if(empty($value['name'])){
                $courseIds = json_decode($value['polymorphic_ids'],true);
                $value['course'] = Course::find()->where(['id'=>$courseIds])->select('id,name')->asArray()->all();
                array_push($data["serverClass"],$value);
            }else{
                array_push($data["serverClass"],$value);
            }
        }
        //整理服务套餐信息
        $serverData = BindPack::find()->where(["and",["card_category_id"=>$id],["polymorphic_type"=>"server"]])
            ->joinWith(["server server"])
            ->select("cloud_bind_pack.*,server.name as serverName")
            ->asArray()->all();
        //整理私课服务套餐信息
        $serverChargeData = BindPack::find()->where(["card_category_id"=>$id])
            ->andWhere(['or',["polymorphic_type"=>"pt"],["polymorphic_type"=>"hs"],["polymorphic_type"=>"birth"]])
            ->joinWith(["chargeClass chargeClass"])
            ->select("cloud_bind_pack.*,chargeClass.name as chargeClassName")
            ->asArray()->all();
        //绑定赠品信息
        $giftData = \backend\models\BindPack::find()
            ->alias('bp')
            ->joinWith(["goodsAll goods"],false)
            ->where(["and",["bp.card_category_id"=>$id],["bp.polymorphic_type"=>"gift"]])
//            ->andWhere(['goods.goods_attribute' => 2])
            ->select("goods.id as goodsId,goods.goods_name,bp.number")
            ->asArray()->all();
        $data["sellVenue"]    = $sellVenueData;
        $data["server"]       = $serverData;
        $data["serverCharge"] = $serverChargeData;
        $data["sellTime"]     = $sellTime;
        $data['gift']         = $giftData;
        return $data;
    }

    /**
     * @云运动 - 会员管理 - 会员卡升级 查询同类型卡种
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/5/25
     * @inheritdoc
     */
    public function getCardCate($memberCardId,$id,$type)
    {
        $memberCard   = MemberCard::find()->where(['id' => $memberCardId])->asArray()->one();
        $cardCategory = CardCategory::find()->where(['id' => $memberCard['card_category_id']])->asArray()->one();
        if ($cardCategory['category_type_id'] == 2) {
            $card = CardCategory::find()
                ->alias('cc')
                ->where(['cc.category_type_id' => $cardCategory['category_type_id']])
                ->andWhere(['>', 'lc.sell_end_time', time()])
                ->andFilterWhere(['>', 'cc.times', $cardCategory['times']]);
        } elseif ($cardCategory['category_type_id'] == 3) {
            $card = CardCategory::find()
                ->alias('cc')
                ->where(['cc.category_type_id' => $cardCategory['category_type_id']])
                ->andWhere(['>', 'lc.sell_end_time', time()])
                ->andFilterWhere(['>', 'cc.recharge_price', $cardCategory['recharge_price']]);
        } else {
            if ($cardCategory['sell_price'] != null){
                $card = CardCategory::find()
                    ->alias('cc')
                    ->where(['cc.category_type_id' => $cardCategory['category_type_id']])
                    ->andWhere(['>', 'lc.sell_end_time', time()])
                    ->andFilterWhere(['or',['>', 'cc.sell_price', $cardCategory['sell_price']],['>', 'cc.min_price', $cardCategory['sell_price']]]);
            } else {
                $card = CardCategory::find()
                    ->alias('cc')
                    ->where(['cc.category_type_id' => $cardCategory['category_type_id']])
                    ->andWhere(['>', 'lc.sell_end_time', time()])
                    ->andFilterWhere(['or',['>', 'cc.min_price', $cardCategory['min_price']],['>','cc.sell_price',$cardCategory['min_price']]]);
            }
        }
        if(isset($type) && $type == 'company'){
            $card = $card->andFilterWhere(['cc.company_id'=>$id]);
        }
        if(isset($type) && $type == 'venue'){
            $card = $card->andFilterWhere(['cc.venue_id'=>$id]);
            $card = $card->andFilterWhere(['lc.venue_id'=>$id]);
        }
        $card = $card
            ->joinWith(['cardCategoryType ct'])
            ->joinWith(['limitCardNumber lc'])
            ->select(
                'cc.id,
                cc.category_type_id,
                cc.card_name,
                cc.original_price,
                cc.sell_price,
                cc.max_price,
                cc.min_price,
                cc.duration,
                cc.times,
                cc.active_time,
                ct.type_name')
            ->asArray()
            ->all();
        return $card;
    }

    /**
     * 云运动 - 请假管理 - 搜索会员卡
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/8/25
     * @param $id
     * @param $type
     * @return boolean
     */
    public function getLeaveCardData($id,$type)
    {
        $query = CardCategory::find()->alias('cc')->select("cc.id,cc.card_name")->asArray();
        $query = $this->getSearchWhereData($query,$id,$type);
        $query = $query->all();
        return $query;
    }

    /**
     * 后台 - 请假管理 - 执行搜索数据过滤
     * @create 2017/8/16
     * @author huanghua<huanghua@itsports.club>
     * @param  $query  //后台的sql语句
     * @param $id
     * @param $type
     * @return  mixed
     */
    public function getSearchWhereData($query,$id,$type)
    {
        if($type && $type == 'company'){
            $query->andFilterWhere(['cc.company_id'=>$id]);
        }
        if($type && ($type == 'venue' || $type == 'department')){
            $query->andFilterWhere(['cc.venue_id'=>$id]);
        }
        return $query;
    }

    /**
     * 云运动 - 会员管理 - 续费获取所有卡种
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/8/25
     * @param $id
     * @param $type
     * @return boolean
     */
    public function getCardData($id,$type)
    {
        $query = CardCategory::find()->alias('cc')->select("cc.id,cc.card_name,cc.sell_price,cc.renew_price,cc.duration")->asArray();
        $query = $this->getSearchWheres($query,$id,$type);
        $query = $query->all();
        return $query;
    }
    
    /**
     * 后台 - 会员管理 - 执行搜索数据过滤
     * @create 2017/8/16
     * @author huanghua<huanghua@itsports.club>
     * @param  $query  //后台的sql语句
     * @param $id
     * @param $type
     * @return  mixed
     */
    public function getSearchWheres($query,$id,$type)
    {
        if($type && $type == 'company'){
            $query->andFilterWhere(['cc.company_id'=>$id]);
        }
        if($type && ($type == 'venue' || $type == 'department')){
            $query->andFilterWhere(['cc.venue_id'=>$id]);
        }
        return $query;
    }
    /**
     * 后台 - 会员管理 - 会员卡续费增加此卡有效期有效时间获取
     * @create 2017/9/23
     * @author huanghua<huanghua@itsports.club>
     * @param $memberCardId
     * @return  mixed
     */
    public function getValidTime($memberCardId)
    {
        $query = MemberCard::find()
            ->select('id as memberCardId,ordinary_renewal,validity_renewal,duration,card_category_id')
            ->where(['id'=>$memberCardId])
            ->asArray()
            ->one();
        if($query['ordinary_renewal'] != NULL && $query['validity_renewal'] != NULL && $query['validity_renewal'] == ''){
            return $query;
        }else{
            return \common\models\base\CardCategory::find()
                ->select('id as categoryId,ordinary_renewal,validity_renewal,duration')
                ->where(['id'=>$query['card_category_id']])
                ->asArray()
                ->one();
        }
    }
    /**
     * 后台 - 卡种管理 - 根据权限获取不同场馆
     * @create 2017/9/23
     * @author lihuien<lihuien@itsports.club>
     * @return  mixed
     */
    public function getVenueIdByRole()
    {
        $roleId             =   \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId            =    Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds       =    array_column($vId, 'id');
        }else{
            //拿到用户有权限查看的场馆
            $venuesId       =    Auth::findOne(['role_id' => $roleId]);
            $authId         =    (!empty($venuesId) && isset($venuesId->venue_id)) ? json_decode($venuesId->venue_id,true) : null;
            $venues         =    Organization::find()->where(['id'=>$authId])->select(['id','name'])->asArray()->all();
            $venueIds       =    array_column($venues, 'id');
        }
        return $venueIds;
    }
    /**
     * 后台 - 卡种管理 - 根据权限获取不同场馆
     * @create 2017/9/23
     * @author lihuien<lihuien@itsports.club>
     * @return  mixed
     */
    public function getVenueDataById()
    {
        $venueId = $this->getVenueIdByRole();
        return Organization::find()->where(['style'=>2,'id'=>$venueId])->asArray()->all();
    }
    /**
     * 后台 - 卡种管理 - 卡种id获取卡种数据
     * @create 2017/1/2
     * @param $cardCategoryId
     * @author huanghua<huanghua@itsports.club>
     * @return  mixed
     */
    public function getCardCategoryMoney($cardCategoryId)
    {
        return CardCategory::find()->where(['id'=>$cardCategoryId])->asArray()->one();
    }


    /**
     * 公共管理 - 属性匹配 - 老会员卡信息查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/2/1
     * @param $params //搜索参数
     * @return \yii\db\ActiveQuery
     */
    public function memberCardCategoryArray($params)
    {
        $this->customLoads($params);
        $query  =  CardCategory::find()
            ->alias('card')
            ->joinWith(['cardCategoryType cardCategoryType'])
            ->joinWith(['classServer classServer'])
            ->joinWith(['serverCombo serverCombo'])
            ->joinWith(['limitCardNumber limitCardNumber'])
            ->joinWith(['deal deal'])
            ->groupBy('limitCardNumber.card_category_id')
            ->orderBy("card.id DESC")
            ->asArray();
        $query                 = $this->getSearchWhereCard($query);
        $dataProvider          =  Func::getDataProvider($query,8);
        return  $dataProvider;
    }

    /**
     * 会员管理 - 会员卡详情 - 会员卡匹配卡种获取所有卡种
     * @author Huang hua <huanghua@itsports.club>
     * @create 2018/3/14
     * @param $id //所属卡种id
     * @return \yii\db\ActiveQuery
     */
    public function cardCategoryAll($id)
    {
        $this->venueId   = $this->getVenueIdByRole();
        $query  =  CardCategory::find()
            ->alias('card')
            ->joinWith(['organization organization'])
            ->where(['or',['card.venue_id'=>$this->venueId],['card.id'=>$id]])
            ->select("
            card.id,
            card.venue_id,
            card.card_name,
            organization.name,
            ")
            ->groupBy('card.id')
            ->orderBy("card.id DESC")
            ->asArray()
            ->all();
        return  $query;
    }

    /**
     * 公共管理 - 属性匹配 - 搜索数据处理数据
     * @create 2017/2/1
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return bool
     */
    public function customLoads($data)
    {
        $venueIds           = $this->getVenueIdByRole();
        $this->venueId      = (isset($data[self::VENUE_ID]) && !empty($data[self::VENUE_ID])) ? $data[self::VENUE_ID] : $venueIds;
        $this->keywords     = (isset($data[self::KEY]) && !empty($data[self::KEY])) ? $data[self::KEY] : null;
        $this->type         = (isset($data[self::TYPES]) && !empty($data[self::TYPES])) ? $data[self::TYPES] : null;
        $this->status       = (isset($data[self::STATUS]) && !empty($data[self::STATUS])) ? (int)$data[self::STATUS] : null;
        $this->attribute    = (isset($data[self::ATTRIBUTE]) && !empty($data[self::ATTRIBUTE])) ? (int)$data[self::ATTRIBUTE] : null;
        $this->isCheck      = (isset($data[self::IS_CHECK]) && !empty($data[self::IS_CHECK])) ? $data[self::IS_CHECK] : null;
        return true;
    }

    /**
     * 公共管理 - 属性匹配 - 增加搜索条件
     * @create 2017/2/1
     * @author huanghua<huanghua@itsports.club>
     * @param $query
     * @return mixed
     */
    public function getSearchWhereCard($query)
    {
        if(!$this->venueId){
            die;
        }
        if(!$this->isCheck){
            $query->andFilterWhere([
                'and',
                ['<=','limitCardNumber.sell_start_time',time()],
                ['>=','limitCardNumber.sell_end_time',time()]
            ]);
            $query->andFilterWhere(['card.status'=>[1,4]]);
        }else{
            $query->andFilterWhere(['card.status'=>[1,2,3,4,5,6]]);
        }
        $query->andFilterWhere(['like','card.card_name',$this->keywords]);
        $query->andFilterWhere(['card.venue_id'=>$this->venueId]);
        $query->andFilterWhere(['card.category_type_id'=>$this->type]);
        $query->andFilterWhere(['card.card_type'=>$this->attribute]);
        $query->andFilterWhere(['card.status'=>$this->status]);
        return $query;
    }
}
