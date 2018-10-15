<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/25
 * Time: 17:22
 */

namespace backend\modules\v1\models;

use common\models\base\Order;
use common\models\relations\EmployeeRelations;
use common\models\PersonalityImages;
use common\models\Evaluate;
use common\models\MemberDetails;

class Employee extends \common\models\base\Employee
{
    const  COACH_PIC = 'http://oo0oj2qmr.bkt.clouddn.com/7993721530597889.jpg?e=1530601489&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:tmrfPK0pRdJ1Hi-xNPWxy68UK-A=';
    use EmployeeRelations;
    public $endPage;         // 判断当前页是否最后一页
    /**
     * 云运动 - Api - 获取私教详情
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @param  $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCoachDetail($id)
    {
        $coach = \backend\models\Employee::getCoachOneById($id);
        if($coach && !empty($coach)){
/*            $coach['intro'] = (isset($coach['intro']) && !empty($coach['intro']))?html_entity_decode(strip_tags($coach['intro'])):'为健身爱好者提供一对一具体指导的健身指导者。 私人健身教练进行的是一对一的工作，工作具有互动性、针对性等特点。私人教练适合不同健康水平、年龄段和经济收入的人群，通过提供个性化的健身计划和关注，服务于健身会员（顾客）。购买私人教练是最好的提高健康和体能、达到设置的目标的方法之一。';*/
            $coach['intro'] = (isset($coach['intro']) && !empty($coach['intro']))?$coach['intro']:'为健身爱好者提供一对一具体指导的健身指导者。 私人健身教练进行的是一对一的工作，工作具有互动性、针对性等特点。私人教练适合不同健康水平、年龄段和经济收入的人群，通过提供个性化的健身计划和关注，服务于健身会员（顾客）。购买私人教练是最好的提高健康和体能、达到设置的目标的方法之一。';
            $group = new GroupClass();
            $score             = $group->getFieldsByOne($v = [],'score');
            if(empty($coach['work_time']))
            {
                $coach['workTime'] = "";
            }else{
                $coach['workTime'] = $coach['work_time'];
            }
            if(empty($coach['age']))
            {
                $coach['age'] = '暂无';
            }
            $coach['score']    = $score['score'];
            $coach['scoreImg'] = $score['scoreImg'];
            $coach['coachPic'] = !empty($coach['pic'])?$coach['pic']:self::COACH_PIC;
            unset($coach['params'],     $coach['create_at'],
                $coach['updated_at'], $coach['admin_user_id'],
                $coach['create_id'],  $coach['salary'],
                $coach['leave_date'], $coach['entry_date'],
                $coach['organization_id'],$coach['birth_time'],
                $coach['mobile'],$coach['email'],
                $coach['work_time']  );
        }
        return $coach;
    }
    /**
     * 云运动 - Api - 获取私教详情
     * @author 辛威 <xinwei@itsports.club>
     * @create 2017/4/24
     * @param  $id  私教id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getNewCoachDetail($id)
    {
        $coach = \backend\models\Employee::getCoachOneById($id);
        if($coach && !empty($coach)){
            $coach['intro'] = (isset($coach['intro']) && !empty($coach['intro']))?$coach['intro']:'为健身爱好者提供一对一具体指导的健身指导者。 私人健身教练进行的是一对一的工作，工作具有互动性、针对性等特点。私人教练适合不同健康水平、年龄段和经济收入的人群，通过提供个性化的健身计划和关注，服务于健身会员（顾客）。购买私人教练是最好的提高健康和体能、达到设置的目标的方法之一。';
            $group = new GroupClass();
            $score             = $group->getFieldsByOne($v = [],'score');
            $personalityImages = $this->personalityPictureShow($id);
            if ($personalityImages) {
                foreach ($personalityImages as $k => $v) {
                    if ($v['type'] == 1) {
                        //coachPics 私教风采展示图片
                        if (!empty($v['url'])) $coach['coachPics'][] = $v['url'];
                    } else {
                        //coachVideos  私教风采展示视频，暂时处理我爱运动的卡顿
                        if ($coach['company_id'] <> 49) {
                            if (!empty($v['url'])) $coach['coachVideos'][] = $v['url'].'&avvod/m3u8/s/960x640/vb/1000k';
                        } else {
                            if (!empty($v['url'])) $coach['coachVideos'][] = $v['url'];
                        }
                    }
                }
            }
            if(empty($coach['work_time']))
            {
                $coach['workTime'] = "";
            }else{
                $coach['workTime'] = $coach['work_time'];
            }
            if(empty($coach['age']))
            {
                $coach['age'] = "暂无";
            }
            $coach['score']    = $score['score'];
            $coach['scoreImg'] = $score['scoreImg'];
            $coach['coachPic'] = !empty($coach['pic']) ? $coach['pic'] : self::COACH_PIC;
            $coach['coachPics'] = isset($coach['coachPics']) ? $coach['coachPics']: NULL;
            $coach['coachVideos'] = isset($coach['coachVideos']) ? $coach['coachVideos'] : NULL;
            $coach['evaluate'] = $this->getPrivateEvaluate($id);
            unset($coach['params'],     $coach['create_at'],
                $coach['updated_at'], $coach['admin_user_id'],
                $coach['create_id'],  $coach['salary'],
                $coach['leave_date'], $coach['entry_date'],
                $coach['organization_id'],$coach['birth_time'],
                $coach['mobile'],$coach['email'],
                $coach['work_time']  );
        }
        return $coach;
    }
    /**
     * @desc:私教评价(此处仅显示2条)
     * @author: 辛威<xinwei@itsports.club>
     * @date: 2018/06/04
     * @param $coachId     私教教练id
     */
    public function getPrivateEvaluate($coachId)
    {
        $evaluate = Evaluate::find()
                ->alias('e')
                ->joinWith(['memberDetails md' => function($q){
                }], false)
                ->where(['coach_id' => $coachId])
                ->select('
                    md.pic,
                    md.name,
                    e.create_at,
                    e.star_level,
                    e.content,
                    ')
                ->orderBy('create_at DESC')
                ->limit('2')
                ->asArray()
                ->all();
        return $evaluate;
    }
    /**
     * @desc:私教照片或者视频展示
     * @author: 辛威<xinwei@itsports.club>
     * @date: 2018/06/02
     * @param $coachId     私教教练id
     */
    public  function  personalityPictureShow($coachId)
    {
        $list = PersonalityImages::find()
            ->where(['employee_id' => $coachId ])
            ->select('type,url')
            ->orderBy('create_at DESC')
            ->asArray()
            ->All();
        return $list;
    }
    /**
     * @desc:私教评价(显示所有)
     * @author: 辛威<xinwei@itsports.club>
     * @date: 2018/06/04
     * @param $coachId     私教教练id
     */
    public function getMorePrivateEvaluate($params)
    {
        $query = Evaluate::find()
            ->alias('e')
            ->joinWith(['memberDetails md' => function($q){
            }], false)
            ->where(['e.coach_id' => $params['id']])
            ->andWhere(['e.consumption_type' => 'privateClass'])
            ->select('
                    md.pic,
                    md.name,
                    e.create_at,
                    e.star_level,
                    e.content,
                    ')
            ->orderBy('create_at DESC');
        $maxNum = $query->count();
        // 分段加载
        $params["page"] = (!isset($params["page"])||empty($params["page"]))?1:$params["page"]+1;
        $page  = $params["page"]*10;
        $this->endPage = ($page>=$maxNum)?2:1;
        $query = $query->limit($page);       //分页加载
        $query = $query->all();
        return $query;
    }
    /**
     * 云运动 - Api - 获取私教
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @param $venueId //场馆id
     * @param $orderId // 订单id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCoach($venueId,$orderId)
    {
        $coachId = "";
        if(!empty($orderId)){
            $coachId = MemberCourseOrder::findOne($orderId);
	        $coachIds = Order::find()->where(["and",["consumption_type"=>"charge"],["consumption_type_id"=>$orderId]])->one();
	        $coach_id =  empty($coachId->private_id) ?$coachIds->new_note :$coachId->private_id;
        }
        if (!empty($coach_id)){
	        $datas = Employee::findOne(["id"=>$coach_id,'venue_id'=>$venueId]);
	        if (empty($datas)){
		        $data =  Employee::find()
			        ->alias('ee')
			        ->joinWith(['organization or'])
			        ->where(['or.code'=>'sijiao'])
			        ->andWhere(['ee.status'=>1])
			        ->andWhere(['ee.venue_id'=>$venueId])->all();
	        }else{
		        $data  = Employee::find()
			        ->alias('ee')
			        ->joinWith(['organization or'])
			        ->where(['or.code'=>'sijiao'])
			        ->andWhere(['ee.status'=>1])
			        ->andWhere(['ee.venue_id'=>$venueId])
			        ->andWhere(["ee.id"=>$coach_id])->all();
	        }
        }else{
	        $data = Employee::find()
		        ->alias('ee')
		        ->joinWith(['organization or'])
		        ->where(['or.code'=>'sijiao','venue_id'=>$venueId])
		        ->andWhere(['ee.status'=>1])
		        ->all();
	
        }
        if ($data) {
            foreach ($data as $k => $v) {
                if (empty($v['pic'])) $data[$k]['pic'] = self::COACH_PIC;
            }
            return $data;
        }
    }
    /**
     * 云运动 - Api - 员工属性
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/24
     * @return array|\yii\db\ActiveRecord[]
     */
    public function fields()
    {
        $fields['name'] = 'name';
        $fields['id']   = 'id';
        $fields['workTime']  = function (){
            return (!empty($this->work_time)) ? $this->work_time :"";
        };
        $fields['age']  = function (){
            return (!empty($this->age)) ? $this->age :'暂无';
        };
        $fields['pic']  = function (){
            return $this->pic ?: '';
        };
        $fields['score'] = function (){
            $group = new GroupClass();
            $score             = $group->getFieldsByOne($v = [],'score');
            $coach['score']    = $score['score'];
            return $coach['score'];
        };
        $fields['scoreImg'] = function (){
            $group = new GroupClass();
            $score             = $group->getFieldsByOne($v = [],'score');
            return $score['scoreImg'];
        };
        $fields['signature'] = 'signature';
        return $fields;
    }
}