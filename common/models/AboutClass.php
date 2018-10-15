<?php

namespace common\models;

use common\models\relations\AboutClassRelations;
class AboutClass extends \common\models\base\AboutClass 
{
      use AboutClassRelations;

    /**
     * 本月取消次数
     */
      public function getMonthCancel()
      {
          $from = strtotime(date('Y-m-1'));
          $to = strtotime(date('Y-m-1',strtotime('+1 month')));
          return static::find()->where(['member_id'=>$this->member_id])->andWhere(['between', 'cancel_time', $from, $to])->count();
      }
}