<?php
namespace backend\models;
use Yii;
use yii\base\Model;
use common\models\base\SmsRecord;
use common\models\base\Member;
use common\models\base\Employee;
class SmsRecordForm extends Model
{

    /**
     * 云运动 - 后台- 新增短信
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/8/12
     * @param $data
     * @param $dataInfo
     * @param $status
     * @param $type
     * @return boolean/object
     */
    public function addSmsData($data,$dataInfo,$status,$type)
    {
        $member               = Member::findOne(['mobile' => $data['to']]);
        $model                = new SmsRecord();
        $model->member_id     = $member['id'];
        $model->mobile        = $member['mobile'];
        $model->send_code     = $data['project'];
        $model->status        = $status;
        $model->type          = $type;
        $model->content       = $dataInfo;
        $model->created_at    = time();
        $model->create_id     = $this->getCreate();
        $model->company_id    = $member['company_id'];
        $model->venue_id      = $member['venue_id'];
        $model->var           = $data['vars'];

        if ($model->save()) {
            return true;
        } else {
            return $model->errors;
        }
    }
    /**
     * 云运动 - 后台- 重新发送短信
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/9/18
     * @param $data
     * @param $dataInfo
     * @param $status
     * @param $type
     * @param $again
     * @return boolean/object
     */
    public function updateSmsData($data,$dataInfo,$status,$type,$again)
    {
        $member               = Member::findOne(['mobile' => $data['to']]);
        $model                = SmsRecord::findOne(['id' => $again]);
        $model->member_id     = $member['id'];
        $model->mobile        = $member['mobile'];
        $model->send_code     = $data['project'];
        $model->status        = $status;
        $model->type          = $type;
        $model->content       = $dataInfo;
        $model->created_at    = time();
        $model->create_id     = $this->getCreate();
        $model->company_id    = $member['company_id'];
        $model->venue_id      = $member['venue_id'];
        $model->var           = $data['vars'];

        if ($model->save()) {
            return true;
        } else {
            return $model->errors;
        }
    }
    
    /**
     * 云运动 - 后台- 新增短信
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/9/8
     * @return boolean/object
     */
    public function getCreate() 
    {
        if(isset(\Yii::$app->user->identity) && !empty(\Yii::$app->user->identity)){
            $create = Employee::findOne(['admin_user_id'=>\Yii::$app->user->identity->id]);
            $create = isset($create->id)?intval($create->id):0;
            return $create;
        }
        return 0;
    }

}