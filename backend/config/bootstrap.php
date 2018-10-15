<?php

defined("LEAD_TIME") or define("LEAD_TIME", 1800);
defined("LATE_TIME") or define("LATE_TIME", 600);
/**
 * 会员端日期工具函数   week: 当周   day: 当天  month: 当月
 * @author 辛威<xinwei@itsport.club>
 * @createAt 2018/06/21
 * @note 备注: 无
 * @tip 提示: 无
 * @param string $identify
 * @return array [start => "开始时间", end => "结束时间"]
 */
function _makeTime($identify = 'day')
{
    switch (strtolower($identify))
    {
        case 'day':
            $time1 = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $time2 = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
            return ['start' => $time1, 'end' => $time2];
        case 'week':
            $time1 = mktime(0, 0, 0, date('m'), date('d')-date('w')+1, date('Y'));
            $time2 = mktime(23, 59, 59, date('m'), date('d')-date('w')+7, date('Y'));
            return ['start' => $time1, 'end' => $time2];
        default:
            $time1 = mktime(0, 0, 0, date('m'), 1, date('Y'));
            $time2 = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
            return ['start' => $time1, 'end' => $time2];
    }
}
