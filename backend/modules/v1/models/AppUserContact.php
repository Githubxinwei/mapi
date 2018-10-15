<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/2
 * Time: 14:09
 * Desc: 用户手机通讯录
 */
namespace backend\modules\v1\models;

use common\models\Func;
use Yii;

class AppUserContact extends \common\models\AppUserContact
{
    public $memberId;     //会员id
    public $companyId;   //公司id
    public $callInfo;   //用户手机通讯录信息
    public $secretKey = 'xingfufit';    //加密密码
    /**
     * 会员端 - 获取用户手机通讯录 - 验证规则
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/06/25
     */
    public function rules()
    {
        return [
            ['memberId','required','message'=>'账户ID不能为空'],
            ['companyId','required','message'=>'公司ID不能为空'],
            [['callInfo'],'safe']
        ];
    }
    /**
     * 会员端-API-生成通讯录
     * @author 辛威 <xinwei@itsport.club>
     * @createAt 2018/07/02
     */
    public function GenerateUserContact()
    {
        /*$transaction = \Yii::$app->db->beginTransaction();
        try {
            $callInfo = json_decode($this->callInfo,true);
            $arr = [];
            foreach ($callInfo as $k => $v) {
                if (isset($v['phone'])) {
                    $res = $this->getAppUserContact($v['phone']);
                    if (!$res) {
                        if (Func::validPhone($v['phone'])) {
                            $arr[$k][] = $this->memberId;
                            $arr[$k][] = $this->companyId;
                            $arr[$k][] = base64_encode(\Yii::$app->security->encryptByPassword($v['name'],$this->secretKey));
                            $arr[$k][] = base64_encode(\Yii::$app->security->encryptByPassword($v['phone'],$this->secretKey));
                            $arr[$k][] = base64_encode(\Yii::$app->security->encryptByPassword(json_encode($v),$this->secretKey));
                        }
                    }
                }
            }
            $query = Yii::$app->db->createCommand();
            $sql = $query->batchInsert('{{cloud_app_user_contact}}',['member_id','company_id','name','phone','note'],$arr);
            $data = $sql->execute();
            if ($transaction->commit() === NULL) {
                return ['code' => 1, 'status' => 'success', 'message' =>'保存成功!','data' => $data];
            } else {
                return ['code' => 0,'status' => 'success','message' => '网络错误，请稍后重试!','data' => []];
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $e->getMessage();
        }*/
        return true;
    }
    /**
     * 会员端-API-判断是否已生成通讯录
     * @author 辛威 <xinwei@itsport.club>
     * @createAt 2018/07/02
     */
    public function getAppUserContact($phone)
    {
        $data = AppUserContact::find()
            ->where(['and',['member_id' => $this->memberId],['phone' => $phone]])
            ->andWhere(['is_delete' => 0])
            ->select('id')
            ->asArray()
            ->all();
        return $data;
    }
}