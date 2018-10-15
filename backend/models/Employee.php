<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/3 0003
 * Time: 上午 9:36
 */
namespace backend\models;
use common\models\ArrayConfig;
use common\models\base\Member;
use common\models\base\Order;
use common\models\relations\EmployeeRelations;
use common\models\Func;
use common\models\base\Admin;
use Yii;

/**
 * @云运动 - 后台 - 继承模型
 * @author Zhu Mengke <zhumengke@itsports.club>
 * @create 2017/3/29
 * @inheritdoc
 */
class Employee extends \common\models\base\Employee
{
    use EmployeeRelations;
    public $keywords;
    public $name;
    public $position;
    public $salary;
    public $sorts;
    public $venueId;
    public $depId;
    public $department;
    public $searchContent;
    public $nowBelongId;
    public $nowBelongType;
    public $companyId;
    public $nowDepartmentId;
    public $departmentId; //角色管理 分配员工的部门id
    public $lowest;
    public $highest;
    public $startTime;
    public $endTime;
    public $type;
    public $memberType;
    public $isCourse;

    public $searchDateStart;//搜索开始时间
    public $searchDateEnd;//搜索结束时间
    public $key;               //搜索员工名或者电话
    const  KEYS        = 'key';  //定义常量

    const COMPANY_ID      = 'companyId';
    const SEARCH_CONTENT  = 'searchContent';
    const NOW_BELONG_ID   = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    const NOW_DEPARTMENT  = 'nowDepartmentId';
    const DEPARTMENT      = 'departmentId';
    const KEY             = 'keywords';


    /**
     * 后台 - 团课课程管理 - 获取教室表数据
     * @author Houkaixin <Houkaixin@itsports.club>
     * @create 2017/4/19
     * @update author HuangPengju
     * @update 2017/4/27
     * @update huangpengju
     * @update 2017/06/10
     * @param $coachName  //教练名字
     * @param $id       //公司或者场馆id
     * @param $type     //类型
     * @return \yii\db\ActiveQuery
     */
    public function coachData($coachName,$id,$type){
        $data = Employee::find()
            ->alias('ee')
            ->joinWith(['organization or'],false)
            ->where(['or.style'=>'3'])
            ->andWhere(['or.code'=>['tuanjiao',"01"]])
            ->select("ee.id,ee.company_id,ee.venue_id,ee.name");
        if($type && $type == 'company'){
            $data->andFilterWhere(['ee.company_id'=>$id]);
        }
        if($type && ($type == 'venue' || $type == 'department')){
            $data->andFilterWhere(['ee.venue_id'=>$id]);
        }
        if(isset($coachName)&&!empty($coachName)){
            $data->andFilterWhere(["like","ee.name",$coachName]);
        }
        $data = $data->asArray()->all();
        return $data;
    }
    /**
     * @云运动 - 后台 - 获取联盟公司下的所团操教练
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/9/16
     * @inheritdoc
     */
    public function getAllCompanyCoach($name,$companyId){
        $data = Employee::find()
            ->alias('ee')
            ->joinWith(['organization or'])
            ->where(['or.style'=>'3'])
            ->andWhere(['or.code'=>'tuanjiao'])
            ->groupBy("ee.id");
        // 查询所对应的公司联盟
          if(!empty($name)){
              $data = $data->andFilterWhere(["like","ee.name",$name]);
          }
        // 超级管理员
            if(empty($companyId)){
              $data = $data->asArray()->all();
              return $data;
            }
          // 非超级管理员（包括公司联盟）
          $allCompanyId  =  $this->getAllCompany($companyId);
          if(!empty($allCompanyId)&&!empty($companyId)){
              $data = $data->andWhere(["in","ee.company_id",$allCompanyId])->asArray()->all();
          }else{
              $data = $data->asArray()->all();
          }
         return $data;
    }
    /**
     * @云运动 - 后台 - 获取所有公司联盟id（companyId）
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/9/16
     * @inheritdoc
     */
    public function getAllCompany($companyId){
          $allCompany = ApplyRecord::find()->where(["and",["or",["apply_id"=>$companyId],["be_apply_id"=>$companyId]],[">","end_apply",time()],["status"=>1]])
                         ->select("apply_id,be_apply_id")
                         ->asArray()
                         ->all();
           $allCompanyId=  $this->dealCompany($allCompany,$companyId);
           return $allCompanyId;
    }
    /**
     * @云运动 - 后台 - 获取所有公司联盟id（companyId）
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/9/16
     * @inheritdoc
     */
    public function dealCompany($allCompany,$companyId){
           if(empty($allCompany)){
               return  [$companyId];
           }
           $allCompanyId = [];
           foreach ($allCompany as $keys=>$values){
               $allCompanyId[] = $values["apply_id"];
               $allCompanyId[] = $values["be_apply_id"];
           }
           $allCompanyId = array_unique($allCompanyId);
           return $allCompanyId;
    }





    
    /**
     * 后台 - 团课课程管理 - 获取教室表数据
     * @author Houkaixin <Houkaixin@itsports.club>
     * @create 2017/4/19
     * @update author HuangPengju
     * @update 2017/4/27
     * @update huangpengju
     * @update 2017/06/10
     * @param $id       //公司或者场馆id
     * @param $type     //类型
     * @return \yii\db\ActiveQuery
     */
    public function coachPrivateData($id,$type){
         $data =  Employee::find()
            ->alias('ee')
            ->joinWith(['organization or'])
            ->where(['or.style'=>'3'])
             ->andWhere(['or.code'=>'sijiao'])
             ->andWhere(['<>','ee.status',2])
            ->select("ee.*")
            ->asArray();
        if(isset($type) && $type == 'company'){
            $data = $data->andFilterWhere(['ee.company_id'=>$id]);
        }
        if(isset($type) && $type == 'venue'){
            $data = $data->andFilterWhere(['ee.venue_id'=>$id]);
        }
        $data = $data->all();
         return $data;
    }
    /**
     * 后台员工管理 - 员工信息查询 - 多表查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/24
     * @param $params //搜索参数
     * @return \yii\db\ActiveQuery
     */
    public function search($params)
    {
        $this->customLoad($params);
        $query = Employee::find()
            ->alias('employee')
            ->with([
                'aboutClass'=>function($query){
                    $query->andWhere('status=1');
                }])
            ->joinWith(['organization organization'])
            ->joinWith(['admin admin'=>function($query) {
                $query->joinWith(['role role'],false);
            }],false)
            ->orderBy($this->sorts)
            ->select(
                "     
                employee.id,
                employee.organization_id,
                employee.name,
                employee.position,
                employee.salary,
                employee.params,
                employee.mobile,
                employee.level,
                employee.sex,
                employee.is_check,
                employee.is_pass,
                employee.alias,
                employee.status,
                employee.admin_user_id,
                employee.pic,
                employee.work_time,
                employee.work_date,
                organization.id as organizations_id,
                organization.name as organization_name,
                organization.style,
                organization.params as organization_params,
                role.name as roleName,
                "
            )
            ->asArray();
        $query                 =  $this->getSearchWhere($query);
        return $dataProvider   =  Func::getDataProvider($query,8);

    }
    /**
     * 后台 - 员工管理 - 员工基本信息删除
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/24
     * @param $id int
     * @return bool
     */
    public  function  getEmployeeDel($id)
    {
        $employee               =   Employee::findOne($id);
        $admin                  =   Admin::find()->where(['id'=>$employee['admin_user_id']])->one();
        $order                  =   Member::findAll(['counselor_id'=>$id]);
        $memOrder               =   MemberCourseOrder::findAll(['private_id'=>$id]);
        if($order || $memOrder){
            return false;
        }
        if(!empty($admin)){
            $employeeDelete         =   $employee->delete();
            $adminDelete            =   $admin->delete();
        }else{
            $employeeDelete         =   $employee->delete();
        }
        if($employeeDelete)
        {
            return true;
        }else{
            return false;
        }
    }
    /**
     * 员工管理 - 员工列表 - 搜索数据处理数据
     * @create 2017/4/7
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return bool
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
        $this->depId             = (isset($data['depId']) && !empty($data['depId'])) ? (int)$data['depId'] : $venueIds;
        $this->keywords          = (isset($data[self::KEY]) && !empty($data[self::KEY])) ? $data[self::KEY] : null;
        $this->venueId           = (isset($data['venueId']) && !empty($data['venueId'])) ? (int)$data['venueId'] : null;
//        $this->depId             = (isset($data['depId']) && !empty($data['depId'])) ? (int)$data['depId'] : null;
        $this->department        = (isset($data['department']) && !empty($data['department'])) ? (int)$data['department'] : null;
        $this->searchContent     = (isset($data[self::SEARCH_CONTENT]) && !empty($data[self::SEARCH_CONTENT])) ? $data[self::SEARCH_CONTENT] : null;
        $this->nowBelongId       = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID])) ? $data[self::NOW_BELONG_ID] : null;
        $this->nowBelongType     = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE])) ? $data[self::NOW_BELONG_TYPE] : null;
        $this->nowDepartmentId   = (isset($data[self::NOW_DEPARTMENT]) && !empty($data[self::NOW_DEPARTMENT])) ? $data[self::NOW_DEPARTMENT] : null;
        $this->sorts             = self::loadSort($data);
        return true;
    }

    /**
     * 会员卡管理 - 会员卡 - 增加搜索条件
     * @create 2017/4/5
     * @author huanghua<huanghua@itsports.club>
     * @param $query
     * @return mixed
     */
    public function getSearchWhere($query)
    {
        $query->andFilterWhere([
            'or',
            ['like','employee.name', $this->keywords],
            ['like','employee.alias',$this->keywords],
            ['like','employee.mobile',$this->keywords]
        ]);

        if(!$this->department){
            $query->andFilterWhere(['employee.venue_id'=>$this->depId]);
        }

        if($this->department || $this->depId || $this->venueId){
            $query->andFilterWhere([
                'and',
                [
                    'employee.organization_id' => $this->department,
                    'employee.venue_id'        => $this->depId,
                    'employee.company_id'      => $this->venueId,
                ],
            ]);
        }


//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['employee.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && $this->nowBelongType == 'venue'){
//            $query->andFilterWhere(['employee.venue_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && $this->nowBelongType == 'department'){
//            $query->andFilterWhere(['employee.venue_id'=>$this->nowDepartmentId]);
//        }
        return $query;
    }
    /**
     * 员工管理 - 员工信息列表 - 获取搜索规格
     * @create 2017/4/24
     * @author huanghua<huanghua@itsports.club>
     * @param $sort
     * @return mixed
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
     * 员工管理 - 员工信息管理 - 获取排序条件
     * @create 2017/4/24
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return mixed
     */
    public static function loadSort($data)
    {
        $sorts = [
            'id' => SORT_DESC
        ];
        if(!isset($data['sortType'])){ return $sorts;}
        switch($data['sortType'])
        {
            case 'employee_name'          :
                $attr = '`employee`.name';
                break;
            case 'employee_position'      :
                $attr = '`employee`.position';
                break;
            case 'employee_salary'        :
                $attr = '`employee`.salary';
                break;
            case 'employee_mobile'        :
                $attr = '`employee`.mobile';
                break;
            case 'employee_params'        :
                $attr = '`employee`.params';
                break;
            case 'employee_status'        :
                $attr = '`employee`.status';
                break;
            default;return $sorts;
        }
        return  $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];

    }

     /**
     * 后台 - 团课课程管理 - 获取教练单条数据
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/19
     * @param $id int //教练id
     * @return \yii\db\ActiveQuery      //教练信息
     */
    public static function getCoachOneById($id){
        return Employee::find()->where(['id'=>$id])->asArray()->one();
    }
    /**
     * 后台会员管理 - 员工信息查询
     * @author Huang hua <huangpengju@itsports.club>
     * @create 2017/4/26
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeModel($id)
    {
        $model = Employee::find()
            ->alias('employee')
            ->joinWith(['organization organization'])
            ->select(
                " 
                employee.id,
                employee.age,
                employee.organization_id,
                employee.name,
                employee.position,
                employee.salary,
                employee.mobile,
                employee.params,
                employee.level,
                employee.sex,
                employee.pic,
                employee.work_time,
                employee.work_date,
                employee.class_hour,
                employee.is_check,
                employee.is_pass,
                employee.alias,
                employee.status,
                employee.intro,
                employee.birth_time,
                employee.identityCard,
                organization.id as organizations_id,
                organization.pid as pid,
                organization.name as organization_name,
                organization.style,
                organization.params as organization_params,
                "
            )
            ->where(['employee.id' => $id])
            ->asArray()->one();
            $pid = $model['pid'];
//        echo '<pre>';
            $data = Organization::find()->where(['id'=>$pid])->asArray()->one();
        $model['venue'] = $data['name'];
        $model['venueId'] = $data['id'];
        $model['venuePid'] = $data['pid'];
        $data2 = Organization::find()->where(['id'=>$data['pid']])->asArray()->one();
        $model['company'] = $data2['name'];
        $model['companyId'] = $data2['id'];
        $model['companyPid'] = $data2['pid'];
//        var_dump($model);
//       exit();
        return $model;
    }
    /**
     * 云运动 - 添加员工 - 获取公司信息
     * @return string
     * @author Huang hua <huangpengju@itsports.club>
     * @update 2017-4-21
     */
    public function getOrganizationOption()
    {
        $venue = Organization::find()
            ->where(['style'=>1])
            ->andWhere(['pid'=>0])
            ->asArray()->all();
        return $venue;
    }
    /**
     * 云运动 - 搜索 - 获取所有场馆信息
     * @return string
     * @author Huang hua <huangpengju@itsports.club>
     * @update 2017-5-8
     */
    public function getVenueOption()
    {
        $venues = Organization::find()
            ->where(['style'=>2])
            ->asArray()->all();
        return $venues;
    }
    /**
     * 云运动 - 添加员工 - 获取场馆信息
     * @return string
     * @author Huang hua <huangpengju@itsports.club>
     * @param @venueID int
     * @update 2017-4-21
     */
    public function getOrganizationData($venueId)
    {
        $data = Organization::find()
            ->where(['pid'=>$venueId])
            ->asArray()->all();
        return $data;
    }
    /**
     * 云运动 - 会员卡管理 - 获取场馆信息
     * @return string
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @param $companyId int
     * @update 2017-9-13
     */
    public function getOrganizationAuthData($companyId)
    {
//        $roleId          =    \Yii::$app->user->identity->level;
//        if($roleId == 0){
//            $vId         =    Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
//            $venueIds    =    array_column($vId, 'id');
//        }else{
//            $venuesId    =    Auth::findOne(['role_id' => $roleId])->venue_id;
//            $venueIds    =    json_decode($venuesId);
//        }
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
        $data               =    Organization::find()->where([ 'and',['pid'=>$companyId],['id'=>$venueIds],['is_allowed_join'=>1]])->asArray()->all();
        return $data;
    }
    /**
     * 云运动 - 搜索 - 获取所有部门信息
     * @return string
     * @author Huang hua <huangpengju@itsports.club>
     * @update 2017-5-8
     */
    public function getDepartmentOption()
    {
        $department = Organization::find()
            ->where(['style'=>3])
            ->asArray()->all();
        return $department;
    }
    /**
     * 云运动 - 添加员工 - 获取部门信息
     * @return string
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017-4-26
     */
    public function getDepartmentData($depId)
    {
        $data = Organization::find()
            ->where(['pid'=>$depId])
            ->asArray()->all();
        return $data;
    }

    /**
     * 后台会员管理 - 会员信息查询 - 会员卡状态修改
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/3/31
     * @return bool
     */
    public static function getUpdateEmployee($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $employee  =  \common\models\base\Employee::findOne($id);
            if($employee->is_check == 1 && $employee->is_pass == 0){
                    $employee->is_pass = 1;
            }
            $employee = $employee->save() ? $employee : $employee->errors;
            if(!$employee){
                throw new \Exception('操作失败');
            }
            $admin = Admin::find()->where(['id'=>$employee->admin_user_id])->one();
            if($admin){
                $admin->status = 20;
            }else{
                return false;
            }
            if(!$admin->save()){
                throw new \Exception('操作失败');
            }
            if ($transaction->commit() === null) {
                return true;
            } else {
                return false;
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            return $error = $e->getMessage();
        }
    }

    /**
     * 云运动 - 添加教练 - 查询手机号是否存在
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017-5-4
     * @param $mobile   //手机号
     * @return array|null|\yii\db\ActiveRecord  //查询结果
     */
    public function getMobileInfo($mobile)
    {
        $data = Employee::find()->where(['mobile'=>$mobile])->asArray()->one();
        return $data;
    }
    /**
     * 云运动 - 添加员工 - 员工名是否存在
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017-6-13
     * @param $name
     * @param $id
     * @param $type
     * @return array|null|\yii\db\ActiveRecord  //查询结果
     */
    public function getEmployeeName($name,$id,$type)
    {
        $data = Employee::find()->where(['name'=>$name])->asArray();
        if(isset($type) && $type == 'company'){
            $data = $data->andFilterWhere(['cloud_employee.company_id'=>$id]);
        }
        if(isset($type) && $type == 'venue'){
            $data = $data->andFilterWhere(['cloud_employee.venue_id'=>$id]);
        }
        $data = $data->one();
        return $data;
    }
    /**
     * 私教管理 - 私教信息查询 - 获取所有的私教信息
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/24
     * @param $pid
     * @param $id
     * @param $type
     * @return \yii\db\ActiveQuery
     */
    public function getAdviser($pid = null,$id,$type){
        $employee = Employee::find()
            ->alias('employee')
            ->joinWith(['organization or'])
            ->where(['or.style'=>'3'])
            ->andWhere(['or.code'=>'sijiao'])
            ->select(
                " 
                employee.id,
                employee.organization_id,
                employee.name,
                employee.age,
                employee.pic,
                employee.work_time,
                employee.work_date,
                or.id as organizations_id,
                or.code,
                "
            )
            ->andFilterWhere(['or.pid'=>$pid])
            ->andFilterWhere(['<>','employee.status',2])
            ->asArray();
//        if(isset($type) && $type == 'company'){
//                    $employee = $employee->andFilterWhere(['employee.company_id'=>$id]);
//        }
//        if(isset($type) && $type == 'venue'){
//
//        }
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
        $venueId  = (isset($params['venueId']) && !empty($params['venueId'])) ? $params['venueId'] : $venueIds;
        $employee = $employee->andFilterWhere(['employee.venue_id'=> $venueId]);
        $employee = $employee->all();
        return $employee;
    }
    /**
     * 员工管理 - 员工详情 - 获取所有员工数据
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/6
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeData(){
        return $employee = Employee::find()
            ->alias('employee')
            ->select(
                "
                employee.id,
                employee.name,
                employee.age,
                employee.pic,
                employee.work_time,
                "
            )
            ->asArray()
            ->all();
    }
    /**
     * 员工管理 - 员工详情 - 替换员工
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/6
     * @param $id
     * @return \yii\db\ActiveQuery
     */
    public function getChooseEmployee($id){
        return $employee = Employee::find()
            ->alias('employee')
            ->joinWith(['member member'])
            ->where(['employee.id' => $id])
            ->asArray()
            ->one();
    }
    /**
     * 员工管理 - 员工详情 - 销售下的所有会员
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/6
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public function employeeMember($params)
    {
        $this->memberLoad($params);
        $query = \backend\models\Member::find()
            ->alias('member')
            ->joinWith(['employee employee'])
            ->joinWith(['memberDetails memberDetails'])
            ->joinWith(['memberCard memberCard'])
            ->joinWith(['memberCourseOrder mco'])
            ->where(['member.counselor_id' => $params['employeeId']])
            ->orderBy('member.register_time DESC')
            ->asArray();
        $query        = $this->setMemberWhereSearch($query);
        $dataProvider = Func::getDataProvider($query,10000);
        return $dataProvider;
    }
    /**
     * 员工管理 - 员工详情 - 销售会员搜索处理
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/10/10
     * @param $params
     * @return bool
     */
    public function memberLoad($params)
    {
        $this->memberType = isset($params['memberType']) ? $params['memberType'] : null;
        $this->keywords   = isset($params['keywords']) ? $params['keywords'] : null;
        $this->isCourse   = isset($params['isCourse']) ? $params['isCourse'] : null;
        return true;
    }
    /**
     * 员工管理 - 员工详情 - 销售会员列表
     * @author 焦冰洋 <jiaopbingyang@itsports.club>
     * @create 2017/10/10
     * @param $query
     * @return bool
     */
    public function setMemberWhereSearch($query)
    {
        $query->andFilterWhere(['member.member_type' => $this->memberType]);
        $query->andFilterWhere([
            'or',
            ['like','memberDetails.name',$this->keywords],
            ['=','member.mobile',$this->keywords],
            ['=','memberCard.card_number',$this->keywords],
        ]);
        if($this->isCourse != null){
            if($this->isCourse == 0){
                $query->andWhere(['IS','mco.product_type',null]);
            }else{
                $query->andFilterWhere(['mco.product_type'=>1]);
            }
        }
        return $query;
    }
    /**
     * 云运动 - 角色管理 - 点击查看详情
     * @author Huanghua <Huanghua@itsports.club>
     * @create 2017/6/17
     * @param $id
     * @return boolean/object
     */
    public function getRoleModel($id,$keywords)
    {
        $query = Employee::find()
            ->alias('employee')
            ->joinWith(['organization organization'])
            ->innerJoinWith(['admin admin'])
            ->andWhere(['=','admin.level',$id])
            ->andWhere(['not in','employee.status', [2]])
            ->asArray();
        if (!empty($keywords)) {
            $query->andFilterWhere(['or',
                ['like','employee.name',$keywords],
                ['like','employee.mobile',$keywords],
                ]);
        }
        $dataProvider = Func::getDataProvider($query,8000);
        return $dataProvider;
    }
    /**
     * 后台角色管理 - 分配员工信息查询 - 多表查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/6/17
     * @param $params //搜索参数
     * @return \yii\db\ActiveQuery
     */
    public function roleSearch($params)
    {
        $this->custom($params);
        $query = Employee::find()
            ->alias('employee')
//            ->joinWith([
//                'admin admin'=>function($query){
//                    $query->joinWith(['role role']);
//                }
//            ])
            ->joinWith(['organization organization'])
            ->innerJoinWith(['admin admin'])
            ->select(
                "
                employee.id,
                employee.organization_id,
                employee.admin_user_id,
                employee.name,
                employee.mobile,
                employee.pic,
                organization.id as organizationsId,
                organization.name as organizationName,
                "
            )
            ->where(['employee.status' => 1])
            ->andWhere(['admin.level'=>null])
            ->asArray();
//        var_dump($query);
//        die();
        $query         = $this->getSearch($query);
        return          $dataProvider        =  Func::getDataProvider($query,10000);

    }
    /**
     * 角色管理 - 分配员工 - 搜索数据处理数据
     * @create 2017/6/17
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return bool
     */
    public function custom($data)
    {
        $this->key           = (isset($data[self::KEYS]) && !empty($data[self::KEYS])) ? $data[self::KEYS] : null;
        $this->departmentId  = (isset($data[self::DEPARTMENT]) && !empty($data[self::DEPARTMENT])) ? $data[self::DEPARTMENT] : null;
        $this->companyId    = (isset($data[self::COMPANY_ID]) && !empty($data[self::COMPANY_ID]))?$data[self::COMPANY_ID]: NULL;
        $this->nowBelongId  = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType= (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;

        return true;
    }
    /**
     * 角色管理 - 分配员工 - 增加搜索条件
     * @create 2017/6/17
     * @author huanghua<huanghua@itsports.club>
     * @param $query
     * @return mixed
     */
    public function getSearch($query)
    {

        $query->andFilterWhere([
            'or',
            ['like','employee.name', $this->key],
            ['like','employee.mobile',$this->key]
        ]);

        $query->andFilterWhere([
            'and',
            ['employee.organization_id'=>$this->departmentId]
        ]);

        $query->andFilterWhere(['employee.company_id'=>$this->companyId]);

        if($this->nowBelongType && $this->nowBelongType == 'company'){
            $query->andFilterWhere(['employee.company_id'=>$this->nowBelongId]);
        }
        return $query;
    }

    /**
     * 后台会员管理 - 员工信息查询
     * @author Huang hua <huangpengju@itsports.club>
     * @create 2017/7/6
     * @param $id 管理员id
     * @return \yii\db\ActiveQuery
     */
    public function     getEmployeeCenter($id)
    {
        $model = Employee::find()
            ->alias('employee')
            ->joinWith(['admin admin'])
            ->joinWith([
                'admin admin'=>function($query){
                    $query->joinWith(['role role']);
                }
            ])
            ->select(
                " 
                employee.id,
                employee.admin_user_id,
                employee.name,
                employee.sex,
                employee.position,
                employee.mobile,
                employee.pic,
                employee.venue_id,
                employee.company_id,
                admin.id as adminId,
                admin.username,
                admin.level,
                "
            )
            ->where(['employee.admin_user_id' => $id])
            ->asArray()->one();
        return $model;
    }
    /**
     * 销售主页 - 销售额
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $params
     * @param $type
     * @return array
     */
    public function actionSalesMember($type,$params)
    {
        $this->customs($params);
        $this->getDateWhere($type);
        return $this->getSalesMember($this->searchDateStart,$this->searchDateEnd);
    }

    /**
     * 销售主页 - 销售额 - 搜索数据处理数据
     * @create 2017/7/21
     * @author 黄华 <huanghua@itsports.club>
     * @param $data
     * @return bool
     */
    public function customs($data)
    {
        $card               =  new CardCategory();
        $this->venueId      =   (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $card->getVenueIdByRole();
        return true;
    }
    /**
     * 后台 - 销售主页 - 处理搜索条件
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $attr
     * @return string
     */
    public function getDateWhere($attr)
    {
        if($attr == 'w'){
            $this->searchDateStart = Func::getTokenClassDate($attr,true);
            $this->searchDateEnd   = Func::getTokenClassDate($attr,false);
        }elseif($attr == 'd'){
            $this->searchDateStart = Func::getGroupClassDate($attr,true);
            $this->searchDateEnd   = Func::getGroupClassDate($attr,false);
        }else{
            $this->searchDateStart = Func::getGroupClassDate($attr,true);
            $this->searchDateEnd   = Func::getGroupClassDate($attr,false);
        }
    }
    /**
     * 销售主页 - 销售额
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $beginDate
     * @param $endDate
     * @return array
     */
    public function getSalesMember($beginDate,$endDate)
    {
        $sales = Employee::find()
            ->alias('employee')
            ->joinWith(['organization or'],false)
            ->where(['or.style' => 3])
//            ->andWhere(['or.code' => 'xiaoshou'])
            ->andWhere(['<>','employee.status',2])
            ->select('employee.id,employee.name,employee.venue_id')
            ->asArray()->all();
//        $sales   = $this->setWhere($sales);         //场馆和公司判断
        $salesId = array_column($sales,'id');
        $count   = [];
        foreach($salesId as $value){
            $order = Order::find()
                ->where(['sell_people_id' => $value])
                ->andWhere(['status'=>2])
                ->andWhere(['between','pay_money_time',strtotime($beginDate),strtotime($endDate)])
                ->andFilterWhere(['venue_id' => $this->venueId])
                ->select('total_price,sell_people_name')
                ->asArray()
                ->all();
            if(!empty($order)){
                $price   = array_column($order,'total_price');
                $sum     = array_sum($price);
                $count[] = ['totalPrice'=>$sum,'name'=>$order[0]['sell_people_name']];
            }
        }
        return $count;
    }
    /**
     * 后台 - 约课管理 - 处理搜索条件
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
     * @param $query
     * @return string
     */
    public function setWhere($query)
    {
        $query->andFilterWhere(['employee.venue_id' => $this->venueId]);
        return $query->all();
    }
    /**
     * 后台 - 销售主页 - 员工业绩统计
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/8/15
     * @param $type
     * @param $params
     * @return array
     */
    public function employeePerformance($type,$params)
    {
        $this->customs($params);
        $this->getDateWhere($type);
        return $this->getEmployeePerformance($this->searchDateStart,$this->searchDateEnd);
    }
    /**
     * 业务后台 - 销售主页 - 员工业绩
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/8/15
     * @param $beginDate
     * @param $endDate
     * @return array
     */
    public function getEmployeePerformance($beginDate,$endDate)
    {
        $array = new ArrayConfig();
        $arr   = array_column($array->setEmployeePosition(),'val');
        $sales = Employee::find()
            ->alias('employee')
            ->joinWith(['organization or'],false)
            ->joinWith(['order order'],false)
            ->where(['or.style' => 3])
            ->andWhere(['<>','employee.status',2])
            ->andWhere(['in','employee.position',$arr])
            ->andWhere(['order.status'=>2])
            ->andFilterWhere(['order.venue_id'=>$this->venueId])
            ->andWhere(['between','order.pay_money_time',strtotime($beginDate),strtotime($endDate)])
            ->select('employee.position,sum(order.total_price) as money')
            ->groupBy("employee.position")
            ->asArray()->all();
//        $sales   = $this->setWhereTime($sales);         //场馆和公司判断
        return $sales;
    }
    /**
     * 后台 - 员工业绩 - 处理搜索条件
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/8/15
     * @param $query
     * @return string
     */
    public function setWhereTime($query)
    {
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['employee.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['employee.venue_id'=>$this->nowBelongId]);
//        }
        $query->andFilterWhere(['employee.venue_id'=>$this->venueId]);
        return $query->all();
    }

    /**
     * 后台 - 财务管理 - 上课收入 - 私教列表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/28
     * @param $params
     * @return string
     */
    public function getPrivates($params)
    {
        $this  -> Privates($params);
        $query =  Employee::find()
            ->alias("ee")
            ->joinWith(["organization org"],false)
            ->joinWith(["organizations ors"],false)
            ->joinWith(["aboutClasses ac"=>function($query){
                $query->joinWith(["memberCourseOrderDetails mod"=>function($query){
                    $query->joinWith(["memberCourseOrder mco"], false);
                }],false);
                $query->joinWith(["memberDetails mmd"], false);
            }])
            ->where(['org.code' => 'sijiao'])
            ->andWhere(['ac.status' => 4])
            ->select("
            ee.*,
            ac.coach_id,
            ac.start,
            ac.type as ac_type,
            sum((mco.money_amount/mco.course_amount)*mco.overage_section) as left_money,
            mco.type as class_type,
            sum(mco.money_amount/mco.course_amount) as token_money,
            count(ac.coach_id) as token_num,
            org.id as org_id,
            ors.name as venue_name,
            org.name as org_name,
            org.code as org_code
            ")
            ->orderBy($this->sorts)
            ->groupBy('ac.coach_id')
            ->asArray();
        $query  =  $this->setPrivatesWhereSearch($query);
        return $query;
    }

    /**
     * @云运动 - 财务管理 - 上课收入 - 搜索处理
     * @author 焦冰洋 <jiaopbingyang@itsports.club>
     * @create 2017/8/28
     * @param $data
     * @return bool
     */
    public function Privates($data)
    {
        $roleId            =    \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId           =    Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds      =    array_column($vId, 'id');
        }else{
            $venuesId      =    Auth::findOne(['role_id' => $roleId])->venue_id;
            $venueIds      =    json_decode($venuesId);
        }
        $this->venueId     =    (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->keywords    =    (isset($data['keywords']) && !empty($data['keywords'])) ? $data['keywords'] : null;
        $this->startTime   =    (isset($data['startTime'])&& !empty($data['startTime']))? (int)strtotime($data['startTime']) : null;
        $this->endTime     =    (isset($data['endTime']) && !empty($data['endTime'])) ? (int)strtotime($data['endTime']) : null;
        $this->highest     =    (isset($data['highest']) && !empty($data['highest'])) ? (int)($data['highest']) : null;
        $this->lowest      =    (isset($data['lowest']) && !empty($data['lowest'])) ? (int)($data['lowest']) : null;
        $this->type        =    (isset($data['type']) && !empty($data['type'])) ? (int)($data['type']) : null;
        $this->sorts       =    self::loadTokenSort($data);        //排序
        return true;
    }

    /**
     * 财务管理 - 上课收入 - 获取排序条件
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/9/2
     * @param $data
     * @return mixed
     */
    public static function loadTokenSort($data)
    {
        $sorts = ['id' => SORT_DESC];
        if(!isset($data['sortType'])){ return $sorts; }
        switch ($data['sortType']){
            case 'token_num'  :
                $attr = 'count(coach_id)';
                break;
            case 'token_money' :
                $attr = 'sum(mco.money_amount/mco.course_amount)';
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
     * 后台 - 财务管理 - 上课收入 - 私教列表
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/31
     * @param $query
     * @return string
     */
    public function getPrivatesList($query)
    {
        $dataProvider  =  Func::getDataProvider($query,8);
        return  $dataProvider;
    }
    /**
     * 后台 - 财务管理 - 上课收入 - 用于统计
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/31
     * @param $query
     * @return string
     */
    public function getPrivatesTotal($query)
    {
        return $query->all();
    }

    /**
     * @上课量统计 - 课程类型（PT、HS、生日课）
     * @create 2017/11/13
     * @author zhumengke <zhumengke@itsports.club>
     * @return array
     */
    public function getCourseTypeTwo()
    {
        $data = MemberCourseOrder::find()
            ->where(['course_type'=>2])
            ->orWhere(['course_type'=>null,'type'=>2])
            ->asArray()->all();
        return array_column($data,'id');
    }
    public function getCourseTypeThree()
    {
        $data = MemberCourseOrder::find()
            ->where(['course_type'=>3])
            ->orWhere(['course_type'=>null,'type'=>3])
            ->asArray()->all();
        return array_column($data,'id');
    }

    /**
     * @云运动 - 财务管理 - 上课收入 - 搜索处理
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/8/21
     * @param $query
     * @return string
     */
    public function setPrivatesWhereSearch($query)
    {
        $query->andFilterWhere(['ee.venue_id' => $this->venueId]);
        if(!empty($this->type)){
            $two   = $this->getCourseTypeTwo();
            $three = $this->getCourseTypeThree();
            if($this->type == 2){
                $query->andWhere(['mco.id'=>$two]);      //HS
            }elseif($this->type == 3){
                $query->andWhere(['mco.id'=>$three]);    //生日课
            }else{
                $query->andWhere(['and',['NOT IN','mco.id',$two],['NOT IN','mco.id',$three]]);    //PT
            }
        }
        $query->andFilterWhere(['like','ee.name',$this->keywords]);
        if($this->lowest != null && $this->highest != null){
            $query->Having([
                'and',
                ['>=','token_num',$this->lowest],
                ['<=','token_num',$this->highest]
            ]);
        }
        $query->andFilterWhere([
            'and',
            ['>=','ac.start',$this->startTime],
            ['<=','ac.start',$this->endTime]
        ]);

        return $query;
    }

    /**
     * @后台 - 卡种审核 - 获取部门下的员工
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/9/28
     * @param  $id   //部门id
     * @return array
     */
    public function getEmployeeByDepartment($id)
    {
        return Employee::find()->where(['organization_id' => $id])->select('id,name')->asArray()->all();
    }
    /**
     * @后台 -  获取指定场馆下所有销售部的员工
     * @author houkaixn <houkaixin@itsports.club>
     * @create 2017/11/28
     * @param  $companyId      //公司id
     * @return array
     */
    public function gainDepEmployee($companyId){
        // 搜索指定部门下的所有员工
        $employee     = Employee::find()
                            // ->where(["status"=>1])   // 在职
                             ->alias("employee")
                             ->joinWith(["organization depart"=>function($query)use($companyId){
                                $query->joinWith(["company venue"])
                                      ->andWhere(["venue.pid"=>$companyId]);
                            }],false)
                             ->select("employee.id,employee.name,depart.code")
                             ->where(["depart.code"=>["xiaoshou","yingyun"]])
                             ->asArray()
                             ->all();
        return $employee;
    }

    /**
     * 员工管理 - 批量修改员工年限
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2018/03/15
     * @return bool|string
     */
    public function updateWorkTime()
    {
        $data = \common\models\base\Employee::find()
            ->where(['is','work_date',null])
            ->andWhere(['>=','work_time',48])
            ->select('id,work_date,work_time')
            ->asArray()->all();
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $employee = \common\models\base\Employee::findOne(['id' => $value['id']]);
                $employee->work_time = NULL;
                $employee->save();
            }
            return true;
        }
    }
}