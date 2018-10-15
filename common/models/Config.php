<?php
namespace common\models;


class Config extends \common\models\base\Config
{
    /**
     * 后台 - 团课排课 - 获取预约设置信息
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/06/02
     * @param   $type    // 登录人员身份
     * @param  $id      // 公司或场馆id
     * @return object     //返回所有预约设置信息
     */
    public function getConfigDetail($type,$id){
        $data   = Config::find()->select("key,value,company_id,venue_id")->asArray();
        $query  = $this->getSearchIdentify($data,$type,$id);
        $data   = $query->all();
        if(!empty($data)){
            $data = $this->combineData($data);
        }else{
            $data = [];
        }
        return $data;
    }
    /**
     * 后台 - 新团课排课 - 按照身份进行数据搜索
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/06/02
     * @param $type         // 登录身份
     * @param $query
     * @param $id          // 场馆id
     * @return object     //返回所有预约设置信息
     */
    public function getSearchIdentify($query,$type,$id){
            if(isset($type)&&$type == "company"){
                $query->andFilterWhere(['company_id'=>$id]);
            }
            if(isset($type)&&$type == "venue"){
                $query->andFilterWhere(['venue_id'=>$id]);
            }
            return $query;
    }
    /**
     * 后台 - 团课排课 - 获取预约设置信息(数据整理展示)
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/06/02
     * @param
     * @return object     //返回所有预约设置信息
     */
    public function combineData($data){
         $dataS = [];
         foreach($data as $keys=>$values){
              $dataS[$values["key"]]   = $values["value"];
         }
        $dataS["company_id"] = $data[0]["company_id"];
        return $dataS;
    }


}