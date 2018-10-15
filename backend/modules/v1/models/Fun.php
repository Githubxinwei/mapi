<?php
namespace backend\modules\v1\models;

use yii\base\Model;
use common\models\Member;
use common\models\MemberAccount;
use common\models\MemberCard;
use common\models\EntryRecord;

class Fun extends Model
{
    /**
     * 会员端-判断会员账户是否是潜在会员账户 是: true  不是: false
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/15
     * @param $accountId
     * @return bool
     * @note 判断某个账户是否是潜在会员账户
     */
    public static function isLatentMember($accountId)
    {
        $accountDataModel = MemberAccount::find()
                            ->where([
                                'id' => $accountId,
                            ])->asArray()->all();
        if($accountDataModel){
            $accountIds = array_unique(array_column($accountDataModel, 'id'));
            $members = Member::find()
                        ->where([
                            'member_account_id' => $accountIds,
                        ])->asArray()->all();
            if(!$members){
                return true;
            }
            $member_type = array_column($members, 'member_type');
            if(in_array(1, $member_type)){
                return false;
            }
            return true;
        }
        return true;
    }

    /**
     * 会员端-关联账户表获取账户下所有帐号ID
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/3/30
     * @param $accountId
     * @return array
     * @note 账户可能不惟一, 兼容特殊情况, 此方法值可以存储session, 但是小程序不支持session.
     */
    public static function receiveAccount($accountId)
    {
      $accountDataModel = MemberAccount::find()
                            ->where([
                                'id' => $accountId,
                            ])->asArray()->all();
      if($accountDataModel){
          $accountIds = array_filter(array_unique(array_column($accountDataModel, 'id')));
          $memberIds = Member::find()
                          ->where([
                              'member_account_id' => $accountIds
                          ])->asArray()->all();
          if($memberIds){
              return array_column($memberIds, 'id');
          }
          return [];
      }
      return [];
    }

    /**
     * 会员端-判断会员账户下是否有卡
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/15
     * @param $accountId
     * @return static[]
     * @note 判断某个账户是否有卡
     */
    public static function isIfCards($accountId)
    {
        $ids = Fun::receiveAccount($accountId);
        $mcObject = MemberCard::findAll(['member_id' => $ids, 'status' => [1, 3, 4]]);
        return $mcObject;
    }
    /*
     * 辛威-查看会员是否有进场记录
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/15
     * @param $ids
     * @param $startTime
     * @return static[]
     * @note 判断会员在特定时间段是否有进馆记录
     */
    public static function isEntryRecords($ids, $startTime, $venueId, $const = true)
    {
        $leadTime = strtotime(date('Y-m-d', $startTime) . ' 0:0:0');
        if($const === true){
            $lateTime = (int)$startTime + LATE_TIME;
        }else{
            $lateTime = (int)$startTime;
        }
        $model = EntryRecord::find()
            ->where(['member_id' => $ids, 'venue_id' => $venueId])
            ->andWhere(['between', 'entry_time', $leadTime, $lateTime])
            ->all();
        return $model;
    }

    /**
     * 会员端-获取团课列表更新会员卡爽约次数
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/15
     * @note 备注: 更新会员卡爽约次数
     * @param $cardId
     * @return int
     */
    public static function updateFreeze($cardId)
    {
        $cardModel = MemberCard::findOne($cardId);
        if($cardModel->absentTimes == null){
            MemberCard::updateAll(['absentTimes' => 0], ['id' => $cardId]);
        }
        return MemberCard::updateAllCounters(['absentTimes' => 1], ['id' => $cardId]);
    }
    /**
     * 会员端-通当前场馆卡的会员ID和场馆名称
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/15
     * @note 备注: 获取通当前场馆有效可用的会员卡的会员ID和场馆名称
     * --当前场馆有只返回当前
     * @tip 提示: 无
     * @param $lastVenueId //当前场馆
     * @param $mobile      //手机号码
     * @return array|mixed|\yii\db\ActiveRecord[]
     */
    public static function getUsedOnThisVenueCards($lastVenueId, $mobile)
    {
        $cardData = Member::find()->alias('mm')
            ->joinWith(['memberCard mc' => function($q){
                $q->joinWith(['venueLimitTimesArr vlt']);
            }], false)
            ->where([
                'or',
                ['vlt.venue_id' => $lastVenueId],
                ['like', 'vlt.venue_ids', '"' . $lastVenueId . '"']
            ])
            ->andWhere(['mm.id' => Fun::receiveAccount($mobile)])
            ->andWhere(['and', ['mc.status' => [1, 4]], ['>', 'mc.invalid_time', time()]])
            ->select('mm.id memberId')
            ->asArray()
            ->all(\Yii::$app->db);
        if(!empty($cardData)){
            $useCardsMemberId = array_filter(array_unique(array_column($cardData, 'memberId')));
            $resultModel = Member::find()->alias('mm')
                ->joinWith(['venue vv'], false)
                ->where(['mm.id' => $useCardsMemberId])
                ->select('mm.id,vv.name,vv.id venueId')
                ->groupBy('vv.id')
                ->asArray()->all();
            $data = array_reduce($resultModel, function($result,$items)use($lastVenueId){
                if($items['venueId'] == $lastVenueId){
                    array_push($result, ['memberId' => $items['id'], 'venueName' => $items['name']]);
                    return $result;
                }else{
                    return $result;
                }
            }, []);
            if(!empty($data)){
                return $data;
            }else{
                $memAndVenue = array_column($resultModel, 'name', 'id');
                $data = [];
                array_walk_recursive($memAndVenue, function($venueName, $memberId) use (&$data){
                    array_push($data, ['memberId' => $memberId, 'venueName' => $venueName]);
                });
                return $data;
            }
        }
        return [];
    }

    /**
     * 会员端-Member表过滤出有效会员数据
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/15
     * @note 备注: 由于member表新增软删除字段,
     * 故封装此方法方便调用和用于统计 涉及修改的数量
     * @tip 提示: 无
     * @param $model
     * @param $where
     * @param $value
     * @param null $alias
     * @return mixed
     */
    public static function where($model, $where, $value, $alias = null)
    {
        $object = $model->andWhere([$where => $value]);
        if($alias != null){
            $object = $model->andWhere([$alias . '.' . $where => $value]);
        }
        return $object;
    }

    /**
     * 会员端-获取会员有效,未过期,正常,未请假会员卡
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/15
     * @note 备注: 无
     * @tip 提示: 无
     * @param $memberId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function activeCards($memberId)
    {
        $activeCards = [];
        // 获取会员正常未过期会员卡, 除请假卡外;
        $result = MemberCard::find()
            ->where([
                'and',
                ['member_id' => $memberId, 'status' => [1, 4]],
                ['>', 'invalid_time', time()]
            ])
            ->select('id')
            ->asArray()
            ->all();
        if(!empty($result)){
            $activeCards = array_filter(array_unique(array_column($result, 'id')));
        }
        //获取会员请假卡
        $begOffCards = static::begOffCards($memberId);
        //去除正常卡中请假会员卡
        $resultCards = array_diff($activeCards, $begOffCards);
        // [] or [1,2,3]
        return $resultCards;
    }

    /**
     * 会员端-获取会员请假卡ID
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/5/15
     * @note 备注: 无
     * @tip 提示: 无
     * @param $memberId
     * @return array
     */
    public static function begOffCards($memberId)
    {
        $cards = [];
        $result = MemberCard::find()
            ->alias('mc')
            ->joinWith(['leaveRecord lr'], false)
            ->where([
                "and",
                ["lr.status" => [1, 4]],
                ["mc.member_id" => $memberId]
            ])
            ->select('mc.id')
            ->asArray()
            ->all();
        if($result){
            $cards = array_filter(array_unique(array_column($result, 'id')));
        }
        return $cards;
    }
}