<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 下午 2:33
 */
namespace backend\models;

use backend\models\CabinetType;
use common\models\Func;
use common\models\relations\CabinetRelations;

class Cabinet  extends \common\models\Cabinet
{
    public  $sorts;
    public  $topSearch;
    public  $sex;
    public  $cabinetStatus;
    public  $venueId;
    public  $startTime;
    public  $endTime;
    public  $endRent;
    public  $params;
    public  $typeId;
    public  $keyword;
    public $nowBelongId;
    public $nowBelongType;

    public $totalPage;   // 总页数

    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    use CabinetRelations;
    /**
     * 后台 - 柜子1管理 - 数据遍历
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/3
     * @param $params      //前台发送过来的参数
     * @param $pageSize    // 当前页码大小
     * @param $requestType  // 接口请求类型
     * @return object     //返回分页数据对象
     */
    public function getData($params,$requestType="",$pageSize=8)
    {
        $this->params  = $params;
        $this->customLoad($params);
        $query =Cabinet::find()
            ->joinWith(["cabinetType"],false)
            ->joinWith(["admin"],false)
            ->joinWith(["memCabinet"=>function($query){
                $query->joinWith(["member"=>function($query){
                    $query->joinWith(["memberDetails"],false);
                    $query->joinWith(['memberCard mc'],false);
                },"employee"]);
            }],false)
            ->select("
               cloud_cabinet.*,
               cloud_cabinet_type.type_name,
               cloud_cabinet.cabinet_model as cabinetModel,
               cloud_cabinet.cabinet_type  as cabinetType,
               cloud_cabinet_type.id as typeId,
               cloud_cabinet_type.venue_id as venueId,
               cloud_member_cabinet.create_at,
               cloud_cabinet_type.sex, 
               cloud_admin.username as operatorName,
               cloud_member_cabinet.id as memberCabinetId,
               cloud_member_cabinet.start_rent,
               cloud_member_cabinet.price,
               cloud_member_cabinet.creater_id  as cabinetOperatorId,
               cloud_member_cabinet.end_rent,
               cloud_member_cabinet.back_rent,
               cloud_member_cabinet.member_id,
               cloud_member.mobile,
               cloud_member_details.name as consumerName,
               cloud_member_details.pic,
               cloud_employee.name as AdminOperator,
               mc.card_number,
                    ")
            ->orderBy($this->sorts)
            ->groupBy('cloud_cabinet.id')
            ->asArray();
         if(!empty($requestType)&&($requestType=="app")){
             $query->where(["cloud_cabinet.status"=>1]);
         }
        //顶部框搜索 日期搜索
         $query = $this->getSearchWhere($query);
        //状态搜索
       //  $query = $this->getSearchStatus($query);
        //租用快到日期搜索
        // $query = $this->getSearchEndRent($query);
         $data              = Func::getDataProvider($query,$pageSize);
         $this->totalPage = ceil($data->pagination->totalCount/8);
         $data->models   =  $this->combineData($data->models);
         return $data;
    }
    /**
     * 后台 - 柜子1管理 - 组装需要的 柜子到期天数
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/3
     * @param  //所有搜索数据
     * @return object   //返回分页数据对象
     */
    public function combineData($data){
          if(!empty($data)){
               foreach($data as $keys=>$values){
                   if(!empty($values["end_rent"])){
                       if(!empty($values["back_rent"])){
                           if($values["back_rent"]>=time()&&$values["end_rent"]>$values["back_rent"]){
                                  $surplusDay = ceil(((int)$values["back_rent"]-time())/86400);
                           }else{
                                  $surplusDay = 0;
                           }
                       }else if(time()>$values["end_rent"]){
                                  $surplusDay = ceil(((int)$values["end_rent"]-time())/86400);
                       }else{
                                  $surplusDay = ceil(((int)$values["end_rent"]-time())/86400);
                       }
                                  $totalDay   = ceil((int)$values["end_rent"]-$values["start_rent"])/86400;
                   }else{
                                  $surplusDay = false;
                                  $totalDay   = false;
                   }
                   $data[$keys]["totalDay"]   =  $totalDay;
                   $data[$keys]["surplusDay"] = $surplusDay;
               }
          }
         return $data;
    }



    /**
     * 后台 - 柜子管理 - 处理柜子租用
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/8
     */
    public function filterData(){
        $memberCabinetId = [];
        $cabinetId       = [];
        $transaction                  =  \Yii::$app->db->beginTransaction();
        try {
            $theData =MemberCabinet::find()->select("id,cabinet_id")->where(["or",["and",["not",["end_rent"=>null]],["<","end_rent",strtotime("-7 day")]],["and",["not",["back_rent"=>null]],["<","back_rent",time()]]])->asArray()->all();
            //执行数据删除
            if(!empty($theData)){
                //获取所有会员所租柜子id（未延期的）
                foreach($theData as $value){
                    $cabinetId[] = $value["cabinet_id"];  //获取柜子id
                    $memberCabinetId[] = $value["id"];     //获取会员柜子id
                }
                // 批量删除  已过期会员租用柜子记录（当前柜子租用历史记录表）
                $memCabinetUpdate    = MemberCabinet::deleteAll(["id"=>$memberCabinetId]);
                //将对应柜子状态 改为未租用批量
                $updateCabinet       = Cabinet::updateAll(["status"=>1],["id"=>$cabinetId]);
            }
            // 数据执行提交
            if($transaction->commit()==null)
            {
                return true;
            }else{
                return "数据提交错误";
            }
        }catch(\Exception $e){
            //如果抛出错误则进入 catch ,先callback,然后捕捉错误，返回错误
            $transaction->rollBack();
            return  $error = $e->getMessage();  //获取抛出的错误
        }
 }
    /**
     * 后台 - 柜子管理 - 处理前台发送过来的搜索参数数据时
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/3
     * @param
     * @return object   //返回分页数据对象
     */
    public function customLoad($data)
    {
        $this->startTime         = (isset($data['startTime']) && !empty($data['startTime'])) ? strtotime($data['startTime']) : null;
        $this->endTime           = (isset($data['endTime']) && !empty($data['endTime'])) ? strtotime($data['endTime']) : null;
        $this->topSearch         = (isset($data['topSearch']) && !empty($data['topSearch'])) ? $data['topSearch'] : null;
        $this->venueId           = (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : null;
        $this->sex               = (isset($data['sex']) && !empty($data['sex'])) ? $data['sex'] : null;
        $this->cabinetStatus     = (isset($data['cabinetStatus']) && !empty($data['cabinetStatus'])) ? $data['cabinetStatus'] : null;
        $this->endRent           = (isset($data['endRent']) && !empty($data['endRent'])) ? $data['endRent'] : null;
        $this->typeId            = (isset($data['typeId']) && !empty($data['typeId'])) ? $data['typeId'] : null;
        $this->keyword           = (isset($data['keyword']) && !empty($data['keyword'])) ? $data['keyword'] : null;
        $this->nowBelongId       = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType     = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->sorts             = self::loadSort($data);
        return true;
    }
    /**
     * 后台 - 柜子管理 - 对各个字段的排序
     * @create 2017/5/3
     * @author houkaixin<houkaixin@itsports.club>
     * @param $data  array //前台获取的排序处理数据
     * @return array
     */
    public static function loadSort($data)
    {
        $sorts = ['cloud_cabinet.status' =>SORT_DESC,"cloud_member_cabinet.create_at"=>SORT_DESC,"cloud_cabinet.id"=>SORT_ASC];
        if(!isset($data['sortType'])){ return $sorts;}
        switch ($data['sortType']){
            case 'cabinetNum':
                $attr = '`cloud_cabinet`.cabinet_number';
                break;
            case 'typeName':
                $attr = 'cloud_cabinet_type.type_name';
                break;
            case 'customerName':
                $attr = 'cloud_member_details.name';
                break;
            case 'create_id':
                $attr = '`cloud_admin.username`.create_id';
                break;
            case 'mobile':
                $attr = '`cloud_member_cabinet`.member_id';
                break;
            case 'price' :
                $attr = '`cloud_member_cabinet`.price';
                break;
            case "date" :
                $attr = '`cloud_member_cabinet`.end_rent';
                break;
            case "operator" :
                $attr = '`cloud_member_cabinet`.creater_id';
                break;
            case "status":
                $attr = '`cloud_cabinet`.status';
                break;
            case "cabinetModel":      //柜子型号
                $attr = "cloud_cabinet.cabinet_model";
                break;
            case 'cabinetType':       //柜子类别
                $attr = "cloud_cabinet.cabinet_type";
                break;
            case 'cabinetEndRent':    //柜子到期剩余天数
                $attr = "cloud_member_cabinet.end_rent";
            break;
            default:
                $attr = NULL;
        };
        if($attr){
            $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }
    /**
     * 后台 - 柜子管理- 获取最终排序规则
     * @create 2017/5/3
     * @author houkaixin<houkaixin@itsports.club>
     * @param $sort     // 前台传过来的排序规则（ASC，DES两种情况）
     * @return string
     */
    public static function convertSortValue($sort)
    {
        if ($sort == 'ASC') {
            return SORT_ASC;
        } elseif ($sort == 'DES') {
            return SORT_DESC;
        }
    }

    /**
     * 后台 - 柜子管理 - 执行搜索数据过滤
     * @create 2017/5/3
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $query  //后台的sql语句
     * @return  mixed
     */
    public function getSearchWhere($query)
    {
//        $query->andFilterWhere([
//            'or',
//            ['like','cloud_cabinet.cabinet_number', $this->topSearch],
//            ['like','cloud_cabinet_type.type_name',$this->topSearch],
//            ['like','cloud_admin.username',$this->topSearch],
//        ]);
//        $query->andFilterWhere([
//            "cloud_cabinet_type.sex"=>$this->sex,
//        ]);
//        $query->andFilterWhere([
//            "cloud_cabinet_type.venue_id"=>$this->venueId,
//        ]);
////        $query->andFilterWhere([
////            'and',
////            ['>=',"cloud_member_cabinet.end_rent",$this->startTime],
////            ['<=',"cloud_member_cabinet.end_rent",$this->endTime]
////        ]);
        $query->andFilterWhere([
            "cloud_cabinet_type.id"=>$this->typeId,
        ]);
        $query->andFilterWhere(["or",
            ["like","cloud_cabinet.cabinet_number",$this->keyword],
            ["mc.card_number" => $this->keyword],
            ["cloud_member.mobile" => $this->keyword],
            ["cloud_member_details.name" => $this->keyword],
        ]);
        /*if($this->nowBelongType && $this->nowBelongType == 'company'){
            $query->andFilterWhere(['cloud_cabinet.company_id'=>$this->nowBelongId]);
        }
        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
            $query->andFilterWhere(['cloud_cabinet.venue_id'=>$this->nowBelongId]);
        }*/
        return $query;
    }

    /**
     * 后台 - 柜子管理 - 柜子快到日期搜搜
     * @create 2017/5/8
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $query  //后台的sql语句
     * @return  mixed
     */
    public function getSearchEndRent($query){
        if(!empty($this->endRent)){
            $sevenTimeStamp = intval($this->endRent*86400);
            $nowTime        = intval(time()+$sevenTimeStamp);
            $query->andFilterWhere([
                'and',
                ['>=',"cloud_member_cabinet.end_rent",time()],
                ['<=',"cloud_member_cabinet.end_rent",$nowTime]
            ]);
        }
        return $query;
    }

    /**
     * 后台 - 柜子管理 - 状态搜索过滤
     * @create 2017/5/3
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $query  //后台的sql语句
     * @return  mixed
     */
    public function getSearchStatus($query)
    {
        if(!empty($this->cabinetStatus)){
            $query->andFilterWhere([
                'in','cloud_cabinet.status',$this->cabinetStatus,
            ]);
        }
        return $query;
    }

    /**
     * 后台 -  柜子管理  - 查询对应柜子类型的柜子
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/3
     * @param $id;
     * @return array
     */
    public function getCabinetData($id){
        $data = Cabinet::find()->where(["cabinet_type_id"=>$id])->andWhere(["status"=>1])->asArray()->all();
        return $data;
    }
    /**
     * 后台 -  柜子管理  - 查询各个类型的柜子 总共数量，已租数量
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/6/4
     * @param  $id
     * @param  $type
     * @return array
     */
    public function getAllCabinetData($id,$type){
        $data =CabinetType::find()
            ->joinWith(["cabinet"=>function($query){
                 $query->select("cloud_cabinet.cabinet_type_id,cloud_cabinet.status,cloud_cabinet.status,cloud_cabinet.id")->where(['cloud_cabinet.cabinet_type'=>2]);
            }])
            ->select("cloud_cabinet_type.id,
                   cloud_cabinet_type.type_name,
                   COUNT(cloud_cabinet.id) as countLike")
            ->groupBy("cloud_cabinet_type.id")
            ->asArray();
         $query  = $this->getSearch($data,$id,$type);
         $query  = $query->all();
         $data = $this->countNum($query);
         return $data;
    }
    /**
     * 后台 -  柜子管理  - 根据场馆人员身份实时搜索
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/6/13
     * @param  $query
     * @param  $id      //对应type的 场馆id
     * @param  $type    // company 或 venue
     * @return array
     */
    public function getSearch($query,$id,$type){
            if(isset($type) && $type == 'company'){
                $query = $query->andFilterWhere(['cloud_cabinet_type.company_id'=>$id]);
            }
            if(isset($type) && $type == 'venue'){
                $query = $query->andFilterWhere(['cloud_cabinet_type.venue_id'=>$id]);
            }
        return $query;
    }
    /**
     * 后台 -  柜子管理  - 查询各个类型的柜子 总共数量，已租数量（数据整理）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/6/4
     * @param
     * @return array
     */
    public function countNum($data){
        foreach ($data as $keys=>$values){
            $data[$keys]["cabinetNum"] = count($values["cabinet"]);
            $data[$keys]["is_rent"]     = $this->isRent($values["cabinet"]);
        }
        return $data;
    }
    /**
     * 后台 -  柜子管理  - 查询各个类型的柜子 总共数量，已租数量（数据整理）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/6/4
     * @param
     * @return array
     */
    public function isRent($data){
        $is_rent =0;
        if(!empty($data)){
            foreach($data  as $keys=>$values){
                if($values["status"]==2 || $values["status"]==4){
                    $is_rent+=1;
                }
            }
        }
        return $is_rent;
    }
    /**
     * 后台 -  新柜子管理  - 退换金额计算
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/6/5
     * @param $data
     * @return array
     */
    public function quiteMoney($data)
    {
       if( $data["quiteRent"]=="now"){
              $quiteRent = time();
       }else{
              $quiteRent = strtotime( $data["quiteRent"]);
       }
        $quiteTime  = $data["endRent"] - $quiteRent;
        $quiteMonth = floor($quiteTime/(60*60*24*30));

        $moneyStamp = 60 * 60 * 24 * 30;
        $dayS       = round(($quiteTime -($quiteMonth * $moneyStamp))/(60*60*24));
        $monthMoney = 0;
        $yearMoney  = 0;
        $dayMoney   = 0;
        if($quiteMonth!=0){
            if($quiteMonth<12&&$quiteMonth>=1){
                 $monthMoney = $quiteMonth * $data["monthRentPrice"];
            }
            if($quiteMonth>=12){
                 $yearMoney   = floor($quiteMonth/12);
                 $monthMoney  = $quiteMonth%12;
            }
        }else{
                 $dayS        = round($quiteTime/(60*60*24));
        }
                 $dayMoney    = $dayS * $data["dayRentPrice"];
                 $money =(int)$monthMoney+(int)$yearMoney+(int)$dayMoney;
                 return $money;
    }
    /**
     * 后台 -  新柜子管理  - 柜子状态 冻结
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/6/5
     * @param $cabinetId
     * @param $status
     * @return array
     */
    public function frozenCabinet($cabinetId,$status){
        $model = \common\models\base\Cabinet::findOne(["id"=>$cabinetId]);
        if(empty($status)){
            $model->status = 4;
        }else{
            $model->status = 2;
        }
        if($model->save()){
            return $model->status == 4 ? "frozen" : 'unfreeze';
        }else{
            return $model->errors;
        }
    }
    /**
     * 后台 -  新柜子管理  - 根据手机号搜索会员信息
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/6/5
     * @param $phone    //搜索电话号码
     * @param $id       //搜索id
     * @param $type      //搜索权限级别
     * @return array
     */
    public  function searchMember($phone,$id,$type){
        $data  = Member::find()->joinWith(["memberDetails"],false)
                   ->select("cloud_member.*, 
                          cloud_member.mobile,
                          cloud_member_details.name,                      
                          cloud_member_details.pic,                      
                          cloud_member_details.member_id,
                          ")
                   ->where(['or',["cloud_member.mobile"=>$phone],['cloud_member.id'=>$phone]])->asArray();
        if(isset($type) && $type == 'company'){
            $data = $data->andFilterWhere(['cloud_member.company_id'=>$id]);
        }
        if(isset($type) && $type == 'venue'){
            $data = $data->andFilterWhere(['cloud_member.venue_id'=>$id]);
        }
        $query = $data->one();
        return $query;
    }
    /**
     * 后台 -  新柜子管理  - 修改指定柜子的状态
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/6/8
     * @param $cabinetId    //柜子id
     * @param $rentStatus   //柜子租用状态
     * @return array
     */
    public function updateCabinetStatus($cabinetId,$rentStatus){
           $model = Cabinet::findOne(["id"=>$cabinetId]);
           if($rentStatus=="rentCabinet"){
               $model->status = 2;
           }else{
               $model->status = 1;
           }
           if(!$model->save()){
               return $model->errors;
           }else{
               return "success";
           }
    }

    /**
     * 后台 -  新柜子管理  -  删除指定的柜子
     * @author Houkaixin<houkaixin@itsports.club>
     * @create 2017/11/15
     * @param $id   //柜子id
     * @return array
     */
    public function deleteCabinet($id){
           $result = Cabinet::findOne($id);
           if(empty($result)){
             return false;
           }
           $result->delete();
           return true;
    }
    /**
     * 后台 -  新柜子管理  -  删除指定柜子类型
     * @author Houkaixin<houkaixin@itsports.club>
     * @create 2017/11/16
     * @param $id   //柜子类型id
     * @return boolean
     */
    public function deleteCabinetType($id){
         // 删除柜子类型
         $deleteCabinetType = CabinetType::findOne($id);
         if(empty($deleteCabinetType)){
               return "柜子类型不存在";
         }
        // 查询该柜子类型是否有已租用的柜子
         $searchIsRentedNum = $this->searchIsRented($id);
         if($searchIsRentedNum>0){
              return "还有柜子被租用呢";
         }
          // 1删除柜子类型
           $deleteCabinetType->delete();
          // 2:删除相关柜子
           Cabinet::deleteAll(["cabinet_type_id"=>$id]);
           return true;
    }
    /**
     * 后台 -  新柜子管理  -  查询是否有柜子被租用
     * @author Houkaixin<houkaixin@itsports.club>
     * @create 2017/11/16
     * @param $id   //柜子类型id
     * @return boolean
     */
    public function searchIsRented($id)
    {
       $num = Cabinet::find()
                ->where(["and",["cabinet_type_id"=>$id],["status"=>2]])
                ->count();
       return  $num;
    }
    /**
     * 后台 -  新柜子管理  -  对柜子数据二次处理
     * @author Houkaixin<houkaixin@itsports.club>
     * @create 2017/11/16
     * @param $data   //对应柜子数据
     * @return boolean
     */
    public function dealCabinet($data){
       if(!empty($data)){
           foreach($data as $keys=>$value){
               unset($data[$keys]["cabinet"]);
           }
       }
       return $data;
    }
    /**
     * 后台 -  新柜子管理  - 获取单个柜子多月设置
     * @author yanghuilei<yanghuilei@itsports.club>
     * @create 2018/1/6
     * @param $cabinetId //柜子类型id
     * @return  boolean || array
     */
    public function getCabinetMonth($cabinetId)
    {
        $model = self::find()
                  ->select([
                   'cabinet_month',
                   'cabinet_money',
                   'cabinet_dis',
                   'give_month'
                   ])
                  ->where(['id' => $cabinetId])
                  ->asArray()
                  ->one();
        return $model ? $model : false;
    }

    /**
     * @desc: 更柜管理-获取大区域列表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/02/11
     * @param $venueId
     * @return $this|array
     */
    public function getAllCabinetList($venueId){
        $data =CabinetType::find()
            ->joinWith(["cabinet"=>function($query){
                $query->select("cloud_cabinet.cabinet_type_id,cloud_cabinet.status,cloud_cabinet.status,cloud_cabinet.id");
            }])
            ->select("cloud_cabinet_type.id,
                   cloud_cabinet_type.type_name,
                   COUNT(cloud_cabinet.id) as countLike")
            ->groupBy("cloud_cabinet_type.id")
            ->asArray();
        $query  = $this->setSearchWhere($data,$venueId);
        $query  = $query->all();
        $data = $this->countNum($query);
        return $data;
    }

    /**
     * @desc: 更柜管理-区域列表搜索条件
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/02/11
     * @param $data
     * @param $venueId
     * @return mixed
     */
    private function setSearchWhere($data,$venueId) {
        if (isset($venueId) && $venueId != null && $venueId != '' && $venueId != 'undefined') {
            $query = $data->andFilterWhere(['cloud_cabinet_type.venue_id'=>$venueId]);
        } else {
            $roleId             =   \Yii::$app->user->identity->level;
            if($roleId == 0){
                $vId            =    Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
                $venueIds       =    array_column($vId, 'id');
            }else{
                //拿到用户有权限查看的场馆
                $venuesId       =    Auth::findOne(['role_id' => $roleId])->venue_id;
                $authId         =    json_decode($venuesId);
                //去掉组织架构里面设置"不显示"的场馆id
                $venues         =    Organization::find()->where(['id'=>$authId])->select(['id','name'])->asArray()->all();
                $venueIds       =    array_column($venues, 'id');
            }
            $query = $data->andFilterWhere(['cloud_cabinet_type.venue_id'=>$venueIds]);
        }
        return $query;
    }
}