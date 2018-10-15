<?php
namespace backend\modules\v1\models;

use backend\models\Organization;
use common\models\base\Member;
use common\models\base\MemberAccount;
use yii\base\Model;
use Yii;

class LoginForm extends Model
{
    public $mobile;
    public $password;
    public $deviceNumber;      // 设备识别码
    public $member = array();
    public $companySign;   // 公司标识
    public $accountId;   // 账户
    public $companySignId;  // 公司标识id
    const  MEMBER_PIC = 'http://oo0oj2qmr.bkt.clouddn.com/2542841526296296.png?e=1526299896&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:lWl3lYosFvkjo1ESo1yAvbpHfvo=';


    public $loginVenueId;  // 场馆登录id

    /**
     * 后台 - 登录 - 验证规则
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     */
    public function rules()
    {
        return [
            ["companySign",'required','message' => '公司标识不能为空'],
            [["loginVenueId"],"safe"],             // 场馆登录id
            ['mobile', 'required','message' => '手机号不能为空'],
      //      ['mobile', 'exist', 'targetClass' => '\common\models\base\Member', 'message' => '您还未注册！'],
            ["mobile","isNotRegister"],
            ['password','required', 'message' => '密码不能为空'],
            ['deviceNumber','required', 'message' => '设备识别码不能为空'],
            ['password','validatePassword']
        ];
    }
    /**
     * 后台 - 登录 - 检查在该场馆 是否注册过
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/10/23
     * @param $attribute
     */
    public function isNotRegister($attribute){
         $companyId = "";
         if(!empty($this->companySign)){
             $this->companySign = ($this->companySign=="WAYD")?"我爱运动瑜伽健身":"迈步运动健身";
             // 获取公司id
             $companyId = $this->getCompanyId();
         }
        if(empty($this->loginVenueId)){
            $member = Member::find()->where(['mobile'=>$this->mobile,'company_id'=>$this->companySignId])->asArray()->one();
            $this->loginVenueId = $member['venue_id'];
        }
         $this->textIsRegister($attribute,$companyId,$this->loginVenueId);     // 测试在指定公司下的场馆是否注册过
    }
    /**
     * 后台 - 登录 - 获取app的公司id
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/10/23
     * @return int    //返回最后的公司id
     */
    public function getCompanyId(){
        // 先查询公司id
        $company   =  Organization::find()
            ->where(["and",["like","name",$this->companySign],["pid"=>0]])
            ->select("name,id,pid")
            ->one();
        $companyId = $company->id;
        $this->companySignId = $companyId;    //（2017-11-01后加新需求）
        return $companyId;
    }
    /**
     * 后台 - 登录 - 测试该会员是否在该公司注册过（Validates the password）
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/10/23
     * @param $attribute   // 验证参数
     * @param $companyId  // 公司id
     * @param $venueId    // 场馆id
     * @return boolean    // 检测最后的返回值
     */
    public function textIsRegister($attribute,$companyId,$venueId){
        if (!$this->hasErrors()){
             $isNotTheMember  = $this->checkTheMember($companyId,$venueId);
            if ($isNotTheMember===false){
                 $this->addError($attribute, '您还没有注册');
            }
            if($isNotTheMember === 3){
                $this->addError($attribute, '您的信息有异常,请到场馆登记');
            }
        }
    }
    /**
     * 后台 - 登录 - 检测该场馆 是否有该会员
     * @author houkaixin<houkaixin@itsport.club>
     * @param  $companyId   // 公司id
     * @param  $venueId     // 场馆id
     * @create 2017/10/23
     * @return boolean    // 检测最后的返回值
     */
    public function checkTheMember($companyId,$venueId=""){
         if(empty($companyId)){
            return false;
         }
         $theMember =   MemberAccount::find()
                        ->where(["and",["company_id"=>$companyId],["mobile"=>$this->mobile]]);
//         if(!empty($venueId)){
//              $theMember->andWhere(["venue_id"=>$venueId]);
//         }
         $theMember = $theMember->count();
         if($theMember==1){
             return true;       // 该会员可以登录(信息只有一条)
         }
         if($theMember==0){
             return false;    // 会员还没有注册
         }
             return 3;        // 该会员信息异常

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
            if ($admin) {
                if(!\Yii::$app->security->validatePassword($this->password,$admin['password'])){
                    $this->addError($attribute, '用户名或者密码出错');
                }
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
        if($this->validate()){

            $this->getMemberAccountList();
//            $this->getMemberCardOne();
            return $this->member;
        }
        return false;
    }
    /**
     * 后台 - 登录 - 获取会员数据
     * @author  lihuien <lihuien@itsport.club>
     * @param    $mId 会员ID
     * @create 2017/3/30
     * @return array
     */
    public function getMemberOne($mId)
    {
        $memberid = array_column(Member::find()->where(['member_account_id'=>$mId])->all(),'id');
        if (empty($this->member)) {
            $member = new \backend\modules\v1\models\Member();
//            $member->companySignId = $this->companySignId;          // 公司标识id
            $this->member =  $member->getMemberOneData($memberid[0]);
            // 对pic 重新定义值
            $this->member["pic"] = $this->member["motto"];           // * 推线
            if(empty($this->member['pic']))
            {
                $this->member['pic'] = self::MEMBER_PIC;             //设置默认头像
            }
            if(empty($this->member['nickname']))
            {
                $this->member['nickname'] = '暂无~';                //设置默认头像
            }
            if(empty(strtotime($this->member['birth_date']))  || $this->member['birth_date'] == "0000-00-00")
            {
                $this->member['birth_date'] = '';
            }
            if(empty($this->member['now_address']))
            {
                $this->member['now_address'] = '';
            }
            if(empty($this->member['profession']))
            {
                $this->member['profession'] = '';
            }
            if(empty($this->member['introduction']))
            {
                $this->member['introduction'] = '';                
            }
            return $this->member;
        }
        return $this->member;
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
        if (empty($this->member)) {
            $member = new \backend\modules\v1\models\Member();
            $member->companySignId = $this->companySignId;          // 公司标识id
            $this->member =  $member->getMemberAccOneData($this->mobile,$this->companySignId);
        }
    }
    /**
     * @云运动 - 后台 - 获取会员级别
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/13
     * @inheritdoc
     */
    public function getMemberCardOne()
    {
        $card   = new MemberCard();
        $query  = $card->getAllMemberCard($this->accountId,[1,2,3,4]);
//        $member = $this->member->toArray();
        if(!empty($query)){
            foreach ($query as $keys=>$values){
                if(!empty($values)&&!empty($values["card_name"])){
                    if(strpos($values["card_name"],'金爵') || strpos($values["card_name"],'尊爵')){
                        $this->member["identify"] = 2;
                        continue;
                    }else{
                        $this->member["identify"] = 1;
                    }
                }else{
                    $this->member["identify"]   = 1;
                }
            }
            return true;
        }
        $this->member["identify"]   = 1;
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
            if ($data[0]['count']==1){
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