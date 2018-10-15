<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/5/2
 * Time: 15:15
 */

namespace backend\modules\v1\models;


use backend\models\Config;
use backend\models\Organization;
use common\models\base\Member;
use common\models\base\MemberAccount;
use common\models\base\MemberDetails;
use common\models\base\MessageCode;
use yii\base\Model;
use Yii;
class SignUpForm extends Model
{
    public $code;
    public $mobile;
    public $password;
    public $newCode;
    public $companyId;
    public $venueId;
    public $memberBelMessage;
    const MOB = 'mobile';
    public $deviceNumber;
    public $name;
    /**
     * @云运动 - 后台 - 验证规则
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['companyId', 'required', 'message' => '请选择公司'],
            ['venueId', 'required', 'message' => '请选择场馆'],
            [self::MOB, 'required', 'message' => '手机号不能为空'],
            ['password', 'required', 'message' => '密码不能为空'],
            ['deviceNumber', 'required', 'message' => '设备码不能为空'],
            [self::MOB, 'trim'],
            [self::MOB,"isNotRegister"],   // 判断该会员在这个公司是否注册过
            // [self::MOB,"memberIsExist"],
            [self::MOB,'string', 'max' => 200],
            ['code', 'trim'],
            ['code', 'required', 'message' => '验证码不能为空！'],
            ['code', 'compare', 'compareAttribute' => 'newCode', 'message' => '验证码错误！'],
            ['code', 'newCodeTime'],
            ['name','safe']
        ];
    }
    /**
     * @云运动 - 后台 - 检测会员是否注册过
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/10/21
     * @inheritdoc
     */
    public function isNotRegister($attribute){
        // 检查是否是新会员
        $endResult = $this->judgeIsRegister();
        if($endResult!==true){
            $this->addError($attribute, '手机号已注册!');
        }
    }
    /**
     * @云运动 - 后台 - 判断会员是否注册过（在这个公司）
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/10/21
     * @inheritdoc
     */
    public function judgeIsRegister(){
        $endResult =  MemberAccount::find()
            ->where(["and",["mobile"=>$this->mobile],["company_id"=>$this->companyId]])
            ->one();
        if(empty($endResult)){
            return true;
        }
        return false;
    }
    /**
     * @云运动 - 后台 - 注册验证码
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
      public function memberIsExist($attribute){
          // 检查是否是新会员
          $endResult = $this->judgeIsNewMember();
          if($endResult!==true){
              $this->addError($attribute, '手机号已注册!');
          }
      }

    /**
     * @云运动 - 后台 - 注册验证码
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function loadCode()
    {
        $temp = $this->getCode();
        $this->newCode = $temp['code'];
    }

    /**
     * @云运动 - 后台 - 注册验证码验证
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function newCodeTime($attribute)
    {
        $temp = $this->getCode();
        $time = $temp['create_at'];
        $num = time() - $time;
        if ($num > 300000) {
            $this->addError($attribute, '验证码已失效！');
        }
    }

    /**
     * @云运动 - 后台 - 注册验证
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    
    public function validateSignUp()
    {
        $this->loadCode();
        if ($this->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();                                                               //开启事务
            try {
                $ma                    = new MemberAccount();
                $ma->mobile            = $this->mobile;
                $ma->username          = empty($this->name) ?"暂无":$this->name;
                $ma->password          = $this->setPassword($this->password);
                $ma->create_at         = time();
                $ma->company_id        = $this->companyId;
                $ma->count =1;
                $ma->deviceNumber = $this->deviceNumber;//设备识别码
                $ma->save();
                if (!isset($ma->id)) {
                   return $ma->errors;
                }
                $member                = new Member();
                $member->mobile        = $this->mobile;
                $member->username      = empty($this->name) ?"暂无":$this->name;
                $member->password      = $this->setPassword($this->password);
                $member->register_time = time();
                $member->status        = 1;
                $member->member_type   = 2;
                $member->venue_id      = $this->venueId;
                $member->company_id    = $this->companyId;
                $member->member_account_id = $ma->id;
                $member = $member->save() ? $member : $member->errors;
                if ($member->id) {
                    $details = $this->setMemberDetails($member->id);
                    if($details != true){
                        return $details;
                    }
                    $this->delCode();
                    $this->getBelongMessage();
                }
                if($transaction->commit() == null)
                {
                    $member = new \backend\modules\v1\models\Member();
                    return $member->getMemberAccOneData($this->mobile,$this->companyId);
                }else{
                    return $member->errors;
                }
            }catch (\Exception $e){
                $transaction->rollBack();                                                                                        //事务回滚
                return  $e->getMessage();
            }
        }
        return $this->errors;
    }
    public function getBelongMessage(){
         $data = Organization::find()
                 ->alias("or")
                 ->joinWith(["organization organization"],false)
                 ->where(["or.id"=>$this->venueId])
                 ->select("or.name as venueName,or.pid,organization.name as companyName")
                 ->asArray()
                 ->one();
         $data["venueName"]   = empty($data["venueName"])?"暂无数据":$data["venueName"];
         $data["companyName"] = empty($data["companyName"])?"暂无数据":$data["companyName"];
         $this->memberBelMessage = $data;
    }




    
    /**
     * @云运动 - 后台 - 注册验证
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/8/11
     * @return array|bool
     */
    public function judgeIsNewMember(){
        //先检该 场官 该会员是否存在
        $data = \backend\models\Member::find()->where(["and",["venue_id"=>$this->venueId],["mobile"=>$this->mobile]])->asArray()->one();
        if(empty($data)){
            return  true;                 // 会员不存在
        }
        // 老会员之前有过历史注册 记录
        $this->setPassword("123456");
        $passwordResult =  Yii::$app->security->validatePassword("123456",$this->password);
        if($passwordResult===false){
             return false;   // 会员之前 有注册过
        }
        //  老会员 之前没有注册历史记录（初始化密码）
        $this->setPassword("12345678");
        $model = Member::findOne(["id"=>$data["id"]]);
        $model->password = $this->password;
        if(!$model->save()){
           return $model->errors;
        }
        return false;        // 会员存在
    }


    /**
     * @云运动 - 后台 - 注册验证
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/27
     * @param $memberId //会员id
     * @return array|bool
     */
    public function setMemberDetails($memberId)
    {
        $detail             = new MemberDetails();
        $detail->member_id  = $memberId;
        $detail->name       = $this->name;
        $detail->sex        = 0;
        $detail->created_at = time();                    //创建时间
        $a = Config::find()->select('id')->where(['value'=>'APP'])->asArray()->one();
        if (!empty($a)){
            $detail->way_to_shop = $a['id'];
        }
        $detail = $detail->save()?$detail:$detail->errors;
        if(!$detail->id){
            return $detail->errors;
        }else{
            return true;
        }

    }
    /**
     * @云运动 - 后台 - 设置密码
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function setPassword($password)
    {
       return  $this->password = Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * @云运动 - 后台 - 获取验证码
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function getCode()
    {
        return MessageCode::find()->where(['mobile'=>$this->mobile])->orderBy('create_at DESC')->asArray()->one();
    }
    /**
     * @云运动 - 后台 - 删除验证码
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function delCode()
    {
        $code = MessageCode::findOne(['mobile'=>$this->mobile]);
        $del  = $code->delete();
        return $del;
    }
}