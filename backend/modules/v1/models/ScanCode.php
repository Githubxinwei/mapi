<?php
namespace backend\modules\v1\models;
use common\models\base\ScanCodeRecord;

class ScanCode extends \common\models\base\ScanCodeRecord
{
    /**
     * @云运动 - 云运动 - 二维码生成数据录入
     * @author 侯凯新<houkaixin@itsports.club>
     * @param  $param   // 生成二维码传送过来的参数
     * @create 2017/7/28
     * @inheritdoc
     */
    public function  insertData($param){
        // 先删除 该会员的所有进场记录 然后在录入
        $param = explode("@",$param);
        // 根据身份删除之前的二维码
        $this->dealBeforeCode($param);
        // 二维码的录入
        $model = new ScanCodeRecord();
        $model->member_id       = $param[0];
        $model->create_at       = $param[1];
        if($param[2]=="000000"){ // 员工身份录入
           $model->member_card_id  = 0;
           $model->identify         = 2;
        }else{                   // 会员信息录入
           $model->member_card_id  =$param[2];
           $model->identify         = 1;
        }
        if(!$model->save()){
            return $model->errors;
        }
        return true;
    }
    /**
     * @云运动 - 云运动 -  录入二维码之前删除 之前的二维码
     * @author 侯凯新<houkaixin@itsports.club>
     * @param  $param  // 搜索参数
     * @create 2017/7/28
     * @inheritdoc
     */
    public function dealBeforeCode($param){
        if($param[2]=="000000"){   //删除指定员工二维码
         ScanCodeRecord::deleteAll(["member_id"=>$param[0]]);
        }else{                     // 删除指定会员二维码
         ScanCodeRecord::deleteAll(["member_card_id"=>$param[2]]);
        }
    }

    /**
     * @云运动 - 云运动 - 进闸机的时候 对比二维码的可用性
     * @author 侯凯新<houkaixin@itsports.club>
     * @param  $memberId  // 会员id
     * @create 2017/7/28
     * @inheritdoc
     */
    public function searchMember($memberId){
        // 查询是否当天有二维码
         $dateStart = strtotime(date("Y-m-d")." 00:00:01");
         $dateEnd   = strtotime(date("Y-m-d")." 23:59:59");
         $endData =  ScanCodeRecord::find()
                       ->where(["and",["member_id"=>$memberId],["between","create_at",$dateStart,$dateEnd]])
                       ->orderBy(["id"=>SORT_DESC])
                       ->asArray()
                       ->limit(1)
                       ->one();
         if(empty($endData)){
            return "noMessageCode";   // 没有二维码
         }
         $timeDifference = (time() - $endData["create_at"]);
         if($timeDifference>6){         // 暂定6秒钟
             return "Invalid";
         }
         return true;
    }


}