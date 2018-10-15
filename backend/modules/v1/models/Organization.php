<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/25
 * Time: 17:53
 */

namespace backend\modules\v1\models;

class Organization extends \common\models\base\Organization
{
    const VENUE_PIC = 'http://oo0oj2qmr.bkt.clouddn.com/1314e596c.jpg?e=1498881007&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:-k0jjLHFf2A0-czA9L8VkSUTtMA=';
    /**
     * 云运动 - Api - 获取场馆详情
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @param  $id int  //场馆ID
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getVenueDetail($id)
    {
        $venue =  \backend\models\Organization::getOrganizationById($id);
        if($venue && !empty($venue)){
            $venue['venueAddress'] = !empty($venue['address'])?$venue['address']:"场馆地址:暂无数据";
            $venue['businessHours'] = '8:00 - 21:30';
            if(empty($venue['describe'])){
                $venue['describe']      = '我爱运动势必在wellness全面健康领域带领潮流，将全球先进时尚科技之硬件设备，融合东方身心平衡之道，开发世界市场，开创全面健康的人生之旅！';
            }
            if(empty($venue['pic'])){
                $venue['pic']  = self::VENUE_PIC;
            }
//            $venue['createTime']    = date('Y-m-d',(int)$venue['created_at']);
            $venue["longitude"] = !empty($venue["longitude"])?$venue["longitude"]:"113.676957";   // 精度
            $venue["latitude"]  =  !empty($venue["latitude"])?$venue["latitude"]:"34.763269";       // 维度
            $group = new GroupClass();
            $score                   = $group->getFieldsByOne($v = [],'score');
            $venue['score']         = $score['score'];
            $venue['area']          = !empty($venue['area'])?$venue['area']."㎡":"暂无数据";
            $venue["establish_time"] = !empty($venue["establish_time"])?$venue["establish_time"]:"暂无数据";
            unset($venue['update_at'],
                  $venue['create_id'],
                  $venue['path'],
                  $venue['created_at'],
                  $venue['params'],
                  $venue['code'],
                  $venue['is_allowed_join']
                  );
        }
        return $venue;
    }

    /**
     * 云运动 - Api - 获取所有的公司
     * @author huangpengju <huangpengju@itsports.club>
     * @param $type     // 公司名称
     * @create 2017/7/3
     * @return array|\yii\db\ActiveRecord[]  //获取所有的公司
     */
    public function getAllCompany($type)
    {
        $data    =  \common\models\base\Organization::find()->select('id,name,style');
        if(!empty($type)){
            $company = ["迈步运动健身"];
            $data    = $data->where(["and",["in","name",$company],['style'=>1]])->asArray()->all();
        }else{
            $company =  ["幸福里智能科技有限公司","迈步运动健身"];
            $data    = $data->where(["and",["not in","name",$company],['style'=>1]])->asArray()->all();
        }
        return $data;
    }
    /**
     * 云运动 - Api - 公司过滤
     * @author 侯凯新<houkaixin@itsports.club>
     * @param $data    // 需要过滤的数据
     * @create 2017/8/22
     * @return array|\yii\db\ActiveRecord[]  //获取所有的公司(过滤后的公司)
     */
    public function filterData($data){
          $companyS = ["幸福里智能科技有限公司","管理公司","着急"];
          if(empty($data)){
             return $data;
          }
          foreach($data as $keys=>$value){
              if(in_array($value["name"],$companyS)){
                  unset($data[$keys]);
              }
          }
            return $data;
    }
    /**
     * 云运动 - Api - 获取选中公司下面的场馆
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/7/4
     * @param  $type         // 公司区分
     * @param $companyId     //公司id
     * @param $requestType  // 请求类型
     * @return array|\yii\db\ActiveRecord[]  //返回所有的场馆
     */
    public function getAllVenue($companyId,$requestType = "pc",$type = "",$maId=null)
    {
        $member   = new Member();
        $venueArr = $member->getMemberAccOneData($maId,$companyId,'ID');
        foreach ($venueArr as &$arr){
            $card = MemberCard::find()->where(['member_id'=>$arr['id'],'status'=>[1,2,3,4]])->andWhere(['>','invalid_time',time()])->asArray()->all();
            if (!empty($card)){
                foreach ($card as $A){
                    if ($A['status'] ==1 || $A['status'] == 4){
                        $cards =1;
                        break;
                    }
                    $cards =2;
                }
            }else{
                $cards =0;
            }

            $arr['card']=$cards;
        }
        $vIdArr   = array_column($venueArr,'venueId');
        $data = \common\models\base\Organization::find()->where(['pid'=>$companyId])->andWhere(['style'=>2])->andWhere(['is_allowed_join'=>1])->andWhere(['not in','id',$vIdArr])->select('id,name');
        // 适用于我爱运动之类条件
        if(($requestType=="ios")&&empty($type)){
           // $filter = ["管理公司","着急"];
            $venueName = ["管理公司"];
            //  $query  = $query->onCondition(["allVenue.name"=>["大上海瑜伽健身馆","艾搏尊爵汇馆"]]);
            $data   = $data->andWhere(["not like","name",$venueName]);
            //$query = $query->onCondition(["not like","allVenue.name",$venueName]);
           // $data   = $data->andWhere(["not in","name",$filter]);
        }
        if(($requestType=="ios")&&!empty($type)){
            $data   = $data->andWhere(["not in","name",["着急"]]);
        }
        $data = $data->asArray()->all();
        $memberVenueArr['memberVenueArr'] = $venueArr;
        $memberVenueArr['venue']          = $data;
        return $memberVenueArr;
    }
    /**
     * 云运动 - ios - 获取指定公司下的场馆和部门
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/8/17
     * @param $companyId     //公司id
     * @param $venueId       // 场馆id
     * @return array|\yii\db\ActiveRecord[]  //返回所有的场馆信息 和 第一条场馆所属部门信息
     */
    public function getAllAppointMessage($companyId,$venueId){
            $data = [];
            $venueData       = $this->getAllVenue($companyId,"ios","maibu");
            if(!empty($venueData)){
                $nowVenueData    = $this->dataUpdate($venueData,$venueId);
            }else{
                $nowVenueData    = [];
            }
            $nowDepMessage   = $this->getDepMessage($venueId);
            $data["venueS"] = $nowVenueData;
            $data["depS"]   = $nowDepMessage;
            return $data;
    }
    /**
     * 云运动 - ios - 组装数据（所属场馆放在第一条）
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/8/17
     * @param $venueData     //指定场馆数据
     * @param $venueId       // 会员所属场馆
     * @return array|\yii\db\ActiveRecord[]
     */
    public function dataUpdate($venueData,$venueId){
            // 1:重新处理 $venueData 里边的数据
            $venueData = $this->dealVenueData($venueData);
            // 2：符合数据处理
            foreach($venueData as $keys=>$values){
                   if($venueData[$keys]["id"]==$venueId){
                       $firstData    = $venueData[0];       // 第一条信息
                       $venueData[0] = $venueData[$keys];  // 将所属场馆信息 赋值给 第一条信息
                       $venueData[$keys] = $firstData;     //将第一条信息 赋值 第一条信息
                   }
            }
           return $venueData;
    }

    /**
     * 云运动 - app -  专门处理场馆数据
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/11/03
     * @param $venueData       // 场馆数据
     * @return array|\yii\db\ActiveRecord[]   //指定场馆的部门信息
     */
    public function dealVenueData($venueData){
        $arr = [];
        $venues = ["管理公司","亚星游泳健身馆（预售）"];
        if(empty($venueData)){
           return [];
        }

        foreach($venueData['venue'] as $keys=>$values){
             if(in_array($values["name"],$venues)){
               continue;
             }
            $arr[] = $venueData['venue'][$keys];
        }
        return $arr;
    }
    /**
     * 云运动 - ios -  获取指定场馆的部门信息
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/8/17
     * @param $venueId       // 场馆id
     * @return array|\yii\db\ActiveRecord[]   //指定场馆的部门信息
     */
    public function getDepMessage($venueId)
    {
          $data = Organization::find()->where(["and",["style"=>3],["pid"=>$venueId]])->select("id,name")->asArray()->all();
          return $data;
    }
    /**
     * 云运动 - ios - 获取指定公司下的所有场馆
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/8/17
     * @param $isNOtMB  //是否是迈步公司
     * @return array|\yii\db\ActiveRecord[]   //指定场馆的部门信息
     */
    public function gainAllCompanyVenueData($isNOtMB = null){
        define("isNotMB",$isNOtMB);
        $filterCompanyName = ["幸福里智能科技有限公司","迈步运动健身"];
        $data = \backend\models\Organization::find()->alias("organization")
                                    ->joinWith(["theOrganization allVenue"=>function($query){
                                        $query->select("allVenue.id,allVenue.pid,allVenue.name");
                                    }])
                                   ->select("organization.id,organization.pid,organization.name");
        if(empty($isNOtMB)) {
            $data = $data->andWhere(["and", ["organization.style" => 1], ["organization.pid" => 0], ["not in", "organization.name", $filterCompanyName]]);
        }else{
            $data = $data->andWhere(["and", ["organization.style" => 1], ["organization.pid" => 0], ["organization.name"=>"迈步运动健身"]]);
        }
        $data = $data->asArray()->all();
        return $data;
    }







}