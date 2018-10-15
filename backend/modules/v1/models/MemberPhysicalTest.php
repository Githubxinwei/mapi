<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/25
 * Time: 12:14
 * Desc:体测信息
 */
namespace backend\modules\v1\models;

use common\models\FitnessAssessment;

class MemberPhysicalTest extends \common\models\MemberPhysicalTest
{
    public $accountId;    //账户id
    public $type;        //类型(1-体测,2-体适能)
    public $testDate;   //体测日期
    /**
     * 会员端 - 获取会员体测信息 - 验证规则
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/06/25
     */
    public function rules()
    {
        return [
            ['accountId','required','message'=>'账户ID不能为空'],
            ['type','required','message'=>'类型不能为空'],
            [['testDate'],'safe']
        ];
    }
    /**
     * 会员端API - 获取体适能评估项目
     * @author 辛威 <xinwei@itsport.club>
     * @createAt 2018/07/02
     */
    public function getFitnessAssessment()
    {
        $arr = FitnessAssessment::find()
            ->alias('fa')
            ->joinWith(['memberPhysicalTest mpt' => function($q) {
                if (isset($this->testDate)) {
                    $time = strtotime($this->testDate) + 86399;
                    $q->andWhere(['between','mpt.create_at',$this->testDate,date('Y-m-d H:i:s',$time)]);
                } else {
                    $time = strtotime($this->getLastTime()) + 86399;
                    $q->andWhere(['between','mpt.create_at',$this->getLastTime(),date('Y-m-d H:i:s',$time)]);
                };
            }],FALSE)
            ->where(['mpt.type' => 2])
            ->select([
                'fa.id',
                'fa.pid',
                'fa.title',
                'fa.unit',
                'mpt.physical_value',
                "DATE_FORMAT(mpt.create_at,'%Y-%m-%d') as create_at"
            ])
            ->asArray()
            ->all();
        return $arr;
    }
    /**
     * 会员端API - 获取会员最近一次的体测时间
     * @author 辛威 <xinwei@itsport.club>
     * @createAt 2018/07/02
     */
    public function getLastTime()
    {
        $arr = MemberPhysicalTest::find()
            ->alias('mpt')
            ->joinWith(['member m'],FALSE)
            ->joinWith(['physicalTest pt'],FALSE)
            ->where([
                'm.member_account_id' => $this->accountId,
                'mpt.type' => $this->type,
                'mpt.is_delete' => 0
            ])
            ->select([
                "DATE_FORMAT(mpt.create_at,'%Y-%m-%d') as create_at"
            ])
            ->groupBy(["DATE_FORMAT(mpt.create_at,'%Y-%m-%d')"])
            ->orderBy('mpt.create_at desc')
            ->asArray()
            ->all();
        if ($arr) {
            $lastTime = $arr['0']['create_at'];
            return $lastTime;
        }
        return NULL;
    }
    /**
     * 会员端API - 获取会员体测信息和体适能信息公共sql
     * @author 辛威 <xinwei@itsport.club>
     * @createAt 2018/06/25
     */
    public function getMemberPhysicalTestPublicSql()
    {
        $arr = MemberPhysicalTest::find()
            ->alias('mpt')
            ->joinWith(['member m' => function($q) {
                if (isset($this->testDate)) {
                    $time = strtotime($this->testDate) + 86399;
                    $q->andWhere(['between','mpt.create_at',$this->testDate,date('Y-m-d H:i:s',$time)]);
                } else {
                    $time = strtotime($this->getLastTime()) + 86399;
                    $q->andWhere(['between','mpt.create_at',$this->getLastTime(),date('Y-m-d H:i:s',$time)]);
                };
            }],FALSE)
            ->joinWith(['physicalTest pt'],FALSE)
            ->where([
                'm.member_account_id' => $this->accountId,
                'mpt.type' => $this->type,
                'mpt.is_delete' => 0
            ])
            ->select([
                'pt.id',
                'pt.pid',
                'pt.title',
                'pt.unit',
                'pt.type',
                'pt.normal_range',
                'mpt.physical_value',
                "DATE_FORMAT(mpt.create_at,'%Y-%m-%d') as create_at"
            ])
            ->orderBy('mpt.create_at desc')
            ->asArray()
            ->all();
        return $arr;
    }
    /**
     * 会员端API - 获取会员体测信息和体适能信息
     * @author 辛威 <xinwei@itsport.club>
     * @createAt 2018/06/25
     */
    public function getMemberPhysicalTestInfo()
    {
        if ($this->getLastTime()) {
            $arr = $this->getMemberPhysicalTestPublicSql();
            if ($arr) {
                $chart = [];
                $title = [];
                $physicalValue = [];
                $physicalTestValue = [];
                $testDate = '';
                $data = [];
                foreach ($arr as $k => $v) {
                    if ($v['type'] == 0) {
                        if ($this->type == 1) {
                            if ($v['pid'] == 0){
                                $title[] = $v['title'];
                                $physicalValue[] = $v;
                            }else{
                                $physicalTestValue[] = $v;
                            }
                        }
                    } else {
                        if (isset($v['normal_range'])){
                            $rate = explode('-',$v['normal_range']);
                            $v['normal_range'] = $rate['1'];
                        }else{
                            $v['normal_range'] = 0;
                        }
                        $chart[] = $v;
                    }
                    $testDate = $v['create_at'];
                }
                //图表
                if ($this->type == 1) $data['chart'] = $chart;
                if ($this->type == 2) {
                    $faArr = $this->getFitnessAssessment();
                    foreach ($faArr as $k => $v) {
                        if ($v['pid'] == 0) {
                            $title[] = $v['title'];
                            $physicalValue[] = $v;
                        } else {
                            $physicalTestValue[] = $v;
                        }
                        $testDate = $v['create_at'];
                    }
                }
                $data['ptTitle'] = $title;
                foreach ($physicalValue as $key =>$val) {
                    foreach ($physicalTestValue as $k =>$v) {
                        if ($val['id'] == $v['pid']) {
                            $physicalValue[$key]['data'][] = $physicalTestValue[$k];
                        }
                    }
                }
                $data['physicalValue'] = $physicalValue;
                $data['testDate'] = $testDate;
                return $data;
            }
            return NULL;
        }
        return NULL;
    }
    /**
     * 会员端API - 获取会员体测日期
     * @author 辛威 <xinwei@itsport.club>
     * @createAt 2018/06/25
     */
    public function getMemberPhysicalTestDate()
    {
        $arr = MemberPhysicalTest::find()
            ->alias('mpt')
            ->joinWith(['member m'],FALSE)
            ->where([
                'm.member_account_id' => $this->accountId,
                'mpt.is_delete' => 0
                ])
            ->select(["DATE_FORMAT(mpt.create_at,'%Y-%m-%d') as testDate"])
            ->groupBy(["DATE_FORMAT(mpt.create_at,'%Y-%m-%d')"])
            ->orderBy('mpt.create_at desc')
            ->asArray()
            ->all();
        return $arr;
    }
}