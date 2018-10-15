<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/5/2
 * Time: 23:09
 */

namespace backend\modules\v1\models;

use common\models\base\MemberAccount;
use backend\models\Member;
use common\models\base\MessageCode;
use yii\base\Model;

class LoginCodeForm extends Model
{
    public $mobile;
    public $code;
    public $newCode;
    public $type;//验证码登录和验证区分 0为验证码 1为验证
    public $member;
    public $deviceNumber;      // 设备识别码
    const  MEMBER_PIC = 'http://oo0oj2qmr.bkt.clouddn.com/2x.png?e=1499151514&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dPF-Zr1Y8OtzB8iyxd8At9GufkU=';

    public $companySign;    // 公司标识
    public $companySignId;  // 公司标识id

    public $loginVenueId;  // 登录场馆id

    public function rules()
    {
        return [
            ["companySign",'required','message' => '公司标识不能为空'],
            [["loginVenueId"],"safe"],
            ['mobile', 'required', 'message' => '手机号不能为空'],
          //  ['mobile', 'exist', 'targetClass' => '\common\models\base\Member', 'message' => '您还未注册！'],
            ['mobile', 'isNotRegister'],
            ['code', 'trim'],
            ['code', 'required', 'message' => '验证码不能为空！'],
            ['deviceNumber', 'required', 'message' => '设备识别码不能为空！'],

            ['code', 'compare', 'compareAttribute' => 'newCode', 'message' => '验证码错误！'],
            ['code', 'newCodeTime'],
        ];
    }
    /**
     * 后台 - 验证码登录 - 检测会员是否有注册
     * @author houkaixin<houkaixin@itsport.club>
     * @create 2017/11/01
     * @param $attribute
     */
    public function isNotRegister($attribute){                                                              
        if (!$this->hasErrors()) {
             $checkResult = $this->judgeIsNotRegister();
             if($checkResult === false){
                 $this->addError($attribute, '您还没有注册');
             }
             if($checkResult === 3){
                 $this->addError($attribute, '您的信息有异常,请到场馆登记');
             }
        }
    }
    /**
     * 后台 - 登录 - 验证码登录（根据手机号判断会员是否有注册）
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     */
    public function judgeIsNotRegister(){
        // 1：获取公司标识id
        $model = new LoginForm();
        $model->mobile = $this->mobile;   // 手机号
        $model->companySign  = ($this->companySign=="WAYD")?"我爱运动瑜伽健身":"迈步运动健身";
        $this->companySignId = $model->getCompanyId();  // 获取公司id
        if(empty($this->loginVenueId)){
            $member = Member::find()->where(['mobile'=>$this->mobile,'company_id'=>$this->companySignId])->asArray()->one();
            $this->loginVenueId = $member['venue_id'];
        }

        // 2:判断会员是否有注册
        $endResult = $model->checkTheMember($this->companySignId,$this->loginVenueId);   // true:表示可以登录 false:表示不可以登录

        return $endResult;
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
            $admin = $this->getMemberAccountOne();
            if (!$admin) {
                $this->addError($attribute, '手机号未注册');
            }
        }
    }
    /**
     * 后台 - 登录 - 登录验证
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     */
    public function validateLogin()
    {
        $this->loadCode();
        if($this->validate()){
            $this->delCode();
            return $this->getMemberAccountList();
        }
        return false;
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
        if ($num > 300000){
            $this->addError($attribute, '验证码已失效！');
        }
    }
    /**
     * @云运动 - 后台 - 获取会员登录信息
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function getMemberOne()
    {
        if (empty($this->member)) {
            $member = new \backend\modules\v1\models\Member();
            $member->companySignId = $this->companySignId;               // 公司标识id
            $this->member = $member->getMemberOneData($this->mobile,$this->loginVenueId);     //查询会员基本信息
            $this->member['pic'] = $this->member["motto"];              // 会员头像修正
            if(empty($this->member['pic']))
            {
                $this->member['pic'] = self::MEMBER_PIC;             //设置默认头像
            }
            if(empty($this->member['nickname']))
            {
                $this->member['nickname'] = '暂无~';             //设置默认头像
            }
            $this->getMemberCardOne();
        }
        return $this->member;
    }
    /**
     * @云运动 - 后台 - 获取会员级别
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/1
     * @inheritdoc
     */
    public function getMemberCardOne()
    {
        $card   = new MemberCard();
        $query  = $card->getMemberCardNum($this->member['id']);
        if(isset($query)&&!empty($query["cardName"])){
            if(strpos($query["cardName"],'金爵') || strpos($query["cardName"],'尊爵')){
                $this->member["identify"] = 2;
            }else{
                $this->member["identify"] = 1;
            }
        }else{
            $this->member["identify"]     = 1;
        }
    }
    /**
     * 后台 - 登录 - 获取会员数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     */
    public function getMemberAccountOne()
    {
        return MemberAccount::find()->where(['mobile'=>$this->mobile,'company_id'=>$this->companySignId])->asArray()->one();
    }
    /**
     * 后台 - 登录 - 获取会员账号列表
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     */
    public function getMemberAccountList()
    {
        $member = new \backend\modules\v1\models\Member();
        $member->companySignId = $this->companySignId;          // 公司标识id
        return $member->getMemberAccOneData($this->mobile,$this->companySignId);
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

    /**
     * @云运动 - 后台 - 判断设备唯一性
     * @return int
     * @author wangliangliang <wangliangliang@itsports.club>
     */
    public function updeviceNumber(){
        $data =  MemberAccount::find()->where(['mobile'=>$this->mobile,'company_id'=>$this->companySignId])->asArray()->all();
        if ($data[0]['deviceNumber'] == '' || $data[0]['deviceNumber']==null){
            $MemberAccount = MemberAccount::findOne($data[0]['id']);
            $MemberAccount->deviceNumber=$this->deviceNumber;
            $MemberAccount->count = 1;
            $MemberAccount->save();
            return 1;
        }elseif ($data[0]['deviceNumber'] == $this->deviceNumber){
            if ($data[0]['count'] >= 4) {
                $MemberAccount = MemberAccount::findOne($data[0]['id']);
                $MemberAccount->count = 4;
                $MemberAccount->save();
                return 4;
            }
            return 1;
        }elseif ($data[0]['deviceNumber'] !== $this->deviceNumber){
            $MemberAccount = MemberAccount::findOne($data[0]['id']);
            if ($data[0]['count'] < 4) {
                $MemberAccount->count = $data[0]['count'] + 1;
                $MemberAccount->deviceNumber = $this->deviceNumber;
                $MemberAccount->save();
            }
            if ($data[0]['count'] == 1){
                return 2;
            }elseif ($data[0]['count'] == 2){
                return 3;
            }elseif ($data[0]['count'] >= 3){
                $MemberAccount = MemberAccount::findOne($data[0]['id']);
                $MemberAccount->count = 4;
                $MemberAccount->save();
                return 4;
            }
        }
    }
}