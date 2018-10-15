<?php

namespace common\models\relations;

use common\models\base\TrainTemplates;
use common\models\base\TrainStage;
use common\models\base\Action;



trait TrainStagesRelations
{
    /**
     * 私教管理 - 训练模板 -   阶段表 动作表 阶段动作关联表 三表关联
     * @author jianbingqi <jianbingqi@itsports.club>
     * @return \yii\db\ActiveQuery
     */
   /* public function getActions()
    {
        return $this->hasMany(Action::className(), ['id' => 'action_id']);

    }*/

    public function getStages()
    {
        return $this->hasMany(TrainStage::className(), ['template_id' => 'id']);
    }


}