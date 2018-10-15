<?php
namespace backend\modules\v1\models;

use common\models\Evaluate;
use common\models\Func;
use common\models\GroupClass;
use common\models\MemberCard;
use common\models\base\MissAboutSet;
use common\models\Config;
use backend\modules\v1\models\EntryRecord;

class MyAboutClass extends \common\models\AboutClass
{
    public function fields()
    {

        $fields = [
            'id',

            'status' => function ($model) {
                return $model->getStatus();
            },

            'type',

            'coach' => function ($model) {
                return Func::getRelationVal($model, 'employee', 'name');
            },

            'start',

            'end',

            'is_read',

            'is_evaluate'=>function($model) {
                return $model->getEvaluate($model);
            },

            'create_at',

            'cancel_time',

            'class_info',

            'is_print_receipt',

            'coach_id',

            'class_id',

            'in_time',
            'evaluate_id'=>function($model) {
                return $model->getEvaluateId($model);
            },

            'member_id',

            'member_card_id',

        ];

        if($this->type == '2'){
            $fields['class_room'] = function($model){
                return Func::getRelationVal($model, 'seat', 'classroom', 'name');
            };

            $fields['seat_number'] = function($model){
                return Func::getRelationVal($model, 'seat', 'seat_number');
            };
        }

        if(in_array($this->type, [3,4])){
            $fields[] = 'had_about_num';
            $fields['times'] = function($model){
                if (!empty(Func::getRelationVal($model, 'chargeGroupClass', 'times'))){
                    return Func::getRelationVal($model, 'chargeGroupClass', 'times');
                }
                return NULL;
            };
        }

        return $fields;
    }

    /**
     * 已预约人数
     */
    public function getHad_about_num()
    {
        return \common\models\AboutClass::find()->where(['class_id'=>$this->class_id, 'type'=>$this->type, 'status'=>[1,3,4]])->count();
    }

    /**
     * ChargeClassNumber课程信息
     */
    public function getClass_info()
    {
//        return MemberCourseOrderDetails::findOne($this->class_id);
        $about = new AboutClass();
        if($this->type == '1') return $about->getNewAboutOne($this->class_id,$this->start,$this->coach_id,$this->member_card_id,$this->member_id,'',$this->id);
        if(in_array($this->type, [3,4]) && isset($this->chargeGroupClass->class_number_id))
            return ChargeClassNumber::findOne($this->chargeGroupClass->class_number_id);
        if($this->type == '2') return MyGroupClass::findOne($this->class_id);
        return (object)[];
    }

    public function getStatus()
    {
        if($this->status==1 && time()>=$this->start){
            if(in_array($this->type, [3,4])){//小团体课
                if(isset($this->class_info->least_number)){
                    if($this->had_about_num < $this->class_info->least_number){
                        \common\models\AboutClass::updateAll(['status'=>9, 'is_read'=>0], ['id' => $this->id]);
                        $this->status = 9;
                    }else{
                        \common\models\AboutClass::updateAll(['status'=>3, 'is_read'=>0], ['id' => $this->id]);
                        $this->status = 3;
                    }
                }
            }elseif(($this->type == 2) && (($this->is_print_receipt == 1) || (!empty($this->in_time) && ($this->in_time <= $this->end)))){//团课(打印小票或者刷手环，此时为上课中)
                \common\models\AboutClass::updateAll(['status'=>3, 'is_read'=>0], ['id' => $this->id]);
                $this->status = 3;
            }
        }
        if(($this->status == 1) && ($this->type == 1) && (time() > $this->end)){//私课爽约
            \common\models\AboutClass::updateAll(['status'=>6], ['id' => $this->id]);
            $this->status = 6;
        }
        if($this->status == 3 && ($this->type != 1) && (time() > $this->end)){//到点课程自动改为下课状态，私教课不自动更改
            \common\models\AboutClass::updateAll(['status'=>4], ['id' => $this->id]);
            $this->status = 4;
        }
        $group_class = $this->group_class;
        if ($group_class) {//有打印小票设置时间-团课爽约
            if (($this->status == 1) && ($this->type != 1) && (time() > (intval($this->start)+intval($group_class['value'])*60)) && ($this->is_print_receipt == 2)) {
                \common\models\AboutClass::updateAll(['status'=>6], ['id' => $this->id]);
                $this->status = 6;
                $this->getColdMemberCard();
            }
        } elseif (($this->status == 1) && ($this->type != 1) && (time() > $this->start)) {//无打印小票设置时间-团课爽约
            \common\models\AboutClass::updateAll(['status'=>6], ['id' => $this->id]);
            $this->status = 6;
            $this->getColdMemberCard();
        }
        return $this->status;
    }
    public function getEvaluate($model){
        if ($model->type ==1){
            //私教课
            $consumption_type = 'privateClass';
            $data = MemberCourseOrderDetails::find()->alias('mcod')
                ->joinWith(['memberCourseOrder mco'],false)
                ->select('mco.id mcoId')
                ->where(['mcod.id'=>$model->class_id])
                ->asArray()->all();
            $class_id=$data[0]['mcoId'];
        }
        if ($model->type ==2){
            //团课
            $consumption_type ='teamClass';
            $class_id=$model->class_id;
        }
        if ($model->type ==3){
            //团体课
            $consumption_type = 'smallClass';
            $class_id=$model->class_id;
        }
        $data = Evaluate::findOne(['consumption_type_id'=>$class_id,'consumption_type'=>$consumption_type,'member_id'=>$model->member_id]);
        if ($model->status==4){
            if (!empty($data)) return 1;
            return 0;
        }
    }
    public function getEvaluateId($model){
        if ($model->type ==1){
            //私教课
            $consumption_type = 'privateClass';
            $data = MemberCourseOrderDetails::find()->alias('mcod')
                ->joinWith(['memberCourseOrder mco'],false)
                ->select('mco.id mcoId')
                ->where(['mcod.id'=>$model->class_id])
                ->asArray()->all();
            $class_id=$data[0]['mcoId'];
        }
        if ($model->type ==2){
            //团课
            $consumption_type ='teamClass';
            $class_id=$model->class_id;
        }
        if ($model->type ==3){
            //团体课
            $consumption_type = 'smallClass';
            $class_id=$model->class_id;
        }
        $member_id = $model->member_id;
        $data = Evaluate::findOne(['consumption_type_id'=>$class_id,'consumption_type'=>$consumption_type,'member_id'=>$member_id]);
        return $data['id'];

    }

    //打印小票设置信息
    public function getGroup_class()
    {
        $groupClass = GroupClass::find()
            ->alias('gc')
            ->joinWith(['config c'=>function($query){
            }
            ],false)
            ->where(['gc.id' => $this->class_id])
            ->andWhere([
                'c.key' => "before_class",
                'c.type' => "league"
            ])
            ->select('c.value')
            ->asArray()
            ->one();
        return $groupClass;
    }
    //团课爽约设置信息
    public function getMiss_about_set()
    {
        $data = GroupClass::find()
            ->alias('gc')
            ->joinWith(['missAboutSet mas'=>function($query){
            }
            ],false)
            ->where(['gc.id' => $this->class_id])
            ->andWhere(['mas.course_type' => 1])
            ->select('mas.miss_about_times')
            ->asArray()
            ->one();
        return $data;
    }
    //获取团课当月爽约次数
    public function getCountClassMiss($time,$id)
    {
        if ($this->type != 1) {
            $missCount = \common\models\AboutClass::find()
                ->where(['status' => 6,'type' => $this->type,'member_card_id' => $id])
                ->andWhere(['>','start',$time])
                ->select('id')
                ->count();
            return $missCount;
        }
    }
    //团课爽约爽约次数增加或冻结会员卡
    public function getColdMemberCard()
    {
        //获取当前月第一天的时间戳
        $beginThisMonth=mktime(0,0,0,date('m'),1,date('Y'));
        //获取当前会员卡的最后一次冻结时间
        $memberCard = MemberCard::find()->where(['id' => $this->member_card_id])->select('status,last_freeze_time')->asArray()->one();
        if (empty($memberCard['last_freeze_time'])) {
            //搜索当前月的爽约次数
            $missCount = $this->getCountClassMiss($beginThisMonth,$this->member_card_id);
        } else{
            //获取上次冻结月份
            $last_date = date('Y-m',$memberCard['last_freeze_time']);
            $current_date = date('Y-m',time());
            if ($last_date === $current_date) {
                //当前月冻结过,爽约次数从冻结时间之后开始
                $missCount = $this->getCountClassMiss($memberCard['last_freeze_time'],$this->member_card_id);
            }else{
                $missCount = $this->getCountClassMiss($beginThisMonth,$this->member_card_id);
            }
        }
        $miss = $this->miss_about_set;
        if (empty($miss)) {
            //修改爽约次数
            if ($missCount) {
                MemberCard::updateAll(["absentTimes"=>$missCount],["id"=>$this->member_card_id]);
            }
        }else {
            $missTimes = !empty($miss['miss_about_times']) ? $miss['miss_about_times'] : 'noTimes';
            if ($missTimes == 'noTimes') {
                //修改爽约次数
                if ($missCount) {
                    MemberCard::updateAll(["absentTimes"=>$missCount],["id"=>$this->member_card_id]);
                }
            }else{
                //团课爽约达到次数冻结会员卡
                if (($missCount >= $miss['miss_about_times']) && !empty($miss['miss_about_times'])) {
                    if ($memberCard['status'] != 3) {
                        MemberCard::updateAll(["status"=>3,"last_freeze_time"=>time(),"recent_freeze_reason"=>1,"absentTimes"=>$missCount],
                            ["id"=>$this->member_card_id]);
                    }
                }else {
                    //修改爽约次数
                    if ($missCount) {
                        MemberCard::updateAll(["absentTimes"=>$missCount],["id"=>$this->member_card_id]);
                    }
                }
            }
        }
    }
    /*
     * 会员端-获取会员上课节数、运动天数
     * @author xinwei<xinwei@itsport.club>
     * @createAt 2018/06/13
     * @param $accountId    账户ID
     */
    public function getTotalNumber($accountId)
    {
        $entryRecord = new EntryRecord();
        //上课节数
        $arr['lessonNumbers'] = MyAboutClass::find()
                ->alias('mac')
                ->joinWith(['member m'], FALSE)
                ->where(['m.member_account_id' => $accountId])
                ->andWhere(['mac.status' => [3,4]])
                ->select('mac.id')
                ->count();
        //运动天数
        $arr['sportsDays'] = $entryRecord->getPersistenceDays($accountId);
        return $arr;
    }
}