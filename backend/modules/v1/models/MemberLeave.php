<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/25
 * Time: 17:53
 */
namespace backend\modules\v1\models;
use backend\models\MemberCard;

class MemberLeave extends \common\models\base\LeaveRecord
{
    /**
     * 员工管理 - 手机app - 获取会员请假
     * @author 侯凯新 <houkaixin@itsports.club>
     * @create 2018/01/06
     * @param   $memberCardId   //  会员卡id
     * @return array
     */
    public  function gainLeaveLimit($memberCardId){
           $model   = new MemberCard();
           $limit   =  $model->getTheLimitData($memberCardId);
           $arr     = [];
          // 空数据的处理
          if(!empty($limit["leave_days_times"])){
              foreach ($limit["leave_days_times"] as $key=>$values){
                  $arr[$key]["days"] = $values[0];
                  $arr[$key]["times"] = $values[1];
              }
              $limit["leave_days_times"] = $arr;

          }
            unset($limit["leave_long_limit"]);
            return $limit;
    }
}