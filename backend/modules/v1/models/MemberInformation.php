<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/19
 * Time: 16:34
 * Desc:会员信息
 */
namespace backend\modules\v1\models;

use common\models\base\MemberDetails;
use Yii;

class MemberInformation extends \common\models\MemberInformation
{
    public $member_id;            //会员ID
    public $sex;                  //性别
    public $birth_date;          //出生日期
    public $profession;          //职业
    public $now_address;         //城市
    public $height;               //会员身高
    public $weight;               //会员体重
    public $fitness_foundation;  //健身基础（1-入门，2-初级，3-中级，4-高级）
    public $fitness_goal;        //健身目标
    /**
     * 会员端 - 第一次登录保存个人信息 - 验证规则
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/06/19
     */
    public function rules()
    {
        return [
            ['member_id','required','message'=>'会员ID不能为空'],
            [['sex','birth_date','profession','now_address','height','weight','fitness_foundation','fitness_goal'],'safe']
        ];
    }
    /**
     * 会员端 - API - 修改会员姓名
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/06/19
     */
    public function modifyMember()
    {
        $model = \common\models\base\MemberInformation::findOne(['member_id' => $this->member_id,'is_delete' => 0]);
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (empty($model)) {
                $model = new \common\models\base\MemberInformation();
                $model->member_id = $this->member_id;
                if (isset($this->height)) $model->height =  $this->height;
                if (isset($this->weight)) $model->weight =  $this->weight;
                if (isset($this->fitness_foundation)) $model->fitness_foundation =  $this->fitness_foundation;
                if (isset($this->fitness_goal)) $model->fitness_goal =  $this->fitness_goal;
                if (!$model->insert()) return $model->errors;
                $md = MemberDetails::findOne(['member_id' => $this->member_id]);
                if (isset($this->sex)) $md->sex =  $this->sex;
                if (isset($this->birth_date)) $md->birth_date =  $this->birth_date;
                if (isset($this->profession)) $md->profession =  $this->profession;
                if (isset($this->now_address)) $md->now_address =  $this->now_address;
                $md->updated_at = time();
                if (!$md->update()) return $md->errors;
            } else {
                $model->member_id = $this->member_id;
                if (isset($this->height)) $model->height =  $this->height;
                if (isset($this->weight)) $model->weight =  $this->weight;
                if (isset($this->fitness_foundation)) $model->fitness_foundation =  $this->fitness_foundation;
                if (isset($this->fitness_goal)) $model->fitness_goal =  $this->fitness_goal;
                $model->update_at            = date("Y-m-d H:i:s");
                if (!$model->update()) return $model->errors;
                $md = MemberDetails::findOne(['member_id' => $this->member_id]);
                if (isset($this->sex)) $md->sex =  $this->sex;
                if (isset($this->birth_date)) $md->birth_date =  $this->birth_date;
                if (isset($this->profession)) $md->profession =  $this->profession;
                if (isset($this->now_address)) $md->now_address =  $this->now_address;
                $md->updated_at = time();
                if (!$md->update()) return $md->errors;
            }
            if ($transaction->commit() === NULL) {
                return ['code' => 1, 'status' => 'success', 'message' =>'保存成功!'];
            } else {
                return ['code' => 0,'status' => 'success','message' => '网络错误，请稍后重试!'];
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $e->getMessage();
        }
    }
    /**
     * 会员端 - API - 修改会员姓名
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/06/23
     */
    public function getMemberInformation($accountId)
    {
        $model = MemberInformation::find()
            ->alias('mi')
            ->joinWith(['member m'],FALSE)
            ->where(['m.member_account_id' => $accountId])
            ->andWhere(['mi.is_delete' => 0])
            ->select('mi.id')
            ->count();
        if ($model > 0) {
            return true;
        }
        return false;
    }
}