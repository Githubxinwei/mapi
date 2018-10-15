<?php
namespace backend\modules\v1\models;
use backend\models\Member;
use common\models\base\MemberCard;

class MachineRecord extends \common\models\base\MachineRecord
{
    /**
     * 后台 - 闸机 - 机器型号存储
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/7/29
     * @param  $data    int 存储参数
     * @return array
     */
    public function insertData($data){
        // 先查询 该场馆是否有该闸机
         $endResult  =  $this->searchIsHaveSave($data["venueId"]);
         if($endResult===false){
            return "hadSave";
         }
         $model = new MachineRecord();
         $model->machine_model  = $data["machineModel"];
         $model->ip              = $data["ip"];
         $model->machine_type   = $data["machineType"];
         $model->machine_status = $data["machineStatus"];
         $model->venue_id        = $data["venueId"];
         $model->company_id     = $data["companyId"];
         if(!$model->save()){
             return $model->errors;
         }
         return true;
    }
    /**
     * 后台 - 闸机 - 查询该场馆是否有该闸机
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/11/08
     * @param  $venueId     int 场馆id
     * @return array
     */
    public function searchIsHaveSave($venueId){
         $data = MachineRecord::findOne(["venue_id"=>$venueId]);
         if(empty($data)){
             return true;
         }
        return false;
    }
    /**
     * 后台 - 闸机 - 判断是否可以生成二维码
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/11/08
     * @param  $memberId     //会员id
     * @param $companyName   // 公司名称
     * @return array
     */
    public function judgeIsNotGenerateCode($memberId,$companyName){
         if(empty($memberId)||empty($companyName)){
            return false;
         }
         $venueIds   =  Member::gainTheVenueS($companyName);
         $endResult  =  $this->gainMemberCard($memberId,$venueIds);
         return $endResult;
    }
    /**
     * 后台 - 闸机 - 判断进门
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/11/08
     * @param  $memberId     //会员id
     * @param  $venueIds   // 公司所属场馆
     * @return array
     */
    public  function  gainMemberCard($memberId,$venueIds){
         $memberCard = MemberCard::find()
                           ->where(["member_id"=>$memberId])
                           ->andWhere(["and",["venue_id"=>$venueIds],[">","invalid_time",time()],["status"=>[1,4]]])
                          // ->andWhere([">","create_at",time()-60*60*12])
                           ->select("id,member_id,venue_id,create_at,status")
                           ->count();
         if($memberCard>0){
             return true;
         }
         return false;
    }
}
?>
