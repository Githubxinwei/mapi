<?php

namespace common\models;
use common\models\relations\EmployeeRelations;
use Yii;

class Employee extends \common\models\base\Employee
{
    use EmployeeRelations;
    
    /**
     * 获取年龄
     */
    public function getAge()
    {
        $age = NULL;
        if(!empty($this->birth_time)){
            $age = strtotime($this->birth_time);
            if($age === false) return NULL;

            list($y1,$m1,$d1) = explode('-',date('Y-m-d', $age));
            list($y2,$m2,$d2) = explode('-',date('Y-m-d'), time());

            $age = $y2 - $y1;
            if((int)($m2.$d2) < (int)($m1.$d1)){
                $age -= 1;
            }
        }
        return $age;
    }
}