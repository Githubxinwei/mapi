<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/3 0003
 * Time: 下午 3:57
 */
namespace backend\models;

use common\models\base\ChargeGroupClass;
use common\models\Func;
use common\models\relations\ChargeClassNumberRelations;

class ChargeClassNumber extends \common\models\base\ChargeClassNumber
{
    use ChargeClassNumberRelations;
    public $venueId;
    public $courseId;
    public $keywords;
    const VENUE_ID  = 'venueId';
    const COURSE_ID = 'courseId';
    const KEYWORDS  = 'keywords';
    /**
     * @私教小团体课 - 获取私教排课列表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2018/01/03
     * @return array|string
     */
    public function arrangeClassList($params)
    {
        $this->customLoad($params);
        $query = ChargeClassNumber::find()
            ->alias('ccn')
            ->joinWith(['chargeClass cc' => function($query){
                $query->joinWith(['coursePackageDetailsAlone cpd' => function($query){
                    $query->joinWith(['course course'],false);
                }],false);
            }],false)
            ->joinWith(['chargeGroupClass cgc' => function($query){
                $query->orderBy('cgc.class_date desc');
            }],false)
            ->where(['ccn.surplus' => 0])
            ->andWhere(['!=','ccn.attend_class_num',0])
            ->select('ccn.*,cc.name as className,course.name as courseName,cgc.class_date,(ccn.valid_start_time + ccn.valid_time*24*60*60) as validTime')
            ->groupBy('ccn.id')
            ->asArray();
        $query = $this->searchWhere($query);
        return Func::getDataProvider($query,8);
    }

    /**
     * @私教小团体课 - 获取私教排课列表 - 处理搜索字段
     * @create 2018/01/03
     * @author zhumengke <zhumengke@itsports.club>
     * @param $params
     * @return bool
     */
    public function customLoad($params)
    {
        $roleId       = \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId      = Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds = array_column($vId, 'id');
        }else{
            //拿到用户有权限查看的场馆
            $venuesId = Auth::findOne(['role_id' => $roleId])->venue_id;
            $authId   = json_decode($venuesId);
            //去掉组织架构里面设置"不显示"的场馆id
            $venues   = Organization::find()->where(['id'=>$authId])->andWhere(['is_allowed_join'=>1])->select(['id','name'])->asArray()->all();
            $venueIds = array_column($venues, 'id');
        }
        $this->venueId  = (isset($params[self::VENUE_ID]) && !empty($params[self::VENUE_ID]))?$params[self::VENUE_ID] : $venueIds;
        $this->courseId = (isset($params[self::COURSE_ID]) && !empty($params[self::COURSE_ID]))?$params[self::COURSE_ID] : NULL;
        $this->keywords = (isset($params[self::KEYWORDS]) && !empty($params[self::KEYWORDS]))?$params[self::KEYWORDS] : NULL;
    }

    /**
     * @私教小团体课 - 获取私教排课列表 - 搜索字段
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2018/01/03
     * @param  $query
     * @return array
     */
    public function searchWhere($query)
    {
        $query->andFilterWhere(['ccn.venue_id' => $this->venueId]);
        $query->andFilterWhere(['course.id' => $this->courseId]);
        $query->andFilterWhere(['like','ccn.class_number',$this->keywords]);
        return $query;
    }

    /**
     * @私教小团体课 - 排课 - 判断课程是否已上完或排完
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2018/01/10
     * @return array|string
     */
    public function isOverClass($id,$date)
    {
        $day = date('Y-m-d',time());
        if($date < $day){
            return '此日期已过期';
        }
        $classNum = ChargeClassNumber::findOne(['id' => $id]);
        if(!empty($classNum)){
            if($classNum['attend_class_num'] == 0){
                return '此课程已上完';
            }
            $num = ChargeGroupClass::find()->where(['and',['class_number_id' => $id],['!=','status',5]])->count();
            if($classNum['total_class_num'] == $num){
                return '此课程已排完';
            }
            return true;
        }
    }

    /**
     * @私教小团体课 - 排课 - 获取这是第几节课
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2018/01/11
     * @return array|string
     */
    public function getEndTime($id,$dayStart)
    {
        $num = ChargeGroupClass::find()
            ->where(['class_number_id' => $id])
            ->andWhere(['<=','start',strtotime($dayStart)])
            ->andWhere(['!=','status',5])
            ->count();
        $num = $num + 1;
        $class['num'] = $num;    //第几节课
        //获取课种和课程时长
        $charge = ChargeClassNumber::findOne(['id' => $id]);
        $class['totalNum'] = $charge['total_class_num'];    //共几节课
        $course = CoursePackageDetail::find()->where(['charge_class_id' => $charge['charge_class_id']])->asArray()->all();
        if(count($course) > 1){
            $courseNum = 0;
            foreach ($course as $key => $value) {
                $courseNum += $value['course_num'];
                if($courseNum >= $num){
                    $name = Course::find()->where(['id' => $value['course_id']])->select('name')->asArray()->one();
                    $class['courseName'] = $name['name'];    //课种名称
                    $class['length']     = $value['course_length'];    //课程时长
                    break;
                }
            }
        }else{
            $name = Course::find()->where(['id' => $course[0]['course_id']])->select('name')->asArray()->one();
            $class['courseName'] = $name['name'];
            $class['length']     = $course[0]['course_length'];
        }
        return $class;
    }

    /**
     * @desc: 私教小团体-私教多人课-获取私教课程购买区间列表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/10
     * @param $chargeId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPriceLimit($chargeId,$venue_id)
    {
        $data = ChargeClassNumber::find()
            ->alias('ccn')
            ->select('ccn.id as numberId,ccn.class_people_id,ccn.class_number,ccn.sell_number,ccn.surplus,ccn.total_class_num,
            ccn.attend_class_num,ccn.venue_id,ccn.company_id,ccp.intervalEnd')
            ->where(['ccn.charge_class_id'=>$chargeId])
            ->andwhere(['>','ccn.surplus','0'])
            ->joinWith(['chargeClassPeople ccl'=>function($query){
                $query->joinWith('chargeClassPrice ccp');
            }])
            ->andwhere(['ccn.venue_id'=>$venue_id])
            ->asArray()
            ->all();
        return $data;
    }

    /**
     * @desc: 私教小团体-私教多人课-获取私教服务购买区间列表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/10
     * @param $chargeId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getServerLimit($chargeId,$venue_id)
    {
        $data = ChargeClassNumber::find()
            ->alias('ccn')
            ->select('ccl.*,ccn.id as numberId,ccn.class_number,ccn.sell_number,ccn.surplus,ccn.total_class_num,
            ccn.attend_class_num,ccn.venue_id,ccn.company_id')
            ->where(['ccn.charge_class_id'=>$chargeId])
            ->andwhere(['>','ccn.surplus','0'])
            ->andwhere(['venue_id'=>$venue_id])
            ->joinWith(['chargeClassPeople ccl'],false)
            //->createCommand()->getRawSql();
            ->asArray()->all();
        return $data;
    }

}