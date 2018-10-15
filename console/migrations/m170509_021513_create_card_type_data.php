<?php

use yii\db\Migration;

class m170509_021513_create_card_type_data extends Migration
{
    /**
     * 云运动 - 数据迁移 - 迁移卡种类型数据
     * @author lihuien<lihuien@itsports.club>
     * @create 2017/5/9
     */
    public function up()
    {
        $data = \common\models\base\CardCategoryType::find()->asArray()->all();
        if(count($data) == 0){
            $this->batchInsert('{{%card_category_type}}',
                ['id','type_name','create_at'],
                [
                    ['1','时间卡',time()], ['2','次卡',time()], ['3','充值卡',time()], ['4','混合卡',time()],
                ]);
        }
    }
    /**
     * 云运动 - 数据迁移 - 回滚卡种类型数据
     * @author lihuien<lihuien@itsports.club>
     * @create 2017/5/9
     * @note   由于卡种类型包含卡种 所以不能迁移删除 所以不写回滚 只做迁移
     */
    public function down()
    {

    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
