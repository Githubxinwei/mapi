<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 下午 2:33
 */
namespace backend\models;
use common\models\base\Cabinet;
use common\models\relations\CabinetTypeRelations;
class CabinetType  extends \common\models\base\CabinetType
{
    use CabinetTypeRelations;
    /**
     * 后台 - 柜子管理 - 获取所有柜子类型
     * @author HouKaiXin <houkaixin@itsports.club>
     * @create 2017/5/3
     * @param $venueId     //场馆类型id
     * @return array      //返回数组
     */
  public function  getCabinetType($venueId){
         $data = CabinetType::find()->where(["venue_id"=>$venueId])->asArray()->all();
         return $data;
  }
    /**
     * 后台 - 柜子管理 - 获取所有柜子的编号
     * @author HouKaiXin <houkaixin@itsports.club>
     * @create 2017/5/3
     * @param $typeId    //指定柜子类型id
     * @return array     //返回数组
     */
    public function  getCabinetNum($typeId){
        $data = Cabinet::find()->where(["AND",["cabinet_type_id"=>$typeId],["status"=>1]])
            ->orderBy(["cabinet_number"=>SORT_ASC])
            ->asArray()->all();
        return $data;
    }
    /**
     * 后台 - 柜子管理 - 获取指定场馆下的柜子
     * @author HouKaiXin <houkaixin@itsports.club>
     * @create 2017/5/3
     * @param  $id  //场馆id
     * @return array     //对应场馆下的柜子类型
     */
    public function getTheData($id){
         $data = CabinetType::find()->where(["venue_id"=>$id])->asArray()->all();
         return $data;
    }

    /**
     * 后台 - 柜子管理 - 获取所有柜子类型
     * @author huanghua <houkaixin@itsports.club>
     * @create 2017/12/14
     * @param $venueId     //场馆类型id
     * @return array      //返回数组
     */
    public function  cabinetTypeData($venueId){
        $data = CabinetType::find()->where(["venue_id"=>$venueId])->asArray()->all();
        return $data;
    }
    /**
     * 新柜子管理 - 类型管理-获取所有柜子类型和指定类型柜子的数据
     * @author yanghuilei<yanghuilei@itsports.club>
     * @create 2017/12/30
     * @return array
     */
    public function getCabinetTypeManageList($cabinetTypeId)
    {
        $cabinet    = Cabinet::find();
        $largeData  = $this -> getLargeCabinetData($cabinet, $cabinetTypeId);
        $middleData = $this -> getMiddleCabinetData($cabinet, $cabinetTypeId);
        $smallData  = $this -> getSmallCabinetData($cabinet, $cabinetTypeId);

        return ['large' => $largeData, 'middle' => $middleData, 'small' => $smallData];
    }
    public function getLargeCabinetData($cabinet, $cabinetTypeId)
    {
        $lz = $cabinet->where(['cabinet_model' => 1, 'cabinet_type' => 2, 'cabinet_type_id' => $cabinetTypeId])->asArray()->all();
        $ll = $cabinet->where(['cabinet_model' => 1, 'cabinet_type' => 1, 'cabinet_type_id' => $cabinetTypeId])->asArray()->all();
        return ['form' => $lz, 'temp' => $ll];
    }
    public function getMiddleCabinetData($cabinet, $cabinetTypeId)
    {
        $mz = $cabinet->where(['cabinet_model' => 2, 'cabinet_type' => 2, 'cabinet_type_id' => $cabinetTypeId])->asArray()->all();
        $ml = $cabinet->where(['cabinet_model' => 2, 'cabinet_type' => 1, 'cabinet_type_id' => $cabinetTypeId])->asArray()->all();
        return ['form' => $mz, 'temp' => $ml];
    }
    public function getSmallCabinetData($cabinet, $cabinetTypeId)
    {
        $sz = $cabinet->where(['cabinet_model' => 3, 'cabinet_type' => 2, 'cabinet_type_id' => $cabinetTypeId])->asArray()->all();
        $sl = $cabinet->where(['cabinet_model' => 3, 'cabinet_type' => 1, 'cabinet_type_id' => $cabinetTypeId])->asArray()->all();
        return ['form' => $sz, 'temp' => $sl];
    }
    /**
     * 云运动 - 后台 - 获取柜子详情
     * @author yanghuilei <yanghuilei@itsports.club>
     * @create 2018/1/2
     * @param $cabinetId   //柜子id
     * @param $cabinetNum  //柜子数量
     * @return  string
     */
    public function getCabinetTypeDetail($cabinetId)
    {
        $data = Cabinet::find()->where(['id' => $cabinetId])->asArray()->one();
        if($data != null){
            return $data;
        }else{
            return false;
        }
    }
    /**
     * 云运动 - 后台 - 获取柜子详情 - 判断柜号重复
     * @author yanghuilei <yanghuilei@itsports.club>
     * @create 2018/1/2
     * @return  string
     */
    public function getDifferentCabinetNumber($post)
    {
        $data = Cabinet::find()->where([
                                        'cabinet_type'    => $post['cabinetType'],
                                        'cabinet_model'   => $post['cabinetSize'],
                                        'cabinet_number'  => $post['cabinetNum'],
                                        'cabinet_type_id' => $post['cabinetTypeId'],
                                        'venue_id'        => $post['venueId'],
                                       ])
                                ->andWhere(['<>', 'id', $post['id']])
                                ->asArray()
                                ->one();
        if($data)
        {
            return $data;
        }else{
            return false;
        }
    }
}

?>