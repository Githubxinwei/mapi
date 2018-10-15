<?php
namespace backend\modules\v1\models;

use common\models\Action;
use backend\modules\v1\models\MemberResult;
use common\models\base\ActionCategory;
use common\models\MemberAction;

class MemberTemplate extends \common\models\MemberTemplate
{
    public function fields()
    {
    	$coach_info = \backend\modules\v1\models\Employee::find()->select('id,name,pic,venue_id')->where(['id'=>$this->coach_id])->asArray()->one();
	    $memberName =  \backend\models\MemberDetails::find()->select('name,member_id')->where(['member_id'=>$this->member_id])->asArray()->one();
	    $imgs = [];
	    $titles = [];
	    return [
            'id',
            'class_id',
	        'coach_info'=>function($model) use($coach_info) {
        	    return $coach_info;
	        },
		    'venue_name'=>function($model) use($coach_info){
	    	    $venue = Organization::findOne(['id'=>$coach_info['venue_id']]);
	    	    return isset($venue) ?$venue->name:'暂无!';
		    },
	        'memberName'=>function($model)use($memberName){
        	    return isset($memberName['name']) ?$memberName['name'] :'';
	        },
            'main' => function($model) use(&$imgs,&$titles)
            {
                $data = $this->jsde($model->main);

                foreach ($data['stages'] as $k => $v)
                {
                    foreach ($v['main'] as $k1 => $v1)
                    {
                        unset($data['stages'][$k]['main'][$k1]['url']);
	                    if ($v1['action_id'] == '-1'){
		                    unset($data['stages'][$k]['main'][$k1]);
	                    }
                    }
                }

                foreach ($data['stages'] as $k => $v)
                {
                    foreach ($v['main'] as $k1 => $v1)
                    {
                        $memberAction = MemberAction::findOne(['action_id'=>$v1['action_id'],'class_id'=>$model->class_id,'stage_id'=>$v['id']]);
                        $data['stages'][$k]['main'][$k1]['mcontent'] = isset($memberAction['content']) ?empty($memberAction['content']) ? "":$memberAction['content'] :"" ;//动作评价
                        $data['stages'][$k]['main'][$k1]['murl'] = isset($memberAction['url']) ?empty($memberAction['url']) ? []:$this->jsde($memberAction['url']) :[] ;//动作评价图片
	                    if ($v1['unit']==1){
	                    	$num = isset($memberAction['action_number']) ?empty($memberAction['action_number']) ? []:$this->jsde($memberAction['action_number'] ):null ;//动作组数
	                    }else{
	                    	$num = isset($memberAction['action_number']) ?empty($memberAction['action_number']) ? []:[['num1'=>$this->jsde($memberAction['action_number'] ),'num2'=>'']]:null ;//动作组数
	                    }
	                    $data['stages'][$k]['main'][$k1]['action_number']  = $num;
	                    
	                    $titles[] =  $var = Action::find()
		                    ->alias("a")
		                    ->select(['c.title'])
		                    ->joinWith('categorys c',FALSE)
		                    ->joinWith('images i',FALSE)
		                    ->where(['a.is_delete'=>0,'a.id'=>$v1['action_id']])
		                    ->andWhere(['NOT',['c.title'=>null]])
		                    ->groupBy(['c.id','a.id'])
		                    ->asArray()
		                    ->all();
//	                    $data['stages'][$k]['main'][$k1]['action_numbersssss'] =array_column($var,'title');
	                    $imgs[] = isset($memberAction['url']) ?empty($memberAction['url']) ? null:$this->jsde($memberAction['url']) :null ;//动作评价图片
                    }
                }
                return $data;
            },
            'result' =>function($model){
                return MemberResult::find()->select('complete,calorie,motion,motion_qd,everyday,member_url')
                    ->where(['class_id'=>$model->class_id,'member_id'=>$model->member_id])->asArray()->all();
            },
	        'InvitationLink' =>function($model) use($coach_info,$memberName){
        	    return  'http://'.$_SERVER['SERVER_NAME'].'/InvitationLink/shareEnroll.html?coach_id='.$coach_info['id'].'&coach_name='.urlencode($coach_info['name']).'&memberName='.urlencode($memberName['name']).'&member_id='.$memberName['member_id'];
	        },
	        'course_name'=>function($model){
	    	$name =  \backend\modules\v1\models\MemberCourseOrderDetails::find()
			    ->alias('mcod')
			    ->select('mcod.course_name')
			    ->joinWith('aboutClass ac',false)
			    ->where(['ac.id'=>$model->class_id])
			    ->one();
	    	return isset($name->course_name) ?$name->course_name:'暂无!';
	        },
		    'img'=>function($model) use(&$imgs){
	    	if (empty($imgs)) return [];
	    	$a=[];
			    foreach ($imgs as $key=>$value){
                	if (!empty($value)){
                		foreach ($value as $k=>$v){
                			$a[]=$v;
		                }
	                }
                }
	    	return empty($a) ?[]:$a;
		    },
		    'titles'=>function($model) use(&$titles){
			    if (empty($titles)) return [];
			    $a = [];
			    foreach ($titles as $key=>$value){
				    if (!empty($value)){
					    foreach ($value as $k=>$v){
						    if (!in_array($v['title'],$a)){
							    $a[]=$v['title'];
						    }
					    }
				    }
			    }
			    return $a;
		    },
		    'time'=>function($model){
	    	$time = date('Y-n-d',time());
	    	$time = explode('-',$time);
	    	$mons = ['一','二','三','四','五','六','七','八','九','十','十一','十二'];
	    	return $mons[$time[1]-1].'月'.$time[2].',　'.$time[0];
		    },
		    'count'=>function($model){
			    $var = MemberAction::find()
				    ->joinWith(['action a'])
				    ->select("id")
				    ->where(['class_id'=>$model->class_id,'a.unit'=>1])
				    ->asArray()
				    ->count();
			    return $var;
		    },
		     'dietary_advice'=>function($model){
	    $data = MemberDietaryAdvice::find()->where(['about_class_id'=>$model->class_id,'coach_id'=>$model->coach_id,'member_id'=>$model->member_id])->asArray()->one();
	    if (isset($data['dietary_advice'])){
		    return $this->jsde($data['dietary_advice']);
	    }else{
		    return null;
	    }
        },
        'Inquiries'=>function($model){
	        $data =   MemberClassBeforeQuestion::findOne(['about_class_id'=>$model->class_id,'member_id'=>$model->member_id]);
	        if (empty($data)) return [];
	    $data = $this->jsde($data['answer_question']);
	    $question = [];
	    foreach ($data as $key=>$value){
		    if (isset($value['option'])){
			    $question[]=$value;
		    }
	    }
	    return $question;
    }
        ];
    }
    public function jsde($data){
        return json_decode(json_decode($data,true),true);
    }
}