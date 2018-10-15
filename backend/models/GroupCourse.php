<?php
namespace backend\models;
use common\models\base\Course;
use yii\base\Model;
use common\models\Func;
use common\models\relations\CourseRelations;
class GroupCourse extends Model
{
    use CourseRelations;
    public $pidS;
    public $sorts;
    public $course;
    public $nowBelongId;
    public $nowBelongType;
    public $nowApplyType;
    public $category;
    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    const NOW_APPLY_TYPE = 'nowApplyType';
    const COURSE    = 'course';
    const CATEGORY  = "category";
    /**
     * 后台 - 新团课管理 -  团课课程 - 获取课种数据
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/25
     * @param  $data
     * @param  $sign
     * @return object     //返回历史课程信息
     */
    public function getData($data,$sign)
    {
        // 查询所有父类pid
        $this->allPid();
        $this->fieldSort($data);
        $this->customLoad($data);
        $query = Course::find()
            ->alias('course')
            ->where(["class_type"=>2])
            ->andWhere(["not in","id",$this->pidS])
            ->orderBy($this->sorts)
            ->asArray();
        if(empty($sign)){
            $query = Course::find()
                ->alias('course')
                ->where(["class_type"=>2])
                ->andWhere(["not in","id",$this->pidS])
                ->orderBy(["category" => SORT_DESC])
                ->asArray();
            $query     = $this->getSearchWhere($query,null);
            return $query->all();
        }
        $query     = $this->getSearchWhere($query,"sign");
        $data      = Func::getDataProvider($query,8);
        return $data;
    }
    
    /**
     * 新团课管理 - 团课 - 搜索数据处理数据
     * @create 2017/6/6
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return  boolean
     */
    public function customLoad($data)
    {

        $this->nowBelongId   = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->nowApplyType  = (isset($data[self::NOW_APPLY_TYPE]) && !empty($data[self::NOW_APPLY_TYPE]))?$data[self::NOW_APPLY_TYPE]: NULL;
        $this->course        = (isset($data[self::COURSE]) && !empty($data[self::COURSE]))?$data[self::COURSE]: NULL;
        $this->category      = (isset($data[self::CATEGORY])) && !empty($data[self::CATEGORY])?$data[self::COURSE]: NULL;
        return true;
    }
    /**
     * 会员卡管理 - 团课管理 - 增加搜索条件
     * @create 2017/4/14
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $query //后台的sql语句
     * @param  $status  // 访问方法区分标志
     * @return  mixed
     */
    public function getSearchWhere($query,$status)
    {
        $query->andFilterWhere([
            "or",
            [
               "like",'course.name',$this->course,
            ],
            [
               "like",'course.category',$this->category,
            ]
        ]);
        if(isset($this->nowBelongType) && $this->nowBelongType == 'company'){
            $query->andFilterWhere(['course.company_id'=>$this->nowBelongId]);
        }
        if(isset($this->nowBelongType) && ($this->nowBelongType == 'venue')&&!empty($status)){
            $query->andFilterWhere(['course.company_id'=>$this->nowBelongId]);
        }else{
            if(isset($this->nowApplyType) && empty($this->nowApplyType)){
                $query->andFilterWhere(['course.venue_id'=>$this->nowBelongId]);
            }
        }
        return $query;
    }
    /**
     * 后台 - 新团课管理 -  团课课程 - 获取所有课程的pid
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/26
     * @param
     */
   public function allPid(){
       $pidS = Course::find()
           ->where(["class_type"=>2])
           ->select("pid")
           ->asArray()->all();
       $pidS = $this->combineData($pidS);
       $this->pidS = $pidS;
   }
    /**
     * 后台 - 新团课管理 -  团课课程 - 获取所有课程的pid（去掉重复的pid）
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/26
     * @param    $pidS   //所有课程的pid（未去重）
     * @return  array    //返回所有课程的pid（去重）
     */
   public function combineData($pidS){
           $thePidS = [];
           foreach($pidS as $keys=>$values){
               $thePidS[] = $values["pid"];
           }
        $data = array_unique($thePidS);
        return $data;
   }
    /**
     * 后台 - 新团课管理 -  团课课程 - 对指定字段排序
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/5/26
     * @param  $data // 发送过来的参数array("sortType"=>,"sortName"=>)
     * @return  array
     */
   public function fieldSort($data){
       $this->sorts       = self::loadSort($data);
   }
    /**
     * 后台 - 新团课 - 团课课程列表 对各个字段的排序
     * @create 2017/5/26
     * @author houkaixin<houkaixin@itsports.club>
     * @param $data  array // 前台发过来的排序参数
     * @return array
     */
    public static function loadSort($data)
    {
        $sorts = ["category" => SORT_DESC];
        if(!isset($data["sortType"])){ return $sorts;}
        switch ($data["sortType"]){
            case 'name'  :
                $attr = 'name';
                break;
            case 'category'  :
                $attr = 'category';
                break;
            case 'course_duration':
                $attr = 'course_duration';
                break;
            case 'people_limit':
                $attr = 'people_limit';
                break;
            case "course_difficulty":
                $attr = 'course_difficulty';
                break;
            default:
                $attr = NULL;
        };
        if($attr){
            $sorts = [ $attr  => self::convertSortValue($data['sortName'])];
        }
        return $sorts;
    }

    /**
     * 后台 - 新团课 - 团课课程列表 对课程字段排序
     * @create 2017/5/26
     * @author houkaixin<houkaixin@itsports.club>
     * @param $sort
     * @return  string
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
     * 后台 - 新团课 - 点击修改的时候获取课种初始化数据
     * @create 2017/5/26
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $id         //课种id
     * @param  $venueId    //场馆id
     * @param  $type       //场馆类型
     * @return  string
     */
   public function classType($id,$venueId,$type){
         $model = new  \backend\models\Course();
         $courseType      = $model->getClassDataS(2,$venueId,$type);
         $endData         = $model->getOneData($id,$courseType);
         return $endData;
   }

    /**
     * 后台 - 新团课 - 获取单条课程信息
     * @create 2017/5/31
     * @author houkaixin<houkaixin@itsports.club>
     * @param
     * @return  string
     */
  public function getOneData($id){
      $data = GroupClass::find()->where(["cloud_group_class.id"=>$id])
          ->joinWith(["employee"],false)
          ->joinWith(["course"],false)
          ->joinWith(["classroom"],false)
          ->joinWith(["seatTypeS"],false)
          ->select(
              "cloud_group_class.*, 
               cloud_employee.pic as pic,
               cloud_employee.name as employeeName,
               cloud_employee.pic,
               cloud_course.name as theCourseName,
               cloud_course.path as theCoursePath,
               cloud_course.course_duration,
               cloud_classroom.name as classroomName,
               cloud_seat_type.name as theSeatTypeName
               ")
          ->asArray()->one();
      $data = $this->getTopCourse($data);
      return $data;
  }

  public function getTopCourse($data)
  {
      $path = json_decode($data["theCoursePath"]);
      $path = explode(",", $path);
      if(count($path)==1){
          $data["courseTypeName"] = $data["theCourseName"];
          $data["topCourseId"]    =  $data["id"];
      }else{
          $courseData = Course::find()->where(["id" => $path[1]])->asArray()->one();
          $data["courseTypeName"] = $courseData["name"];
          $data["topCourseId"]    = $path[1];
      }
      return $data;
  }

    
    
    
    





}