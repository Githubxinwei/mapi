<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/5/2
 * Time: 22:52
 */

namespace backend\modules\v1\models;


use common\models\base\Member;
use common\models\base\MemberAccount;
use common\models\base\MessageCode;
use yii\base\Model;

class ModifyPasswordForm extends Model
{
    public $mobile;
    public $code;
    public $newCode;
    public $password;
    public $oldPassword;
    public $rePassword;
    public $member;

    public $account_id;

    /**
     * 后台 - 修改密码 - 验证规则
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @return array
     */
    public function rules()
    {
        return [
            ['mobile', 'trim'],
            ['mobile', 'required', 'message' => '手机号不能为空！'],
            ['account_id', 'required', 'message' => '账户不能为空！'],
            ['mobile', 'string', 'max' => 200],
            ['mobile', 'exist', 'targetClass' => '\common\models\base\Member', 'message' => '您还未注册！'],

            ['code', 'trim'],
            ['code', 'required', 'message' => '验证码不能为空！'],
            ['code', 'compare', 'compareAttribute' => 'newCode', 'message' => '验证码错误！'],
            ['code', 'newCodeTime'],
            ['oldPassword','required','message'=>'旧密码不能为空'],
            ['oldPassword','validatePassword'],

            ['password', 'trim'],
            ['password', 'required', 'message' => '密码不能为空！'],
            ['password', 'string', 'min' => 6, 'max' => 255],

            [['account_id'],'safe'],
        ];
    }

    /**
     * 后台 - 登录 - 验证密码（Validates the password）
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     * @param $attribute
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $admin = $this->getMemberOne();
            if ($admin) {
                if(!\Yii::$app->security->validatePassword($this->oldPassword, $admin['password'])){
                    $this->addError($attribute, '旧密码输入不正确');
                }
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
        return MessageCode::find()->where(['mobile'=>$this->mobile])->orderBy('create_at DESC')->asArray()->one();
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
     * @create 2017/4/6
     * @inheritdoc
     */
    public function updatePassword()
    {
        if(empty($this->account_id)){
            $member = MemberAccount::findOne(['mobile' => $this->mobile]);
        }else{
            $member = MemberAccount::findOne($this->account_id);
        }
        $member->password = $this->setPassword($this->password);
        if($member->save()){
            $this->delCode();
            return true;
        }else{
            return $member->errors;
        }
    }
    /**
     * @云运动 - API - 设置密码
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function setPassword($password)
    {
        return  $this->password = \Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * @云运动 - API - 获取会员登录数据
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function getMemberOne()
    {
        if (!isset($this->member) && empty($this->member)) {
            $member = new \backend\modules\v1\models\Member();
            if(empty($this->account_id)){
                $this->member = $member->getMemberOneData($this->mobile);
            }else{
                    $this->member = MemberAccount::find()->where(['id'=>$this->account_id])->asArray()->one();
            }
        }
        return $this->member;
    }
    /**
     * @云运动 - API - 删除验证码
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