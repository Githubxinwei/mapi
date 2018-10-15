<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/7
 * Time: 上午 10:29
 */

namespace common\models;
use common\models\relations\EvaluateRelations;

class Evaluate extends \common\models\base\Evaluate
{
    use EvaluateRelations;
//    public function fields()
//    {
////        return [
////            'nickname'=>function($model){
////                return Func::getRelationVal($model, 'memberDetails', 'nickname');
////            },
////            'content',
////            'star_level',
////            'enclosure'=>function(){
////                return is_null($this->enclosure) ? 'null' :$this->enclosure;
////            },
////            'create_at'=>function(){
////                return date('Y.m.d',$this->create_at);//格式化时间戳
////            },
////        ];
//    }
}