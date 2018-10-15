<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/3 0003
 * Time: 上午 9:36
 */
namespace backend\models;
use common\models\base\Classroom;
use Yii;
use common\models\Func;
use common\models\relations\CourseRelations;

/**
 * @云运动 - 后台 - 继承模型
 * @author Zhu Mengke <zhumengke@itsports.club>
 * @create 2017/3/29
 * @inheritdoc
 */
class Organization extends \common\models\base\Organization
{
    use CourseRelations;
    public $sorts;
    public $startTime;
    public $topSearch;
    public $endTime;
    public $ids;               //顶级公司id
    public $departmentId;     //场馆id
    public $venueId;
    public $companyId;
    public $departId;
    public $nowBelongId;
    public $nowBelongType;

    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';

    //常量定义
    const START_TIME               = "startTime";
    const END_TIME                 = "endTime";
    const TOP_SEARCH               = "topSearch";
    const ORGAN_CREATED_AT         ="cloud_organization.created_at";
    const SORT_TYPE                = "sortType";
    const CREATED_AT               = "created_at";
    const STYLE                     ="style";
    const VEN                      = 'venueId';
    const COM                      = 'companyId';
    const DEP                      = 'departId';

    /**
     * @云运动 - 组织架构表 - 查询数据
     * @author Zhu Mengke <zhumengke@itsports.club>
     * @create 2017/3/29
     * @inheritdoc
     */
    public static function getCloudOrganization()
    {
        $model = Organization::find()->where([self::STYLE => 3])->asArray()->all();
        return $model;
    }
    public static function getOrganizationVenue($id,$type)
    {
        $model = Organization::find()->alias('or') ->joinWith(['organization org'])->where(['or.'.self::STYLE  => 2])->asArray();
        if(isset($type) && $type == 'company'){
            $model = $model->andFilterWhere(['org.pid'=>$id]);
        }
        if(isset($type) && $type == 'venue'){
            $model = $model->andFilterWhere(['or.pid'=>$id]);
        }
        if(isset($type) && $type == 'department'){
            $model = $model->andFilterWhere(['or.id'=>$id]);
        }
        $model = $model->all();
        return $model;
    }
    /**
     * 云运动 - 添加教练 - 获取场馆信息
     * @return string
     * @author Huang Pengju <huangpengju@itsports.club>
     * @param $id
     * @param $type
     * @param $status
     * @param $companyId
     * @param $identity
     * @update 2017-4-21
     */
    public function getOrganizationOption($id,$type,$status=null,$companyId=null,$identity = null)
    {
        $venue = Organization::find()
            ->where([self::STYLE=>2])
            ->andFilterWhere(['identity'=>$identity])
            ->asArray();
        if(!empty($status)){
            $venue =  $this->filterMemberCard($venue,$companyId);   // 针对卡种场馆筛选
        }else{
            $venue =  $this->filter($venue,$id,$type);             // 针对卡种以外的场馆筛选
//            $venue = $venue->andFilterWhere(['pid'=>$companyId]);
//            $venue = $venue->all();
        }
        return $venue;
    }
    /**
     * 云运动 - 添加教练 - 获取场馆信息
     * @return string
     * @author houkaixin <houkaixin@itsports.club>
     * @param $venue       // 查询场馆sql语句
     * @param $companyId  // 公司id
     * @update 2017-4-21
     */
    public function filterMemberCard($venue,$companyId){
         $allCompanyId   = [$companyId];
         // 获取公司联盟下的所有公司
         $allCompanyData = ApplyRecord::find()->where(["and",["apply_id"=>$companyId],[">","end_apply",time()],["status"=>1]])
                         ->select(["be_apply_id"])
                         ->asArray()->all();
         if(!empty($allCompanyData)){
             foreach($allCompanyData as $keys=>$value){
                 $allCompanyId[] = $value["be_apply_id"];
             }
         }
         $venue = $this->filterVenue($venue,$allCompanyId);
         return $venue;
    }
    /**
     * 云运动 - 后台- 筛选指定公司下的场馆
     * @author houkaixin <houkaixin@itsports.club>
     * @param $venue         // 查询场馆sql语句
     * @param $allCompanyId // 联盟所有公司id
     * @return string     // 返回指定公司下的场馆
     * @update 2017-7-6
     */
    public function filterVenue($venue,$allCompanyId){
       $allCompanyId = array_unique($allCompanyId);
       $venue = $venue->andFilterWhere(["in","pid",$allCompanyId])->all();
       return $venue;
    }



    /**
     * 云运动 - 添加教练 - 获取场馆信息
     * @return string
     * @author houkaixin <houkaixin@itsports.club>
     * @param $venue   // 查询场馆sql语句
     * @param $id      // 身份id
     * @param $type   //身份类型鉴别
     * @update 2017-4-21
     */
    public function filter($venue,$id,$type){
        // 如果场馆信息不为空 增加帅选
        if(isset($type) && $type == 'venue'|| $type == 'department'){
            $venue = $venue->andFilterWhere(['id'=>$id]);
        }
        if(isset($type) && $type == 'company'){
            $venue = $venue->andFilterWhere(['pid'=>$id]);
        }
        if(isset($type) && $type == 'apply'){
            $venue = $venue->andFilterWhere(['in','pid',$id]);
        }
        return $venue->all();
    }

    /**
     * 云运动 - 添加教练 - 获取公司信息
     * @return string
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @param $id   // 公司id
     * @update 2017-5-9
     */
    public function getOrganizationCompany($id)
    {

        $company = Organization::find()
            ->where([self::STYLE=>1])
            ->asArray();
        if(!empty($id)){
            $company =  $company->andFilterWhere(["in",'id',$id]);
        }

        return $company->all();
    }
    /**
     * 云运动 - 添加教练 - 获取部门信息
     * @return string
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @param $id
     * @param $type
     * @update 2017-5-9
     */
    public function getOrganizationDepartment($id,$type)
    {
        $department = Organization::find()
            ->alias('or')
            ->joinWith(['organization org'])
            ->where(['or.'.self::STYLE=>3])
            ->asArray();
        if(isset($type) && $type == 'company'){
            $department = $department->andFilterWhere(['org.pid'=>$id]);
        }
        if(isset($type) && $type == 'venue'){
            $department = $department->andFilterWhere(['or.pid'=>$id]);
        }
        if(isset($type) && $type == 'department'){
            $department = $department->andFilterWhere(['or.id'=>$id]);
        }
        $department = $department->all();
        return $department;
    }
    /**
     * 云运动 - 角色管理 - 分配员工获取部门信息
     * @return string
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @param $id
     * @param $type
     * @param $companyId
     * @update 2017-5-9
     */
    public function getOrganizationDepartmentDate($id,$type,$companyId)
    {
        $department = Organization::find()
            ->alias('or')
            ->joinWith(['organization org'])
            ->where(['or.'.self::STYLE=>3])
            ->andFilterWhere(['org.pid'=>$companyId])
            ->asArray();
        if(isset($type) && $type == 'company'){
            $department = $department->andFilterWhere(['org.pid'=>$id]);
        }
        if(isset($type) && $type == 'venue'){
            $department = $department->andFilterWhere(['or.pid'=>$id]);
        }
        if(isset($type) && $type == 'department'){
            $department = $department->andFilterWhere(['or.id'=>$id]);
        }
        $department = $department->all();
        return $department;
    }
    /**
     * 云运动 - 添加教练 - 获取部门信息
     * @return string
     * @author Huang Pengju <huangpengju@itsports.club>
     * @param $venueId
     * @param $requestType //请求类型
     * @create 2017-4-21
     */
    public function getOrganizationData($venueId,$requestType="")
    {
        $data = Organization::find()
            ->where(['pid'=>$venueId])
            ->andWhere(['is_allowed_join'=>1]);
        if(!empty($requestType)){
            $data = $data->select("id,name");
        }
            $data = $data->asArray()->all();
        return $data;
    }
    /**
     * 云运动 - 添加教练 - 获取一级场馆部门信息
     * @author houkaixin <houkaixin@itsports.club>
     * @return array      //返回所有pid=0的所有场馆信息
     * @create 2017-4-22
     */
    public static function getTopOrganization()
    {
        $model = Organization::find()->where(['pid' => 0])->asArray()->all();
        return $model;
    }


    /**
     * 后台 - 组织架构管理 - 数据遍历
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/24
     * @param
     * @return object   //返回分页数据对象
     */
    public function getData($params)
    {
        $this->customLoad($params);
        //查询所有的公司
        $this->searchCompany();
        $query = Organization::find()->joinWith(["admin"])
               ->select("cloud_organization.*,cloud_admin.username")
               ->orderBy($this->sorts)
               ->where(["cloud_organization.style"=>2])
               ->asArray();
        $query = $this->getSearchWhere($query);
        $data  = Func::getDataProvider($query,8);
        //整理所有的数据 将上级部门放入数据中
        $data->models  = $this->dataArrange($data->models);
        return $data;
    }
    /**
     * 后台 - 组织架构管理- 场馆 - 按照公司进行搜索（查询所有顶级公司的id，模糊查询）
     * @create 2017/5/2
     * @author houkaixin<houkaixin@itsports.club>
     * @param
     */
    public function searchCompany(){
        $id = [];
        $data = Organization::find()->where([self::STYLE=>1])->asArray()->all();
        if($this->topSearch!=null){
            foreach($data  as $keys=>$values){
                $resultOne   = preg_match_all("/$this->topSearch/",$values["name"]);
                $resultTwo   = preg_match_all("/$this->topSearch/",$values["name"]);
                $resultThree = preg_match_all("/$this->topSearch/",$values["name"]);
                if($resultOne==0&&$resultTwo==0&&$resultThree==0){
                    unset($data[$keys]);
                }else{
                    $id[]=$data[$keys]["id"];
                }
            };
        }
             $this->ids = $id;
    }

    /**
     * 后台 - 场地管理 - 公司 - 根据名字搜索公司
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/9/13
     * @param
     * @return object   //返回分页数据对象
     */
    public function companyLoad($data)
    {
        $this->topSearch  = (isset($data['topSearch']) && !empty($data['topSearch']))? $data['topSearch'] : null;
        return true;
    }


    /**
     * 后台 - 组织架构管理 - 处理前台发送过来的搜索参数数据时
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/24
     * @param
     * @return object   //返回分页数据对象
     */
    public function customLoad($data)
    {
        $roleId             =   \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId            =    Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds       =    array_column($vId, 'id');
        }else{
            //拿到用户有权限查看的场馆
            $venuesId       =    Auth::findOne(['role_id' => $roleId])->venue_id;
            $authId         =    json_decode($venuesId);
            //去掉组织架构里面设置"不显示"的场馆id
            $venues         =    Organization::find()->where(['id'=>$authId])->andWhere(['is_allowed_join'=>1])->select(['id','name'])->asArray()->all();
            $venueIds       =    array_column($venues, 'id');
        }
        $this->venueId     = (isset($data[self::VEN]) && !empty($data[self::VEN])) ? $data[self::VEN] : $venueIds;
        $this->startTime   = (isset($data[self::START_TIME]) && !empty($data[self::START_TIME])) ? strtotime($data[self::START_TIME]) : null;
        $this->endTime     = (isset($data[self::END_TIME])   && !empty($data[self::END_TIME])) ? strtotime($data[self::END_TIME]) : null;
        $this->topSearch   = (isset($data[self::TOP_SEARCH]) && !empty($data[self::TOP_SEARCH])) ? $data[self::TOP_SEARCH] : null;
//        $this->venueId     = (isset($data[self::VEN]) && !empty($data[self::VEN])) ? $data[self::VEN] : null;
        $this->companyId     = (isset($data[self::COM]) && !empty($data[self::COM])) ? $data[self::COM] : null;
        $this->departId     = (isset($data[self::DEP]) && !empty($data[self::DEP])) ? $data[self::DEP] : null;
        $this->nowBelongId  = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType= (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->sorts       = self::loadSort($data);
        return true;
    }
    /**
     * 后台 - 组织架构管理 - 对各个字段的排序
     * @create 2017/4/24
     * @author houkaixin<houkaixin@itsports.club>
     * @param $data  array //前台获取的排序处理数据
     * @return array
     */
    public static function loadSort($data)
    {
        $sorts = [self::ORGAN_CREATED_AT => SORT_DESC];
        if(!isset($data[self::SORT_TYPE ])){ return $sorts;}
        switch ($data[self::SORT_TYPE ]){
            case 'departmentName'  :
                $attr = '`cloud_organization`.name';
                break;
            case 'BelDepartmentName'  :
                $attr = '`cloud_organization`.pid';
                break;
            case 'params':
                $attr = '`cloud_organization`.params';
                break;
            case 'create_id':
                $attr = '`cloud_admin.username`.create_id';
                break;
            case self::CREATED_AT:
                $attr = '`cloud_organization`.created_at';
                break;
            case 'update_at' :
                $attr = '`cloud_organization`.update_at';
                break;
            case "companyName" :
                $attr = '`cloud_organization`.name';
                break;
            case "companyId" :
                $attr = '`cloud_organization`.id';
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
     * 后台 - 组织架构管理- 获取最终排序规则
     * @create 2017/4/24
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
     * 后台 - 组织架构管理- 获取指定场馆的数据
     * @create 2017/4/24
     * @author houkaixin<houkaixin@itsports.club>
     * @param $id
     * @return array
     */
    public static function getOrganizationById($id)
    {
        $model = Organization::find()->where(['id' => $id])->asArray()->one();
        return $model;
    }

    /**
     * 后台 - 组织架构管理 - 执行搜索数据过滤
     * @create 2017/4/24
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $query  //后台的sql语句
     * @return  mixed
     */
    public function getSearchWhere($query)
    {
        $query->andFilterWhere([
            'or',
            ['like','cloud_organization.name', $this->topSearch],
            ['like','cloud_admin.username',$this->topSearch]
        ]);

        $query->andFilterWhere([
            'and',
            ['>=',self::ORGAN_CREATED_AT,$this->startTime],
            ['<=',self::ORGAN_CREATED_AT,$this->endTime]
        ]);
        $query->orFilterWhere([
            'in','cloud_organization.pid',$this->ids,
        ]);
        $query->andFilterWhere([
            'and',
            ['cloud_organization.id'=>$this->venueId ]
        ]);
        $query->andFilterWhere([
            'and',
            ['cloud_organization.id'=>$this->companyId ]
        ]);
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['cloud_organization.pid'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && $this->nowBelongType == 'venue'){
//            $query->andFilterWhere(['cloud_organization.id'=>$this->nowBelongId]);
//        }
//        if(isset($this->nowBelongType) && $this->nowBelongType == 'apply'){
//            $query->andFilterWhere(['in','pid',$this->nowBelongId]);
//        }
        return $query;
    }

    /**
     * 后台 - 组织架构管理 - 将上级部门数据整理到数组里边
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/24
     * @param $data
     */
    public function dataArrange($data)
    {
        foreach($data  as $keys=>$values){
                if($values["pid"]==0){
                    $data[$keys]["superior"] = "顶级部门";
                }else{
                    $superior =Organization::findOne($values["pid"])["name"];
                    $data[$keys]["superior"] = $superior;
                }
        }
       return $data;
    }
    /**
     * 后台 - 组织架构管理 - 删除指定数据
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/24
     * @param $id
     * @return boolean   //返回删除数据的结果
     */
    public function getDel($id){
        $result = Organization::findOne($id);
        $deleteWhere         = Employee::find()->where(["organization_id"=>$id])->asArray()->all();
        if(empty($deleteWhere)){
            $delResult = $result->delete();
        }else{
            return false;
        }
        if ($delResult) {
            return true;
        } else {
            return "delError";
        }
    }
    /**
     * 后台 - 组织架构管理 - 获取场馆所有顶级分类信息
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/24
     * @param $id
     * @param $type
     * @return array //返回所有顶级分类信息
     */
  public function getTopData($id,$type){
        $data = Organization::find()->where([self::STYLE=>1])->asArray();
      if(isset($type) && $type == 'company'){
          $data = $data->andFilterWhere(['cloud_organization.id'=>$id]);
      }
      if(isset($type) && $type == 'venue'){
          $data = $data->andFilterWhere(['cloud_organization.id'=>$id]);
      }
      if(isset($type) && $type == 'apply'){
          $data = $data->andFilterWhere(['in','cloud_organization.id',$id]);
      }
      $data = $data->all();
        return $data;
  }
    /**
     * 后台 - 组织架构管理 - 公司（获取分页数据）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/25
     * @param $params
     * @return object   //返回分页数据对象
     */
    public function getMyData($params)
    {
        $this->companyLoad($params);
        $roleId         =  \Yii::$app->user->identity->level;
        if($roleId == 0){
            $comId      =   Organization::find()->select('id')->where(['style'=>1])->asArray()->all();
            $adComId    =   array_column($comId, 'id');
        }else{
            $ComId      =   Auth::findOne(['role_id' => $roleId])->company_id;
            $companyId  =   json_decode($ComId);
        }
        $companyIds     =   (isset($adComId) && !empty($adComId)) ? $adComId : $companyId;
        $query = Organization::find()
            ->joinWith(["admin"])
            ->select("cloud_organization.*,cloud_admin.username")
            ->orderBy($this->sorts)
            ->where(["cloud_organization.style"=>1])
            ->andWhere(['cloud_organization.id' => $companyIds])
            ->asArray();
        $query = $this->getSearchCompanyWhere($query);
        $data  = Func::getDataProvider($query, 8);
        //整理所有的数据 将上级部门放入数据中
        return $data;
    }
    /**
     * 后台 - 组织架构管理 -公司 （ 删除指定数据）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/26
     * @param $id
     * @return boolean   //返回删除数据的结果
     */
    public function getDelCompany($id){
        $result = Organization::findOne($id);
        $venue  = Organization::find()->where(["pid"=>$id])->asArray()->all();
        if(empty($venue)){
            $delResult = $result->delete();
        }else{
            return false;
        }
        if ($delResult) {
            return true;
        } else {
            return "delError";
        }
    }

    /**
     * 后台 - 组织架构管理 - 公司 - 执行搜索数据过滤
     * @create 2017/4/26
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $query  //后台的sql语句
     * @return  mixed
     */
    public function getSearchCompanyWhere($query)
    {
        $query->andFilterWhere(['like','cloud_organization.name', $this->topSearch]);
//        $query->andFilterWhere([
//            'and',
//            ['>=',self::ORGAN_CREATED_AT,$this->startTime],
//            ['<=',self::ORGAN_CREATED_AT,$this->endTime]
//        ]);
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['cloud_organization.id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue')){
//            $query->andFilterWhere(['cloud_organization.id'=>$this->nowBelongId]);
//        }
        return $query;
    }

    /**
     * 后台 - 组织架构管理 - 获取对应场馆下的公司
     * @create 2017/4/26
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $id   //获取对应场馆id
     * @return  mixed
     */
    public function  getRelevantVenue($id){
        $data =  Organization::find()->where(["pid"=>$id])->asArray()->all();
        return  $data;
    }
    /**
     * 后台 - 组织架构管理 - 获取指定公司下的场馆
     * @create 2017/4/27
     * @author houkaixin<houkaixin@itsports.club>
     * @param $pid   //指定公司下的id
     * @return  object
     */
   public function  getVenueData($pid){
       $data = Organization::find()->where(["pid"=>$pid])->asArray()->all();
       return $data;
   }
    /**
     * 后台 - 组织架构管理 - 数据遍历（获取所有部门信息）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/28
     * @param  $params   //搜索和排序参数
     * @return object   //返回分页数据对象
     */
   public function getMyDepData($params){
       $this->customLoads($params);
       $this->superiorDepartment();      //搜集查询部门id
       $query = Organization::find()
              ->alias('or')
              ->joinWith(['organization org'])
              ->orderBy($this->sorts)
              ->where(['or.'.self::STYLE=>3])
              ->asArray();
       $query           =    $this->getSearchDepWhere($query);
       $data            =    Func::getDataProvider($query,8);
       //整理所有的数据 将上级部门放入数据中
       $data->models   = $this->dataArrangement($data->models);
       return $data;
   }
    /**
     * 后台 - 组织架构管理- 按照公司进行搜索（查询所有公司的id，模糊查询）
     * @create 2017/5/5
     * @author houkaixin<houkaixin@itsports.club>
     * @param
     */
    public function  getSearchTopWhere(){
        $id = [];
        $data = Organization::find()->where([self::STYLE=>1])->asArray()->all();
        if($this->topSearch!=null){
            foreach($data  as $keys=>$values){
                $resultOne   = preg_match_all("/$this->topSearch/",$values["name"]);
                $resultTwo   = preg_match_all("/$this->topSearch/",$values["name"]);
                $resultThree = preg_match_all("/$this->topSearch/",$values["name"]);
                if($resultOne==0&&$resultTwo==0&&$resultThree==0){
                    unset($data[$keys]);
                }else{
                    $id[]=$data[$keys]["id"];
                }
            };
        }
        $this->ids = $id;
    }
    /**
     * 后台 - 组织架构管理- 按照部门进行搜索（查询所有场馆的id，模糊查询）
     * @create 2017/5/2
     * @author houkaixin<houkaixin@itsports.club>
     * @param
     */
    public function  superiorDepartment(){
        $id = [];
        $data = Organization::find()->where([self::STYLE=>2])->asArray()->all();
        if($this->topSearch!=null){
            foreach($data  as $keys=>$values){
                $resultOne   = preg_match_all("/$this->topSearch/",$values["name"]);
                $resultTwo   = preg_match_all("/$this->topSearch/",$values["name"]);
                $resultThree = preg_match_all("/$this->topSearch/",$values["name"]);
                if($resultOne==0&&$resultTwo==0&&$resultThree==0){
                    unset($data[$keys]);
                }else{
                    $id[]=$data[$keys]["id"];
                }
            };
        }
        $this->departmentId = $id;
    }

    /**
     * 后台 - 组织架构管理 - 整理部门数据信息
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/28
     * @param  $data   //传过来的分页数据
     * @return object
     */
    public function dataArrangement($data){
        if(isset($data) && !empty($data) && $data){
            foreach($data as $keys=>$values){
                $path                          = explode(",",json_decode($values["path"]));
                if(isset($path) && !empty($path) && is_array($path) && isset($path[1]) && isset($path[2])) {
                    $company = Organization::find()->where(["id" => $path[1]])->select("name,id")->asArray()->one();
                    $venue   = Organization::find()->where(["id" => $path[2]])->select("name,id")->asArray()->one();
                    $data[$keys]["beCompanyId"] = $company["id"];
                    $data[$keys]["beCompanyName"] = $company["name"];
                    $data[$keys]["beVenueId"]     = $venue["id"];
                    $data[$keys]["beVenueName"]   = $venue["name"];
                }else{
                    $data[$keys]["beCompanyId"]   = 0;
                    $data[$keys]["beCompanyName"] = '顶级部门';
                    $data[$keys]["beVenueId"]     =  0;
                    $data[$keys]["beVenueName"]   = '顶级部门';
                }
            }
        }
        return $data;
    }
    /**
     * 后台 - 组织架构管理 - 部门信息进行搜索
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/28
     * @param
     * @return object
     */
   public  function getSearchDepWhere($query){
       if(!$this->venueId){
           $this->venueId = -1;
       }
       $query->andFilterWhere([
           'or',
           ['like','or.name',$this->topSearch],
           ['like','org.name',$this->topSearch]
       ]);
       $query->andFilterWhere(['or.pid'=>$this->venueId]);
       return $query;
   }
    /**
     * 后台 - 组织架构管理 - 部门 - 顶级框内的搜索
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/28
     * @param $data
     * @return object
     */
  public  function getSearchContent($data){
          if($this->topSearch!=null){
              foreach($data  as $keys=>$values){
                  $resultOne   = preg_match_all("/$this->topSearch/",$values["beCompanyName"]);
                  $resultTwo   = preg_match_all("/$this->topSearch/",$values["beVenueName"]);
                  $resultThree = preg_match_all("/$this->topSearch/",$values["name"]);
                  if($resultOne==0&&$resultTwo==0&&$resultThree==0){
                      unset($data[$keys]);
                  }
              };
              return $data;
          }else{
              return $data;
          }
  }
    /**
     * 后台 - 组织架构管理 - 处理前台发送过来的搜索参数数据时
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/28
     * @param
     * @return object   //返回分页数据对象
     */
    public function customLoads($data)
    {
        $roleId             =   \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId            =    Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds       =    array_column($vId, 'id');
        }else{
            //拿到用户有权限查看的场馆
            $venuesId       =    Auth::findOne(['role_id' => $roleId])->venue_id;
            $authId         =    json_decode($venuesId);
            //去掉组织架构里面设置"不显示"的场馆id
            $venues         =    Organization::find()->where(['id'=>$authId])->andWhere(['is_allowed_join'=>1])->select(['id','name'])->asArray()->all();
            $venueIds       =    array_column($venues, 'id');
        }
        $this->venueId       = (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->topSearch     = (isset($data['topSearch']) && !empty($data['topSearch'])) ? $data['topSearch'] : null;
        $this->sorts         = self::loadSorts($data);
        return true;
    }
    /**
     * 后台 - 组织架构管理 - 对各个字段的排序
     * @create 2017/4/28
     * @author houkaixin<houkaixin@itsports.club>
     * @param $data  array //前台获取的排序处理数据
     * @return array
     */
    public static function loadSorts($data)
    {
        $sorts = ['or.'.self::CREATED_AT => SORT_DESC];
        if(!isset($data[self::SORT_TYPE])){ return $sorts;}
        switch ($data[self::SORT_TYPE]){
            case 'name'  :
                $attr = 'or.name';
                break;
            case 'code'  :
                $attr = 'or.code';
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
     * 后台 - 组织架构管理 -公司 （ 删除指定数据）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/26
     * @param $id
     * @return boolean   //返回删除数据的结果
     */
    public function delMyResult($id){
        $result      = Organization::findOne($id);
        $department  = Organization::find()->where(["pid"=>$id])->asArray()->all();  //查询下面是否有部门
        $classroom   = Classroom::find()->where(["venue_id"=>$id])->asArray()->all();  //查询场馆下是否有教室                                                                           //查询场馆下面是否有教室
        if(empty($department)&&empty($classroom)){
            $delResult = $result->delete();
        }else{
            return false;
        }
        if ($delResult) {
            return true;
        } else {
            return "delError";
        }
    }
    /**
     * 后台 - 柜子管理 - 获取所有我爱运动 下面的场馆
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/4
     * @param
     * @return array //所有我爱运动下面的场馆信息
     */
    public function getTheVenueData(){
        $data        = Organization::find()->where(["name"=>"我爱运动"])->asArray()->one();
        $cabinetData = Organization::find()->where(["pid"=>$data["id"]])->asArray()->all();
        return  $cabinetData;
    }
    /**
     * 后台 - 组织架构管理-  获取场馆详情
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/4/26
     * @param
     * @return object   //返回修改公司的结果
     */
     public static function getVenueDetailDataOne($id)
     {
         $data =  Organization::find()
             ->alias('or')
             ->joinWith(['admin admin'])
             ->where(['or.id'=>$id])->asArray()->one();
         if($data){
             $data['pName'] = Organization::find()->where(['id'=>$data['pid']])->asArray()->one();
         }
         return $data;
     }
    /**
     * 后台 - 组织架构管理-  获取组织单条详情
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/4/26
     * @param
     * @return object   //返回修改公司的结果
     */
    public static function getVenueOne($id)
    {
        $data =  Organization::find()->where(['id'=>$id])->asArray()->one();
        return $data;
    }
    /**
     * 后台 - 组织架构管理-  根据名称获取场馆
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/4/26
     * @param
     * @return object   //返回修改公司的结果
     */
    public static function getVenueOneDataByLikeName()
    {
        $organ   = Organization::find()->where(["like","name","迈步"])->asArray()->one();
       return  Organization::find()->where(['style'=>2,'pid'=>isset($organ['id'])?$organ['id']:0])->asArray()->all();//获取场馆id
    }

    /**
     * 组织架构场馆 - 场馆是否能查看 - 查看状态修改
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/30
     * @param $id
     * @return bool
     */
    public static function getUpdateStatus($id)
    {
        $organization  =  \common\models\base\Organization::findOne($id);

        if($organization->is_allowed_join == null){

            $organization->is_allowed_join = 1;
        }elseif($organization->is_allowed_join == 1) {

            $organization->is_allowed_join = 2;
        }else{

            $organization->is_allowed_join = 1;
        }

        if($organization->save()){
            return true;
        }else{
            return $organization->errors;
        }
    }
    /**
     * 后台 - 组织架构管理-  根据名称获取场馆
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/4/26
     * @param
     * @return object   //返回修改公司的结果
     */
    public static function getVenueIdByName($name)
    {
        return Organization::find()->where(["like","name",$name])->andWhere(['style'=>2])->asArray()->one();
    }
    /**
     * 后台 - 潜在会员管理 - 获取指定级别的所有场馆和公司
     * @create 2017/7/14
     * @param $type          // 所属类别
     * @param $companyId     // 公司id
     * @return object   // 返回指定身份的所有所属信息
     */
    public function getAllIdentifyData($type,$companyId){
         $company = \common\models\base\Organization::find()->where(["style"=>1]);
         if(empty($type)){
             $company = $company->asArray()->all();
             $company["identify"] = "admin";
         }else{
             $company = $company->andWhere(["id"=>$companyId])->asArray()->all();
             $company["identify"] = " manager";
         }
         return $company;
    }
    /**
     * 后台 - 潜在会员管理- 获取指定公司下的场馆
     * @create 2017/7/14
     * @param $companyId     // 公司id
     * @return object   // 返回指定身份的所有所属信息
     */
    public function getAllVenue($companyId){
         $data = \common\models\base\Organization::find()->where(["and",["style"=>2],["pid"=>$companyId]])->asArray()->all();
         return $data;
    }

    /**
     * @云运动 - 仓库管理 - 根据公司名称获取公司id
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/10/17
     * @param $companyName
     * @return object
     */
    public function companyData($companyName)
    {
        $companyId = Organization::find()->where(['name' =>$companyName])->andWhere(['pid' => 0])->select('id')->asArray()->one();
        return $companyId;
    }
    /**
     * @云运动 - 仓库管理 - 根据登录角色的场馆id称获取场馆
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/10/17
     * @param $venueId
     * @return object
     */
    public function venueData($venueId)
    {
        $venueData = Organization::find()->where(['id' =>$venueId])->andWhere(['style' => 2])->select('id,name')->asArray()->one();
        return $venueData;
    }
    /**
     * @云运动 - 仓库管理 - 根据搜索的公司id获取场馆
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/10/18
     * @param $companyId
     * @return object
     */
    public function venueDataAll($companyId)
    {
        $venueData = Organization::find()->where(['pid' =>$companyId])->andWhere(['style' => 2])->select('id,name')->asArray()->all();
        return $venueData;
    }
}