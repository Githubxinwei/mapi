<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/25
 * Time: 17:22
 */

namespace backend\modules\v1\models;

use common\models\MemberCourseOrderDetails;
use yii\base\Model;
use common\models\Func;
class EvaluateView extends \common\models\Evaluate
{
    public function fields()
    {

        $fields = [
            'id',
            'content',
            'display_status'=>function($model){
                if ($model->display_status == 2){
                    return Func::getRelationVal($model,'memberDetails','name');
                }else{
                    return '匿名';
                }
            },
            'star_level',
            'label_id'=>function($model){
            return json_decode($model->label_id);
            },
            'consumption_type',
            'class_infos',
            'enclosure'=>function($model){
            $len = strlen($model->enclosure);
            if ($len<=4){
                return '';
            }else{
                return implode(',',json_decode($model->enclosure));
            }

            },
        ];
        return $fields;
    }



    public function getClass_infos()
    {
        if($this->consumption_type == 'privateClass'){
            return MyAboutClass::findOne(['class_id'=>$this->consumption_type_id]);
        }
        if($this->consumption_type == 'teamClass' || $this->consumption_type == 'smallClass') return MyAboutClass::findOne(['class_id'=>$this->consumption_type_id]);
        return (object)[];
    }



}