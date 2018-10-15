<?php

namespace common\models;

use common\models\relations\MemberDetailsRelations;

class MemberDetails extends \common\models\base\MemberDetails
{
    use MemberDetailsRelations;

    /**
     * 获取年龄
     */
    public function getAge()
    {
        $age = NULL;
        if(!empty($this->birth_date)){
            $age = strtotime($this->birth_date);
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

    /**
     * 获取性别
     */
    public function getSexname()
    {
        return $this->sex == 1 ? '男' : '女';
    }

}