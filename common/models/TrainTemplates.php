<?php

namespace common\models;

use common\models\Action;
use common\models\base\ActionImages;
use common\models\relations\TrainStagesRelations;
use yii\base\InvalidParamException;

class TrainTemplates extends \common\models\base\TrainTemplates
{
    use TrainStagesRelations;
    public function fields()
    {
        return [
            'id',
            'title',
            'stages'=>function($model)
            {
                $var = $model->stages;
                foreach ($var as $key=>$value){
                    $data[$key]['title'] = $value['title'];
                    $data[$key]['id'] = $value['id'];
                    $value = $value['main'];
                    $main =[];
                    foreach ($value as $k=>$v){
                        $v = (array)$v;
                        $main[$k]['action_id'] = intval($v['action_id']);
                        $main[$k]['title'] = Action::findOne(intval($v['action_id']))->title;
                        $main[$k]['ssentials'] = Action::findOne(intval($v['action_id']))->ssentials;
                        $main[$k]['unit'] = Action::findOne(intval($v['action_id']))->unit;
                        if($main[$k]['unit'] == 2 || $main[$k]['unit'] == 3)
                        {
                            $main[$k]['energy'] = Action::findOne(intval($v['action_id']))->energy;
                        }
                        $main[$k]['number'] = intval($v['number']);
                        $main[$k]['url'] = ActionImages::find()
                                                ->select(['url'])
                                                ->where(['aid'=>$v['action_id']])
                                                ->andWhere(['NOT',['url'=>null]])
                                                ->asArray()
                                                ->all();

                    }
                    $data[$key]['main'] = $main;
                }

                return $data;
            },


        ];
    }

}