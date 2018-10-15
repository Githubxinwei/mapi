<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/25
 * Time: 17:22
 */

namespace backend\modules\v1\models;

use common\models\Evaluate;
use common\models\relations\EvaluateRelations;
use common\models\MemberCourseOrderDetails;
use yii\base\Model;

class EvaluateForm extends Model
{
    public $member_id;
    public $consumption_type;
    public $display_status;
    public $venue_id;
    public $company_id;
    public $content;
    public $label_id;
    public $star_level;
    public $consumption_type_id;
    public $enclosure;

    public function rules()
    {
        return [
            [['member_id','consumption_type','consumption_type_id','display_status','venue_id','company_id','content','label_id','star_level'], 'required'],
            [['company_id', 'venue_id' ,'star_level',], 'integer'],
            [['content','label_id'], 'string'],
            ['consumption_type', 'in', 'range' =>['teamClass', 'privateClass','smallClass']],
            ['company_id', 'default', 'value'=>1],
//            [['enclosure'], 'file','skipOnEmpty' => false, 'extensions'=>'jpg,jpeg,png,gif', 'maxFiles'=>4, 'maxSize'=>2048*1024],
        ];
    }
    public function saves($url ='')
    {
        if ($this->validate()) {
            if ($this->consumption_type ==='privateClass'){
                $this->consumption_type_id = $this->consumption_type_id;
            }
            $ev = Evaluate::find()->where(['member_id'=>$this->member_id,'consumption_type_id'=>$this->consumption_type_id])->all();
            if (!empty($ev)) return ['code'=>0,'msg'=>'该课程已评价!'];
            foreach ($this as $key=>$value){
                if (!empty($value)){
                    $arr[]=$key;
                }
            }
            $date = New Evaluate();
            foreach ($arr as $field){
                if(isset($this->$field)) $date->$field = $this->$field;
            }

//            $date ->member_id = $this->member_id;
//            $date->consumption_type =$this->consumption_type;
//            $date->consumption_type_id=$this->consumption_type_id;
//            $date->display_status=$this->display_status;
//            $date->company_id=$this->company_id;
//            $date->content=$this->content;
            $date->create_at = time();
//            $date->label_id=$this->label_id;
//            $date->star_level=$this->star_level;
            $date->enclosure=json_encode($url);

            if(!$date->save()){;
                $date->addErrors($this->errors);
                ['code'=>0,'msg'=>'评价失败!'];
            }
            return TRUE;
        } else {
            ['code'=>0,'msg'=>'评价失败!'];
        }
    }
}