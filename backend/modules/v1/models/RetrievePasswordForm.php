<?php
namespace backend\modules\v1\models;

use common\models\base\Member;
use common\models\base\MemberAccount;
use common\models\base\MessageCode;
use yii\base\Model;

class RetrievePasswordForm extends Model
{
    public $code;
    public $mobile;
    public $password;
    public $newCode;
    public $companySign;  // 公司标识

    public $theVenueId;  // 场馆id
    /**
     * @云运动 - 后台 - 验证规则
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['mobile', 'trim'],
            ['mobile', 'required', 'message' => '手机号不能为空！'],
            ['mobile', 'string', 'max' => 200],
            [["theVenueId"],"safe"],
           // ['mobile', 'exist', 'targetClass' => '\common\models\base\Member', 'message' => '您还未注册！'],
            ['mobile', 'isNotExist'],
            ['password','required','message'=>'密码不能为空'],

            ['code', 'trim'],
            ['code', 'required', 'message' => '验证码不能为空！'],
            ['code', 'compare', 'compareAttribute' => 'newCode', 'message' => '验证码错误！'],
            ['code', 'newCodeTime'],

            [["companySign"],'safe'],
        ];
    }

    /**
     * @云运动 - 后台 - 检测手机号是否存在
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/12/15
     * @inheritdoc
     */
    public function isNotExist($attribute)
    {
        if (!$this->hasErrors()) {
            // 根据公司标识查询指定的查询id
            $member  = Member::find()->where(['mobile' => $this->mobile]);
            if(!empty($this->companySign)){
                $venueIds = \backend\models\Member::gainTheVenueS($this->companySign);
                $member->andWhere(["venue_id"=>$venueIds]);
            };
            if(!empty($this->theVenueId)){
                $member->andWhere(["venue_id"=>$this->theVenueId]);
            }
            $num = $member->count();
            if($num==0){
                $this->addError($attribute, '您还未注册');
            }
            if($num>1){
                $this->addError($attribute, '您的信息有异常,请到场馆登记');
            }
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
     * @云运动 - 后台 - 获取验证码
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function getCode()
    {
        return MessageCode::find()->where(['mobile'=>$this->mobile])->orderBy('id DESC')->asArray()->one();
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
     * @云运动 - 后台 - 重置密码
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function setRetrieveSave()
    {
        // 根据公司标识查询指定的查询id
        if(!empty($this->companySign)){
//            $venueIds = \backend\models\Member::gainTheVenueS($this->companySign);
            $this->companySign = $this->getCompanyId();
            $member  = MemberAccount::find()->where(["and",['mobile' => $this->mobile],["company_id" => $this->companySign]]);
        }else{
            if(!empty($this->theVenueId)){
                $company  = Organization::findOne(['venue_id'=>$this->theVenueId]);
                $member  = MemberAccount::findOne(['mobile' => $this->mobile,'company_id'=>$company['pid']]);
            }else{
                $member  = MemberAccount::findOne(['mobile' => $this->mobile]);
            }
        }
        $member = $member->one();
        $member->password = $this->setPassword($this->password);
        if($member->save()){
            $this->delCode();
            return true;
        }else{
            return $member->errors;
        }
    }
    public function setPassword($password)
    {
        return  $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    public function getCompanyId()
    {
        // 获取指定公司下的所有场馆
        if($this->companySign == "maibu"){
            $venueName = "迈步运动健身";
        }
        if($this->companySign == "wayd"){
            $venueName = "我爱运动瑜伽健身";
        }
        return Organization::find()
            ->where(["style"=>1])
            ->andWhere(["name"=>$venueName])
            ->select("id")
            ->asArray()
            ->column();
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