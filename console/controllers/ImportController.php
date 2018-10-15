<?php
namespace console\controllers;

use backend\models\CabinetExcel;
use backend\models\ConsumptionHistory;
use backend\models\ExcelAiBoMember;
use backend\models\ExcelCardExpire;
use backend\models\ExcelCharge;
use backend\models\ExcelMemberNumber;
use backend\models\ExcelOperate;
use backend\models\MemberCard;
use backend\models\MemberCardDelayForm;
use backend\models\PrivateExcel;
use backend\models\SwimExcel;
use backend\models\ExcelCardCategory;
use backend\models\YanExcel;
use backend\models\UnitDataExcel;
use backend\models\ExcelTurnMembers;
use backend\models\ZhangExcel;
use backend\models\JingExcel;
use backend\models\DelKaiExcel;
use backend\models\CorrectCardExcel;
use backend\models\DelCardExcel;
use backend\models\TimeExcel;
use backend\models\ExcelBring;
use backend\models\ExcelTakeClass;
use backend\models\ExcelPhone;
use backend\models\ExcelDv;
use backend\models\ExcelCollege;
use backend\models\ExcelDuplicateCard;
use backend\models\ExcelZhuanKa;
use backend\models\ExcelMatchCourse;
use backend\models\ExcelDelMember;
use backend\models\ExcelCorrectTime;
use backend\models\ExcelZhangShuXia;
use backend\models\ExcelDuJian;
use backend\models\ExcelYaXing;
use backend\models\ExcelBuKa;
use backend\models\ExcelAddProfile;
use backend\models\ExcelZhuanRang;
use backend\models\ExcelXiaoFei;
use backend\models\ExcelDayOff;
use common\models\base\MemberDetails;
use common\models\CardCategory;
use common\models\LimitCardNumber;
use common\models\Member;
use common\models\VenueLimitTimes;
use common\models\WipeData;
use yii\console\Controller;
use console\models\Import;
use backend\controllers\ExcelController;
use yii\helpers\ArrayHelper;

class ImportController  extends Controller
{
    
    public function actionIndex()
    {
        
    }
    public function actionSale($consoleType='')
    {
        $data = Import::Handle();
        set_time_limit(0);
        $model = new ExcelOperate();
        foreach ($data as $k=>$v){
            $excel = $model->loadFile($v[0],$v[1],$v[2],$v[3],$consoleType);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionMobile()
    {
        set_time_limit(0);
        $data = Import::HandleMobile();
        $model = new ExcelCharge();
        foreach ($data as $k=>$v){
            echo  $v[0];
            $excel = $model->memberInfoFile($v[0],$v[1]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionNumber($type='number')
    {
        set_time_limit(0);
        $data = Import::HandleNumber();
        $model = new ExcelMemberNumber();
        foreach ($data as $k=>$v){
            $excel = $model->loadFileMemberNumber($v[0],$type);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }

    /**
     * 修改亚星送人卡绑定会员
     * @param string $type
     */
    public function actionSendMember($type='number')
    {
        set_time_limit(0);
        $data = Import::HandleSendMember();
        $model = new ExcelMemberNumber();
        foreach ($data as $k=>$v){
            $excel = $model->loadFileSendMemberNumber($v[0],$type);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionEmployee()
    {
        set_time_limit(0);
        $data = Import::HandleEmployee();
        $model = new ExcelOperate();
        foreach ($data as $k=>$v){
            $excel = $model->loadEmployeeFile($v[0]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionLeave()
    {
        set_time_limit(0);
        $data = Import::HandleLeave();
        $model = new ExcelOperate();
        foreach ($data as $k=>$v){
            $excel = $model->loadEmployeeFile($v[0]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionTransfer()
    {
        set_time_limit(0);
        $data = Import::HandleTransfer();
        $model = new ExcelOperate();
        foreach ($data as $k=>$v){
            $excel = $model->loadEmployeeFile($v[0]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionCabinet()
    {
        set_time_limit(0);
        $data = Import::HandleCabinet();
        $model = new CabinetExcel();
        foreach ($data as $k=>$v){
            $excel = $model->loadFile($v[0],$v[1],$v[2]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionEndCharge()
    {
        set_time_limit(0);
        $data = Import::HandleEndCharge();
        $model = new ExcelCharge();
        foreach ($data as $k=>$v){
            $excel = $model->loadFile($v[0],'charge',$v[1],$v[2]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionCharge()
    {
        set_time_limit(0);
        $data = Import::HandleCharge();
        $model = new ExcelCharge();
        foreach ($data as $k=>$v){
            $excel = $model->loadFile($v[0],'chargeClassList',$v[1],$v[2]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionCardExpire($consoleType='')
    {
        set_time_limit(0);
        $data = Import::HandleCardExpire();
        $model = new ExcelCardExpire();
        foreach ($data as $k=>$v){
            $excel = $model->loadFile($v[0],$v[1],$v[2],$v[3],$consoleType);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionMember()
    {
        set_time_limit(0);
        $data = Import::HandleMember();
        $model = new ExcelAiBoMember();
        foreach ($data as $k=>$v){
            $excel = $model->loadMember($v[0],$v[1],$v[2],'one');
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionMemberXiao()
    {
        set_time_limit(0);
        $data = Import::HandleMemberXiao();
        $model = new ExcelAiBoMember();
        foreach ($data as $k=>$v){
            $excel = $model->loadFile($v[0],$v[1],$v[2],$v[3]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionMemberXiaoNew()
    {
        set_time_limit(0);
        $data = Import::HandleMemberXiaoTwo();
        $model = new ExcelAiBoMember();
        foreach ($data as $k=>$v){
            $excel = $model->loadFile($v[0],$v[1],$v[2],$v[3]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionMemberTwoXiao()
    {
        set_time_limit(0);
        $data = Import::HandleMemberTwoXiao();
        $model = new ExcelAiBoMember();
        foreach ($data as $k=>$v){
            $excel = $model->loadTwoFile($v[0],$v[1],$v[2],'one');
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionMemberThree()
    {
        set_time_limit(0);
        $data = Import::HandleMemberThreeXiao();
        $model = new ExcelAiBoMember();
        foreach ($data as $k=>$v){
            $excel = $model->loadTwoFile($v[0],$v[1],$v[2],'two');
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionMemberSwim()
    {
        set_time_limit(0);
        $data = Import::HandleMemberSwim();
        $model = new SwimExcel();
        foreach ($data as $k=>$v){
            $excel = $model->loadFile($v[0],$v[1],$v[2]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionMemberCharge()
    {
        set_time_limit(0);
        $data = Import::HandleMemberCharge();
        $model = new PrivateExcel();
        foreach ($data as $k=>$v){
            $excel = $model->loadFile($v[0],$v[1],$v[2]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionMemberChargeNew()
    {
        set_time_limit(0);
        $data = Import::HandleMemberChargeNew();
        $model = new PrivateExcel();
        foreach ($data as $k=>$v){
            $excel = $model->loadAiBoFile($v[0],$v[1],$v[2]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }

    public function actionYan()
    {
        set_time_limit(0);
        $data  = Import::HandleYan();
        $model = new YanExcel();
        foreach ($data as $k=>$v) {
            $excel = $model->loadFile($v[0], $v[1], $v[2]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionUnitData()
    {
        set_time_limit(0);
        $data = Import::HandleUnitData();
        $model = new UnitDataExcel();
        foreach($data as $k=>$v){
            $excel = $model->LoadFile($v[0],$v[1],$v[2]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }

    public function actionCardCategory()
    {
        set_time_limit(0);
        $data = Import::HandleCardCategory();
        $model = new ExcelCardCategory();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionTurnMembers()
    {
        set_time_limit(0);
        $data = Import::HandleTurnMembers();
        $model = new ExcelTurnMembers();
        foreach($data as $k=>$v){
            $excel = $model->LoadFile($v[0]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionZhang()
    {
        set_time_limit(0);
        $data = Import::HandleZhang();
        $model = new ZhangExcel();
        foreach($data as $k=>$v){
            $excel = $model->LoadFile($v[0]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionJing()
    {
        set_time_limit(0);
        $data = Import::HandleJing();
        $model = new JingExcel();
        foreach($data as $k=>$v){
            $excel = $model->LoadFile($v[0]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }

    public function actionTakeClass()
    {
        set_time_limit(0);
        $data = Import::HandleTakeClass();
        $model = new ExcelTakeClass();
        foreach($data as $k=>$v){
            $excel = $model->LoadFile($v[0]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }

    public function actionTime()
    {
        set_time_limit(0);
        $data = Import::HandleTime();
        $model = new TimeExcel();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';
                print_r($excel);
            }
        }
        echo 'success';
    }

    public function actionDelKai()
    {
        set_time_limit(0);
        $data = Import::HandleDelKai();
        $model = new DelKaiExcel();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';
                print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionCorrectCard()
    {
        set_time_limit(0);
        $data = Import::HandleCorrectCard();
        $model = new CorrectCardExcel();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';
                print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionDelCard()
    {
        set_time_limit(0);
        $data = Import::HandleDelCard();
        $model = new DelCardExcel();
        foreach($data as $k=>$v){
            $excel = $model->LoadFile($v[0]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionMemberChargeNewTwo()
    {
        set_time_limit(0);
        $data = Import::HandleMemberChargeNewTwo();
        $model = new PrivateExcel();
        foreach ($data as $k=>$v){
            $excel = $model->loadAiBoFile($v[0],$v[1],$v[2]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionMemberYaXiao($consoleType='')
    {
        set_time_limit(0);
        $data = Import::HandleMemberYaXiao();
        $model = new ExcelAiBoMember();
        foreach ($data as $k=>$v){
            $excel = $model->loadYaXingMember($v[0],$v[1],$v[2],$v[3],$consoleType);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionPhone()
    {
        set_time_limit(0);
        $data = Import::HandlePhone();
        $model = new ExcelPhone();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionDuplicateCard()
    {
        set_time_limit(0);
        $data = Import::HandleDuplicateCard();
        $model = new ExcelDuplicateCard();
        foreach($data as $k=>$v){
            $excel = $model->LoadFile($v[0],$v[1],$v[2]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionCollege()
    {
        set_time_limit(0);
        $data = Import::HandleCollege();
        $model = new ExcelCollege();
        foreach($data as $k=>$v){
            $excel = $model->LoadFile($v[0]);
            if($excel !== true){
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionBring()
    {
        set_time_limit(0);
        $data = Import::HandleBring();
        $model = new ExcelBring();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';
                print_r($excel);
            }
        }
        echo 'success';
    }

    public function actionDv()
    {
        set_time_limit(0);
        $data = Import::HandleDv();
        $model = new ExcelDv();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionZhuanKa()
    {
        set_time_limit(0);
        $data = Import::HandleZhuanKa();
        $model = new ExcelZhuanKa();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionMatchCourse()
    {
        set_time_limit(0);
        $data = Import::HandleMatchCourse();
        $model = new ExcelMatchCourse();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionDelMember()
    {
        set_time_limit(0);
        $data = Import::HandleDelMember();
        $model = new ExcelDelMember();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionCorrectTime()
    {
        set_time_limit(0);
        $data = Import::HandleCorrectTime();
        $model = new ExcelCorrectTime();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    
    public function actionDuJian()
    {
        set_time_limit(0);
        $data = Import::HandleDuJian();
        $model = new ExcelDuJian();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }

    public function actionYaXing()
    {
        set_time_limit(0);
        $data = Import::HandleYaXing();
        $model = new ExcelYaXing();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }

    public function actionBuKa()
    {
        set_time_limit(0);
        $data = Import::HandleBuKa();
        $model = new ExcelBuKa();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }

    public function actionAddProfile()
    {
        set_time_limit(0);
        $data = Import::HandleAddProfile();
        $model = new ExcelAddProfile();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }

    public function actionZhuanRang()
    {
        set_time_limit(0);
        $data = Import::HandleZhuanRang();
        $model = new ExcelZhuanRang();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }

    public function actionZhangShuXia()
    {
        set_time_limit(0);
        $data = Import::HandleZhangShuXia();
        $model = new ExcelZhangShuXia();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    public function actionDaShangDelay()
    {
        set_time_limit(0);
        $data = Import::HandleDelay();
        $model = new MemberCardDelayForm();
        foreach ($data as $k => $v) {
            $excel = $model->setPath($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }
    /**
     * 去除会员详情重复数据
     */
    public function actionMemberDetailRepeat()
    {
        $member_ids = MemberDetails::find()->select(['member_id', 'COUNT(*) as `count`'])->groupBy('member_id')->having(['>', 'count', 1])->asArray()->all();
        $member_ids = ArrayHelper::getColumn($member_ids, 'member_id');
        !empty($member_ids) or exit('no repeat');

        $fields = array_keys((new MemberDetails())->attributeLabels());//所有字段

        foreach ($member_ids as $member_id){
            echo "$member_id->";
            $ids = MemberDetails::find()->select('id')->where(['member_id' => $member_id])->asArray()->all();
            $ids = ArrayHelper::getColumn($ids, 'id');

            $big   = MemberDetails::findOne(max($ids));
            $small = MemberDetails::findOne(min($ids));

            foreach ($fields as $field){
                if(empty($small->$field) && !empty($big->$field)){
                    $small->$field = $big->$field;
                }
            }
            $small->save();
            MemberDetails::deleteAll(['and', ['member_id'=>$member_id], ['>', 'id', $small->id]]);
        }
        echo 'success';
    }

    /**
     * 更新售卖结束时间为当天23:59:59(须在64位系统及64位php下执行)
     */
    public function actionUpdateCardSellendtime()
    {
        $cardCategorys = CardCategory::find()->select('id, sell_end_time')->where(['>', 'sell_end_time', 1])->all();
        if(!empty($cardCategorys)){
            foreach ($cardCategorys as $cardCategory){
                $datetime = date('Y-m-d H:i:s', $cardCategory->sell_end_time);
                echo "\r\ncard_category {$cardCategory->id}:{$datetime}";
                $sellEndTime = strtotime(date('Y-m-d', $cardCategory->sell_end_time) . ' 23:59:59');
                if($cardCategory->sell_end_time == $sellEndTime) continue;
                $cardCategory->sell_end_time =$sellEndTime;
                $cardCategory->save();
                $datetime = date('Y-m-d H:i:s', $cardCategory->sell_end_time);
                echo "->{$datetime}";
            }
        }

        $limitCardNumbers = LimitCardNumber::find()->select('id, sell_end_time')->where(['>', 'sell_end_time', 1])->all();
        if(!empty($limitCardNumbers)){
            foreach ($limitCardNumbers as $limitCardNumber){
                $datetime = date('Y-m-d H:i:s', $limitCardNumber->sell_end_time);
                echo "\r\nlimit_card_number {$limitCardNumber->id}:{$datetime}";
                $sellEndTime = strtotime(date('Y-m-d', $limitCardNumber->sell_end_time) . ' 23:59:59');
                if($limitCardNumber->sell_end_time == $sellEndTime) continue;
                $limitCardNumber->sell_end_time =$sellEndTime;
                $limitCardNumber->save();
                $datetime = date('Y-m-d H:i:s', $limitCardNumber->sell_end_time);
                echo "->{$datetime}";
            }
        }

        exit('success');
    }

    /**
     * 更新历史纪录行为
     */
    public function actionUpdateHistoryCategory()
    {
        $data = [
            '会员卡(升级)' =>'升级',
            '会员卡(发卡)' =>'发卡',
            '会员卡(回款)' =>'回款',
            '会员卡(定金)' =>'定金',
            '会员卡(续回)' =>'续回',
            '会员卡(续定)' =>'续定',
            '会员卡(续费)' =>'续费',
        ];
        foreach ($data as $old => $new)
            ConsumptionHistory::updateAll(['category'=>$new],['category'=>$old]);
    }

    /**
     * 根据身份证号更新出生日期和性别
     */
    public function actionUpdateBirth()
    {
        ini_set('memory_limit', '-1');
        $ids = MemberDetails::find()->select('id')->where(['or', ['LENGTH(`id_card`)'=>15], ['LENGTH(`id_card`)'=>18]])->andWhere(['birth_date'=>NULL])->all();
        foreach ($ids as $id){
            $detail = MemberDetails::findOne($id);
            $idcard = $detail->id_card;
            $birth = strlen($idcard)==15 ? ('19' . substr($idcard, 6, 6)) : substr($idcard, 6, 8);
            $detail->birth_date = substr($birth,0,4) . '-' . substr($birth,4,2) . '-' . substr($birth,6);
            $detail->sex = intval(substr($idcard, (strlen($idcard)==15 ? -1 : -2), 1)) % 2 ? 1 : 2;
            $detail->save();
            echo "[$idcard|{$detail->birth_date}|{$detail->sex}]->";
        }
        echo 'success';
    }

    public function actionYaxing1218()
    {
        //出生日期
        $num = \common\models\MemberDetails::updateAll(['birth_date'=>NULL], ['birth_date'=>'1970-01-01']);
        echo "birth_date($num)->";
        //证件类型
        $num = \common\models\MemberDetails::updateAll(['document_type'=>1], ['and',['document_type'=>NULL],['or', ['LENGTH(`id_card`)'=>15], ['LENGTH(`id_card`)'=>18]]]);
        echo "document_type($num)->success";
    }
    //批量修改进场次数
    public function actionMemberCardLimitCard()
    {
        //出生日期
        $num = MemberCard::find()->alias('mc')->joinWith(['venueLimitTimesArr vlt'])
            ->where('vlt.venue_id = mc.venue_id')
            ->andWhere(['or',['vlt.total_times'=>[null,0]],['week_times'=>[null,0]]])
            ->asArray()->all();
        foreach ($num as $k=>$v){
            $limit = VenueLimitTimes::findOne(['member_card_id'=>$v['id'],'venue_id'=>$v['venue_id']]);
            $one   = VenueLimitTimes::find()->where(['member_card_id'=>$v['id'],'venue_id'=>$v['venue_id']])
                         ->andWhere(['and',['IS NOT','total_times',null],['<>','total_times',0]])
                         ->asArray()->one();
            if($one){ continue;}
            $two   = VenueLimitTimes::find()->where(['member_card_id'=>$v['id'],'venue_id'=>$v['venue_id']])
                     ->andWhere(['and',['IS NOT','week_times',null],['<>','week_times',0]])
                     ->asArray()->one();
            if($two){ continue;}
            if(($limit['total_times'] == 0 || $limit['total_times'] == null) && ($limit['week_times'] == 0 || $limit['week_times'] == null)){
               $cardLimit = \backend\models\LimitCardNumber::findOne(['card_category_id'=>$v['card_category_id'],'venue_id'=>$v['venue_id'],'status'=>[1,3]]);
               if(!empty($cardLimit) && (($cardLimit['times'] != 0 || $cardLimit['times'] != null) || ($cardLimit['week_times'] != 0 || $cardLimit['week_times'] != null))){
                   $limit->total_times = $cardLimit->times;
                   $limit->week_times  = $cardLimit->week_times;
                   if(empty($cardLimit->week_times)){
                       $limit->overplus_times = $cardLimit->times;
                   }else{
                       $limit->overplus_times = $cardLimit->week_times;
                   }
                   $limit->level = $cardLimit->level;
                   $limit->apply_start = $cardLimit->apply_start;
                   $limit->apply_end = $cardLimit->apply_end;
                   if(!$limit->save()){
                       return $v['card_number'].'->'.$limit->errors;
                   }
               }else{
                   $limit->total_times    = -1;
                   $limit->week_times     = null;
                   $limit->overplus_times = -1;
                   $limit->level          = 1;
                   if(!$limit->save()){
                       return $v['card_number'].'->'.$limit->errors;
                   }
               }
               echo $v['card_number'].'->>>' ."\n";
            }
        }
        echo count($num);
    }
    //批量修改进场次数
    public function actionMemberCardNotLimitCard()
    {
        //出生日期
        $num = MemberCard::find()->alias('mc')->joinWith(['venueLimitTimesArr vlt'])
            ->where(['vlt.member_card_id'=>null])
            ->asArray()->all();
        foreach ($num as $k=>$v){
                $limit = new \common\models\base\VenueLimitTimes();
                $cardLimit = \backend\models\LimitCardNumber::findOne(['card_category_id'=>$v['card_category_id'],'venue_id'=>$v['venue_id'],'status'=>[1,3]]);
                if(!empty($cardLimit) && (($cardLimit['times'] != 0 || $cardLimit['times'] != null) || ($cardLimit['week_times'] != 0 || $cardLimit['week_times'] != null))){
                    $limit->member_card_id = $v['id'];
                    $limit->venue_id       = $v['venue_id'];
                    $limit->total_times = $cardLimit->times;
                    $limit->week_times  = $cardLimit->week_times;
                    if(empty($cardLimit->week_times)){
                        $limit->overplus_times = $cardLimit->times;
                    }else{
                        $limit->overplus_times = $cardLimit->week_times;
                    }
                    $limit->level = $cardLimit->level;
                    $limit->apply_start = $cardLimit->apply_start;
                    $limit->apply_end = $cardLimit->apply_end;
                    if(!$limit->save()){
                        return $v['card_number'].'->'.$limit->errors;
                    }
                }else{
                    $limit->member_card_id = $v['id'];
                    $limit->venue_id       = $v['venue_id'];
                    $limit->total_times    = -1;
                    $limit->week_times     = null;
                    $limit->overplus_times = -1;
                    $limit->level          = 1;
                    if(!$limit->save()){
                        return $v['card_number'].'->'.$limit->errors;
                    }
                }
                echo $v['card_number'].'->>>' ."\n";
            }
        echo count($num);
    }

    public function actionXiaoFei()
    {
        set_time_limit(0);
        $data = Import::HandleXiaoFei();
        $model = new ExcelXiaoFei();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }

    public function actionDayOff()
    {
        set_time_limit(0);
        $data = Import::HandleDayOff();
        $model = new ExcelDayOff();
        foreach ($data as $k => $v) {
            $excel = $model->LoadFile($v[0]);
            if ($excel !== true) {
                print '<pre>';print_r($excel);
            }
        }
        echo 'success';
    }

}