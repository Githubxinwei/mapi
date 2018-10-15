<?php
namespace backend\modules\v1\models;
use backend\models\Organization;
use common\models\relations\CourseRelations;
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/7/8
 * Time: 14:46
 */
class UserActivityStatistics extends \common\models\base\UserActivityStatistics
{
   use CourseRelations;
   public $deviceNumber;        // 设备编号
   public $theRequestPage;     // 求页面类型 0:下载安装 1:团课列表 2:私教列表 3:团课详情 4:私课详情
   public $deviceType;        // 设备类型 1:安卓 2:ios
   public $venueId;           // 场馆id
   public $companyId;         // 公司id
   public $memberId;          // 会员id
   public $params;
    /**
     * api - app 活跃用户统计
     * @author Hou kaixin <houkaixin@itsports.club>
     * @param $post    // 请求参数
     * @create 2017/01/03
     * @return mixed
     */
   public function insertActiveUser($post){
       $this->autoParams($post);
       // 如果是安装 进行判断
       if($this->theRequestPage==0){
           $count =  UserActivityStatistics::find()
                           ->where(["device_number"=>$this->deviceNumber])
                           ->count();
           if($count!=0){
               return true;
           }
       }
       $model = new \common\models\base\UserActivityStatistics();
       $model->device_number = $this->deviceNumber;
       $model->request_page  = $this->theRequestPage;
       $model->device_type   = $this->deviceType;
       $model->request_date  = date("Y-m-d",time());
       $model->create_at     = time();
       $model->venue_id      = $this->venueId;
       $model->company_id    = $this->companyId;
       $model->member_id     = $this->memberId;
       if(!$model->save()){
           return $model->errors;
       }
       return true;
   }
    /**
     * api - 请求参数自动加载
     * @author Hou kaixin <houkaixin@itsports.club>
     * @param $params    array   // 数组请求参数
     * @create 2017/01/03
     * @return mixed
     */
  public function autoParams($params){
      $this->deviceNumber   = isset($params["deviceNumber"])?$params["deviceNumber"]:null;
      $this->theRequestPage = isset($params["theRequestPage"])?$params["theRequestPage"]:null;
      $this->deviceType     = isset($params["deviceType"])?$params["deviceType"]:null;
      $this->venueId        = isset($params["venueId"])?$params["venueId"]:null;
      $this->memberId        = isset($params["memberId"])?$params["memberId"]:null;
      $this->companyId      = $this->gainCompanyId($params);    // 公司id 暂时写死
      return true;
  }
    /**
     * api - 获取公司id
     * @author Hou kaixin <houkaixin@itsports.club>
     * @param $params    array   // 数组请求参数
     * @create 2017/01/03
     * @return mixed
     */
 public function gainCompanyId($params){
     if(!isset($params["companySign"])){
         $companyId = null;
     }elseif($params["companySign"]=="mb"){
         $companyId = 49;
     }elseif($params["companySign"]=="wayd"){
         $companyId = 1;
     }else{
         $companyId = null;
     }
     return $companyId;
 }

   // 获取所有公司的用户量
   public function combineData($param)
   {
        // 统计各个场馆的活跃量
       $query1   = UserActivityStatistics::find()->alias('activityUser')
                    ->where(['IS NOT','activityUser.venue_id',null])
                    ->select("count(activityUser.venue_id) as activeNum,activityUser.venue_id,venue.id,venue.name,")
                    ->joinWith(['venue venue'],false)
                    ->groupBy('activityUser.venue_id');
          $query1     = $this->search($query1, $param);
          $activeNum = $query1->asArray()->all();
        // 统计各个公司的下载数量
           $query2 = Organization::find()
                              ->alias("company")
                              ->where(["company.style"=>1])
                              ->joinWith(["activityUsers activityUser"=>function($query){
                                    $query->groupBy("activityUser.company_id")
                                          ->where(["activityUser.request_page"=>0])
                                          ->select("count(activityUser.id) as downLoadNum,activityUser.company_id");
                              }])
                              ->select("company.name,company.id");
       $query2      = $this->search($query2, $param);
       $downloadNum = $query2->asArray()->all();
       //访问量
       $query3   = UserActivityStatistics::find()->alias('activityUser')
           ->where(['IS NOT','activityUser.venue_id',null])
           ->select("count(activityUser.venue_id) as visitNum,activityUser.venue_id,venue.id,venue.name,")
           ->joinWith(['venue venue'],false)
           ->groupBy('activityUser.device_number');
       $query3     = $this->search($query3, $param);
       $visitNum = $query3->asArray()->all();
       return ["activeNum"=>$activeNum,"downloadNum"=>$downloadNum,'visitNum'=>$visitNum];
   }
   private function search($query, $param)
   {
       $startTime = isset($param['startTime']) && $param['startTime'] ? strtotime(date('Y-m-d',$param['startTime']).' 00:00:00') : NULL;
       $endTime   = isset($param['endTime']) && $param['endTime'] ? strtotime(date('Y-m-d',$param['endTime']).' 23:59:59') : NULL;
       if($startTime != NULL && $endTime != NULL){
           $query->andFilterWhere(['between', 'activityUser.create_at', $startTime, $endTime]);
       }
       $company_id = isset($param['companyId']) && $param['companyId'] ? $param['companyId'] : null;
       if ($company_id != null) {
           $query->andFilterWhere(['company_id'=>$company_id]);
       }
       $device_type = isset($param['client']) && $param['client'] ? $param['client'] : null;
       if ($device_type !== null) {
           $query->andFilterWhere(['device_type'=>$device_type]);
       }
       return $query;
   }

    /**
     * @desc: 手机数据统计-获取结果数组整合
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/24
     * @param $activeNum
     * @param $visitNum
     * @return array
     */
   private function getArray($activeNum,$visitNum) {
       $arr = [];
//       var_dump($visitNum);die();
       $visitNum  = $this->setArrayNum($visitNum,'visitNum');
       $activeNum = $this->setArrayNum($activeNum,'activeNum');
       foreach ($activeNum as $k1=>$v1) {
           foreach ($visitNum as $k2=>$v2) {
               if ($v1['id'] == $v2['id']) {
                   $tmpArr = [
                       'activeNum' => $v1['activeNum'],
                       'id'        => $v1['id'],
                       'name'      => $v1['name'],
                       'visitNum'  => $v2['visitNum']
                   ];
                   array_push($arr,  $tmpArr);
               }
           }
       }
       return $arr;
   }
    /**
     * @desc: 手机数据统计-获取结果数组整合
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/24
     * @param $visitNum
     * @return array
     */
    private function setArrayNum($visitNum,$str) {
        $arr = [];
        $venueArr = array_column($visitNum,'id');
//        var_dump($visitNum);die();
        foreach ($venueArr as $k=>$v){
            foreach ($visitNum as $k1 => $v1) {
                 if($v == $v1['id']){
                     if(isset($arr[$v])){
                         $arr[$v][$str] += $v1[$str];
                     }else{
                         $arr[$v] = [
                             'id'       =>$v1['id'],
                             'name'     =>$v1['name'],
                             $str       =>$str == 'visitNum' ? $this->getDeviceNumberGroupData($v1['id'])['visitNum'] : $v1[$str]
                         ];
                     }
                 }
            }
        }
        return $arr;
    }
   public function tableData($param) {
       // 统计各个场馆的活跃量
       $query1   = UserActivityStatistics::find()->alias('activityUser')
           ->where(['IS NOT','activityUser.venue_id',null])
           ->select("count(activityUser.venue_id) as activeNum,activityUser.venue_id,venue.id,venue.name,")
           ->joinWith(['venue venue'],false)
           ->groupBy('activityUser.venue_id');
       $query1     = $this->search($query1, $param);
       $activeNum = $query1->asArray()->all();
       //访问量
       $query3   =  Organization::find()->alias('oo')
           ->joinWith(['activityUser activityUser'],false)
           ->where(['IS NOT','activityUser.venue_id',null])
           ->andWhere(['oo.style'=>2])
           ->andWhere('activityUser.venue_id = oo.id')
           ->select("count(activityUser.device_number) as visitNum,activityUser.venue_id,oo.id,oo.name,")
           ->groupBy('activityUser.venue_id,');
       $query3     = $this->search($query3, $param);
       $this->params = $param;
       $visitNum = $query3->asArray()->all();
       if (!empty($activeNum)) {
           $arr = $this->getArray($activeNum,$visitNum);
           return $arr;
       }
       return [];
   }

    /**
     * 获取手机类型数据统计
     * @param  $vId
     * @author 李慧恩<lihuien@itsports.club>
     * @return array
     */
    public function getDeviceNumberGroupData($vId)
    {
       $query =  UserActivityStatistics::find()
           ->alias('activityUser')
           ->where(['venue_id'=>$vId])
           ->select('count(distinct(device_number)) as visitNum')->asArray();
       return $this->search($query, $this->params)->one();
    }
}