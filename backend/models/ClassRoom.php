<?php
namespace backend\models;
use backend\modules\v1\models\Organization;
use common\models\base\Seat;
use common\models\Func;
use  common\models\relations\GroupClassRelations;

class ClassRoom extends \common\models\base\Classroom
{
    public $sorts;
    public $venueId;
    public $startTime;
    public $topSearch;
    public $endTime;
    public $classroomId;
    public $ids;      //所有符合数据的顶级id 也就是公司
    const  DEL_TODO = "座位删除失败";
    const  START_TIME = 'startTime';
    const  END_TIME   = 'endTime';
    const  TOP_SEARCH = 'topSearch';
    const  ROOM        = 'classroomId';
    public $nowBelongId;
    public $nowBelongType;

    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    use GroupClassRelations;
    /**
     * 后台 - 团课课程管理 - 获取教室表数据
     * @author Houkaixin <Houkaixin@itsports.club>
     * @param $id
     * @param $type
     * @create 2017/4/14
     * @return \yii\db\ActiveQuery
     */
    public function getClassRoomData($id,$type){
        $classRoomData =ClassRoom::find()->asArray();
        if(isset($type) && $type == 'venue'|| $type == 'department'){
            $classRoomData = $classRoomData->andFilterWhere(['cloud_classroom.venue_id'=>$id]);
        }
        if(isset($type) && $type == 'company'){
            $classRoomData = $classRoomData->andFilterWhere(['cloud_classroom.company_id'=>$id]);
        }
        $classRoomData = $classRoomData->all();
        return $classRoomData;
    }
    /**
     * 后台 - 团课课程管理 - 获取指定场馆对应的教室信息
     * @author Houkaixin <Houkaixin@itsports.club>
     * @create 2017/4/20
     * @param $id     //指定场馆id
     * @return array  //指定场馆的所有信息
     */
    public function myClassroom($id){
        return ClassRoom::find()->where(["venue_id"=>$id])->asArray()->all();
    }
    /**
     * 后台 - 获取教室 - 获取教室信息
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/20
     * @param $id     //指定教室id
     * @return array  //指定场馆的所有信息
     */
    public static function getClassroomOneById($id){
        return ClassRoom::find()->where(["id"=>$id])->asArray()->one();
    }
    /**
     * 后台 - 获取座位数 -  根据座位排次 获取座位数
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/8/10
     * @param $seatTypeId     //座位排次id
     * @param $classroomId    // 教室id
     * @return array  //返回 座位数
     */
    public static function getSeatNum($seatTypeId,$classroomId){
        return Seat::find()->where(["and",["seat_type_id"=>$seatTypeId],["classroom_id"=>$classroomId],["!=","seat_type",0]])->count();
    }


    /**
     * 后台 - 组织架构管理 - 场馆（获取分页数据）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/25
     * @param $params
     * @return object   //返回分页数据对象
     */
    public function getMyClassroomData($params)
    {
        $this->customLoad($params);
        $this->searchCompany();
        $query          = ClassRoom::find()
            ->joinWith("organization")
            ->select(" cloud_classroom.*,
                     cloud_organization.name as venueName,
                     cloud_organization.created_at,
                     cloud_organization.pid,
                     ")
            ->orderBy($this->sorts)
            ->asArray();
        //按照日期进行搜索
        $query          = $this->getSearchVenueWhere($query);
        $data           = Func::getDataProvider($query,8);
        $data->models   = $this->dataArrange($data->models);
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
        $data = Organization::find()->where(["style"=>1])->asArray()->all();
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
     * 后台 - 组织架构管理 - 教室 - 将上级部门数据整理到数组里边
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/26
     * @param $data
     */
    public function dataArrange($data)
    {
        foreach($data  as $keys=>$values){
            $superior =Organization::findOne($values["pid"])["name"];
            $id       =Organization::findOne($values["pid"])["id"];
            $data[$keys]["superior"]   = $superior;
            $data[$keys]["superior_id"] = $id;
        }
        return $data;
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
     * 后台 - 组织架构管理 - 场地- 按照创建日期搜索
     * @create 2017/4/26
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $query  //后台的sql语句
     * @return  mixed
     */
    public function getSearchVenueWhere($query)
    {
        if(!$this->venueId){
            $this->venueId = 0;
        }
        $query->andFilterWhere([
            'and',
            ['>=','cloud_organization.created_at',$this->startTime],
            ['<=','cloud_organization.created_at',$this->endTime]
        ]);
        $query->orFilterWhere([
            'in','cloud_organization.pid',$this->ids,
        ]);
        $query->orFilterWhere([
            'like','cloud_organization.name',$this->topSearch,
        ]);
        $query->orFilterWhere([
            'like','cloud_classroom.name',$this->topSearch,
        ]);
//        $query->andFilterWhere([
//            'and',
//            ['cloud_classroom.id'=>$this->classroomId ]
//        ]);
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['cloud_organization.pid'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && $this->nowBelongType == 'venue'){
//            $query->andFilterWhere(['cloud_organization.id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && $this->nowBelongType == 'apply'){
//            $query->andFilterWhere(['in','cloud_organization.pid',$this->nowBelongId]);
//        }
        $query->andFilterWhere(['cloud_organization.id' => $this->venueId]);
        return $query;
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
        $roleId                =     \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId               =      Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds          =      array_column($vId, 'id');
        }else{
            //拿到用户有权限查看的场馆
            $venuesId          =      Auth::findOne(['role_id' => $roleId])->venue_id;
            $authId            =      json_decode($venuesId);
            //去掉组织架构里面设置"不显示"的场馆id
            $venues            =      Organization::find()->where(['id'=>$authId])->andWhere(['is_allowed_join'=>1])->select(['id','name'])->asArray()->all();
            $venueIds          =      array_column($venues, 'id');
        }
        $this->venueId         =   (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
        $this->startTime       =   (isset($data[self::START_TIME]) && !empty($data[self::START_TIME])) ? strtotime($data[self::START_TIME]) : null;
        $this->endTime         =   (isset($data[self::END_TIME])   && !empty($data[self::END_TIME]))   ? strtotime($data[self::END_TIME]) : null;
        $this->topSearch       =   (isset($data[self::TOP_SEARCH]) && !empty($data[self::TOP_SEARCH])) ? $data[self::TOP_SEARCH] : null;
        $this->nowBelongId     =   (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType   =   (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->sorts           =   self::loadSort($data);
        $this->classroomId     =   (isset($data[self::ROOM]) && !empty($data[self::ROOM])) ? $data[self::ROOM] : null;
        return true;
    }

    /**
     * 后台 - 组织架构管理 - 对各个字段的排序
     * @create 2017/4/26
     * @author houkaixin<houkaixin@itsports.club>
     * @param $data  array //前台获取的排序处理数据
     * @return array
     */
    public static function loadSort($data)
    {
        $sorts = ['cloud_classroom.classroom_area' => SORT_DESC];
        if(!isset($data['sortType'])){
            return $sorts;
        }
        switch ($data['sortType']){
            case 'venueName'  :
                $attr = '`cloud_organization`.name';
                break;
            case 'companyName'  :
                $attr = '`cloud_organization`.pid';
                break;
            case 'classroom':
                $attr = '`cloud_classroom`.name';
                break;
            case 'area' :
                $attr = '`cloud_classroom`.classroom_area';
                break;
            case "total_seat" :
                $attr = '`cloud_classroom`.total_seat';
                break;
            default:
                $attr = NULL;
        }
        if($attr){
            $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }


    /**
     * 后台 - 组织架构管理 -教室（ 删除指定数据）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/26
     * @param $id
     * @return boolean   //返回删除数据的结果
     */
    public function getDelClassroom($id){
        $transaction                  =  \Yii::$app->db->beginTransaction();                //开启事务
        try{
            $result     = ClassRoom::findOne($id);
            $delResult  = $result->delete();
            if ($delResult) {
              $number = Seat::find()->where(["classroom_id"=>$id])->count();
              if($number>0){
                  $result = Seat::deleteAll(["classroom_id"=>$id]);
                  if(!$result){
                      throw new \Exception(self::DEL_TODO);
                  }
              }
            }else{
                throw new \Exception(self::DEL_TODO);
            }

            if($transaction->commit())
            {
                return true;
            }else{
                return "数据递交错误";
            }
        }catch(\Exception $e){
            //如果抛出错误则进入 catch ,先callback,然后捕捉错误，返回错误
            $transaction->rollBack();
            return  $error = $e->getMessage();  //获取抛出的错误
        }
    }

    /**
     * 后台 - 场馆管理 - 删除教室场地
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/7/27
     * @return object     //返回添加数据成功与否结果
     */
    public function delClassRoom($roomId)
    {
        $seat = \common\models\base\SeatType::findOne(['classroom_id' => $roomId]);
        if(!empty($seat)){
            return '该教室已绑定:'.$seat['name'].'，不可删除';
        }else{
            $room = \common\models\base\Classroom::findOne(['id' => $roomId]);
            if($room->delete()){
                return true;
            }else{
                return $room->errors;
            }
        }
    }
}