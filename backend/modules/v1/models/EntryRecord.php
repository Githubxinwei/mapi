<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/11
 * Time: 16:51
 * Desc:进馆记录
 */
namespace backend\modules\v1\models;

class EntryRecord extends \common\models\EntryRecord
{
    /*
     * 会员端-获取会员运动日期,总坚持天数,最长连续天数
     * @author xinwei<xinwei@itsport.club>
     * @createAt 2018/06/11
     * @param $accountId    账户ID
     */
    public function getMotionDays($accountId)
    {
        $enrtyTime = $this->getMotionDates($accountId);
        $persistenceDays = intval($this->getPersistenceDays($accountId));
        $sustainedDays = $this->getSustainedDays($accountId);
        $arr['enrtyTime'] = $enrtyTime;
        $arr['persistenceDays'] = $persistenceDays;
        $arr['sustainedDays'] = $sustainedDays;
        return $arr;
    }
    /*
     * 会员端-查看会员运动日期
     * @author xinwei<xinwei@itsport.club>
     * @createAt 2018/06/11
     * @param $accountId    账户ID
     */
    public function getMotionDates($accountId)
    {
        $entryRecord = EntryRecord::find()
            ->alias('er')
            ->joinWith(['members m'], FALSE)
            ->where(['m.member_account_id' => $accountId])
            ->select('er.entry_time')
            ->groupBy(["DATE_FORMAT(from_unixtime(er.entry_time),'%Y-%m-%d')"])
            ->orderBy('er.entry_time desc')
            ->asArray()
            ->all();
        return $entryRecord;
    }
    /*
     * 会员端-查看会员总坚持天数（运动天数）
     * @author xinwei<xinwei@itsport.club>
     * @createAt 2018/06/11
     * @param $accountId    账户ID
     */
    public function getPersistenceDays($accountId)
    {
        $frequency = EntryRecord::find()
            ->alias('er')
            ->joinWith(['members m'],FALSE)
            ->where(['m.member_account_id' => $accountId])
            ->select('er.entry_time')
            ->groupBy(["DATE_FORMAT(from_unixtime(er.entry_time),'%Y-%m-%d')"])
            ->count();
        return $frequency;
    }
    /*
     * 会员端-查看会员最长连续天数
     * @author xinwei<xinwei@itsport.club>
     * @createAt 2018/06/11
     * @param $accountId    账户ID
     */
    public function getSustainedDays($accountId)
    {
        $list = $this->getMotionDates($accountId);
        $max = 0;
        if ($list) {
            $len = 1;
            $max = 1;
            $num = count($list);
            foreach ($list as $k => $v){
                if ($k+1 == $num) break;
                if ((strtotime(date("Y-m-d",$list[$k]['entry_time'])) - 60*60*24) == strtotime(date("Y-m-d",$list[$k+1]['entry_time']))) {
                    $len++;
                } else {
                    if ($len > $max) $max = $len;
                    $len = 1;
                }
                if ($len > $max) $max = $len;
            }
        }
        return $max;
    }
}