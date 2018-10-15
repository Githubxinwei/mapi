<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/3/30
 * Time: 9:59
 */
namespace backend\modules\v1\models;
use backend\models\MemberCard;
use backend\models\Member;

class MemberCourseOrder extends \common\models\base\MemberCourseOrder
{
    /**
     * 移动端API - 扫码进馆和跨店约课 - 自动筛选出优先卡片
     * @author 辛威 <xinwei@itsport.club>
     * @createAt 2018/3/30
     * @param $mobile
     * @param $venueId
     * @param $vid
     * @return mixed|null
     */
    public function getEntryVenueMemberCard($mobile,$companyId,$venueId,$vid,$type)
    {
        //我的所有-有效正常会员卡
        $cards = $this->getMyAllMemberCard($mobile,$companyId,$type);
        //如果没有返回NULL
        if(!$cards || empty($cards)) return NULL;
        //其他...
        $models = $this->getMySortMaxCard($cards, $venueId);
        if($models){
            $reCard = [];
            $reCard['id']         = $models['id'];
            $reCard['member_id']  = $models['member_id'];
            $reCard['cardNumber'] = $models['cardNumber'];
            $reCard['cardStatus'] = $models['status'] == 1 ? '已激活': '未激活';
            $reCard['cardType']   = $models['cardType'];
            if($vid == 'code'){
                return $reCard;
            }else{
                return [$reCard];
            }
        }elseif (empty($type) && ($vid == 'about')){
            if ($cards['is_leave'] == 1) {
                return $cards;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }
    /**
     * 移动端API-约课-扫码进馆-获取我的所有-有效正常会员卡
     * @author辛威<xinwei@itsport.club>
     * @createAt 2018/3/30
     * @param $mobile    //会员手机号
     * @return mixed|null|\yii\db\ActiveRecord
     */
    public function getMyAllMemberCard($mobile,$companyId,$type)
    {
        $leaveCards = $this->gainMyInvalidCards($mobile,$companyId);
        $cards = Member::find()->alias('mb')
            ->joinWith(['memberCard mc' => function($q) use ($leaveCards,$type){
                $q->joinWith(['cardCategory ccg' => function($q){
                    $q->joinWith(['cardCategoryType cct']);
                }]);
                $q->where([
                    'and',
                    ['mc.status'=>[1,4]],
                    [
                        'or',
                        ['mc.usage_mode'=> null],
                        ['mc.usage_mode'=> 1]]
                ]);
                if ($type){

                }else{
                    if(is_array($leaveCards) && !empty($leaveCards)){
                        $q->andWhere(['not in', 'mc.id', $leaveCards]);
                    };
                }
            }], false)
            ->where(['mb.mobile' => $mobile,'mb.company_id' => $companyId])
            ->select('
                           mc.id,
                           mc.card_number as cardNumber,
                           mc.active_time,
                           mc.invalid_time,
                           mc.total_times,
                           mc.consumption_times,
                           mc.balance,
                           mc.status,
                           mc.venue_id,
                           mc.member_id,
                           cct.id cardType
                           ')
            ->groupBy('mc.id')
            ->orderBy(['cct.id' => SORT_ASC])
            ->asArray()
            ->all();
        //is_leave = 1;为请假中，0为未请假
        if ($leaveCards) {
            $cards['is_leave'] = 1;
        } else {
            $cards['is_leave'] = 0;
        }
        return $cards;
    }
    /**
     * 移动端API-会员卡-根据优先级返回优先卡片
     * @author辛威<xinwei@itsport.club>
     * @createAt 2018/3/7
     * @param $cards
     * @param $venueId
     * @return mixed|null
     */
    public function getMySortMaxCard($cards, $venueId)
    {
        //本场馆卡片
        $curVenueCards  = [];
        //其他场馆卡片
        $restVenueCards = [];
        //获取本场馆卡和其他场馆卡
        foreach ($cards as $k => $v){
            if($v['venue_id'] == $venueId){
                array_push($curVenueCards, $v);
            }else{
                array_push($restVenueCards, $v);
            }
        }
        //获取当前场馆有效卡片
        if(!empty($curVenueCards)){
            $tmpCardsStorage   = $this->gainDiffTypeCards($curVenueCards);
            $activeTimeCards   = $tmpCardsStorage[0];
            $noActiveTimeCards = $tmpCardsStorage[1];
            $activeRideCards   = $tmpCardsStorage[2];
            $activeMoneyCards  = $tmpCardsStorage[3];
            $activeMixCards    = $tmpCardsStorage[4];
        }else{
            $activeTimeCards   = [];
            $noActiveTimeCards = [];
            $activeRideCards   = [];
            $activeMoneyCards  = [];
            $activeMixCards    = [];
        }
        //获取其他场馆有效卡片
        if(!empty($restVenueCards)){
            $restData          = $this->getActiveCards($restVenueCards,$venueId);
            $otherCardsStorage = $this->gainDiffTypeCards($restData);
            $timeCard    = $otherCardsStorage[0];
            $notTimeCard = $otherCardsStorage[1];
            $rideCard    = $otherCardsStorage[2];
            $moneyCard   = $otherCardsStorage[3];
            $mixCard     = $otherCardsStorage[4];
        }else{
            $timeCard    = [];
            $notTimeCard = [];
            $rideCard    = [];
            $moneyCard   = [];
            $mixCard     = [];
        }
        $fun = function ($p, $k, $where){
            for($n=1; $n<count($p); $n++){
                for($i=0; $i<count($p)-$n; $i++){
                    if($p[$i][$k] > $p[$i+1][$k]){
                        $tmp     = $p[$i];
                        $p[$i]   = $p[$i+1];
                        $p[$i+1] = $tmp;
                    }
                }
            }
            if($where == 'one'){
                return current($p);
            }else{
                return end($p);
            }
        };
        //时间卡
        if(count($activeTimeCards) == 1){
            return $activeTimeCards[0];
        }elseif(count($activeTimeCards) > 1){
            $data = $this->getActiveCards($activeTimeCards, $venueId);
            $numOne   = [];
            $numTwo   = [];
            foreach ($data as $k => $v) {
                if ($v['level'] == 2) {
                    array_push($numOne, $v);
                } else {
                    array_push($numTwo, $v);
                }
            }
            if(count($numOne)==1){
                return $numOne[0];
            }elseif(count($numOne) > 1){
                return call_user_func($fun, $numOne, 'active_time', 'one');
            }elseif(count($numTwo) > 1){
                return call_user_func($fun, $numTwo, 'active_time', 'one');
            }else{
                return $numTwo[0];
            }
            //次卡
        }elseif(count($activeRideCards) == 1){
            return $activeRideCards[0];
        }elseif(count($activeRideCards) > 1){
            return call_user_func($fun, $activeRideCards, 'consumption_times', 'end');
            //储值卡
        }elseif(!empty($activeMoneyCards)){
            return $activeMoneyCards[0];
            //混合卡
        }elseif(!empty($activeMixCards)){
            return $activeMixCards[0];
        }else{
            if(!empty($noActiveTimeCards)){
                //未激活时间卡
                $passCards = $this->getActiveCards($noActiveTimeCards, $venueId);
                $numOne    = [];
                $numTwo    = [];
                foreach ($passCards as $k => $v) {
                    if ($v['level'] == 2) {
                        array_push($numOne, $v);
                    } else {
                        array_push($numTwo, $v);
                    }
                }
                if(!empty($numOne)){
                    return $numOne[0];
                }else{
                    return $numTwo[0];
                }
            }else{
                if(count($timeCard) == 1){
                    return $timeCard[0];
                }elseif(count($timeCard) > 1){
                    return call_user_func($fun, $timeCard, 'active_time', 'one');
                }elseif(count($rideCard) == 1){
                    return $rideCard[0];
                }elseif(count($rideCard) > 1){
                    return call_user_func($fun, $rideCard, 'consumption_times', 'end');
                }elseif(!empty($moneyCard)){
                    return $moneyCard[0];
                }elseif(!empty($mixCard)){
                    return $mixCard[0];
                }elseif(!empty($notTimeCard)){
                    return $notTimeCard[0];
                }else{
                    return NULL;
                }
            }
        }
    }
    /**
     * 移动端API-我的会员卡-获取我的请假卡
     * @author辛威<xinwei@itsport.club>
     * @createAt 2018/3/30
     * @param $mobile
     * @return array|\yii\db\ActiveRecord[]
     */
    public function gainMyInvalidCards($mobile,$companyId)
    {
        $cards = Member::find()->alias('mb')
            ->joinWith(['memberCard mc' => function($q){
                $q->joinWith(['leaveRecord leaveRecord' => function($q){
                    $q->where([
                        "and",
                        ["leaveRecord.status"=>1]
                    ]);
                }]);
            }], false)
            ->where(['mb.mobile' => $mobile,'mb.company_id' => $companyId])
            ->select('mc.id,mc.member_id')
            ->groupBy('mc.id')
            ->asArray()
            ->all();
        if(!$cards){
            return NULL;
        }
        return array_column($cards, 'id');
    }
    /**
     * 移动端API-过滤出不同类别有效卡片
     * @author辛威<xinwei@itsport.club>
     * @createAt 2018/3/30
     * @param $cards
     * @return array
     */
    protected function gainDiffTypeCards($cards)
    {
        $noActiveTimeCards  = [];               //时间卡未激活
        $activeTimeCards    = [];               //时间卡已激活
        $activeRideCards    = [];               //有效次卡
        $activeMoneyCards   = [];               //有效储值卡
        $activeMixCards     = [];               //有效混合卡
        /*过滤出有效卡片*/
        foreach ($cards as $k => $v){
            switch ($v['cardType'])
            {
                case 1:
                    //时间卡
                    if($v['invalid_time'] > time() && $v['active_time'] != NULL && $v['active_time'] != 0){
                        //过滤出有效已激活卡片
                        array_push($activeTimeCards, $v);
                    }else{
                        //过滤出有效未激活卡片
                        if($v['invalid_time'] > time()){
                            array_push($noActiveTimeCards, $v);
                        }
                    }
                    break;
                case 2:
                    //次卡
                    if($v['consumption_times'] < $v['total_times']){
                        //过滤出有效次卡
                        array_push($activeRideCards, $v);
                    }
                    break;
                case 3:
                    //充值卡
                    if($v['balance'] > 0){
                        //过滤出有效储值卡
                        array_push($activeMoneyCards, $v);
                    }
                    break;
                default:
                    //混合卡
                    array_push($activeMixCards, $v);
            }
        }
        return [$activeTimeCards, $noActiveTimeCards, $activeRideCards, $activeMoneyCards, $activeMixCards];
    }
    /**
     * 移动端API-筛选-获取不同场馆会员卡等级 和 通店卡片
     * @author辛威<xinwei@itsport.club>
     * @createAt 2018/3/30
     * @param $cards
     * @param $venueId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getActiveCards($cards, $venueId)
    {
        $memberCardId  = array_column($cards, 'id');
        $models        = MemberCard::find()->alias('mc')
            ->joinWith(['cardCategory ccg' => function($q){
                $q->joinWith(['cardCategoryType cct']);
            }], false)
            ->joinWith(['venueLimitTimesArr vlt'], false)
            ->where(['mc.id' => $memberCardId])
            ->andWhere([
                'or',
                ['vlt.venue_id' => $venueId],
                ['like', 'vlt.venue_ids', '"' . $venueId . '"']
            ])
            ->select('
                                   mc.id,
                                   mc.card_number as cardNumber,
                                   mc.active_time,
                                   mc.invalid_time,
                                   mc.total_times,
                                   mc.consumption_times,
                                   mc.balance,
                                   mc.venue_id,
                                   mc.status,
                                   mc.member_id,
                                   cct.id cardType,
                                   vlt.level
                                           ')
            ->asArray()
            ->groupBy('mc.id')
            ->all();
        return $models;
    }

    public function getMemberCourseOrderDetails()
    {
        return $this->hasMany(\common\models\MemberCourseOrderDetails::className(),['course_order_id'=>'id']);
    }
    /**
     * 移动端API-获取会员id
     * @author辛威<xinwei@itsport.club>
     * @createAt 2018/07/04
     */
    public function getMemberId($mobile,$companyId,$venueId)
    {
        $arr = Member::find()
            ->where(['and',['mobile' => $mobile],['company_id' => $companyId]])
            ->andWhere(['venue_id' => $venueId])
            ->select('id')
            ->asArray()
            ->one();
        if ($arr) {
            $memberId = $arr['id'];
            return $memberId;
        }
    }
}