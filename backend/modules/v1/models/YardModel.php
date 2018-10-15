<?php
namespace backend\modules\v1\models;

use yii\base\Model;
use common\models\VenueYard;
use backend\models\AboutYard;
use common\models\base\VenueYardCardcategory;
use common\models\Member;
use common\models\MemberCard;
use yii\rest\Serializer;

class YardModel extends Model
{
    /**
     * 会员端-指定场馆下所有场地
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/14
     * @param $venueId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getYardLister($venueId)
    {
      $r = VenueYard::find()
            ->where(['venue_id' => $venueId])
            ->asArray()
            ->select('id,yard_name')
            ->all();
        return $r;
    }

    /**
     * 会员端-API-指定场地详细
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/14
     * @param $yardId
     * @param $aboutTimes
     * @return array
     */
    public function getYardSlotLister($yardId, $aboutTimes)
    {
        $p = [];
        $p['aboutTimes'] = $aboutTimes;
        $p['yardId'] = $yardId;
        return $this->dealWithTimeColumns($p);
    }
    /**
     * 会员端-预约场地时间段详情
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/15
     * @param $p
     * @return array
     */
    public function dealWithTimeColumns($p)
    {
        $aboutTimes = isset($p['aboutTimes']) ? $p['aboutTimes'] : null;
        $yardId = isset($p['yardId']) ? $p['yardId'] : null;
        $venueYardModel = VenueYard::findOne($yardId);
        if($venueYardModel === null){
            return [];
        }
        $timeColumns = $venueYardModel->business_time;
        $timeLongs_m = $venueYardModel->active_duration;
        $peopleLimit = $venueYardModel->people_limit;
        if($aboutTimes === null || $yardId === null){
            return [];
        }
        $start = strtotime($aboutTimes . ' ' . (explode('-', $timeColumns)[0]));
        $end   = strtotime($aboutTimes . ' ' . (explode('-', $timeColumns)[1]));
        $timeLongs_s = (int)$timeLongs_m * 60;
        $ceilCount = (int)ceil(($end-$start)/$timeLongs_s);
        $columns = [];
        $n = 0;
        do{
            $n++;
            if(!$ceilCount || !$timeLongs_s){
                \Yii::trace((new \Exception('start: ' . $start . 'end: ' . $end))->getMessage());
                return false;
            }
            $start = ($n === 1) ? $start : $start+$timeLongs_s;
            if (((int)$start + $timeLongs_s) > $end) {
                break;
            }
            $next = $start+$timeLongs_s;
            if($next>=$end){
                $next = $end;
            }
            $status = 1;
            if($start<time()){
                $status = 0;
            }
            $timeSlot = date('H:i', $start) . '-' . date('H:i', $next);
            $people = $this->getEveryTimeAboutPeoplesCount($yardId, $aboutTimes, $timeSlot);
            $column = ['timeColumns' => $timeSlot, 'status' => $status, 'peoplesLimit' => $peopleLimit, 'aboutPeoples' => $people];
            $eleCount = array_push($columns, $column);
            if($eleCount == $ceilCount){
                break;
            }
        } while(true);
        return $columns;
    }
    /**
     * 会员端-每个时间段预约人的数量
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/14
     * @param $yardId
     * @param $aboutTimes
     * @param $timeSlot
     * @return int
     */
    public function getEveryTimeAboutPeoplesCount($yardId, $aboutTimes, $timeSlot)
    {
        //格式化预约时间
        $time = strtotime($aboutTimes);
        $aboutTime = date("Y-m-d",$time);
        $count = AboutYard::find()
                    ->where([
                        'aboutDate' => $aboutTime,
                        'about_interval_section' => $timeSlot,
                        'status' => [1,2,3,4],
                        'yard_id' => $yardId,
                    ])->count();
        return (int)$count;
    }

    /**
     * 会员端-预约场地-获取会员卡
     * @author 会员端<xinwei@itsport.club>
     * @createAt 2018/5/14
     * @param $accountId
     * @param $venueId
     * @param $yardId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMemberVenueCards($accountId, $venueId, $yardId)
    {
        /*解决脏数据问题，一个场馆存在同一个会员两个账号*/
        //1，查看场馆下会员账号的数量
        $memberCount = Member::find()
            ->where(['member_account_id' => $accountId, 'venue_id' => $venueId])
            ->select('id,status,member_type')
            ->asArray()
            ->count();
        if($memberCount){
            //2. 获取会员请假卡
            $begOffCards = Fun::begOffCards(Fun::receiveAccount($accountId));
            //3. 如果账号数量为 1。
            if($memberCount == 1){
                $memberInfo = Member::find()
                    ->where([
                        'member_account_id' => $accountId,
                        'venue_id' => $venueId
                    ])->select('id,status,member_type')
                    ->asArray()
                    ->one();
            }else{
                //4. 如果账号数量为多个
                //5. 获取会员账号下所有正常、未过期、自用、未请假会员卡
                $data = Member::find()
                    ->alias('mm')
                    ->joinWith(['memberCard mc' => function($q)use($begOffCards){
                        $q->joinWith(['cardCategory cc']);
                        if($begOffCards){
                            $q->where(['not in', 'mc.id', $begOffCards]);
                        }
                    }], false)
                    ->andWhere([
                        'and',
                        [
                            'mm.member_account_id' => $accountId,
                            'mm.venue_id' => $venueId,
                            'mm.member_type' => 1,
                            'mc.status' => [1, 4]
                        ],
                        ['not', ['mc.id' => null]],
                        ['>', 'mc.invalid_time', time()],
                        ['or', ['mc.usage_mode'=> null], ['mc.usage_mode'=> 1]]
                    ])
                    ->select('mc.id cardId, mc.status, cc.card_name cardName, cc.id cardCategoryId')
                    ->groupBy('mc.id')
                    ->asArray()
                    ->all();
                if(!$data){
                    //6. 没有卡或卡过期或卡异常或卡冻结或卡不是自用
                    return ['status' => 'error', 'code' => 0, 'message' => '对不起，您没有用来预约的会员卡!' , 'data' => []];
                }
                $yardCardCategory = VenueYardCardcategory::find()->where(['yard_id' => $yardId])->asArray()->all();
                if(!$yardCardCategory){
                    //7. 没有场地预约卡的限制，直接返回所有卡
                    return ['status' => 'success', 'code' => 1, 'message' => '请求成功!' , 'data' => $data];
                }else{
                    //8. 有场地预约卡的限制，取交集
                    $allowCardCategory = array_intersect(array_column($data, 'cardCategoryId'), array_column($yardCardCategory, 'card_category_id'));
                    if(!empty($allowCardCategory)){
                        //9. 数据处理逻辑
                        $cards = array_column($data, 'cardId', 'cardCategoryId');
                        $result = [];
                        array_walk_recursive($allowCardCategory, function($v) use(&$result, $cards){
                            if(array_key_exists($v, $cards)){
                                array_push($result, $cards[$v]);
                            }
                        });
                        $reCards = [];
                        foreach ($data as $k => $v){
                            if(in_array($v['cardId'], $result)){
                                array_push($reCards, $v);
                            }
                        }
                        //10. 响应成功
                        return ['status' => 'success', 'code' => 1, 'message' => '请求成功!' , 'data' => $reCards];
                    }
                    //11. 卡不符合项第预约配置
                    return ['status' => 'error', 'code' => 0, 'message' => '您的卡不能预约场地!' , 'data' => []];
                }
            }
            //12. 账号数量为 1。
            if($memberInfo['status'] == 2){
                //13. 状态是 2 为异常会员账号
                return ['status' => 'error', 'code' => 0, 'message' => '您的会员帐号异常!' , 'data' => []];
            }elseif($memberInfo['member_type'] == 2){
                //14. 类型为 2 为潜在会员
                return ['status' => 'error', 'code' => 0, 'message' => '您在本场馆是潜在会员!' , 'data' => []];
            }else{
                //15. 正常会员
                $memberId = $memberInfo['id'];
                //16. 获取会员正常、未过期、自用、未请假会员卡
                $data = MemberCard::find()
                    ->alias('mc')
                    ->joinWith(['cardCategory cc' => function($q)use($begOffCards){
                        if($begOffCards){
                            $q->where(['not in', 'mc.id', $begOffCards]);
                        }
                    }],false)
                    ->andWhere([
                        'and',
                        ['mc.member_id' => $memberId, 'mc.status' => [1, 4]],
                        ['>', 'mc.invalid_time', time()],
                        ['or', ['mc.usage_mode'=> null], ['mc.usage_mode'=> 1]]
                    ])
                    ->select('mc.id cardId, mc.card_name cardName, mc.status, cc.id cardCategoryId')
                    ->groupBy('mc.id')
                    ->asArray()
                    ->all();
                //17. 获取该场馆场地预约限制
                if($data){
                    $yardCardCategory = VenueYardCardcategory::find()->where(['yard_id' => $yardId])->asArray()->all();
                    if(!$yardCardCategory){
                        return ['status' => 'success', 'code' => 1, 'message' => '请求成功!' , 'data' => $data];
                    }else{
                        //18. 取交集
                        $allowCardCategory = array_intersect(array_column($data, 'cardCategoryId'), array_column($yardCardCategory, 'card_category_id'));
                        if(!empty($allowCardCategory)){
                            //19. 获取会员卡
                            //20. 数据处理逻辑
                            $cards = array_column($data, 'cardId', 'cardCategoryId');
                            $result = [];
                            array_walk_recursive($allowCardCategory, function($v) use(&$result, $cards){
                                if(array_key_exists($v, $cards)){
                                    array_push($result, $cards[$v]);
                                }
                            });
                            $reCards = [];
                            foreach ($data as $k => $v){
                                if(in_array($v['cardId'], $result)){
                                    array_push($reCards, $v);
                                }
                            }
                            //21. 响应成功
                            return ['status' => 'success', 'code' => 1, 'message' => '请求成功!' , 'data' => $reCards];
                        }
                        //22. 您的卡不符合场地配置
                        return ['status' => 'error', 'code' => 0, 'message' => '您的卡不能预约场地!' , 'data' => []];
                    }
                }else{
                    //23. 没有有效卡片
                    return ['status' => 'error', 'code' => 0, 'message' => '对不起，您没有用来预约的会员卡！' , 'data' => []];
                }
            }
        }
        //24. 还不是会员
        return ['status' => 'error', 'code' => 0, 'message' => '您还不是会员!' , 'data' => []];
    }

    /**
     * 会员端-判断会员在该时间段有没有预约  有: true  无: false
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/14
     * @param $accountId
     * @param $aboutTime
     * @param $timeSection
     * @return bool
     */
    public static function getMemberAboutOtherStatus($accountId, $aboutTime, $timeSection)
    {
        $ids = Fun::receiveAccount($accountId);
        if(!$ids){
            return false;
        }
        //格式化预约时间
        $time = strtotime($aboutTime);
        $aboutTimes = date("Y-m-d",$time);
        $result = AboutYard::find()
            ->where([
                'member_id' => $ids,
                'aboutDate' => $aboutTimes,
                'about_interval_section' => $timeSection
            ])
            ->andWhere(['<>', 'status', 5])
            ->asArray()
            ->all();
        if($result){
            return true;
        }
        return false;
    }

    /**
     * 会员端-判断潜在会员是否在本场馆预约场地
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/14
     * @param $ids
     * @param $venueId
     * @return bool
     */
    //此方法保留, 微程潜在会员无法预约团课,场地;
    public static function isAbout($ids, $venueId)
    {
        $num = \backend\models\AboutYard::find()
            ->alias("aboutYard")
            ->joinWith(["venueYard venueYard"=>function($query){
                $query->joinWith(["organization or"]);
            }],false)
            ->select("
                   aboutYard.id,
                   aboutYard.yard_id,
                   aboutYard.member_id,
                   aboutYard.status,
                   venueYard.venue_id,
                   or.pid,
                   ")
            ->where(["and",["aboutYard.member_id"=>$ids],["aboutYard.status"=>1]])
            ->andWhere(["or.pid"=>1])
            ->andWhere(['venueYard.venue_id'=>$venueId])
            ->count();
        if($num>0){
            return true;
        }
        return false;
    }

    /**
     * 会员端-场地预约-取消预约
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/14
     * @param $aboutYardId
     * @return int
     */
    public static function cancelYardAbout($aboutYardId)
    {
        $data = AboutYard::find()->where(['id' => $aboutYardId])->select('about_start')->asArray()->one();
        if ($data['about_start'] < time()) {
            return ['status' => 'error', 'code' => 0, 'message' => '您预约的场地时间已开始，不可以取消！' , 'data' => []];
        }
        $affectedRows = AboutYard::updateAll(
           ['status' => 5],
           'id=:aboutYardId',
           [':aboutYardId' => $aboutYardId]
        );
        if ($affectedRows) {
            return ['status' => 'success', 'code' => 1, 'message' => '取消预约成功！' , 'data' => []];
        }
        return ['status' => 'error', 'code' => 0, 'message' => '网络错误，取消预约失败！' , 'data' => []];
    }

    /**
     * 会员端-获取我的预约场地列表
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/14
     * @param $accountId
     * @return mixed
     */
    public function gainMyYardAboutLister($accountId)
    {
        //1. yii2 notice抑制
        error_reporting(~E_NOTICE);
        //2. 获取帐号ID
        $ids = Fun::receiveAccount($accountId);
        //3. 获取aboutYard对象
        $query = AboutYard::find()->alias('ay')
            ->joinWith(['memberCard mc' => function($q){
                $q->joinWith(['organization or']);
                $q->joinWith(['cardCategory cc']);
            }], false)
            ->joinWith(['venueYard vy'],false)
            ->select('
                    ay.id aboutYardId,
                    ay.about_interval_section aboutTimeColumn,
                    ay.aboutDate,
                    ay.create_at createTime,
                    or.name venueName,
                    or.id venueId,
                    cc.card_name cardName,
                    mc.card_number cardNumber,
                    ay.status aboutStatus,
                    vy.yard_name yardName,
                    ay.about_start aboutStart,
                    ay.about_end aboutEnd,
                    ay.is_print_receipt,
                    ')
            ->where(['ay.member_id' => $ids])
            ->orderBy('ay.create_at DESC')
            ->asArray();
        //4. 分页model
        $dataProvider = new \yii\data\ActiveDataProvider(['query' => $query]);
        //5. 处理分页数据
        $serialize = new Serializer();
        $serialize->collectionEnvelope = 'items';
        $serialize->linksEnvelope = 'link';
        $serialize->metaEnvelope = 'meta';
        $data = $serialize->serialize($dataProvider);
        //6. 状态逻辑
        foreach ($data['items'] as $k => $v){
            $isEntryRecords = Fun::isEntryRecords($ids, $v['aboutStart'], $v['venueId']);
            $data['items'][$k]['createTime'] = date('Y-m-d', $v['createTime']);
            //7. 如果当前时间大于开始时间小于结束时间,并且状态不是已取消, 则已开始
            if(intval($v['aboutStart']) <= time() && time() <= intval($v['aboutEnd']) && $v['aboutStatus'] != 5){
                // 到开课时间给初始状态
                $data['items'][$k]['aboutStatus'] = '已开始';
                if($v['aboutStatus'] != 2){
                    AboutYard::updateAll(['status' => 2], ['id' => $v['aboutYardId']]);
                }
                if(time() >= (int)$v['aboutStart'] + LATE_TIME){
                    // 打印小票是0,老数据问题解决 - 如果有进场记录,则是已开始; // 打印小票状态为1, 也是已开始;
                    if(($isEntryRecords && $v['is_print_receipt'] == 0) || $v['is_print_receipt'] == 1){
                        $data['items'][$k]['aboutStatus'] = '已开始';
                        if($v['aboutStatus'] != 2){
                            AboutYard::updateAll(['status' => 2], ['id' => $v['aboutYardId']]);
                        }
                    }else{
                        // 不满足if, 就是爽约状态, 改变爽约状态返回字段;
                        $data['items'][$k]['aboutStatus'] = '已爽约';
                        if($v['aboutStatus'] != 4){
                            AboutYard::updateAll(['status' => 4], ['id' => $v['aboutYardId']]);
                        }
                    }
                }
                //8. 如果开始时间大于当前时间, 并且状态是已预约 , 则已预约
            }elseif(intval($v['aboutStart']) > time() && $v['aboutStatus'] == 1){
                $data['items'][$k]['aboutStatus'] = '已预约';
                //9. 如果状态是5, 则已取消
            }elseif($v['aboutStatus'] == 5){
                $data['items'][$k]['aboutStatus'] = '已取消';
            }else{
                //10. 如果当前时间大于结束时间
                if(($isEntryRecords && $v['is_print_receipt'] == 0) || $v['is_print_receipt'] == 1){
                    //11. 当天有进场记录并且是老数据 || 已经打印小票, 走状态3
                    if($v['aboutStatus'] != 3){
                        AboutYard::updateAll(['status' => 3], ['id' => $v['aboutYardId']]);
                    }
                    //12. 满足if, 则结束状态
                    $data['items'][$k]['aboutStatus'] = '已结束';
                }else{
                    //13. 不满足if, 则已爽约
                    if($v['aboutStatus'] != 4){
                        AboutYard::updateAll(['status' => 4], ['id' => $v['aboutYardId']]);
                    }
                    $data['items'][$k]['aboutStatus'] = '已爽约';
                }
            }
        }
        $message = ['status' => 'success', 'message' => '请求成功', 'code' => 1];
        return array_merge(['data' => $data], $message);
    }
}
