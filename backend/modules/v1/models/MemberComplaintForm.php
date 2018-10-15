<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/27
 * Time: 11:51
 */
namespace backend\modules\v1\models;
use common\models\base\MemberComplaint;
use yii\base\Model;
class MemberComplaintForm  extends  Model
{
    public  $venueId;             // 场馆id
    public  $departmentId;      // 部门id
    public  $complaintType;     // 投诉类型
    public  $complaintContent; // 举报内容
    public  $memberId;          // 会员id
    /**
     * 云运动 - Api - 投诉规则
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/22
     * @return array|\yii\db\ActiveRecord[]
     */
    public function rules()
    {
        return [
            ['venueId','required','message'=>'请选择投诉场馆'] ,
            ['departmentId','required' ,'message'=>'请选择投诉部门'] ,
            ["complaintType",'required','message'=>'请选择投诉类型'],
            ["memberId",'required','message'=>'系统错误(对象丢失)'],
            ["complaintContent",'safe']
        ];
    }
    /**
     * 云运动 - Api - 投诉数据存储
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/22
     * @return array|\yii\db\ActiveRecord[]
     */
    public function saveMessage(){
        $model = new MemberComplaint();
        $model->venue_id         = $this->venueId;
        $model->department_id   = $this->departmentId;
        $model->member_id        = $this->memberId;
        $model->complaint_type  = $this->complaintType;
        $model->complaint_content = $this->complaintContent;
        $model->create_at        = time();
        if(!$model->save()){
            return $model->errors;
        }
        return true;
    }
    /**
     * 云运动 - Api - 保存信息获取
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/8/22
     * @param  $message
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getErrorMessage($message){
          if(empty($message)){
              return "系统出错,马上处理";
          }
          foreach($message as $keys=>$value){
                return $value;
          }
    }
}
?>