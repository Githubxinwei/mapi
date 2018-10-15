<?php
namespace backend\models;

use common\models\Func;
use common\models\relations\ClassRecordRelations;

class ClassRecord extends \common\models\base\ClassRecord
{
    use ClassRecordRelations;
    public $keywords;
    /**
     *后台会员管理 - 会员详细信息 -  上课记录信息查询
     * @author Huang hua <huanghua@itsports.club>
     * @param @id
     * @param $chargeId
     * @create 2017/4/20
     * @return bool|string
     */
    public function ClassRecordData($id,$chargeId)
    {
        $model = ClassRecord::find()
            ->alias('cr')
            ->joinWith(['member mm'])
            ->leftJoin('cloud_charge_class cc','cc.id = cr.multiple_id')
            ->select(
                "     cr.id,
                      cr.member_id,
                      mm.id,
                      mm.counselor_id,       
                      cc.*        
               "
            )
            ->where(['mm.id' => $id])
            ->andWhere(['multiple_type'=>'charge'])
            ->andWhere(['multiple_id'=>$chargeId])
            ->asArray();
        $dataProvider = Func::getDataProvider($model, 8);
        $dataProvider->models      =  $this->getEmployeeData($dataProvider->models);
        return $dataProvider;
    }


    /**
     * 后台会员管理 - 会员信息查询 - 获取员工表教练数据
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/18
     * @return string
     */
    public function getEmployeeData($data)
    {

        foreach ($data as &$value){
            $value['employee'] =  Member::find()->alias('mm')
                ->select('cloud_employee.*,mm.counselor_id')
                ->joinWith(['employee'])
                ->where(['mm.id'=>$value['member_id']])->asArray()->one();
        }

        return $data;
    }
    /**
     * 后台私教管理 - 私教上课查询 - 多表查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/5/24
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public function search($params)
    {
        $this->customLoad($params);
        $query = ClassRecord::find()
            ->alias('cr')
            ->joinWith(['member mm'])
            ->joinWith(['employee ee'])
//            ->leftJoin('cloud_member_course_order mc','mc.id = cr.multiple_id')
//            ->joinWith([
//                'memberCourseOrder mc'=>function($query){
//                    $query->joinWith(['memberCourseOrderDetails memberCourseOrderDetails']);
//                }
//            ])
//                 mc.id as orderId,
            ->joinWith([
                'member mm'=>function($query){
                    $query->joinWith(['memberDetails memberDetails']);
                    $query->joinWith(['memberCourseOrder memberCourseOrder']);
                }
            ])
            ->select(
                "     cr.id,
                      cr.member_id,
                      cr.coach_id,
                      cr.start,
                      cr.end,
                      cr.status,
                      cr.created_at,
                      cr.multiple_type,
                      cr.multiple_id,
                      mm.id as memberId,
                      mm.mobile,
                      ee.id as employeeId,
                      ee.name,
                      ee.pic, 
                "
            )
//            ->where(['cr.multiple_type'=>'order'])
//            ->andWhere(['cr.multiple_id'=>'cc.chargeClassId'])
            ->asArray();

        $query                 = $this->getSearchWhere($query);
        $dataProvider          = Func::getDataProvider($query,8);
//        $dataProvider->models = $this->getMemberDetails($dataProvider->models);
        return $dataProvider;
    }
    /**
     * 后台私课管理 - 获取会员详细信息表数据
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/5/24
     * @param $data
     * @return string
     */
    public function getMemberDetails($data)
    {
        foreach ($data as &$value){
            $value['memberDetails'] =  Member::find()->alias('mm')
                ->select('cloud_member_details.*,mm.id')
                ->joinWith(['memberDetails'])
                ->where(['mm.id'=>$value['member_id']])->asArray()->one();
        }

        return $data;
    }

    /**
     * 私课管理 - 私教上课 - 搜索数据处理数据
     * @create 2017/5/24
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return bool
     */
    public function customLoad($data)
    {
        $this->keywords    = (isset($data['keywords']) && !empty($data['keywords'])) ? $data['keywords'] : null;
        return true;
    }
    /**
     * 私课管理 - 私教上课 - 增加搜索条件
     * @create 2017/5/24
     * @author huanghua<huanghua@itsports.club>
     * @param $query
     * @return mixed
     */
    public function getSearchWhere($query)
    {
        $query->andFilterWhere([
            'and',
            [
                'ee.name' => $this->keywords,
            ],
        ]);
        return $query;
    }
    /**
     *私课管理 - 会员信息查询 - 上课状态修改
     * @author Huang hua <huanghua@itsports.club>
     * @param $id
     * @create 2017/5/25
     * @return bool
     */
    public static function getUpdateClass($id)
    {
        $aboutClass  =  \common\models\base\ClassRecord::findOne($id);

        if($aboutClass->status == 1){
            $aboutClass->status = 2;
        }elseif($aboutClass->status == 2){
            $aboutClass->status = 3;
        }else{
            $aboutClass->status = 1;
        }

        if($aboutClass->save()){
            return true;
        }else{
            return $aboutClass->errors;
        }
    }
    /**
     * 私课管理 - 私教上课 - 上课记录表删除
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/5/25
     * @param $id
     * @return bool
     */
    public  function  getClassDataDel($id)
    {
        $classRecord        =   ClassRecord::findOne($id);
        $resultDelMem       =   $classRecord->delete();

        if($resultDelMem)
        {
            return true;
        }else{
            return false;
        }
    }
    /**
     * 后台私教管理 - 会员信息查询 - 多表查询
     * @author Huang hua <huangpengju@itsports.club>
     * @create 2017/5/25
     * @param $id
     * @return \yii\db\ActiveQuery
     */
    public function     getMemberData($id)
    {

        $model = ClassRecord::find()
            ->alias('c')
            ->joinWith(['member m'])
            ->leftJoin('cloud_member_course_order_details mc','mc.id = c.multiple_id')
            ->joinWith([
                'member m'=>function($query){
                    $query->joinWith(['memberDetails memberDetails']);
                    $query->joinWith(['memberCourseOrder memberCourseOrder']);
                }
            ])
            ->select(
                "     c.id,
                      c.member_id,
                      c.start,
                      c.end,
                      c.status,
                      c.created_at,
                      c.multiple_id,
                      c.multiple_type,
                      m.id as memberId,
                      m.mobile,
                      mc.*,
                      "
            )
            ->where(['m.id' => $id])
            ->andWhere(['c.multiple_type'=>'order'])
            ->asArray()->one();

        return $model;

    }
    
}