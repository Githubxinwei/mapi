<?php
namespace backend\models;
use common\models\base\MemberCabinetRentHistory;
use common\models\base\MemberDetails;
use common\models\base\Order;
use common\models\base\Employee;
use common\models\base\Organization;
use common\models\Func;
use Yii;
use yii\base\Model;

class CabinetRuleForm extends Model
{
    public $originalCabinetId; //原始柜子Id
    public $cabinetId;       //柜子id
    public $memCabinetId;    //会员柜子id
    public $quiteDate;       //退租日期

    public $organizationId;  //组织场馆id
    public $cabinetTypeId;   //柜子类型id
    public $cabinetNum;       //柜子数量
    public $dayRentPrice;     //日租用价格
    public $monthRentPrice;  //月租用价格
    public $yearRentPrice;   //年租用价格
    public $halfYearRentPrice;//半年价格
    public $cabinetModel;    //柜子型号
    public $cabinetType;     //柜子类别
    public $deposit;         // 押金
    public $cabinetPrefix;   //柜子前缀
    public $cabinetNumStart;  //柜子起始编号
    //新增
    public $cabinetMonth; //多月设置月数
    public $cabinetMoney; //多月设置金额
    public $cabinetDis;   //多月设置折扣
    public $sex;              //柜子针对人群性别
    public $cabinetTypeName; //柜子类型名字
    public $giveMonth;       //赠送月份
    public $cabinetNumber;  //柜子编号

    public $adminOperator;   //经办人
    public $cardNum;         //卡号
    public $theMobile;       //移动电话
    public $userName;        //用户名
    public $date;            //办理日期

    public $renewDate;     //续组日期
    public $renewNumDay;   //续组天数
    public $renewRentPrice; //续租补价

    public $memberId;           //会员id
    public $cabinetRentStart;  //租柜开始日期
    public $cabinetRentEnd;     //租柜结束日期
    public $price;               //租用价格

    public $changeCabinetDate;  //调柜 更正 柜子到期日期

    public $memberCardId;       //会员卡id
    public $give_month;         //租柜给客户赠送的月数
    /**
     * @云运动 - 后台 - 场景多表单定义
     * @create 2017/5/3
     * @return array
     */
    public function  scenarios(){
        return [
            'changeCabinet'      =>["memCabinetId","cabinetId","originalCabinetId","changeCabinetDate","price","memberId"],
            "quiteCabinet"       =>["memberCardId","memCabinetId","quiteDate","memberId","cabinetId","price","quiteDate"],
            "addCabinet"         =>["cabinetTypeId","giveMonth","deposit","cabinetNum","monthRentPrice","cabinetMoney",
                "cabinetDis", "cabinetModel","cabinetType","cabinetPrefix","cabinetNumStart","cabinetMonth"],
            "addCabinetType"     =>["cabinetTypeName"],
            "bindMember"         =>["adminOperator","cardNum","theMobile","userName","date","cabinetId","give_month"],
            "updateCabinet"      =>["dayRentPrice","yearRentPrice","monthRentPrice","adminOperator","memCabinetId","cabinetId","date"],
            "renewCabinet"       =>["memberId",'memCabinetId',"renewDate","renewNumDay","renewRentPrice","memberCardId","give_month"],
            "bindNewMember"      =>["memberId","cabinetRentStart","cabinetRentEnd","price","cabinetId","memberCardId","deposit","give_month"],
            "update"             =>["cabinetModel","cabinetType","cabinetId","monthRentPrice","yearRentPrice","deposit",
                "cabinetDis","cabinetMonth","cabinetMoney","giveMonth"],
            "modifyCabinet"      =>["cabinetTypeId","giveMonth","deposit","cabinetNum","monthRentPrice","cabinetMoney","cabinetId",
                "cabinetDis", "cabinetModel","cabinetType","cabinetPrefix","cabinetNumStart","cabinetMonth"],
            "deleteCabinet"      =>["cabinetId","cabinetNum"],
            "quitCabinetSetting" => ['setDays',"dateType","setCost"],
        ];
    }
    /**
     * @云运动 - 后台 - 柜子管理验证(规则验证)
     * @create 2017/5/3
     * @return array
     */
    public function rules()
    {
        return [
            [["cabinetId","sex","memCabinetId","cabinetTypeName","quiteDate",
                "organizationId","cabinetTypeId","cabinetNum","originalCabinetId",
                "adminOperator","cardNum","theMobile","userName","date",
                'renewDate',"renewNumDay","renewRentPrice","memberId","cabinetRentStart","cabinetRentEnd","cabinetModel","cabinetType"], 'required',
                'on'=>["bindMember",'changeCabinet',"quiteCabinet","addCabinet","addCabinetType","updateCabinet","renewCabinet","bindNewMember"]],
            [["venueAddress","changeCabinetDate","dayRentPrice","monthRentPrice","halfYearRentPrice","price","cabinetDis","cabinetMoney",
                "yearRentPrice",'giveMonth',"memberCardId","deposit","cabinetPrefix","cabinetNumStart","cabinetMonth","cabinetTypeId",'setDays',"dateType","setCost",'give_month'],"safe",
                'on'=>["addCabinet","update",'changeCabinet',"quiteCabinet","bindNewMember","renewCabinet","modifyCabinet","deleteCabinet","quitCabinetSetting"]],
        ];
    }

    /**
     * 云运动 - 后台- 客户调柜修改
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/5/3
     * @param $data // 发送过来的参数
     * @param $companyId;  //公司id
     * @param $venueId     //场馆id
     * @return boolean/object
     */
    public  function changeCabinet($data,$companyId,$venueId){
        $transaction = \Yii::$app->db->beginTransaction();                //开启事务
        try {
            $model  = MemberCabinet::findOne($this->memCabinetId);
            $startDate  = $model->start_rent;
            $endDate    = $model->end_rent;
            if(empty($model  ->change_cabinet_remark)){
                $model  ->change_cabinet_remark = json_encode([[date("Y-m-d",time()),$model->cabinet_id,$this->cabinetId]]);
            }else{
                $dataS = (array)json_decode($model  ->change_cabinet_remark);
                $dataS[] = [date("Y-m-d",time()),$model->cabinet_id,$this->cabinetId];
                $model  ->change_cabinet_remark = json_encode($dataS);
            }
            $model  ->cabinet_id             = $this->cabinetId;
            $model  ->price                   = (int)$model  ->price + $this->price;
            //如果有更改日期，修改柜子的租赁日期
            if(isset($this->changeCabinetDate)&&!empty($this->changeCabinetDate)){
                $model->end_rent   = strtotime($this->changeCabinetDate);
                $endDate = strtotime($this->changeCabinetDate);
            }
            $result = $model->save();
            if(!$result){
                \Yii::trace($model->errors);
                throw new \Exception("操作失败");
            }
            //同时原始柜子租用状态改为 未租用
            $cabinet = Cabinet::findOne(["id"=>$this->originalCabinetId]);
            $cabinet->status = 1;
            $cabinetResult = $cabinet->save();
            if(!$cabinetResult){
                \Yii::trace($cabinet->errors);
                throw new \Exception("操作失败");
            }
            //现有租用柜子状态改为租用
            $nowCabinet          = Cabinet::findOne(["id"=>$this->cabinetId]);
            $nowCabinet->status = 2;
            $nowCabinetResult    = $nowCabinet->save();
            if(!$nowCabinetResult){
                \Yii::trace($cabinet->errors);
                throw new \Exception("操作失败");
            }
            // 会员租用历史记录表数据录入
            $memCabinetHistoryTableInsert = $this->cabinetHistoryTableInsert([$startDate,$endDate],"调柜",$this->price);
            if($memCabinetHistoryTableInsert["status"]!=="success"){
                return $memCabinetHistoryTableInsert;
            }

            //消费历史记录表 数据录入
            $consumeHistory = new ConsumptionHistory();
            $combineData    = $this->combineData($companyId,$venueId,$memCabinetHistoryTableInsert,
                "cabinet","调柜",0,$this->price,$this->cabinetRentEnd);
            $insertResult = $consumeHistory->cabinetDataInsertHistory($combineData);
            if($insertResult!=="success"){
                return $insertResult;
            }
            //执行数据递交
            if($transaction->commit()==null)
            {
                return true;
            }else{
                return "数据保存失败" ;
            }
        }catch(\Exception $e){
            //如果抛出错误则进入 catch ,先callback,然后捕捉错误，返回错误
            $transaction->rollBack();
            return  $error = $e->getMessage();  //获取抛出的错误
        }
    }
    /**
     * 云运动 - 后台- 柜子退租
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/5/3
     * @param   $companyId  // 公司id
     * @param   $venueId    // 场馆id
     * @return boolean/object
     */
    public  function quiteCabinet($companyId,$venueId){
        $memberCabinetModel  = MemberCabinet::findOne($this->memCabinetId);

        // 会员柜子历史表的数据录入
        $memCabinetHistoryTableInsert = $this->cabinetHistoryTableInsert([$memberCabinetModel->start_rent,$memberCabinetModel->end_rent],"退押金","-".$this->price);
        if($memCabinetHistoryTableInsert["status"]!=="success"){
            return "租柜历史记录表保存失败";
        }
        //对操作柜子的 执行删除
        $deleteResult  = $memberCabinetModel->delete();
        if($deleteResult!=1){
            return "当前会员柜子表删除失败";
        }

        // 退租柜子的 状态 2变1
        $cabinetModel = new Cabinet();
        $result = $cabinetModel->updateCabinetStatus($this->cabinetId,"quiteCabinet");
        if($result!="success"){
            return $result;
        }
        //消费历史记录表 数据录入
        $consumeHistory = new ConsumptionHistory();
        $combineData    = $this->combineData($companyId,$venueId,$memCabinetHistoryTableInsert,
            "cabinet","退柜",0,$this->price,$this->cabinetRentEnd);
        $quiteResult = $consumeHistory->cabinetDataInsertHistory($combineData);
        if($quiteResult!="success"){
            return $quiteResult;
        }
        //查找订单
        $data = Order::findOne(['member_id'=>$this->memberId,'venue_id'=>$venueId,'company_id'=>$companyId,'note'=>'租柜','consumption_type'=>'cabinet']);
        $adminModel = Employee::findOne(['admin_user_id'=>\Yii::$app->user->identity->id]);
        if (!empty($data)) {
            $order = new Order();
            $order->venue_id  = $data->venue_id;
            $order->member_id = $data->member_id;
            $order->card_category_id = $data->card_category_id;
            $order->total_price = $this->price;
            $order->order_time = time();
            $order->pay_money_time = time();
            $order->pay_money_mode = 1;
            $order->sell_people_id = \Yii::$app->user->identity->id;
            $order->payee_id = \Yii::$app->user->identity->id;
            $order->create_id = \Yii::$app->user->identity->id;
            $order->status = 5;
            $order->note = '退柜';
            $number = Func::setOrderNumber();
            $order->order_number = "{$number}";
            $order->card_name = '退柜';
            $order->sell_people_name = $adminModel->name;
            $order->payee_name = $adminModel->name;
            $order->member_name = $data->member_name;
            $order->pay_people_name = $data->pay_people_name;
            $order->company_id = $data->company_id;
            $order->consumption_type_id = $data->consumption_type_id;
            $order->consumption_type = 'cabinet';
            $order->deposit = 0;
            $order->cash_coupon = 0;
            $order->net_price = $this->price;
            $order->all_price = $this->price;
            $order->apply_time = time();
            $order->review_time = time();
            $order->purchase_num = 1;
            if (!$order->save()) {
                return $order->errors;
            }
        }
        return true;
    }
    /**
     * 云运动 - 后台-  消费历史记录表数据录入
     * @author houkaixin <houkaixin@itsports.club>
     * @param  $companyId     //公司id
     * @param  $venueId      // 场馆id
     * @param  $memCabinetHistoryTableInsert  //会员柜子历史记录表 数据录入
     * @param  $consumeStatus    //消费类型
     * @param  $status           // 消费状态
     * @param  $deposit          // 押金
     * @param  $rentMoney        //租金
     * @create 2017/6/19
     * @return boolean/object
     */
    public  function combineData($companyId,$venueId,$memCabinetHistoryTableInsert,$consumeStatus,$status,$deposit,$rentMoney,$due_date){
        $data = [];
        $deposit = !empty($deposit)?$deposit:0;
        $data["companyId"]       = $companyId;
        $data["venueId"]         = $venueId;
        $data["consumeTypeId"]  = (int)$memCabinetHistoryTableInsert["data"];     //消费类型id
        $data["consumeStatus"]  = $consumeStatus;
        $data["memberId"]        = $this->memberId;
        $data["status"]           = $status;
        $data["price"]            = $this->price;
        $data['due_date']         = $due_date;
        $data["consumeDescribe"] = json_encode(["押金"=>$deposit,$status=>$rentMoney]);
        return $data;
    }
    /**
     * 云运动 - 后台-  会员柜子历史表数据录入
     * @author houkaixin <houkaixin@itsports.club>
     * @param  $theModel   //模型变量
     * @param  $type      // 柜子类型（续组，调柜等）
     * @param  $price     // 租用价格
     * @create 2017/6/19
     * @return boolean/object
     */
    public function cabinetHistoryTableInsert($theModel,$type,$price){
        $model = new MemberCabinetRentHistory();
        $model->member_id      = $this->memberId;
        $model->price           = (int)$price;
        $model->start_rent     = (int)$theModel[0];
        $model->end_rent       = (int)$theModel[1];
        $model->back_rent      =  !empty($this->quiteDate)?strtotime($this->quiteDate):null;
        $model->create_at      = time();
        $model->cabinet_id     = $this->cabinetId;
        $model->member_card_id = !empty($this->memberCardId)?$this->memberCardId:null;
        $model->rent_type      = $type;
        $model->give_month     = $this->give_month;
        $model->create_id      = isset(Yii::$app->user->identity->id)?Yii::$app->user->identity->id:null ;
        if(!$model->save()){
            return ["status"=>"error","data"=>$model->errors];
        }
        return ["status"=>"success","data"=>$model->id];
    }

    /**
     * 云运动 - 后台- 新增柜子
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/5/3
     * @param $venueId   //场馆id
     * @param $companyId  //公司id
     * @return  string  //返回报错信息
     */
    public function addCabinet($venueId,$companyId){
        $transaction = \Yii::$app->db->beginTransaction();                //开启事务
        try {
            // 获取最后一个柜子编号
            $result = Cabinet::find()->where(["venue_id"=>$venueId])->select("cabinet_number")->asArray()->orderBy("cabinet_number DESC")->all();
            $cabinetNumber = count($result);
            //查场馆名字
            $myName = Organization::find()->where(["id"=>$venueId])->select("name")->asArray()->one();
            for ($i = 1; $i <= $this->cabinetNum; $i++) {
                if($this->cabinetNumStart == null){
                    $startNum = $cabinetNumber;
                }else{
                    $startNum = ($this->cabinetNumStart - 2) + $i;
                }
                $cabinetNumber = $startNum + 1;
//                $num   = strlen($cabinetNumber);
//                $dLen  = 4-$num;
//                if($dLen>0){
//                    $str           = str_repeat("0",$dLen);
//                    $cabinetNumber =$str.$cabinetNumber;
//                }
                //柜子编号的录入
                $model = new Cabinet();
                $model->cabinet_type_id  = $this->cabinetTypeId;
//                $model->cabinet_number   = (string)$myName["name"]."-".$cabinetNumber;
                if($this->cabinetNumStart == null){
                    $model->cabinet_number   = $cabinetNumber;
                }else{
                    $model->cabinet_number   = $this->cabinetPrefix.$cabinetNumber;
                }
                $model->status           = 1;
                $model->creater_id       = Yii::$app->user->identity->id;
                $model->created_at       = time();
                $model->update_at        = time();
                $model->monthRentPrice   = $this->monthRentPrice;
                $model->yearRentPrice    = $this->yearRentPrice;
                $model->company_id       = $companyId;
                $model->venue_id          = $venueId;
                $model->cabinet_model    = $this->cabinetModel;
                $model->cabinet_type     = $this->cabinetType;
                $model->deposit          = $this->deposit;
                $model->give_month       = json_encode($this->giveMonth);
                $model->cabinet_month    = json_encode($this->cabinetMonth);
                $model->cabinet_money    = json_encode($this->cabinetMoney);
                $model->cabinet_dis      = json_encode($this->cabinetDis);
                $result = $model->save();
                if(!$result){
                    \Yii::trace($model->errors);
                    throw new \Exception($model->errors);
                    break;
                }
            }
            //执行数据递交
            if($transaction->commit()==null)
            {
                return true;
            }else{
                return "数据保存失败" ;
            }
        }catch(\Exception $e){
            //如果抛出错误则进入 catch ,先callback,然后捕捉错误，返回错误
            $transaction->rollBack();
            return  $error = $e->getMessage();  //获取抛出的错误
        }
    }

    /**
     * 云运动 - 后台-  新柜子管理 - 添加柜子类型
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/5/8
     * @param $venueId
     * @param $companyId
     * @return  string  //返回报错信息
     */
    public function addCabinetType($venueId,$companyId){
        $model = new CabinetType();
        $model->type_name   = $this->cabinetTypeName;
        $model->created_at  = time();
        $model->venue_id    = $venueId;
        $model->company_id  = $companyId;
        $model->sex          = 3;
        $result = $model->save();
        if($result){
            return true;
        }else{
            return $model->errors;
        }
    }
    /**
     * 云运动 - 后台- 绑定会员
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/5/9
     * @param
     * @return  string  //返回报错信息
     */
    public function bindMember(){
        //先查询是否这个会员
        $mem = Member::find()->where(["AND",["username"=>$this->userName],["mobile"=>$this->theMobile]])->asArray()->one();
        if(empty($mem)){
            return "会员名或电话号有误";
        }
        //查询经办人是否 正确
        $employee = Employee::find()->where(["name"=>$this->adminOperator])->one();
        if (empty($employee)){
            return "经办人不存在";
        };
        // 查询 会员卡号是否存在
        $memberCard = MemberCard::find()->where(["card_number"=>$this->cardNum])->one();
        if(empty($memberCard)||$memberCard["member_id"]!=$mem["id"]){
            return "会员卡号有误或不存在";
        }
        //开始数据录入
        $model = new MemberCabinet();
        $model ->member_id  = $mem["id"];
        $model ->price      = 111;
        $model ->end_rent   = 44444;
        $model->status      = 1;
        $model->creater_id  = $employee["id"];
        $model->create_at   = time();
        $model->end_rent    = time()+111111111;
        $model->update_at   = time();
        $model->cabinet_id  = $this->cabinetId;
        //同时修改对应柜子租用状态、
        $rentStatus = Cabinet::findOne(["id"=>$this->cabinetId]);
        $rentStatus->status = 2;
        $rentResult  = $rentStatus->save();
        $result  = $model->save();
        if($result&&$rentResult){
            return "success";
        }else{
            return "error";
        }
    }
    /**
     * 云运动 - 后台- 柜子信息修改
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/5/9
     * @param
     * @return  string  //返回报错信息
     */
    public function updateCabinet(){
        $transaction = \Yii::$app->db->beginTransaction();                //开启事务
        try {
//            //先查询经办人是否存在
//            $operator = Employee::find()->where(["name" => $this->adminOperator])->asArray()->one();
//            if (empty($operator)) {
//                return "经办人不存在";
//            }
            //柜子信息修改
            $cabinet = Cabinet::findOne(["id" => $this->cabinetId]);
            $cabinet->monthRentPrice = $this->monthRentPrice;
            $cabinet->yearRentPrice  = $this->yearRentPrice;
            $cabinet->cabinet_model  = $this->cabinetModel;
            $cabinet->cabinet_type   = $this->cabinetType;
            $cabinet->deposit        = $this->deposit;
            $cabinet->cabinet_month  = json_encode($this->cabinetMonth);
            $cabinet->cabinet_dis    = json_encode($this->cabinetDis);
            $cabinet->cabinet_money  = json_encode($this->cabinetMoney);
            $cabinet->give_month     = json_encode($this->giveMonth);
            $cabinetResult = $cabinet->save();
            if(!$cabinetResult){
                \Yii::trace($cabinet->errors);
                throw new \Exception("修改失败");
            }
            //会员柜子信息修改
//            $model = MemberCabinet::findOne(["id" => $this->memCabinetId]);
//            $model->end_rent = strtotime($this->date);
//            $model->creater_id = $operator["id"];
//            $result = $model->save();
//            if(!$result){
//                \Yii::trace($model->errors);
//                throw new \Exception("操作失败");
//            }
            //执行数据递交
            if($transaction->commit()==null)
            {
                return "success";
            }else{
                return "error";
            }
        }catch(\Exception $e){
            //如果抛出错误则进入 catch ,先callback,然后捕捉错误，返回错误
            $transaction->rollBack();
            return  $error = $e->getMessage();  //获取抛出的错误
        }
    }
    /**
     * 云运动 - 后台- 新柜子管理-柜子续组
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/5
     * @param $companyId   //公司id
     * @param $venueId     // 场馆id
     * @param $data        // 发送参数
     * @return  string  //返回报错信息
     */
    public function renewCabinet($companyId,$venueId,$data){
        $model = MemberCabinet::findOne(["id"=>$this->memCabinetId]);
        // 续租起始日期
        $startRent      = (int)$model->end_rent+60*60*24;
        $this->cabinetId = $model->cabinet_id;
        // 会员租用历史记录表数据录入
        $memCabinetHistoryTableInsert = $this->cabinetHistoryTableInsert([$startRent,strtotime($this->renewDate)],"续组",$this->renewRentPrice);
        if($memCabinetHistoryTableInsert["status"]!=="success"){
            return "租柜历史记录表保存失败";
        }
        // 当前会员柜子表数据 修正保存
        $model->end_rent        = strtotime($this->renewDate);
        $model->price           = (int)$model->price + $this->renewRentPrice;
        if(!$model->save()){
            return $model->errors;
        }

        //消费历史记录表 数据录入
        $consumeHistory = new ConsumptionHistory();
        $this->price    = $this->renewRentPrice;
        $combineData    = $this->combineData($companyId,$venueId,$memCabinetHistoryTableInsert,
            "cabinet","续租柜子",0,$this->price,$startRent-60*60*24);
        $quiteResult = $consumeHistory->cabinetDataInsertHistory($combineData);
        $re = $this->addOrderRecord($companyId,$venueId,$this->memCabinetId,'续柜');
        if (!isset($re->id)) {
            return $re;
        }
        if($quiteResult!=="success"){
            return $quiteResult;
        }
        return true;
    }
    /**
     * 云运动 - 后台- 新柜子管理- 绑定会员
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/8
     * @param $companyId  // 公司id
     * @param $venueId    //场馆id
     * @param $data      //前台发送过来的参数
     * @return  string  //返回报错信息
     */
    public function bindTheMember($data,$companyId,$venueId)
    {
        $model = new MemberCabinet();
        $model->member_id   = $this->memberId;
        $model->price        = $this->price;
        $model->creater_id  = 1;
        $model->start_rent  = (int)strtotime($this->cabinetRentStart);
        $model->end_rent    = (int)strtotime($this->cabinetRentEnd);
        $model->status      = 1;
        $model->creater_id  = Yii::$app->user->identity->id;
        $model->create_at   = time();
        $model->cabinet_id  = $this->cabinetId;
        $model->member_card_id = !empty($this->memberCardId)?$this->memberCardId:null;
        $model->rent_type   = "新租";
        $theResult  = $model->save();
        if(!$theResult){
            return  $model->errors;
        }
        $adminModel = Employee::findOne(['admin_user_id'=>\Yii::$app->user->identity->id]);
        $member     = MemberDetails::findOne(['member_id' => $this->memberId]);
        $cabinet    = Cabinet::findOne(['id'=>$model['cabinet_id']]);
        $cabinetModel        = new Cabinet();
        // 更新绑定柜子的状态
        $result = $cabinetModel->updateCabinetStatus($this->cabinetId,"rentCabinet");
        if($result!=="success"){
            return $result;
        }
        // 会员租用历史记录表数据录入
        $memCabinetHistoryTableInsert = $this->cabinetHistoryTableInsert([strtotime($this->cabinetRentStart),strtotime($this->cabinetRentEnd)],"新租",$this->price);
        if($memCabinetHistoryTableInsert["status"]!=="success"){
            return "租柜历史记录表保存失败";
        }
        // 消费历史记录表数据录入
        $insertConsumeHistory  = new ConsumptionHistory();
        $combineData    = $this->combineData($companyId,$venueId,$memCabinetHistoryTableInsert,
            "cabinet","新租柜子",$this->deposit,$this->price,$model->end_rent);
        $insertResult  =  $insertConsumeHistory->cabinetDataInsertHistory($combineData);
        $re = $this->addOrderRecord($companyId,$venueId,$model->id,'租柜');
        if (!isset($re->id)) {
            return $re;
        }
        if($insertResult!=="success"){
            return $model->errors;
        }else{
            return true;
        }
    }
    /**
     * 云运动 - 后台- 新柜子管理-  柜子状态修正
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/8
     * @param $numberMonth   // 月数
     * @param $startRent     //  租用起始日期
     * @return  string  //返回报错信息
     */
    public function bindMemberDateCalculate($numberMonth,$startRent){
        $dateEnd = date("Y-m-d",strtotime("+$numberMonth month",strtotime($startRent)));
        return $dateEnd;
    }
    /**
     * 云运动 - 后台- 批量修改柜子
     * @author yanghuilei <yanghuilei@itsports.club>
     * @create 2018/1/2
     * @param $venueId   //场馆id
     * @param $companyId  //公司id
     * @return  string
     */
    public function modifyCabinet($venueId)
    {
        $transaction = \Yii::$app->db->beginTransaction();                //开启事务
        try {
            /* //获取柜号
                 $numArr = Cabinet::find()->where(['id' => $this->cabinetId])->select('cabinet_number')->asArray()->one();
                  if($this->cabinetNumStart !== null && ($numArr['cabinet_number'] == $this->cabinetPrefix . $this->cabinetNumStart))
                  {
                         $cabinetNum = intval($this->cabinetNumStart) - 1;
                  }else{
                      $data   = Cabinet::find()->where(['venue_id' => $venueId, 'cabinet_type_id' => $this->cabinetTypeId,
                          'cabinet_model' => $this->cabinetModel, 'cabinet_type' => $this->cabinetType])
                          ->select("cabinet_number")->orderBy("id DESC")->asArray()->one();
                      if($data != null){
                          preg_match('/\d+/',$data['cabinet_number'],$match);
                          $cabinetNum = $match[0];
                      }else{
                          $cabinetNum = 0;
                      }
                  }*/
            //遍历批量修改柜子数据
            $n = 0;
            for ($i = $this->cabinetId; $i < ($this->cabinetId + $this->cabinetNum); $i++) {
                $cabinet = Cabinet::findOne($i);
                $cabinet->update_at      = time();
                $cabinet->monthRentPrice = $this->monthRentPrice;
                $cabinet->cabinet_model  = $this->cabinetModel;
                $cabinet->cabinet_type   = $this->cabinetType;
                $cabinet->deposit        = $this->deposit;
                $cabinet->cabinet_number = $this->cabinetPrefix . (intval($this->cabinetNumStart) + $n);
                $cabinet->give_month     = json_encode($this->giveMonth);
                $cabinet->cabinet_month  = json_encode($this->cabinetMonth);
                $cabinet->cabinet_money  = json_encode($this->cabinetMoney);
                $cabinet->cabinet_dis    = json_encode($this->cabinetDis);
                $bool = $cabinet->save();
                if(!$bool){
                    \Yii::trace($cabinet->errors);
                    throw new \Exception("操作失败");
                }
                $n++;
            }
            if($transaction->commit() == null)
            {
                return true;
            }else{
                return "数据保存失败" ;
            }
        }catch(\Exception $e){
            //如果抛出错误则进入 catch ,先callback,然后捕捉错误，返回错误
            $transaction->rollBack();
            return  $error = $e->getMessage();  //获取抛出的错误
        }
    }
    /**
     * 云运动 - 后台- 批量删除
     * @author yanghuilei <yanghuilei@itsports.club>
     * @create 2018/1/2
     * @return  string
     */
    public function deleteCabinet()
    {
        $id   = $this->cabinetId;
        $cabinetNum  = $this->cabinetNum;
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            for($i=$id; $i<$id+$cabinetNum; $i++){
                $cabinet = Cabinet::findOne($i);
                $bool    = $cabinet->delete();
                if(!$bool){
                    \Yii::trace($cabinet->errors);
                    throw new \Exception("操作失败");
                }
            }
            if($transaction->commit() == null)
            {
                return true;
            }else{
                return "数据保存失败" ;
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return  $error = $e->getMessage();
        }
    }

    /**
     * @desc: 添加消费记录表
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/30
     * @param $companyId
     * @param $venueId
     * @param $cabinetId
     * @param $note
     * @return array|Order
     */
    public function addOrderRecord($companyId,$venueId,$cabinetId,$note)
    {
        $adminModel = Employee::findOne(['admin_user_id'=>\Yii::$app->user->identity->id]);
        $cardInfo = \common\models\MemberCard::findOne(['member_id'=>$this->memberId]);
        $this->memberCardId = $cardInfo->id;
        $order = new Order();
        $order->venue_id  = $venueId;
        $order->member_id = $this->memberId;
        $order->card_category_id = $this->memberCardId;
        $order->total_price = $this->price;
        $order->order_time = time();
        $order->pay_money_time = time();
        $order->pay_money_mode = 1;
        $order->sell_people_id = \Yii::$app->user->identity->id;
        $order->payee_id = \Yii::$app->user->identity->id;
        $order->create_id = \Yii::$app->user->identity->id;
        $order->status = 2;
        $order->note = $note;
        $number = Func::setOrderNumber();
        $order->order_number = "{$number}";
        $order->card_name = $note;
        $order->sell_people_name = $adminModel->name;
        $order->payee_name = $adminModel->name;
        $memberInfo = MemberDetails::findOne(['member_id'=>$this->memberId]);
        $order->member_name = $memberInfo->name;
        $order->pay_people_name = $memberInfo->name;
        $order->company_id = $companyId;
        $order->consumption_type_id = $cabinetId;
        $order->consumption_type = 'cabinet';
        $order->deposit = 0;
        $order->cash_coupon = 0;
        $order->net_price = $this->price;
        $order->all_price = $this->price;
        $order->purchase_num = 1;
        $result = $order->save() ? $order : $order->errors;
        return $result;
    }

    /**
     * @desc:更柜管理-退柜设置-添加修改配置
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/30
     * @param $post
     * @param $companyId
     * @param $venueId
     * @return array|bool
     */
    public function setQuitCabinet($post,$companyId,$venueId)
    {
        if (!empty($post)) {
            foreach ($post as $key=>$value) {
                $error = $this->config($key,$value,$companyId,$venueId);
                if($error!==true){
                    return  $error;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @desc: 更柜管理-退柜设置-添加修改配置执行操作
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/30
     * @param $key
     * @param $value
     * @param $companyId
     * @param $venueId
     * @return array|bool
     */
    public function config($key,$value,$companyId,$venueId)
    {
        $data = \common\models\Config::findOne(['key'=>$key,'venue_id'=>$venueId,'type'=>'quitCabinet']);
        if (empty($data)) {
            $data = new Config();
        }
        $data->key = (string)$key;
        $data->value = (string)$value;
        $data->type = 'quitCabinet';
        $data->company_id = $companyId;
        $data->venue_id = $venueId;
        if (!$data->save()) {
            return $data->errors;
        } else {
            return true;
        }
    }
}
?>