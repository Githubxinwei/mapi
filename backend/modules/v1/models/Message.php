<?php
namespace backend\modules\v1\models;

use common\models\Func as commonFunc;

class Message extends \common\models\AboutClass
{
    public function fields()
    {
        return [
            'id',

            'status' => function ($model) {
                if($model->status==1 && time()>$model->start){
                    if (isset($model->class_info->least_number)){
                        if($model->had_about_num < $model->class_info->least_number){
                            \common\models\AboutClass::updateAll(['status'=>9, 'is_read'=>0], ['class_id'=>$this->id, 'type'=>$this->type]);
                        }
                    }

                }
                return $model->status;
            },

            'type',

            'coach' => function ($model) {
                return commonFunc::getRelationVal($model, 'employee', 'name');
            },

            'start',

            'is_read',

            'create_at',

            'had_about_num',

            'class_info',

            'times' => function($model){
                if (!empty(commonFunc::getRelationVal($model, 'chargeGroupClass', 'times'))){
                    return commonFunc::getRelationVal($model, 'chargeGroupClass', 'times');
                }
                return NULL;
            },
            'in_time',
        ];
    }

    /**
     * 已预约人数
     */
    public function getHad_about_num()
    {
        return \common\models\AboutClass::find()->where(['class_id'=>$this->id, 'type'=>$this->type, 'status'=>1])->count();
    }

    /**
     * ChargeClassNumber课程信息
     */
    public function getClass_info()
    {
        if(in_array($this->type, [3,4]) && isset($this->chargeGroupClass->class_number_id))
            return ChargeClassNumber::findOne($this->chargeGroupClass->class_number_id);
        if($this->type == '2') return MyGroupClass::findOne($this->class_id);
        return (object)[];
    }


}