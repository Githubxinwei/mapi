<?php
namespace backend\modules\v1\models;

use backend\models\Config;
use backend\models\MemberDetails;
use common\models\base\Member;
use common\models\base\MemberAccount;
use common\models\base\MemberBase;
use common\models\base\MessageCode;
use yii\base\Model;

class BinkLandForm extends Model
{
    public $type;     //绑定的类型
    public $openId;   //唯一ID
    public $deviceNumber;      // 设备识别码
    public $mobile;
    public $companyId;
    public $venueId;
    public $password;
    public $member;
    public $code;  // 验证码
    const  MEMBER_PIC = 'http://oo0oj2qmr.bkt.clouddn.com/2x.png?e=1499151514&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dPF-Zr1Y8OtzB8iyxd8At9GufkU=';
    public $memberBelMessage;   // 会员所属信息


    public $companySign;   // 公司信息标识
    public $companySignId;  // 公司标识id
    public $accountId;      // 账号id
	public $name;   //姓名



    public function __construct(array $config,$scenario)
    {
        $this->scenario = $scenario;
        parent::__construct($config);
    }

    public function scenarios()
    {
        return [
            'login'=>['type','openId',"companySign","venueId"],
            'bink' =>['type','openId','mobile','password',"companySign","venueId",'deviceNumber'],
            'sign' =>['type','openId','mobile','companyId','venueId','password',"code",'deviceNumber','name'],
        ];
    }

    public function rules()
    {
        return [
            ["companySign",'required', 'message' => '唯一ID不能为空',"on"=>['bink',"login"]],
            ['mobile', 'required', 'message' => '电话不能为空','on' => ['bink','sign']],
            ['openId', 'required', 'message' => '唯一ID不能为空'],
            ['type', 'required', 'message' => '类型不能为空'],
            ['type', 'in', 'range'=>['wx', 'qq', 'wb', 'al','mi']],
     //       ['mobile', 'exist', 'targetClass' => '\common\models\base\Member', 'message' => '您还未注册！','on'=>'bink'],
            [['companyId','venueId','password'],'safe'],
            ["mobile","isNotRegister",'on'=>['bink']],
     //       ['mobile', 'unique', 'targetClass' => '\common\models\base\Member', 'message' => '您已经注册过了,请登录！','on'=>'sign'],
            ["mobile","checkIsNotRegister",'on'=>['sign']],  // 这个不用获取公司标识
            ['password','validatePassword','on'=>['bink']],
            ['code','checkCode',"on"=>['sign']],
            ['deviceNumber','required',"on"=>['sign']],//设备识别码不能为空
	        ['name','required',"on"=>['sign']],//设备识别码不能为空
        ];
    }

    /**
     * 后台 - 第三方登录 - 检测会员是否注册过(不含公司标识)
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/11/02
     * @param $attribute
     */
    public function checkIsNotRegister($attribute){
        // 判断 手机号是否注册过
        $endResult = $this->checkRegister();
        if($endResult===false){
            $this->addError($attribute,'您已经注册过了!');
        }
        // 判断 微信是否已经绑定在指定公司
        $this->companySignId = $this->companyId;
        $isNotBinkWx = $this->judgeIsNotBind();
        if($isNotBinkWx===false){
           $this->addError($attribute,'已经绑定过微信了!');
        }
    }
    /**
     * 后台 - 第三方登录 - 检测会员是否注册过(不含公司标识)逻辑代码
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/11/02
     */
    public function checkRegister(){
//        $member = \backend\models\Member::find()    // 检测会员是否注册过
//                      ->where(["and",["mobile"=>$this->mobile],["venue_id"=>$this->venueId]])
//                      ->count();
        // 获取公司标识id
       // $this->companySignId = empty($this->companySignId)?$this->gainCompanyId():$this->companySignId;
        $member  = MemberAccount::find()
                        ->where(["and",["mobile"=>$this->mobile],["company_id"=>$this->companyId]])
                        ->count();
        if($member!=0){     //已经注册过
          return false;
        }
        return true;      // 没有注册过
    }

    /**
     * 后台 - 第三方登录 - 获取公司id
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/11/01
     */
    public function gainCompanyId(){
        // 1：获取公司标识id
        $model = new LoginForm();
        $model->mobile = $this->mobile;   // 手机号
        $model->companySign  = ($this->companySign=="WAYD")?"我爱运动瑜伽健身":"迈步运动健身";
        $this->companySignId = $model->getCompanyId();  // 获取公司id
        return $this->companySignId;
    }


    /**
     * 后台 - 第三方登录 - 检测会员是否注册过
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/11/01
     * @param $attribute
     */
    public function isNotRegister($attribute){
        // 绑定前 判断 会员手机号是否注册
//        $endResult       = $this->judgeMemberIsNotRegister();
//        if($endResult===false){
//            $this->addError($attribute,'手机号还未注册!');
//        }
        // 绑定前 判断是否已经绑定过了
        $judgeIsNotBind  = $this->judgeIsNotBind();
        if($judgeIsNotBind===false){
            $this->addError($attribute,'您已经绑定过了!');
        }
        // 绑定过以后查询 该会员在该场馆是否能登录
//        $judgeIsNotLogin = $this->judgeIsNotLogin();
//        if($judgeIsNotLogin!==true){
//            if($judgeIsNotLogin==3){
//                $this->addError($attribute,'您在本场馆信息异常!');
//            }
//            if($judgeIsNotLogin==0){
//                $this->addError($attribute,'您在本场馆还没注册呢!');
//            }
//        }
    }

    

    public  function judgeIsNotLogin(){
         $num = Member::find()->where(["and",
                                      ["venue_id"=>$this->venueId],
                                      ["mobile"=>$this->mobile]])
                              ->count();
         if($num>1){
             return 3;   // 您在 该场馆 信息异常
         }
         if($num==0){
             return 1;  // 您在 该场馆还没注册
         }
         return true;  // 正常注册
    }

    public function judgeIsNotBind(){
        // 根据会员手机号  和  openid 和 公司标识 判断 会员是否 绑定过app
         $num = MemberBase::find()
                         ->where(["and",
                                 [$this->openIdField=>$this->openId],
                                 ["company_id"=>$this->companySignId]])
                         ->count();
         if($num>0){
            return false;
         }
         return true;
    }



    /**
     * 后台 - 第三方登录 - 检测第三方是否可以登录
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/11/01
     */
    public function judgeMemberIsNotRegister(){
        // 1：获取公司标识id
        $model = new LoginForm();
        $model->mobile = $this->mobile;   // 手机号
        $model->companySign  = ($this->companySign=="WAYD")?"我爱运动瑜伽健身":"迈步运动健身";
        $this->companySignId = $model->getCompanyId();  // 获取公司id
        // 2:判断会员是否有注册
        $endResult = $model->checkTheMember($this->companySignId);   // true:表示可以登录 false:表示不可以登录
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
        if (!$this->hasErrors()){
            $admin = $this->checkMemberMessage();
            if(empty($admin)){
                $this->addError($attribute, '您还没注册呢');
            }
            if (!empty($admin)) {
                if(!\Yii::$app->security->validatePassword($this->password,$admin['password'])){
                    $this->addError($attribute, '用户名或者密码出错');
                }
            }
        }
    }

    public function checkMemberMessage(){
       // 判断公司id是否存在
       if(empty($this->companySignId)){
           $model = new LoginForm();
           $model->companySign  = ($this->companySign=="WAYD")?"我爱运动瑜伽健身":"迈步运动健身";
           $this->companySignId = $model->getCompanyId();  // 获取公司id
       }
       $member = MemberAccount::find()
                        ->where(["and",
                                ["company_id"=>$this->companySignId],
                                ["mobile"=>$this->mobile]])
                        ->asArray()
                        ->one();
        $this->accountId = !empty($member)?$member["id"]:null;
        return $member;
    }
    /**
     * 后台 - 第三方登录 - 验证code输入是否正确
     * @author  houkaixin<houkaixin@itsport.club>
     * @create 2017/8/11
     * @param $attribute
     */
    public function checkCode($attribute){
        if (!$this->hasErrors()) {
            if (!$this->compareCode()) {
                $this->addError($attribute, '您输入的验证码有误');
            }
        }
    }
    /**
     * 后台 - 第三方登录 - 校验验证码
     * @author houkaixin<houkaixin@itsport.club>
     * @create 2017/8/21
     */
    public function compareCode()
    {
        $data          = MessageCode::find()->where(['mobile'=>$this->mobile])->orderBy('create_at DESC')->asArray()->one();
        $data["code"] = !empty($data["code"])?$data["code"]:"";
        if($data["code"]==$this->code){
            return true;
        }else{
            return false;
        }
    }
    //未注册第三方登陆
    public function landLogin()
    {
        if($this->validate()){
            // 获取公司id
            $this->getCompanyId();     // 获取公司id
            return $this->getMemberOne();
        }
        return $this->errors;
    }
    /**
     * 后台 - 第三方登录微信 - 获取公司标识id
     * @author houkaixin<houkaixin@itsport.club>
     * @create 2017/8/21
     */
    public function getCompanyId(){
        // 1：获取公司标识id
        $model = new LoginForm();
        $model->companySign  =  ($this->companySign=="WAYD")?"我爱运动瑜伽健身":"迈步运动健身";
        $this->companySignId = $model->getCompanyId();  // 获取公司id
    }
    //注册
    public function landSignUp()
    {
        if($this->validate()){
            $member = $this->setLandSignMember();
            $this->deleteCode();                         // 删除二维码
            $this->companySignId = $this->companyId;   //获取公司标识id
            return ["status"=>"success","data"=>$member];
        }
        return ["status"=>"error","data"=>$this->errors];
    }

    //删除验证码(操作成功删除)
    public function deleteCode(){
         MessageCode::deleteAll(["mobile"=>$this->mobile]);
    }

    public function binkMobile($deviceNumber)
    {
        if($this->validate()){
            // 判断是否有公司标识id(有的话 加上公司标识id)
            if(empty($this->companySignId)){
               $this->getCompanyId();
            }
            $memberAccont = MemberAccount::findOne(['mobile'=>$this->mobile, 'company_id'=>$this->companySignId]);
            if(empty($memberAccont)) return ['status'=>'error', 'data'=>[['message'=>'会员不存在']]];
            $data = MemberAccount::findOne($memberAccont['id']);
            $data->count=1;
            $data->deviceNumber=$deviceNumber;
            $data->save();
            $this->accountId = $memberAccont->id;

            $base = MemberBase::findOne(['member_account_id'=>$this->accountId]);
            if(empty($base)){
                $memberBase = new MemberBase();
                $memberBase->{$this->openIdField} = $this->openId;
                if(!empty($this->companySignId)){
                    $memberBase->company_id = $this->companySignId;  // 公司标识id
                }

                $memberBase->member_account_id = $this->accountId;  // 会员账号id

                $memberBase->create_at = time();
                $memberBase->update_at = time();
                if(!$memberBase->save()){
                    return $memberBase->errors;
                }
            }else{
                /*if($this->type == 'qq'){
                    $base->qq_open_id = $this->openId;
                }elseif ($this->type == 'wx'){
                    $base->wx_open_id = $this->openId;
                }else if ($this->type == 'wb'){
                    $base->wb_open_id = $this->openId;
                }*/
                $base->{$this->openIdField} = $this->openId;
                if(!empty($this->companySignId)){
                    $base->company_id = $this->companySignId;  // 公司标识id
                }
//                if(!empty($this->mobile)){
//                    $base->mobile      = $this->mobile;
//                }
                $base->update_at      = time();
                if(!$base->save()){
                    return $base->errors;
                }
            }
            return ["status"=>"success","data"=>$this->getMemberOne()];
        }
        return  ["status"=>"error","data"=>$this->errors];
    }
    //注册
    public function setLandSignMember()
    {
        $transaction = \Yii::$app->db->beginTransaction();                                                               //开启事务
        try {
            //账号表 存储数据 信息提示
            $saveAccount = $this->saveAccount();
            if($saveAccount["status"]!=="success"){
                throw new \Exception($saveAccount["data"]);
            }
            $member                = new \common\models\base\Member();
            $member->mobile        = !empty($this->mobile) ? $this->mobile : '0';
            $member->username      = empty($this->name)?$this->mobile:$this->name;
            $member->password      = $this->setPassword($this->password);
            $member->register_time = time();
            $member->status        = 1;
            $member->venue_id      = $this->venueId;
            $member->company_id    = $this->companyId;
            $member->member_type   = 2;
            $member->member_account_id = $saveAccount["data"];
            $member = $member->save() ? $member : $member->errors;
            if (isset($member->id)) {
                //会员详情表数据存储
                $details    = $this->setMemberDetails($member->id);
                if($details != true){
                    throw new \Exception($details);
                }
                // 微信绑定信息存储
                $memberBase = new MemberBase();
             //   $memberBase->member_id = $member->id;
                if($this->type == 'qq'){
                    $memberBase->qq_open_id = $this->openId;
                }elseif ($this->type == 'wx'){
                    $memberBase->wx_open_id = $this->openId;
                }else if ($this->type == 'wb'){
                    $memberBase->wb_open_id = $this->openId;
                }else if($this->type == 'al'){
                    $memberBase->alipay_open_id = $this->openId;
                }else if($this->type == 'mi'){
                    $memberBase->mi_open_id  = $this->openId;
                }
                    $memberBase->company_id         = empty($this->companySignId)?null:$this->companySignId; // 第三方公司id
                    $memberBase->member_account_id =  empty($saveAccount["data"])?null:$saveAccount["data"];
                    $memberBase->create_at = time();
                    $memberBase->update_at = time();
                if(!$memberBase->save()){
                    throw new \Exception($memberBase->errors);
                }
            }
            // 获取公司名称,场馆名称
            $model = new SignUpForm();
            $model->venueId = $this->venueId;
            $model->getBelongMessage();
            $this->memberBelMessage = $model->memberBelMessage;
            // 返回会员所能登录场馆的信息
            $member = new \backend\modules\v1\models\Member();
            $member->companySignId = $this->companySignId;          // 公司标识id
            $venueS                 = $member->getMemberAccOneData($this->mobile,$this->companySignId);
            $transaction->commit();
            return $venueS;
        }catch (\Exception $e){
            $transaction->rollBack();                                                                                        //事务回滚
            return  $e->getMessage();
        }
    }

    /**
     * @云运动 - 后台 - 会员账号的保存
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2018/01/19
     * @return array|bool
     */
    public function saveAccount(){
            $model = new MemberAccount();
            $model->username = !empty($this->mobile)?$this->mobile:null;
            $model->password = $this->setPassword($this->password);
            $model->mobile   = !empty($this->mobile)?$this->mobile:null;
            $model->company_id = !empty($this->companySignId)?$this->companySignId:null;
            $model->create_at  = time();
            $model->count=1;
            $model->deviceNumber = $this->deviceNumber;//设备识别码
        if(!$model->save()){
                return ["status"=>"error","data"=>$model->errors];
            }
            return ["status"=>"success","data"=>$model->id];
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
        $detail->sex        = 0;                          // 0表示未知
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
        return  $this->password = \Yii::$app->security->generatePasswordHash($password);
    }
//    /**
//     * @云运动 - 后台 - 设置密码
//     * @author lihuien <lihuien@itsports.club>
//     * @create 2017/4/1
//     * @inheritdoc
//     */
//    public function getMemberOne()
//    {
//        if (empty($this->member)) {
//            $member = new \backend\modules\v1\models\Member();
//            $this->member = $member->getMemberOneData($this->mobile);     //查询会员基本信息
//            $this->getMemberCardOne();
//
//        }
//        return $this->member;
//    }
    /**
     * 后台 - 登录 - 获取会员数据
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     */
    public function getMemberOne()
    {
        if($this->scenario == 'login'){
//            $base = MemberBase::find()->where(['or',['qq_open_id'=>$this->openId],['wx_open_id'=>$this->openId],['wb_open_id'=>$this->openId]])->asArray()->one();
/*            if($this->type == 'qq')
            {
                $base = MemberBase::find()->where(['qq_open_id'=>$this->openId])->asArray()->one();
            }else if($this->type == 'wx')
            {
                $base = MemberBase::find()
                          ->where(["and",['wx_open_id'=>$this->openId],["company_id"=>$this->companySignId]])
                          ->asArray()->one();
            }else if($this->type == 'wb')
            {
                $base = MemberBase::find()->where(['wb_open_id'=>$this->openId])->asArray()->one();
            }*/

            $base = MemberBase::find()->where([$this->openIdField=>$this->openId, 'company_id'=>$this->companySignId])->asArray()->one();
            if(empty($base)){            //未进行第三方绑定
                return false;
            }
            // 先进行该会员的数量判断
//            $num = Member::find()
//                          ->where(["and",['mobile'=>$base['mobile']],["venue_id"=>$this->venueId]])
//                          ->count();
//            if($num==0){
//                return "noRegister";      // 未注册
//            }
//            if($num>1){
//                return "messageUnusual"; // 信息异常
//            }
            // 第三方绑定以后  查询基本信息
            $member = MemberAccount::findOne($base["member_account_id"]);
            $this->mobile = $member->mobile;
//            $member = Member::find()
//                           ->where(["and",['mobile'=>$base['mobile']],["venue_id"=>$this->venueId]])
//                           ->one();
//            $this->mobile = $member->mobile;
        }

        if (empty($this->member)) {
            $member = new \backend\modules\v1\models\Member();
            if(!empty($this->companySignId)){
                 $member->companySignId = $this->companySignId;   // 赋值公司标识
            }
            $this->member = $member->getMemberAccOneData($this->mobile,$this->companySignId);
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

    public function getOpenIdField()
    {
        $fields = ['wx'=>'wx_open_id', 'qq'=>'qq_open_id', 'wb'=>'wb_open_id', 'al'=>'alipay_open_id','mi'=>'mi_open_id'];
        return $fields[$this->type];
    }
}